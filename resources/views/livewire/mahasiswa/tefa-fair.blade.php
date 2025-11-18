<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4">Jadwal Tefa Fair</h2>

    <table class="min-w-full divide-y divide-gray-200 mt-4">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Judul Karya
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Mahasiswa
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Semester
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($tefaFairs as $tf)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $tf->projekAkhir->judul }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $tf->projekAkhir->mahasiswa->nama }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $tf->semester }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-8">

    @if ($tefaFair)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Status Pendaftaran Tefa Fair</h2>
            <p><strong>Judul Karya:</strong> {{ $tefaFair->projekAkhir->judul }}</p>
            <p><strong>Semester:</strong> {{ $tefaFair->semester }}</p>
            <p><strong>File Presentasi:</strong> <a href="{{ asset('storage/' . $tefaFair->file_presentasi) }}" target="_blank">Lihat File</a></p>
            <p><strong>Daftar Kebutuhan:</strong> {{ $tefaFair->daftar_kebutuhan }}</p>
        </div>
    @else
        <form wire:submit.prevent="store" enctype="multipart/form-data">
            <h2 class="text-2xl font-bold mb-4">Pendaftaran Tefa Fair</h2>
            <div class="mt-4">
                <label for="semester" class="block font-medium text-sm text-gray-700">Semester</label>
                <input type="text" wire:model="semester" id="semester" class="block mt-1 w-full">
                @error('semester') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <label for="file_presentasi" class="block font-medium text-sm text-gray-700">File Presentasi</label>
                <input type="file" wire:model="file_presentasi" id="file_presentasi" class="block mt-1 w-full">
                @error('file_presentasi') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <label for="daftar_kebutuhan" class="block font-medium text-sm text-gray-700">Daftar Kebutuhan</label>
                <textarea wire:model="daftar_kebutuhan" id="daftar_kebutuhan" class="block mt-1 w-full"></textarea>
                @error('daftar_kebutuhan') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Daftar Tefa Fair
                </button>
            </div>
        </form>
    @endif
</div>