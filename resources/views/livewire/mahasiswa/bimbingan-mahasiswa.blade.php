<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="store">
        <div class="mt-4">
            <label for="tanggal" class="block font-medium text-sm text-gray-700">Tanggal</label>
            <input type="date" wire:model="tanggal" id="tanggal" class="block mt-1 w-full">
            @error('tanggal') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="catatan_bimbingan" class="block font-medium text-sm text-gray-700">Catatan Bimbingan</label>
            <textarea wire:model="catatan_bimbingan" id="catatan_bimbingan" class="block mt-1 w-full"></textarea>
            @error('catatan_bimbingan') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="pencapaian" class="block font-medium text-sm text-gray-700">Pencapaian</label>
            <input type="text" wire:model="pencapaian" id="pencapaian" class="block mt-1 w-full">
            @error('pencapaian') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="selectedNidn" class="block font-medium text-sm text-gray-700">Dosen Pembimbing</label>
            <select wire:model="selectedNidn" id="selectedNidn" class="block mt-1 w-full">
                <option value="">Pilih Dosen Pembimbing</option>
                @foreach ($pembimbings as $pembimbing)
                    <option value="{{ $pembimbing->nidn }}">{{ $pembimbing->nama }}</option>
                @endforeach
            </select>
            @error('selectedNidn') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Ajukan Bimbingan
            </button>
        </div>
    </form>

    <hr class="my-8">

    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Riwayat Bimbingan
    </h2>

    <table class="min-w-full divide-y divide-gray-200 mt-4">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Tanggal
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Catatan Bimbingan
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Pencapaian
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($bimbingans as $bimbingan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $bimbingan->tanggal }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $bimbingan->catatan_bimbingan }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $bimbingan->pencapaian }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $bimbingan->status_persetujuan }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>