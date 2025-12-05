@extends('kaprodi.layouts.app')

@section('title', 'Monitoring Mahasiswa')
@section('page-title', 'Monitoring Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-4">Monitoring Perkembangan dan Status Mahasiswa</h3>

        <form action="{{ route('kaprodi.monitoring') }}" method="GET" class="mb-6 flex items-center space-x-4">
            <div class="flex-grow">
                <label for="status" class="block text-sm font-medium text-gray-700">Filter berdasarkan Status</label>
                <select id="status" name="status" class="mt-1 block w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $selectedStatus == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="pt-6">
                <button type="submit" class="px-5 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Filter</button>
                <a href="{{ route('kaprodi.monitoring') }}" class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">Reset</a>
            </div>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @php
                            $columns = [
                                'nim' => 'NIM',
                                'nama' => 'Nama Mahasiswa',
                                'tahapan_saat_ini' => 'Tahapan Saat Ini',
                                'progress' => 'Progress',
                                'status' => 'Status'
                            ];
                        @endphp

                        @foreach($columns as $key => $value)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('kaprodi.monitoring', array_merge(request()->query(), ['sort_by' => $key, 'sort_dir' => ($sortBy == $key && $sortDir == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    {{ $value }}
                                    @if($sortBy == $key)
                                        <i class="fas {{ $sortDir == 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} ml-2"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300 ml-2"></i>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($monitoringData as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->nim }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data->tahapan_saat_ini }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $data->progress }}%"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium">{{ $data->progress }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($data->status == 'lulus')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">{{ ucfirst($data->status) }}</span>
                            @elseif($data->status == 'aktif')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">{{ ucfirst($data->status) }}</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-800">{{ ucfirst($data->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('kaprodi.monitoring.show', ['mahasiswa' => $data->nim]) }}" class="px-3 py-1 text-xs bg-teal-600 text-white rounded hover:bg-teal-700">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data mahasiswa untuk ditampilkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
