@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-6 py-4">
    <h1 class="text-2xl font-bold mb-4">Dashboard Pengguna</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Pengiriman Aktif</h2>
            <p class="text-3xl font-bold text-primary mt-2">{{ $pengirimanAktif }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Total Pengiriman</h2>
            <p class="text-3xl font-bold text-primary mt-2">{{ $totalPengiriman }}</p>
        </div>
    </div>

    <div class="mt-6 bg-white rounded shadow p-4">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">Pengiriman Terakhir</h2>
        <table class="w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2">Resi</th>
                    <th class="p-2">Penerima</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengirimanTerakhir as $item)
                <tr>
                    <td class="p-2">{{ $item->nomor_resi }}</td>
                    <td class="p-2">{{ $item->penerima_nama ?? '-' }}</td>
                    <td class="p-2 text-blue-600">{{ $item->status }}</td>
                    <td class="p-2">{{ $item->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td class="p-2 text-center" colspan="4">Belum ada pengiriman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
