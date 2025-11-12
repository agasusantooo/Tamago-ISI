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
            $variant = $status['variant'] ?? 'yellow';
            $text = $status['text'] ?? 'Tidak ada status';
        @endphp
        <div class="px-3 py-4 rounded {{ 'bg-'.($variant).'-50' }}">
            <div class="font-semibold {{ 'text-'.($variant).'-800' }}">{{ $text }}</div>
            <div class="text-sm text-gray-600 mt-2">Setelah mengajukan, pengajuan Anda akan diverifikasi oleh admin dalam 1-3 hari kerja.</div>
        </div>
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
