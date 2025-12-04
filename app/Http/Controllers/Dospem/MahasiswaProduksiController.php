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
                    status_produksi => $p->status_produksi_akhir ?? 'belum_upload',
                    'file_skenario' => $p->file_skenario,
                    'file_storyboard' => $p->file_storyboard,
                    'file_dokumen_pendukung' => $p->file_dokumen_pendukung,
                    'file_produksi_akhir' => $p->file_produksi_akhir,
                    'feedback_pra_produksi' => $p->feedback_pra_produksi,
                    'feedback_produksi_akhir' => $p->feedback_produksi_akhir,
                    'tanggal_upload_pra' => $p->tanggal_upload_pra?->format('Y-m-d H:i:s'),
                    'tanggal_upload_akhir' => $p->tanggal_upload_akhir?->format('Y-m-d H:i:s'),
                ];
            })
            ->toArray();

        return $produksiList;
    }

    /**
     * Approve/Revisi/Tolak Pra Produksi
     */
    public function approvePraProduksi(Request $request, $id)
    {
        try {
            // Validasi input - support both 'status' dan 'produksi_status' field names
            $statusField = $request->has('status') ? 'status' : 'produksi_status';
            $feedbackField = $request->has('feedback') ? 'feedback' : 'produksi_feedback';
            
            $validated = $request->validate([
                $statusField => 'required|in:disetujui,revisi,ditolak',
                $feedbackField => 'required|string|min:5',
            ]);

            // Cari produksi berdasarkan ID
            $produksi = Produksi::findOrFail($id);

            // Otorisasi: cek apakah dosen adalah pembimbing mahasiswa ini
            // Simplified: asumsikan akses sudah diverifikasi di middleware
            
            $status = $validated[$statusField];
            $feedback = $validated[$feedbackField];
            
            // Update status dan feedback
            $produksi->update([
                'status_pra_produksi' => $status,
                'feedback_pra_produksi' => $feedback,
                'tanggal_review_pra' => now(),
            ]);

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
     */
    public function rejectPraProduksi(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'feedback' => 'required|string|min:5',
            ]);

            // Cari produksi
            $produksi = Produksi::findOrFail($id);

            // Update status ke ditolak
            $produksi->update([
                'status_pra_produksi' => 'ditolak',
                'feedback_pra_produksi' => $validated['feedback'],
                'tanggal_review_pra' => now(),
            ]);

            return $this->handleResponse($request, 'success', '❌ Pra Produksi telah ditolak.', 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', array_values($e->errors())[0]), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Approve/Revisi/Tolak Produksi Akhir
     */
    public function approveProduksiAkhir(Request $request, $id)
    {
        try {
            // Validasi input - support both 'status' dan 'produksi_status' field names
            $statusField = $request->has('status') ? 'status' : 'produksi_status';
            $feedbackField = $request->has('feedback') ? 'feedback' : 'produksi_feedback';
            
            $validated = $request->validate([
                $statusField => 'required|in:disetujui,revisi,ditolak',
                $feedbackField => 'required|string|min:5',
            ]);

            // Cari produksi berdasarkan ID
            $produksi = Produksi::findOrFail($id);

            $status = $validated[$statusField];
            $feedback = $validated[$feedbackField];
            
            // Update status dan feedback
            $produksi->update([
                status_produksi => $status,
                'feedback_produksi_akhir' => $feedback,
                'tanggal_review_akhir' => now(),
            ]);

            $messages = [
                'disetujui' => '✅ Produksi Akhir berhasil disetujui!',
                'revisi' => '⚠️ Feedback revisi telah dikirim ke mahasiswa.',
                'ditolak' => '❌ Produksi Akhir ditolak.'
            ];
            $message = $messages[$status] ?? 'Produksi Akhir berhasil diproses.';

            return $this->handleResponse($request, 'success', $message, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = array_values($e->errors())[0] ?? [];
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', $errors), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reject Produksi Akhir
     */
    public function rejectProduksiAkhir(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'feedback' => 'required|string|min:5',
            ]);

            // Cari produksi
            $produksi = Produksi::findOrFail($id);

            // Update status ke ditolak
            $produksi->update([
                status_produksi => 'ditolak',
                'feedback_produksi_akhir' => $validated['feedback'],
                'tanggal_review_akhir' => now(),
            ]);

            return $this->handleResponse($request, 'success', '❌ Produksi Akhir telah ditolak.', 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleResponse($request, 'error', 'Validasi gagal: ' . implode(', ', array_values($e->errors())[0]), 422);
        } catch (\Exception $e) {
            return $this->handleResponse($request, 'error', '❌ Gagal memproses: ' . $e->getMessage(), 500);
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

        return redirect()->back()->with(
            $status === 'success' ? 'success' : 'error',
            $message
        );
    }
}
