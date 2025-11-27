@extends('kaprodi.layouts.app')

@section('title', 'Dashboard Kaprodi TA')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Total Mahasiswa TA</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalMahasiswa }}</p>
                <p class="text-xs text-green-600 mt-2">+12 dari semester lalu</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-teal-500">
                <p class="text-sm text-gray-600">Sudah Ujian</p>
                <p class="text-3xl font-bold text-teal-600">{{ $sudahUjian }}</p>
                <p class="text-xs text-teal-600 mt-2">{{ $totalMahasiswa ? round(($sudahUjian/$totalMahasiswa)*100,1) : 0 }}% dari total</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Lulus</p>
                <p class="text-3xl font-bold text-green-600">{{ $lulus }}</p>
                <p class="text-xs text-green-600 mt-2">{{ $totalMahasiswa ? round(($lulus/$totalMahasiswa)*100,1) : 0 }}% tingkat kelulusan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-800">Tugas Menunggu Persetujuan</h4>
                        <span class="text-xs text-gray-500">{{ $tugasMenungguPersetujuan->count() }} Pending</span>
                    </div>
                    <div class="space-y-3">
                        @forelse($tugasMenungguPersetujuan as $task)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">Mahasiswa: {{ $task->student }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(isset($task->id))
                                    <form method="POST" action="{{ route('kaprodi.tasks.approve', $task->id) }}" class="kaprodi-task-form inline-block mr-1">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-xs approve-btn" data-intent="approve">✔</button>
                                    </form>

                                    <form method="POST" action="{{ route('kaprodi.tasks.reject', $task->id) }}" class="kaprodi-task-form inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-xs reject-btn" data-intent="reject">✖</button>
                                    </form>
                                @else
                                    <button disabled class="px-3 py-1 bg-gray-300 text-white rounded text-xs">✔</button>
                                    <button disabled class="px-3 py-1 bg-gray-300 text-white rounded text-xs">✖</button>
                                @endif
                            </div>
                        </div>
                    </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Attach click handlers to kaprodi approve/reject buttons to perform AJAX with confirmation
                            document.querySelectorAll('.kaprodi-task-form button[type="submit"]').forEach(function(btn) {
                                btn.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    var intent = btn.getAttribute('data-intent') || 'approve';
                                    var msg = intent === 'approve' ? 'Setujui tugas ini?' : 'Tolak tugas ini?';
                                    if (!confirm(msg)) return;

                                    var form = btn.closest('form');
                                    var action = form.action;
                                    var tokenInput = form.querySelector('input[name="_token"]');
                                    var token = tokenInput ? tokenInput.value : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                                    fetch(action, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': token,
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        },
                                        body: new URLSearchParams([])
                                    }).then(function (res) {
                                        if (res.ok) {
                                            // Reload to reflect changes
                                            window.location.reload();
                                        } else {
                                            res.text().then(function (t) { alert('Action gagal: ' + (t || res.status)); });
                                        }
                                    }).catch(function () { alert('Gagal menghubungi server.'); });
                                });
                            });
                        });
                        </script>
                        @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada tugas menunggu persetujuan</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h4 class="font-semibold text-gray-800 mb-4">Jadwal Mendatang</h4>
                    <div class="space-y-3">
                        @forelse($jadwalMendatang as $jadwal)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="font-semibold text-blue-700">{{ $jadwal->title }}</p>
                            <p class="text-xs text-gray-500">{{ $jadwal->tanggal }} • {{ $jadwal->subtitle }}</p>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada jadwal mendatang</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h4 class="font-semibold text-gray-800 mb-4">Pengumuman Global</h4>
            @forelse($pengumumanPenting as $item)
            <div class="p-4 bg-yellow-50 rounded-lg mb-3">
                <p class="font-semibold">{{ $item->judul }}</p>
                <p class="text-xs text-gray-500">{{ $item->tanggal }}</p>
            </div>
            @empty
            <p class="text-gray-500">Tidak ada pengumuman</p>
            @endforelse
        </div>
    </div>
@endsection
