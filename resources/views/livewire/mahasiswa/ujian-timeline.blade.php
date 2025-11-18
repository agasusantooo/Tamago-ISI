<div wire:poll.5s>
    @php
        // guard against undefined variables when this blade is accidentally rendered outside Livewire
        $timeline = $timeline ?? [];
        $status = $status ?? ['text' => 'Tidak ada status', 'variant' => 'yellow'];
        // guard newly added Livewire props
        $hasUjian = $hasUjian ?? false;
        $ujianStatus = $ujianStatus ?? null;
    @endphp

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h4 class="font-semibold mb-3">Timeline Ujian</h4>
        <ul class="space-y-3 text-sm text-gray-700">
            @forelse($timeline as $item)
                <li class="flex items-start">
                    @php
                        $color = $item['color'] ?? 'gray';
                        $dotClass = $color === 'green' ? 'bg-green-500' : ($color === 'blue' ? 'bg-blue-500' : 'bg-gray-300');
                    @endphp
                    <span class="w-3 h-3 {{ $dotClass }} rounded-full mr-3 mt-1"></span>
                    <div>
                        <div class="font-semibold">{{ $item['title'] }}</div>
                        <div class="text-xs text-gray-500">{{ $item['date'] }}</div>
                    </div>
                </li>
            @empty
                <li class="text-sm text-gray-500">Belum ada aktivitas.</li>
            @endforelse
        </ul>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h4 class="font-semibold mb-3">Status Pengajuan</h4>
        @php
            // Friendly mappings for pendaftaran statuses
            $pendaftaranMap = [
                'pengajuan_ujian' => ['label' => 'Pengajuan Ujian', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                'jadwal_ditetapkan' => ['label' => 'Jadwal Ditetapkan', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                'ujian_berlangsung' => ['label' => 'Ujian Berlangsung', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                'belum_ujian' => ['label' => 'Belum Ujian', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                'selesai_ujian' => ['label' => 'Selesai Ujian', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
            ];

            // Determine current status info from the available ujianTA if present, else fallback to $status
            $currentPendaftaran = null;
            if (!empty($ujianTA)) {
                $key = $ujianTA->status_pendaftaran ?? null;
                $currentPendaftaran = $pendaftaranMap[$key] ?? ['label' => ucfirst(str_replace('_',' ',$key ?? 'Tidak ada status')), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
            } else {
                $variant = $status['variant'] ?? 'yellow';
                $currentPendaftaran = ['label' => $status['text'] ?? 'Tidak ada status', 'bg' => 'bg-'.$variant.'-50', 'text' => 'text-'.$variant.'-800'];
            }
        @endphp

        <div class="px-3 py-4 rounded {{ $currentPendaftaran['bg'] }}">
            <div class="font-semibold {{ $currentPendaftaran['text'] }}">{{ $currentPendaftaran['label'] }}</div>
            <div class="text-sm text-gray-600 mt-2">Setelah mengajukan, pengajuan Anda akan diverifikasi oleh admin dalam 1-3 hari kerja.</div>
        </div>
        {{-- Role-guarded status editor for staff/admin --}}
        @php $user = auth()->user(); @endphp
        @if($user && ($user->isAdmin() || $user->isDospem() || $user->isDosenPenguji() || $user->isKaprodi() || $user->isKoordinatorTA()))
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status Pendaftaran</label>
                <div class="flex items-center gap-2">
                    <select wire:model.defer="selectedStatus" class="border rounded px-3 py-2 text-sm">
                        <option value="">-- Pilih Status --</option>
                        @foreach($allowedStatuses as $key)
                            {{-- map to friendly label if present in the pendaftaranMap, else prettify --}}
                            @php
                                $label = $pendaftaranMap[$key]['label'] ?? ucwords(str_replace('_',' ',$key));
                            @endphp
                            <option value="{{ $key }}" @if(($ujianTA->status_pendaftaran ?? null) == $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button wire:click="updateStatus" class="px-3 py-2 bg-green-600 text-white rounded text-sm">Simpan</button>
                </div>
            </div>
        @endif
        <div class="mt-4 text-center">
            @if($hasUjian && $ujianStatus === 'selesai_ujian')
                <a href="{{ route('mahasiswa.ujian-ta.hasil') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded shadow text-sm hover:bg-indigo-700">Lihat Hasil Ujian</a>
            @elseif($hasUjian)
                <span class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">Hasil akan tersedia setelah ujian selesai</span>
            @else
                <span class="inline-block px-4 py-2 bg-gray-100 text-gray-600 rounded text-sm">Belum terdaftar ujian</span>
            @endif
        </div>
    </div>
 </div>

<script>
    // Simple browser event handler fallback for Livewire notifications
    window.addEventListener('notify', function(e) {
        try {
            var detail = e.detail || {};
            var message = detail.message || 'Notifikasi';
            // prefer a nicer toast if available, otherwise simple alert
            if (window.toastr) {
                if (detail.type === 'success') toastr.success(message);
                else if (detail.type === 'error') toastr.error(message);
                else toastr.info(message);
            } else {
                alert(message);
            }
        } catch (ex) {
            // noop
        }
    });
</script>
