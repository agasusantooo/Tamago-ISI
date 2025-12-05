<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Traits\MapsUjianStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\UjianTA;
use App\Models\Produksi;
use App\Models\Proposal;
use App\Models\ProjekAkhir;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UjianTAController extends Controller
{
    use MapsUjianStatus;

    /**
     * Display ujian TA page (Informasi & Pendaftaran)
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Get approved proposal
        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->where('status', 'disetujui')
            ->latest()
            ->first();
        
        $missingProposal = false;
        if (!$proposal) {
            // Instead of redirecting, show the page with a clear message and CTA
            $missingProposal = true;
        }

        // Check if produksi akhir approved
        // produksi.mahasiswa_id references users.id
        $produksi = null;
        if ($proposal) {
            $produksi = Produksi::where('mahasiswa_id', $user->id)
                ->where('proposal_id', $proposal->id)
                ->first();
        }

        $produksiNotApproved = false;
        if (!$produksi || $produksi->status_produksi !== 'disetujui') {
            // show page with message instead of redirect so mahasiswa tahu langkah selanjutnya
            $produksiNotApproved = true;
        }
        
        // Find projek_akhir for this mahasiswa and use it to get ujian TA
        $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
        $ujianTA = $projek ? UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->first() : null;

        // Pass flags to the view for UX guidance
        return view('mahasiswa.ujian-ta', compact('proposal', 'produksi', 'ujianTA', 'missingProposal', 'produksiNotApproved', 'projek'));
    }

    /**
     * Store ujian TA registration
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_surat_pengantar' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'file_transkrip_nilai' => 'required|file|mimes:pdf|max:5120', // 5MB
        ], [
            'file_surat_pengantar.required' => 'Surat pengantar wajib diunggah',
            'file_surat_pengantar.mimes' => 'Surat pengantar harus berformat PDF atau DOC',
            'file_surat_pengantar.max' => 'Surat pengantar maksimal 5 MB',
            'file_transkrip_nilai.required' => 'Transkrip nilai wajib diunggah',
            'file_transkrip_nilai.mimes' => 'Transkrip nilai harus berformat PDF',
            'file_transkrip_nilai.max' => 'Transkrip nilai maksimal 5 MB',
        ]);

        if ($validator->fails()) {
            Log::info('UjianTA store validation failed', ['user_id' => optional(Auth::user())->id, 'errors' => $validator->errors()->all()]);
            return back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('UjianTA store called', ['user_id' => optional(Auth::user())->id]);
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;

            // Get proposal
            $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                ->where('status', 'disetujui')
                ->latest()
                ->first();

            if (!$proposal) {
                return back()->with('error', 'Proposal belum disetujui');
            }

            // Find projek_akhir for this mahasiswa. If missing, try to create one from the approved proposal.
            $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
            if (!$projek) {
                // create projek_akhir based on proposal to streamline registration
                $judul = $proposal->judul ?? ('Projek Akhir ' . $mahasiswa->nim);
                $projekData = [
                    'nim' => $mahasiswa->nim,
                    'judul' => $judul,
                    'file_proposal' => $proposal->file_proposal ?? null,
                    'status' => 'aktif',
                ];

                $projek = ProjekAkhir::create($projekData);
                Log::info('ProjekAkhir auto-created for ujian registration', ['user_id' => $user->id, 'projek_id' => $projek->id_proyek_akhir]);
            }

            // Check if already registered by projek. Only block new registration when
            // an active/ongoing registration already exists (avoid duplicate active rows).
            $existing = UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first();

            // These pendaftaran statuses indicate an active or non-final registration
            $blockedStatuses = ['pengajuan_ujian', 'jadwal_ditetapkan', 'ujian_berlangsung'];

            if ($existing && in_array($existing->status_pendaftaran, $blockedStatuses)) {
                Log::info('UjianTA already exists for projek', ['user_id' => $user->id, 'projek_id' => $projek->id_proyek_akhir, 'status' => $existing->status_pendaftaran]);
                return back()->with('error', 'Anda sudah terdaftar ujian TA');
            }

            // Upload files
            $fileSuratPengantar = null;
            $fileTranskrip = null;

            if ($request->hasFile('file_surat_pengantar')) {
                $file = $request->file('file_surat_pengantar');
                $fileName = 'surat_pengantar_' . time() . '.' . $file->getClientOriginalExtension();
                $fileSuratPengantar = $file->storeAs('ujian-ta/' . $user->id, $fileName, 'public');
            }

            if ($request->hasFile('file_transkrip_nilai')) {
                $file = $request->file('file_transkrip_nilai');
                $fileName = 'transkrip_' . time() . '.pdf';
                $fileTranskrip = $file->storeAs('ujian-ta/' . $user->id, $fileName, 'public');
            }

            Log::info('UjianTA files stored', ['user_id' => $user->id, 'surat' => $fileSuratPengantar, 'transkrip' => $fileTranskrip]);

            // Create ujian TA using only columns that exist in the DB to avoid migration mismatch errors
            $cols = Schema::getColumnListing('ujian_tugas_akhir');
            $data = [];
            if (in_array('id_proyek_akhir', $cols)) $data['id_proyek_akhir'] = $projek->id_proyek_akhir;
            if (in_array('dosen_pembimbing_id', $cols)) $data['dosen_pembimbing_id'] = $proposal->dosen_id ?? null;
            if (in_array('file_surat_pengantar', $cols)) $data['file_surat_pengantar'] = $fileSuratPengantar;
            if (in_array('file_transkrip_nilai', $cols)) $data['file_transkrip_nilai'] = $fileTranskrip;
            // When the DB column is an ENUM, ensure we insert a valid enum value to avoid truncation warnings.
            if (in_array('status_pendaftaran', $cols)) {
                $desired = 'pengajuan_ujian';
                // try to read allowed enum values from DB
                $allowed = null;
                try {
                    $col = DB::select("SHOW COLUMNS FROM ujian_tugas_akhir LIKE 'status_pendaftaran'");
                    if (!empty($col) && isset($col[0]->Type)) {
                        // Type looks like: enum('val1','val2',...)
                        preg_match_all("/'([^']+)'/", $col[0]->Type, $m);
                        $allowed = $m[1] ?? [];
                    }
                } catch (\Throwable $t) {
                    // ignore — fallback to default
                    $allowed = null;
                }

                if (is_array($allowed) && count($allowed) > 0) {
                    if (in_array($desired, $allowed)) {
                        $data['status_pendaftaran'] = $desired;
                    } elseif (in_array(str_replace('_', '-', $desired), $allowed)) {
                        $data['status_pendaftaran'] = str_replace('_', '-', $desired);
                    } elseif (in_array(str_replace('_', ' ', $desired), $allowed)) {
                        $data['status_pendaftaran'] = str_replace('_', ' ', $desired);
                    } else {
                        // fallback to first allowed value to guarantee DB insert succeeds
                        $data['status_pendaftaran'] = $allowed[0];
                    }
                } else {
                    $data['status_pendaftaran'] = $desired;
                }
            }
            if (in_array('status_ujian', $cols)) {
                $data['status_ujian'] = $this->mapUjianStatus('belum_ujian');
            }
            if (in_array('tanggal_daftar', $cols)) $data['tanggal_daftar'] = now();

            $ujianTA = UjianTA::create($data);

            Log::info('UjianTA created', ['user_id' => $user->id, 'ujian_id' => $ujianTA->id_ujian ?? null]);

            return redirect()
                ->route('mahasiswa.ujian-ta.index')
                ->with('success', 'Pendaftaran ujian TA berhasil! Menunggu jadwal dari koordinator.');

        } catch (\Exception $e) {
            // Log the exception so we can inspect DB / query errors that prevented creation
            Log::error('UjianTA store exception: ' . $e->getMessage(), [
                'user_id' => optional(Auth::user())->id,
                'exception' => $e,
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Map a desired status string to an allowed enum value for a table column.
     * Returns a valid enum value present in the DB or a sensible fallback.
     */
    protected function mapToEnumValueForTable(string $table, string $column, $desired)
    {
        $desiredNorm = $this->normalizeString($desired);

        try {
            $col = DB::select("SHOW COLUMNS FROM {$table} LIKE ?", [$column]);
            $allowed = [];
            if (!empty($col) && isset($col[0]->Type)) {
                preg_match_all("/'([^']+)'/", $col[0]->Type, $m);
                $allowed = $m[1] ?? [];
            }
        } catch (\Throwable $t) {
            $allowed = [];
        }

        $normMap = [];
        foreach ($allowed as $val) {
            $normMap[$this->normalizeString($val)] = $val;
        }

        if (isset($normMap[$desiredNorm])) {
            return $normMap[$desiredNorm];
        }

        $alt = str_replace(['_', '-'], ' ', $desired);
        $altNorm = $this->normalizeString($alt);
        if (isset($normMap[$altNorm])) {
            return $normMap[$altNorm];
        }

        $synonyms = [
            'belumujian' => 'Menunggu_hasil',
            'ujianberlangsung' => 'Berlangsung',
            'selesaiujian' => 'Selesai',
            'lulus' => 'Lulus',
            'tidaklulus' => 'Tidak Lulus',
            'menungguhasil' => 'Menunggu_hasil',
        ];
        $d = $desiredNorm;
        if (isset($synonyms[$d]) && in_array($synonyms[$d], $allowed)) {
            return $synonyms[$d];
        }

        return $allowed[0] ?? $desired;
    }

    protected function normalizeString($s)
    {
        if (is_null($s)) return '';
        $s = mb_strtolower((string)$s);
        $s = str_replace([' ', '_', '-'], '', $s);
        $s = preg_replace('/[^a-z0-9]/u', '', $s);
        return $s;
    }

    /**
     * Display hasil ujian page
     */
    public function hasil()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Get ujian TA data by projek_akhir
        $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
        $ujianTA = $projek ? UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first() : null;

        if (!$ujianTA) {
            return redirect()->route('mahasiswa.ujian-ta.index')
                ->with('error', 'Anda belum terdaftar ujian TA');
        }

        // Check if ujian already done
        if ($ujianTA->status_ujian !== 'selesai_ujian') {
            return redirect()->route('mahasiswa.ujian-ta.index')
                ->with('error', 'Ujian belum dilaksanakan');
        }
        
        // The application uses `resources/views/mahasiswa/ujian-result.blade.php` for the hasil view.
        // Render that existing view to avoid missing view errors.
        return view('mahasiswa.ujian-result', compact('ujianTA'));
    }

    /**
     * Submit revisi pasca ujian
     */
    public function submitRevisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_revisi' => 'required|file|mimes:pdf,doc,docx|max:51200', // 50MB
            'deskripsi_revisi' => 'nullable|string|max:1000',
        ], [
            'file_revisi.required' => 'File revisi wajib diunggah',
            'file_revisi.mimes' => 'File revisi harus berformat PDF atau DOC',
            'file_revisi.max' => 'File revisi maksimal 50 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;

            // Get ujian TA by projek_akhir
            $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
            $ujianTA = $projek ? UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first() : null;
            
            if (!$ujianTA) {
                return back()->with('error', 'Data ujian tidak ditemukan');
            }

            // Upload file revisi
            $fileRevisi = null;
            if ($request->hasFile('file_revisi')) {
                $file = $request->file('file_revisi');
                $fileName = 'revisi_' . time() . '.' . $file->getClientOriginalExtension();
                $fileRevisi = $file->storeAs('ujian-ta/' . $user->id, $fileName, 'public');
            }

            $ujianTA->update([
                'file_revisi' => $fileRevisi,
                'deskripsi_revisi' => $request->deskripsi_revisi,
                'status_revisi' => 'menunggu_persetujuan',
                'tanggal_submit_revisi' => now(),
            ]);

            return redirect()
                ->route('mahasiswa.ujian-ta.hasil')
                ->with('success', 'Revisi berhasil disubmit! Menunggu persetujuan dosen.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download file
     */
    public function download($id, $type)
    {
        $ujianTA = UjianTA::findOrFail($id);
        
        // Check authorization
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Authorize by checking the projek_akhir owner (projek stores nim)
        $projek = ProjekAkhir::where('id_proyek_akhir', $ujianTA->id_proyek_akhir)->first();
        if (!$projek || $projek->nim !== $mahasiswa->nim) {
            abort(403, 'Unauthorized action.');
        }

        $filePath = null;
        switch ($type) {
            case 'surat':
                $filePath = $ujianTA->file_surat_pengantar;
                break;
            case 'transkrip':
                $filePath = $ujianTA->file_transkrip_nilai;
                break;
            case 'revisi':
                $filePath = $ujianTA->file_revisi;
                break;
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filePath);
    }
}