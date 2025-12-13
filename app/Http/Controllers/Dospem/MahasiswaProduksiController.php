<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Produksi;
use Illuminate\Support\Facades\Auth;

class MahasiswaProduksiController extends Controller
{
    /**
     * Get produksi items for a mahasiswa (untuk view dospem detail mahasiswa)
     * @param string $mahasiswaIdentifier NIM atau user_id
     */
    public function getProduksiList($mahasiswaIdentifier)
    {
        // Ambil mahasiswa berdasarkan NIM atau user_id
        $mahasiswa = Mahasiswa::where('nim', $mahasiswaIdentifier)
            ->orWhere('user_id', $mahasiswaIdentifier)
            ->first();

        if (!$mahasiswa) {
            return [];
        }
        
        // Ambil produksi mahasiswa berdasarkan user_id (mahasiswa_id di tabel tim_produksi)
        $produksiList = Produksi::where('mahasiswa_id', $mahasiswa->user_id ?? $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'status_pra_produksi' => $p->status_pra_produksi ?? 'belum_upload',
                    'status_produksi_akhir' => $p->status_produksi ?? 'belum_upload',
                    'file_skenario' => $p->file_skenario,
                    'file_storyboard' => $p->file_storyboard,
                    'file_dokumen_pendukung' => $p->file_dokumen_pendukung,
                    'file_produksi_akhir' => $p->file_produksi,
                    'feedback_pra_produksi' => $p->feedback_pra_produksi,
                    'feedback_produksi_akhir' => $p->feedback_produksi,
                    'tanggal_upload_pra' => $p->tanggal_upload_pra?->format('Y-m-d H:i:s'),
                    'tanggal_upload_akhir' => $p->tanggal_upload_produksi?->format('Y-m-d H:i:s'),
                    'tanggal_review_pra' => $p->tanggal_review_pra?->format('Y-m-d H:i:s'),
                    'tanggal_review_akhir' => $p->tanggal_review_produksi?->format('Y-m-d H:i:s'),
                ];
            })
            ->toArray();

        return $produksiList;
    }

    /**
     * Approve/Revisi/Tolak Pra Produksi
     * 
     * FEEDBACK POLICY:
     * - Feedback hanya bisa diisi oleh DOSPEM (tidak oleh mahasiswa)
     * - Feedback OPSIONAL untuk status "disetujui"
     * - Feedback WAJIB untuk status "revisi" atau "ditolak" (min 5 karakter)
     */
    public function approvePraProduksi(Request $request, $id)
    {
        try {
            \Log::info('approvePraProduksi called', ['id' => $id, 'payload' => $request->all()]);
            
            // Validasi input - support both 'status' dan 'produksi_status' field names
            $statusField = $request->has('status') ? 'status' : 'produksi_status';
            $feedbackField = $request->has('feedback') ? 'feedback' : 'produksi_feedback';
            
            $status = $request->input($statusField);
            $feedback = $request->input($feedbackField);
            
            // Validasi status
            $validStatuses = ['disetujui', 'revisi', 'ditolak'];
            if (!in_array($status, $validStatuses)) {
                return $this->handleResponse($request, 'error', 'Status tidak valid.', 422);
            }
            
            // Validasi feedback: wajib hanya untuk status 'revisi' atau 'ditolak'
            if (in_array($status, ['revisi', 'ditolak'])) {
                if (empty($feedback) || strlen($feedback) < 5) {
                    return $this->handleResponse($request, 'error', 'Feedback wajib diisi minimal 5 karakter untuk status revisi/ditolak.', 422);
                }
            }

            // Cari produksi berdasarkan ID
            $produksi = Produksi::findOrFail($id);

            \Log::info('Produksi found for pra', ['produksi_id' => $produksi->id, 'mahasiswa_id' => $produksi->mahasiswa_id]);

            // OTORISASI: Validasi bahwa DOSPEM adalah pembimbing mahasiswa ini
            $dosenAuth = auth()->user();
            $authNidn = null;
            if ($dosenAuth) {
                $dosenModel = \App\Models\Dosen::where('user_id', $dosenAuth->id)->first();
                $authNidn = $dosenModel?->nidn ?? null;
            }

            // Cari mahasiswa untuk mendapatkan dosen pembimbing yang benar
            $mahasiswa = \App\Models\Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
            $mahasiswaDosenPembimbingId = $mahasiswa?->dosen_pembimbing_id ?? null;

            \Log::info('Authorization check for approvePraProduksi', [
                'user_id' => $dosenAuth?->id,
                'auth_nidn' => $authNidn,
                'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                'produksi_dosen_id' => $produksi->dosen_id
            ]);

            // Validasi bahwa dosen yang login adalah dosen pembimbing mahasiswa ini
            if (!$dosenAuth || !$authNidn || $authNidn !== $mahasiswaDosenPembimbingId) {
                \Log::warning('Unauthorized access attempt to approve produksi pra', [
                    'auth_nidn' => $authNidn,
                    'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                    'produksi_id' => $produksi->id,
                    'user_id' => auth()->id()
                ]);
                return $this->handleResponse($request, 'error', 'Anda tidak berhak approve produksi mahasiswa ini.', 403);
            }
            
            // Prepare update data
            $updateData = [
                'status_pra_produksi' => $status,
                'tanggal_review_pra' => now(),
            ];
            
            // Update feedback hanya jika ada (dospem adalah satu-satunya yang bisa mengisi)
            if (!empty($feedback)) {
                $updateData['feedback_pra_produksi'] = $feedback;
            }
            
            // Update status dan feedback
            \Log::info('About to update produksi pra', ['produksi_id' => $produksi->id, 'updateData' => $updateData]);
            $result = $produksi->update($updateData);
            \Log::info('Produksi pra update result', ['result' => $result, 'produksi_id' => $produksi->id]);

            \Log::info('Produksi pra updated', ['produksi_id' => $produksi->id, 'status' => $status, 'has_feedback' => !empty($feedback)]);
            \Log::info('Produksi pra after update', ['status' => $produksi->fresh()->status_pra_produksi, 'feedback' => $produksi->fresh()->feedback_pra_produksi]);

            $messages = [
                'disetujui' => '✅ Pra Produksi berhasil disetujui!',
                'revisi' => '⚠️ Feedback revisi telah dikirim ke mahasiswa.',
                'ditolak' => '❌ Pra Produksi ditolak.'
            ];
            $message = $messages[$status] ?? 'Pra Produksi berhasil diproses.';

            return $this->handleResponse($request, 'success', $message, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = array_values($e->errors())[0] ?? [];
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', $errors), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reject Pra Produksi
     * Feedback WAJIB untuk penolakan
     */
    public function rejectPraProduksi(Request $request, $id)
    {
        try {
            // Cari produksi
            $produksi = Produksi::findOrFail($id);

            // OTORISASI: Validasi bahwa DOSPEM adalah pembimbing mahasiswa ini
            $mahasiswa = Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
            if (!$mahasiswa) {
                \Log::warning('Mahasiswa not found for user_id', ['user_id' => $produksi->mahasiswa_id]);
                return $this->handleResponse($request, 'error', 'Mahasiswa tidak ditemukan.', 404);
            }

            // Cek apakah dosen pembimbing - resolve nidn via Dosen model linked to auth user
            $dosenAuth = auth()->user();
            $authNidn = null;
            if ($dosenAuth) {
                $dosenModel = \App\Models\Dosen::where('user_id', $dosenAuth->id)->first();
                $authNidn = $dosenModel?->nidn ?? null;
            }

            $mahasiswaDosenPembimbingId = $mahasiswa?->dosen_pembimbing_id ?? null;

            \Log::info('Authorization check for rejectPraProduksi', [
                'user_id' => $dosenAuth?->id,
                'auth_nidn' => $authNidn,
                'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                'produksi_dosen_id' => $produksi->dosen_id
            ]);

            // Validasi bahwa dosen yang login adalah dosen pembimbing mahasiswa ini
            if (!$dosenAuth || !$authNidn || $authNidn !== $mahasiswaDosenPembimbingId) {
                \Log::warning('Unauthorized access attempt to reject produksi pra', [
                    'auth_nidn' => $authNidn,
                    'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                    'produksi_id' => $produksi->id,
                    'user_id' => auth()->id()
                ]);
                return $this->handleResponse($request, 'error', 'Anda tidak berhak reject produksi mahasiswa ini.', 403);
            }

            // Validasi input - feedback WAJIB untuk penolakan
            $validated = $request->validate([
                'feedback' => 'required|string|min:5',
            ]);

            // Update status ke ditolak dengan feedback wajib
            $produksi->update([
                'status_pra_produksi' => 'ditolak',
                'feedback_pra_produksi' => $validated['feedback'],
                'tanggal_review_pra' => now(),
            ]);

            \Log::info('Produksi pra rejected', ['produksi_id' => $produksi->id]);

            return $this->handleResponse($request, 'success', '❌ Pra Produksi telah ditolak dengan feedback.', 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', array_values($e->errors())[0]), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Approve/Revisi/Tolak Produksi (tahap tengah - file produksi/karya)
     * 
     * FEEDBACK POLICY:
     * - Feedback hanya bisa diisi oleh DOSPEM (tidak oleh mahasiswa)
     * - Feedback OPSIONAL untuk status "disetujui"
     * - Feedback WAJIB untuk status "revisi" atau "ditolak" (min 5 karakter)
     */
    public function approveProduksi(Request $request, $id)
    {
        try {
            \Log::info('approveProduksi called', ['id' => $id, 'payload' => $request->all()]);
            
            // Validasi input - support both 'status' dan 'produksi_status' field names
            $statusField = $request->has('status') ? 'status' : 'produksi_status';
            $feedbackField = $request->has('feedback') ? 'feedback' : 'produksi_feedback';

            $status = $request->input($statusField);
            $feedback = $request->input($feedbackField);
            
            // Validasi status
            $validStatuses = ['disetujui', 'revisi', 'ditolak'];
            if (!in_array($status, $validStatuses)) {
                return $this->handleResponse($request, 'error', 'Status tidak valid.', 422);
            }
            
            // Validasi feedback: wajib hanya untuk status 'revisi' atau 'ditolak'
            if (in_array($status, ['revisi', 'ditolak'])) {
                if (empty($feedback) || strlen($feedback) < 5) {
                    return $this->handleResponse($request, 'error', 'Feedback wajib diisi minimal 5 karakter untuk status revisi/ditolak.', 422);
                }
            }

            // Cari produksi berdasarkan ID
            $produksi = Produksi::findOrFail($id);

            \Log::info('Produksi found for produksi', ['produksi_id' => $produksi->id, 'mahasiswa_id' => $produksi->mahasiswa_id]);

            // OTORISASI: Validasi bahwa DOSPEM adalah pembimbing mahasiswa ini
            $dosenAuth = auth()->user();
            $authNidn = null;
            if ($dosenAuth) {
                $dosenModel = \App\Models\Dosen::where('user_id', $dosenAuth->id)->first();
                $authNidn = $dosenModel?->nidn ?? null;
            }

            // Cari mahasiswa untuk mendapatkan dosen pembimbing yang benar
            $mahasiswa = \App\Models\Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
            $mahasiswaDosenPembimbingId = $mahasiswa?->dosen_pembimbing_id ?? null;

            \Log::info('Authorization check for approveProduksi', [
                'user_id' => $dosenAuth?->id,
                'auth_nidn' => $authNidn,
                'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                'produksi_dosen_id' => $produksi->dosen_id
            ]);

            // Validasi bahwa dosen yang login adalah dosen pembimbing mahasiswa ini
            if (!$dosenAuth || !$authNidn || $authNidn !== $mahasiswaDosenPembimbingId) {
                \Log::warning('Unauthorized access attempt to approve produksi produksi', [
                    'auth_nidn' => $authNidn,
                    'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                    'produksi_id' => $produksi->id,
                    'user_id' => auth()->id()
                ]);
                return $this->handleResponse($request, 'error', 'Anda tidak berhak approve produksi mahasiswa ini.', 403);
            }

            // Prepare update data
            $updateData = [
                'status_produksi' => $status,
                'tanggal_review_produksi' => now(),
            ];
            
            // Update feedback hanya jika ada (dospem adalah satu-satunya yang bisa mengisi)
            if (!empty($feedback)) {
                $updateData['feedback_produksi'] = $feedback;
            }

            $produksi->update($updateData);

            \Log::info('Produksi produksi updated', ['produksi_id' => $produksi->id, 'status' => $status, 'has_feedback' => !empty($feedback)]);
            \Log::info('Produksi produksi after update', ['status' => $produksi->fresh()->status_produksi, 'feedback' => $produksi->fresh()->feedback_produksi]);

            $messages = [
                'disetujui' => '✅ Produksi berhasil disetujui!',
                'revisi' => '⚠️ Feedback revisi telah dikirim ke mahasiswa.',
                'ditolak' => '❌ Produksi ditolak.'
            ];
            $message = $messages[$status] ?? 'Produksi berhasil diproses.';

            return $this->handleResponse($request, 'success', $message, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = array_values($e->errors())[0] ?? [];
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', $errors), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reject Produksi (tahap tengah)
     * Feedback WAJIB untuk penolakan
     */
    public function rejectProduksi(Request $request, $id)
    {
        try {
            // Cari produksi
            $produksi = Produksi::findOrFail($id);

            // OTORISASI: Validasi bahwa DOSPEM adalah pembimbing mahasiswa ini
            $mahasiswa = Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
            if (!$mahasiswa) {
                \Log::warning('Mahasiswa not found for user_id', ['user_id' => $produksi->mahasiswa_id]);
                return $this->handleResponse($request, 'error', 'Mahasiswa tidak ditemukan.', 404);
            }

            // Cek apakah dosen pembimbing
            $dosenAuth = auth()->user();
            $authNidn = null;
            if ($dosenAuth) {
                $dosenModel = \App\Models\Dosen::where('user_id', $dosenAuth->id)->first();
                $authNidn = $dosenModel?->nidn ?? null;
            }

            $mahasiswaDosenPembimbingId = $mahasiswa?->dosen_pembimbing_id ?? null;

            \Log::info('Authorization check for rejectProduksi', [
                'user_id' => $dosenAuth?->id,
                'auth_nidn' => $authNidn,
                'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                'produksi_dosen_id' => $produksi->dosen_id
            ]);

            // Validasi bahwa dosen yang login adalah dosen pembimbing mahasiswa ini
            if (!$dosenAuth || !$authNidn || $authNidn !== $mahasiswaDosenPembimbingId) {
                \Log::warning('Unauthorized access attempt to reject produksi produksi', [
                    'auth_nidn' => $authNidn,
                    'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                    'produksi_id' => $produksi->id,
                    'user_id' => auth()->id()
                ]);
                return $this->handleResponse($request, 'error', 'Anda tidak berhak reject produksi mahasiswa ini.', 403);
            }

            // Validasi input - feedback WAJIB untuk penolakan
            $validated = $request->validate([
                'feedback' => 'required|string|min:5',
            ]);

            // Update status ke ditolak dengan feedback wajib - PRODUKSI
            $produksi->update([
                'status_produksi' => 'ditolak',
                'feedback_produksi' => $validated['feedback'],
                'tanggal_review_produksi' => now(),
            ]);

            \Log::info('Produksi produksi rejected', ['produksi_id' => $produksi->id]);

            return $this->handleResponse($request, 'success', '❌ Produksi telah ditolak dengan feedback.', 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', array_values($e->errors())[0]), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Approve/Revisi/Tolak Pasca Produksi (tahap akhir)
     * 
     * FEEDBACK POLICY:
     * - Feedback hanya bisa diisi oleh DOSPEM (tidak oleh mahasiswa)
     * - Feedback OPSIONAL untuk status "disetujui"
     * - Feedback WAJIB untuk status "revisi" atau "ditolak" (min 5 karakter)
     */
    public function approvePascaProduksi(Request $request, $id)
    {
        try {
            \Log::info('approvePascaProduksi called', ['id' => $id, 'payload' => $request->all()]);
            
            // Validasi input - support both 'status' dan 'produksi_status' field names
            $statusField = $request->has('status') ? 'status' : 'produksi_status';
            $feedbackField = $request->has('feedback') ? 'feedback' : 'produksi_feedback';

            $status = $request->input($statusField);
            $feedback = $request->input($feedbackField);
            
            // Validasi status
            $validStatuses = ['disetujui', 'revisi', 'ditolak'];
            if (!in_array($status, $validStatuses)) {
                return $this->handleResponse($request, 'error', 'Status tidak valid.', 422);
            }
            
            // Validasi feedback: wajib hanya untuk status 'revisi' atau 'ditolak'
            if (in_array($status, ['revisi', 'ditolak'])) {
                if (empty($feedback) || strlen($feedback) < 5) {
                    return $this->handleResponse($request, 'error', 'Feedback wajib diisi minimal 5 karakter untuk status revisi/ditolak.', 422);
                }
            }

            // Cari produksi berdasarkan ID
            $produksi = Produksi::findOrFail($id);

            \Log::info('Produksi found for pasca', ['produksi_id' => $produksi->id, 'mahasiswa_id' => $produksi->mahasiswa_id]);

            // OTORISASI: Validasi bahwa DOSPEM adalah pembimbing mahasiswa ini
            $dosenAuth = auth()->user();
            $authNidn = null;
            if ($dosenAuth) {
                $dosenModel = \App\Models\Dosen::where('user_id', $dosenAuth->id)->first();
                $authNidn = $dosenModel?->nidn ?? null;
            }

            // Cari mahasiswa untuk mendapatkan dosen pembimbing yang benar
            $mahasiswa = \App\Models\Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
            $mahasiswaDosenPembimbingId = $mahasiswa?->dosen_pembimbing_id ?? null;

            \Log::info('Authorization check for approvePascaProduksi', [
                'user_id' => $dosenAuth?->id,
                'auth_nidn' => $authNidn,
                'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                'produksi_dosen_id' => $produksi->dosen_id
            ]);

            // Validasi bahwa dosen yang login adalah dosen pembimbing mahasiswa ini
            if (!$dosenAuth || !$authNidn || $authNidn !== $mahasiswaDosenPembimbingId) {
                \Log::warning('Unauthorized access attempt to approve pasca produksi', [
                    'auth_nidn' => $authNidn,
                    'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                    'produksi_id' => $produksi->id,
                    'user_id' => auth()->id()
                ]);
                return $this->handleResponse($request, 'error', 'Anda tidak berhak approve produksi mahasiswa ini.', 403);
            }

            // Prepare update data - PASCA PRODUKSI
            $updateData = [
                'status_pasca_produksi' => $status,
                'tanggal_review_pasca' => now(),
            ];
            
            // Update feedback hanya jika ada (dospem adalah satu-satunya yang bisa mengisi)
            if (!empty($feedback)) {
                $updateData['feedback_pasca_produksi'] = $feedback;
            }

            $produksi->update($updateData);

            \Log::info('Produksi pasca updated', ['produksi_id' => $produksi->id, 'status' => $status, 'has_feedback' => !empty($feedback)]);
            \Log::info('Produksi pasca after update', ['status' => $produksi->fresh()->status_pasca_produksi, 'feedback' => $produksi->fresh()->feedback_pasca_produksi]);

            $messages = [
                'disetujui' => '✅ Pasca Produksi berhasil disetujui!',
                'revisi' => '⚠️ Feedback revisi telah dikirim ke mahasiswa.',
                'ditolak' => '❌ Pasca Produksi ditolak.'
            ];
            $message = $messages[$status] ?? 'Pasca Produksi berhasil diproses.';

            return $this->handleResponse($request, 'success', $message, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = array_values($e->errors())[0] ?? [];
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', $errors), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reject Pasca Produksi (tahap akhir)
     * Feedback WAJIB untuk penolakan
     */
    public function rejectPascaProduksi(Request $request, $id)
    {
        try {
            // Cari produksi
            $produksi = Produksi::findOrFail($id);

            // OTORISASI: Validasi bahwa DOSPEM adalah pembimbing mahasiswa ini
            $mahasiswa = Mahasiswa::where('user_id', $produksi->mahasiswa_id)->first();
            if (!$mahasiswa) {
                \Log::warning('Mahasiswa not found for user_id', ['user_id' => $produksi->mahasiswa_id]);
                return $this->handleResponse($request, 'error', 'Mahasiswa tidak ditemukan.', 404);
            }

            // Cek apakah dosen pembimbing
            $dosenAuth = auth()->user();
            $authNidn = null;
            if ($dosenAuth) {
                $dosenModel = \App\Models\Dosen::where('user_id', $dosenAuth->id)->first();
                $authNidn = $dosenModel?->nidn ?? null;
            }

            $mahasiswaDosenPembimbingId = $mahasiswa?->dosen_pembimbing_id ?? null;

            \Log::info('Authorization check for rejectPascaProduksi', [
                'user_id' => $dosenAuth?->id,
                'auth_nidn' => $authNidn,
                'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                'produksi_dosen_id' => $produksi->dosen_id
            ]);

            // Validasi bahwa dosen yang login adalah dosen pembimbing mahasiswa ini
            if (!$dosenAuth || !$authNidn || $authNidn !== $mahasiswaDosenPembimbingId) {
                \Log::warning('Unauthorized access attempt to reject pasca produksi', [
                    'auth_nidn' => $authNidn,
                    'mahasiswa_dosen_pembimbing_id' => $mahasiswaDosenPembimbingId,
                    'produksi_id' => $produksi->id,
                    'user_id' => auth()->id()
                ]);
                return $this->handleResponse($request, 'error', 'Anda tidak berhak reject produksi mahasiswa ini.', 403);
            }

            // Validasi input - feedback WAJIB untuk penolakan
            $validated = $request->validate([
                'feedback' => 'required|string|min:5',
            ]);

            // Update status ke ditolak dengan feedback wajib - PASCA PRODUKSI
            $produksi->update([
                'status_pasca_produksi' => 'ditolak',
                'feedback_pasca_produksi' => $validated['feedback'],
                'tanggal_review_pasca' => now(),
            ]);

            \Log::info('Produksi pasca rejected', ['produksi_id' => $produksi->id]);

            return $this->handleResponse($request, 'success', '❌ Pasca Produksi telah ditolak dengan feedback.', 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', array_values($e->errors())[0]), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get produksi data by ID (for real-time updates)
     * API endpoint untuk fetch data produksi terbaru
     */
    public function getProduksiData($id)
    {
        try {
            $produksi = Produksi::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $produksi->id,
                    // Pra Produksi Stage
                    'status_pra_produksi' => $produksi->status_pra_produksi ?? 'belum_upload',
                    'feedback_pra_produksi' => $produksi->feedback_pra_produksi,
                    'tanggal_review_pra' => $produksi->tanggal_review_pra?->format('Y-m-d H:i:s'),
                    // Produksi Stage (Middle)
                    'status_produksi' => $produksi->status_produksi ?? 'belum_upload',
                    'feedback_produksi' => $produksi->feedback_produksi,
                    'tanggal_review_produksi' => $produksi->tanggal_review_produksi?->format('Y-m-d H:i:s'),
                    // Pasca Produksi Stage (Final)
                    'status_pasca_produksi' => $produksi->status_pasca_produksi ?? 'belum_upload',
                    'feedback_pasca_produksi' => $produksi->feedback_pasca_produksi,
                    'tanggal_review_pasca' => $produksi->tanggal_review_pasca?->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produksi tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get all produksi for a mahasiswa (for real-time updates)
     * API endpoint untuk fetch semua produksi mahasiswa
     */
    public function getMahasiswaProduksiData($mahasiswaId)
    {
        try {
                    $mahasiswa = Mahasiswa::where('user_id', $mahasiswaId)
                        ->orWhere('nim', $mahasiswaId)
                        ->firstOrFail();
            $produksiList = Produksi::where('mahasiswa_id', $mahasiswa->user_id ?? $mahasiswa->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        // Pra Produksi Stage
                        'status_pra_produksi' => $p->status_pra_produksi ?? 'belum_upload',
                        'feedback_pra_produksi' => $p->feedback_pra_produksi,
                        'tanggal_upload_pra' => $p->tanggal_upload_pra?->format('Y-m-d H:i:s'),
                        'tanggal_review_pra' => $p->tanggal_review_pra?->format('Y-m-d H:i:s'),
                        // Produksi Stage (Middle)
                        'status_produksi' => $p->status_produksi ?? 'belum_upload',
                        'feedback_produksi' => $p->feedback_produksi,
                        'file_produksi' => $p->file_produksi,
                        'tanggal_upload_produksi' => $p->tanggal_upload_produksi?->format('Y-m-d H:i:s'),
                        'tanggal_review_produksi' => $p->tanggal_review_produksi?->format('Y-m-d H:i:s'),
                        // Pasca Produksi Stage (Final)
                        'status_pasca_produksi' => $p->status_pasca_produksi ?? 'belum_upload',
                        'feedback_pasca_produksi' => $p->feedback_pasca_produksi,
                        'file_pasca_produksi' => $p->file_pasca_produksi,
                        'tanggal_upload_pasca' => $p->tanggal_upload_pasca?->format('Y-m-d H:i:s'),
                        'tanggal_review_pasca' => $p->tanggal_review_pasca?->format('Y-m-d H:i:s'),
                        // Supporting files
                        'file_skenario' => $p->file_skenario,
                        'file_storyboard' => $p->file_storyboard,
                        'file_dokumen_pendukung' => $p->file_dokumen_pendukung,
                    ];
                })
                ->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $produksiList
            ]);
        } catch (\Exception $e) {
            \Log::error('getMahasiswaProduksiData error', ['id' => $mahasiswaId, 'error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Mahasiswa tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Helper method to handle responses (JSON or redirect)
     */
    private function handleResponse($request, $status, $message, $httpCode = 200)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'success' => $status === 'success',
                'message' => $message,
                'code' => $httpCode
            ], $httpCode);
        }

        return redirect()->back()->with(["status" => $status, "message" => $message]);
    }
}