@extends('mahasiswa.layouts.app')

@section('title', 'Riwayat Pengajuan Proposal - Tamago ISI')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Riwayat Pengajuan Proposal</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar semua proposal yang pernah Anda ajukan.</p>
            </div>
            <a href="{{ route('mahasiswa.proposal.create') }}" class="bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-800 transition">
                <i class="fas fa-plus mr-2"></i>Ajukan Proposal Baru
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($proposalHistory as $proposal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($proposal->judul, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $proposal->tanggal_pengajuan ? $proposal->tanggal_pengajuan->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php $badge = $proposal->statusBadge; @endphp
                                <span class="{{ $badge['class'] }} px-3 py-1 text-xs font-semibold rounded-full">
                                    {{ $badge['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('mahasiswa.proposal.show', $proposal->id) }}" class="text-yellow-600 hover:text-yellow-900">Detail</a>
                                @if($proposal->status == 'revisi')
                                    <a href="{{ route('mahasiswa.proposal.edit', $proposal->id) }}" class="text-blue-600 hover:text-blue-900 ml-4">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum pernah mengajukan proposal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
