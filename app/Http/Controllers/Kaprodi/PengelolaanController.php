<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\RumpunIlmu;
use App\Models\User;
use App\Models\Role;

class PengelolaanController extends Controller
{
    /**
     * Display the management page.
     */
    public function index()
    {
        $dosens = Dosen::with('user')->get();
        
        $jabatanOptions = [
            'Asisten Ahli' => 'Asisten Ahli',
            'Lektor' => 'Lektor',
            'Lektor Kepala' => 'Lektor Kepala',
            'Guru Besar' => 'Guru Besar',
        ];

        return view('kaprodi.pengelolaan', compact('dosens', 'jabatanOptions'));
    }

    /**
     * Update the functional rank of a lecturer.
     */
    public function updateDosen(Request $request, Dosen $dosen)
    {
        $request->validate([
            'jabatan_fungsional' => 'required|string|in:Asisten Ahli,Lektor,Lektor Kepala,Guru Besar',
        ]);

        $dosen->update([
            'jabatan_fungsional' => $request->jabatan_fungsional,
        ]);

        return redirect()->route('kaprodi.pengelolaan')->with('success', 'Jabatan fungsional dosen berhasil diperbarui.');
    }

    /**
     * Display the Rumpun Ilmu management page.
     */
    public function rumpunIlmuIndex()
    {
        $rumpunIlmus = RumpunIlmu::with('dosens.user')->get();
        $dosens = Dosen::with('user')->get();
        return view('kaprodi.rumpun-ilmu', compact('rumpunIlmus', 'dosens'));
    }

    /**
     * Store a new Rumpun Ilmu.
     */
    public function rumpunIlmuStore(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:rumpun_ilmus,nama']);
        RumpunIlmu::create($request->all());
        return redirect()->route('kaprodi.rumpun-ilmu')->with('success', 'Rumpun Ilmu berhasil ditambahkan.');
    }

    /**
     * Assign a Dosen to a Rumpun Ilmu.
     */
    public function assignDosenToRumpun(Request $request)
    {
        $request->validate([
            'dosen_nidn' => 'required|exists:dosen,nidn',
            'rumpun_ilmu_id' => 'required|exists:rumpun_ilmus,id',
        ]);

        $dosen = Dosen::find($request->dosen_nidn);
        $dosen->rumpunIlmus()->syncWithoutDetaching([$request->rumpun_ilmu_id]);

        return redirect()->route('kaprodi.rumpun-ilmu')->with('success', 'Dosen berhasil ditambahkan ke Rumpun Ilmu.');
    }

    /**
     * Display the Dosen Seminar management page.
     */
    public function dosenSeminarIndex()
    {
        $dosens = Dosen::with('user')->get();
        return view('kaprodi.dosen-seminar', compact('dosens'));
    }

    /**
     * Update the list of Dosen Seminar.
     */
    public function dosenSeminarUpdate(Request $request)
    {
        $request->validate([
            'dosen_nidns' => 'array',
            'dosen_nidns.*' => 'string|exists:dosen,nidn',
        ]);

        // Reset all lecturers
        Dosen::query()->update(['is_dosen_seminar' => false]);

        // Set the selected lecturers
        if ($request->has('dosen_nidns')) {
            Dosen::whereIn('nidn', $request->dosen_nidns)->update(['is_dosen_seminar' => true]);
        }

        return redirect()->route('kaprodi.dosen-seminar')->with('success', 'Dosen Seminar berhasil diperbarui.');
    }

    /**
     * Display the Koordinator TEFA management page.
     */
    public function koordinatorTefaIndex()
    {
        $dosens = Dosen::with('user')->whereHas('user')->get();
        $koordinatorTefaRole = Role::where('name', 'koordinator_tefa')->first();
        $currentCoordinator = $koordinatorTefaRole ? User::where('role_id', $koordinatorTefaRole->id)->first() : null;

        return view('kaprodi.koordinator-tefa', compact('dosens', 'currentCoordinator'));
    }

    /**
     * Update the Koordinator TEFA.
     */
    public function koordinatorTefaUpdate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $koordinatorTefaRole = Role::where('name', 'koordinator_tefa')->firstOrFail();
        
        // Remove the role from the old coordinator
        User::where('role_id', $koordinatorTefaRole->id)->update(['role_id' => null]); // Or assign a default 'dosen' role

        // Assign the role to the new coordinator
        $newCoordinator = User::find($request->user_id);
        $newCoordinator->role_id = $koordinatorTefaRole->id;
        $newCoordinator->save();

        return redirect()->route('kaprodi.koordinator-tefa')->with('success', 'Koordinator TEFA berhasil diperbarui.');
    }
}
