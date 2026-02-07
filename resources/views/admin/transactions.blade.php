<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight italic font-serif">
            {{ __('Daftar Pesanan Bunga 🌸') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-pink-100 p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Produk & Ucapan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Pengiriman</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Bukti Bayar</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status & Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($transactions as $trx)
                            <tr class="hover:bg-pink-50/30 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $trx->sender_name }}</div>
                                    <div class="text-xs text-gray-500">Penerima: {{ $trx->receiver_name }}</div>
                                    <div class="text-[10px] text-pink-400 font-bold uppercase mt-1">{{ $trx->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-blue-600 font-semibold">{{ $trx->product_name }}</div>
                                    <div class="text-[10px] italic text-gray-500 max-w-xs block mb-1">"{{ $trx->greeting }}"</div>
                                    <span class="px-2 py-0.5 text-[9px] bg-pink-100 rounded-full text-pink-600 font-bold uppercase">{{ $trx->category }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-700">{{ $trx->delivery_date }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $trx->delivery_time }}</div>
                                    <div class="text-[10px] text-gray-400 max-w-[150px] truncate mt-1" title="{{ $trx->address }}">{{ $trx->address }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($trx->payment_proof)
                                        <div class="flex flex-col gap-1 items-center">
                                            {{-- Indikator jika bukti baru masuk tapi belum diproses --}}
                                            @if($trx->status == 'Confirmed')
                                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
                                            @endif
                                            
                                            <a href="{{ asset('storage/' . $trx->payment_proof) }}" target="_blank" class="relative group inline-block">
                                                <img src="{{ asset('storage/' . $trx->payment_proof) }}" class="h-14 w-14 object-cover rounded-lg border-2 border-pink-200 group-hover:scale-110 transition shadow-md">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center text-[8px] text-white font-black uppercase tracking-tighter">LIHAT FOTO</div>
                                            </a>
                                            <span class="text-[9px] font-black text-blue-600 uppercase">{{ $trx->bank_name }}</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center opacity-30">
                                            <span class="text-xl">⏳</span>
                                            <span class="text-[9px] text-gray-400 italic font-bold">Belum Ada Bukti</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.transactions.update', $trx->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" 
                                            class="text-[10px] font-black rounded-full border-none px-3 py-1 cursor-pointer shadow-sm w-full
                                            @if($trx->status == 'Pending') bg-gray-100 text-gray-600 
                                            @elseif($trx->status == 'Confirmed') bg-blue-100 text-blue-600
                                            @elseif($trx->status == 'Diproses') bg-purple-100 text-purple-600
                                            @elseif($trx->status == 'Dikirim') bg-yellow-100 text-yellow-600
                                            @elseif($trx->status == 'Selesai') bg-green-100 text-green-600
                                            @elseif($trx->status == 'Cancelled') bg-red-100 text-red-600
                                            @endif">
                                            <option value="Pending" {{ $trx->status == 'Pending' ? 'selected' : '' }}>PENDING</option>
                                            <option value="Confirmed" {{ $trx->status == 'Confirmed' ? 'selected' : '' }}>CONFIRMED (Menunggu Bayar)</option>
                                            <option value="Diproses" {{ $trx->status == 'Diproses' ? 'selected' : '' }}>DIPROSES (Sudah Verifikasi)</option>
                                            <option value="Dikirim" {{ $trx->status == 'Dikirim' ? 'selected' : '' }}>DIKIRIM</option>
                                            <option value="Selesai" {{ $trx->status == 'Selesai' ? 'selected' : '' }}>SELESAI</option>
                                            <option value="Cancelled" {{ $trx->status == 'Cancelled' ? 'selected' : '' }}>BATAL</option>
                                        </select>
                                    </form>
                                    <div class="flex flex-col items-center mt-2">
                                        <p class="text-[8px] text-gray-400 italic">Klik untuk ganti status</p>
                                        <p class="text-[7px] font-bold text-green-500 uppercase tracking-widest mt-0.5">Auto WhatsApp ✅</p>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->isEmpty())
                    <div class="text-center py-20">
                        <span class="text-4xl opacity-20">🌸</span>
                        <p class="text-gray-400 italic mt-2">Belum ada pesanan yang masuk hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>