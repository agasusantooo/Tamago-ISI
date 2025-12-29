@extends('mahasiswa.layouts.app')

@section('page-title', 'Progress Tugas Akhir')

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

        {{-- Flash messages are handled in the layout; avoid duplicating here. --}}

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
                <tbody id="proposalHistoryBody" class="bg-white divide-y divide-gray-200">
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

@section('scripts')
<script>
    async function fetchProposalUpdates() {
        try {
            const res = await fetch("{{ route('mahasiswa.proposal.check-updates') }}", { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const json = await res.json();
            if (!json.success) return;
            const tbody = document.getElementById('proposalHistoryBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            const proposals = json.proposals || [];
            if (proposals.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Anda belum pernah mengajukan proposal.</td></tr>`;
                return;
            }
            proposals.forEach(function(p, idx){
                const tanggal = p.tanggal_pengajuan ? new Date(p.tanggal_pengajuan).toLocaleDateString() : '-';
                const status = p.status || 'N/A';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${idx + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${p.judul ?? 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${tanggal}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"> <span class="px-3 py-1 text-xs font-semibold rounded-full">${status}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><a href="#" class="text-yellow-600 hover:text-yellow-900">Detail</a></td>
                `;
                tbody.appendChild(row);
            });
        } catch (e) {
            console.error('Failed to fetch proposals updates', e);
        }
    }
    document.addEventListener('DOMContentLoaded', function(){
        fetchProposalUpdates();
        setInterval(fetchProposalUpdates, 15000);
    });
</script>
@endsection
