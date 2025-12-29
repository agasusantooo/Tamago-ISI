<?php

namespace App\Http\Controllers\KoordinatorTefa;

use App\Http\Controllers\Controller;
use App\Models\TefaFair;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $registrations = TefaFair::with('mahasiswa.user')->get();

        return view('koordinator_tefa.monitoring', compact('registrations'));
    }

    public function approve(Request $request, $id)
    {
        if (! auth()->user() || ! auth()->user()->hasRole('koordinator_tefa')) {
            abort(403, 'Unauthorized');
        }

        $registration = TefaFair::findOrFail($id);

        if (in_array($registration->status, ['disetujui', 'ditolak'])) {
            return redirect()->route('koordinator_tefa.monitoring')->with('warning', 'Status pendaftaran sudah final.');
        }

        try {
            $registration->status = 'disetujui';
            $registration->save();

            \Log::info('TEFA registration approved', ['id' => $registration->{$registration->getKeyName()}, 'by' => auth()->id()]);

            return redirect()->route('koordinator_tefa.monitoring')->with('success', 'Pendaftaran TEFA Fair berhasil disetujui.');
        } catch (\Exception $e) {
            \Log::error('Failed to approve TEFA registration', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('koordinator_tefa.monitoring')->with('error', 'Terjadi kesalahan saat menyetujui pendaftaran.');
        }
    }

    public function reject(Request $request, $id)
    {
        if (! auth()->user() || ! auth()->user()->hasRole('koordinator_tefa')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'reason' => 'nullable|string|min:5|max:1000',
        ], [
            'reason.min' => 'Alasan penolakan harus minimal 5 karakter.',
            'reason.max' => 'Alasan penolakan terlalu panjang.',
        ]);

        $registration = TefaFair::findOrFail($id);

        if (in_array($registration->status, ['disetujui', 'ditolak'])) {
            return redirect()->route('koordinator_tefa.monitoring')->with('warning', 'Status pendaftaran sudah final.');
        }

        try {
            $registration->status = 'ditolak';
            // TEFA model doesn't have a dedicated note column; log the reason if provided
            if ($request->filled('reason')) {
                \Log::info('TEFA registration rejected with reason', ['id' => $registration->{$registration->getKeyName()}, 'reason' => $request->input('reason'), 'by' => auth()->id()]);
            } else {
                \Log::info('TEFA registration rejected', ['id' => $registration->{$registration->getKeyName()}, 'by' => auth()->id()]);
            }

            $registration->save();

            return redirect()->route('koordinator_tefa.monitoring')->with('success', 'Pendaftaran TEFA Fair berhasil ditolak.');
        } catch (\Exception $e) {
            \Log::error('Failed to reject TEFA registration', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('koordinator_tefa.monitoring')->with('error', 'Terjadi kesalahan saat menolak pendaftaran.');
        }
    }
}
