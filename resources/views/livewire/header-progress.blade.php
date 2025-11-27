<div wire:poll.5000ms="refresh" class="py-2">
    <div class="flex items-center justify-between mb-1">
        <div class="text-sm text-gray-600">Progress Tugas Akhir</div>
        <div class="text-sm font-semibold text-yellow-700">{{ $progress }}%</div>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
        <div class="bg-yellow-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
    </div>
</div>
