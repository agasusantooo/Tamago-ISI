<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4">Jadwal Story Conference</h2>

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
                    Tanggal
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Waktu
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($storyConferences as $sc)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $sc->projekAkhir->judul }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $sc->projekAkhir->mahasiswa->nama }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $sc->tanggal }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $sc->waktu }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-8">

    @if ($storyConference)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Status Pendaftaran Story Conference</h2>
            <p><strong>Judul Karya:</strong> {{ $storyConference->projekAkhir->judul }}</p>
            <p><strong>Tanggal Pendaftaran:</strong> {{ $storyConference->tanggal }}</p>
            <p><strong>Waktu:</strong> {{ $storyConference->waktu }}</p>
            <p><strong>Status:</strong> {{ $storyConference->status }}</p>
            <p><strong>Catatan Evaluasi:</strong> {{ $storyConference->catatan_evaluasi }}</p>
        </div>
    @else
        <form wire:submit.prevent="store" enctype="multipart/form-data">
            <h2 class="text-2xl font-bold mb-4">Pendaftaran Story Conference</h2>
            <div class="mt-4">
                <label for="waktu" class="block font-medium text-sm text-gray-700">Slot Waktu</label>
                <input type="text" wire:model="waktu" id="waktu" class="block mt-1 w-full">
                @error('waktu') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <label for="file" class="block font-medium text-sm text-gray-700">File</label>
                <input type="file" wire:model="file" id="file" class="block mt-1 w-full">
                @error('file') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Daftar Story Conference
                </button>
            </div>
        </form>
    @endif
</div>