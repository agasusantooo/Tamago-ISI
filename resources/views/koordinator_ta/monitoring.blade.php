@extends('koordinator_ta.layouts.app')

@section('title', 'Monitoring Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-green-800 mb-4">Monitoring & Persetujuan Tahapan Mahasiswa</h3>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-green-50 border-b border-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Judul TA</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-green-50">
                    @forelse($projekAkhirs as $projek)
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $projek->mahasiswa->nim ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $projek->mahasiswa->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $projek->proposal->judul ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">{{ Str::title(str_replace('_', ' ', $projek->status ?? 'Belum ada status')) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <form action="{{ route('koordinator_ta.monitoring.approve', $projek->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Setujui</button>
                            </form>
                            <form action="{{ route('koordinator_ta.monitoring.reject', $projek->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada data proyek akhir untuk ditampilkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

