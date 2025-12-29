@extends('mahasiswa.layouts.app')

@section('title', 'Produksi - Tamago ISI')
@section('page-title', 'Progress Tugas Akhir')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Flash messages are handled in the layout; avoid duplicating here. --}}

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Ringkasan Produksi</h3>
                <p class="text-sm text-gray-600 mt-1">Status dan ringkasan tahapan produksi Anda.</p>
            </div>
            <a href="{{ route('mahasiswa.produksi.manage') }}" class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition">
                <i class="fas fa-tasks mr-2"></i>Kelola Produksi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Proyek</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="produksiListBody" class="bg-white divide-y divide-gray-200">
                    @forelse ($produksis as $produksi)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit(optional($produksi->proposal)->judul ?? 'N/A', 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    // Compute a stage-aware status label and class
                                    if ((($produksi->status_pra_produksi === 'disetujui' || $produksi->status_pra_produksi === 'selesai') &&
                                        ($produksi->status_produksi === 'disetujui' || $produksi->status_produksi === 'selesai') &&
                                        ($produksi->status_pasca_produksi === 'disetujui' || $produksi->status_pasca_produksi === 'selesai'))) {
                                        $statusText = 'Selesai';
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($produksi->status_pasca_produksi === 'revisi') {
                                        $statusText = 'Revisi Pasca-Produksi';
                                        $statusClass = 'bg-orange-300 text-orange-900';
                                    } elseif ($produksi->status_pasca_produksi === 'menunggu_review') {
                                        $statusText = 'Review Pasca-Produksi';
                                        $statusClass = 'bg-yellow-300 text-yellow-900';
                                    } elseif ($produksi->status_produksi === 'revisi') {
                                        $statusText = 'Revisi Produksi';
                                        $statusClass = 'bg-orange-200 text-orange-900';
                                    } elseif ($produksi->status_produksi === 'menunggu_review') {
                                        $statusText = 'Menunggu Review Produksi';
                                        $statusClass = 'bg-yellow-200 text-yellow-900';
                                    } elseif ($produksi->status_pra_produksi === 'revisi') {
                                        $statusText = 'Revisi Pra-Produksi';
                                        $statusClass = 'bg-orange-100 text-orange-800';
                                    } elseif ($produksi->status_pra_produksi === 'menunggu_review') {
                                        $statusText = 'Menunggu Review Pra-Produksi';
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($produksi->status_pra_produksi === 'disetujui') {
                                        $statusText = 'Disetujui Pra-Produksi';
                                        $statusClass = 'bg-green-50 text-green-800';
                                    } else {
                                        $statusText = $produksi->overallStatusBadge['text'] ?? 'Belum Dimulai';
                                        $statusClass = $produksi->overallStatusBadge['class'] ?? 'bg-gray-100 text-gray-800';
                                    }
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('mahasiswa.produksi.manage', $produksi->id) }}" class="text-yellow-600 hover:text-yellow-900">Kelola</a>
                            </td>
                        </tr>
                        {{-- No sub-row; the status cell above shows the current stage (pra/produksi/pasca) in one row. --}}
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum memiliki proyek produksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function fetchProduksiUpdates() {
        try {
            const res = await fetch("{{ route('mahasiswa.produksi.check-updates') }}", { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const json = await res.json();
            if (!json.success) return;

            const tbody = document.getElementById('produksiListBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            const produksis = json.produksi || [];
            if (produksis.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Anda belum memiliki proyek produksi.</td></tr>`;
                return;
            }
            const manageBase = "{{ url('mahasiswa/produksi/manage') }}";
            produksis.forEach(function(p, idx) {
                function computeBadge(prod) {
                    if ((prod.status_pasca_produksi === 'disetujui' && prod.status_produksi === 'disetujui' && prod.status_pra_produksi === 'disetujui') ||
                        (prod.status_pasca_produksi === 'selesai' && prod.status_produksi === 'selesai' && prod.status_pra_produksi === 'selesai')) {
                        return { text: 'Selesai', class: 'bg-green-100 text-green-800' };
                    }
                    if (prod.status_pasca_produksi === 'revisi') return { text: 'Revisi Pasca-Produksi', class: 'bg-orange-300 text-orange-900' };
                    if (prod.status_pasca_produksi === 'menunggu_review') return { text: 'Review Pasca-Produksi', class: 'bg-yellow-300 text-yellow-900' };
                    if (prod.status_produksi === 'revisi') return { text: 'Revisi Produksi', class: 'bg-orange-200 text-orange-900' };
                    if (prod.status_produksi === 'menunggu_review') return { text: 'Menunggu Review Produksi', class: 'bg-yellow-200 text-yellow-900' };
                    if (prod.status_pra_produksi === 'revisi') return { text: 'Revisi Pra-Produksi', class: 'bg-orange-100 text-orange-800' };
                    if (prod.status_pra_produksi === 'menunggu_review') return { text: 'Menunggu Review Pra-Produksi', class: 'bg-yellow-100 text-yellow-800' };
                    if (prod.status_pra_produksi === 'disetujui') return { text: 'Disetujui Pra-Produksi', class: 'bg-green-50 text-green-800' };
                    return { text: 'Belum Dimulai', class: 'bg-gray-100 text-gray-800' };
                }

                const badge = computeBadge(p);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${idx + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${p.judul ?? 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-3 py-1 text-xs font-semibold rounded-full ${badge.class}">${badge.text}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><a href="${manageBase}/${p.id}" class="text-yellow-600 hover:text-yellow-900">Kelola</a></td>
                `;
                tbody.appendChild(row);
            });
            });
        } catch (e) {
            console.error('Failed to fetch produksi updates', e);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchProduksiUpdates();
        setInterval(fetchProduksiUpdates, 15000);
    });
</script>
@endsection