<div class="flex items-center mt-2 mb-1">
    <p class="text-xs text-gray-500 mr-2">Progress Tugas Akhir</p>
    @php $headerPct = isset($progress) ? (int) max(0, min(100, $progress)) : 0; @endphp
    <div class="flex-1 bg-gray-100 h-3 rounded-full">
        <div id="headerProgressBarInner" class="bg-yellow-700 h-3 rounded-full transition-all duration-500"
             style="width: {{ $headerPct }}%;"></div>
    </div>
    <p id="headerProgressPct" class="text-xs font-semibold text-yellow-800 ml-2">
        {{ $headerPct }}%
    </p>
</div>
