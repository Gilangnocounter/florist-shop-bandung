<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight italic">
            Riwayat Pesanan Saya 🌸
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="text-sm text-pink-600 font-bold hover:underline">← Kembali ke Katalog</a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-pink-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-pink-100">
                                    <th class="py-4 px-2 text-[10px] font-bold text-stone-400 uppercase">Produk</th>
                                    <th class="py-4 px-2 text-[10px] font-bold text-stone-400 uppercase">Tanggal Kirim</th>
                                    <th class="py-4 px-2 text-[10px] font-bold text-stone-400 uppercase">Status</th>
                                    <th class="py-4 px-2 text-[10px] font-bold text-stone-400 uppercase text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-pink-50">
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="py-4 px-2">
                                            <p class="font-bold text-gray-800 text-sm">{{ $order->product_name }}</p>
                                            <p class="text-[10px] text-gray-400 italic">Ke: {{ $order->receiver_name }}</p>
                                        </td>
                                        <td class="py-4 px-2 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                                            <span class="block text-[10px] font-bold text-pink-400">{{ $order->delivery_time }}</span>
                                        </td>
                                        <td class="py-4 px-2">
                                            @php
                                                $statusColor = match($order->status) {
                                                    'Pending' => 'bg-amber-100 text-amber-600',
                                                    'Confirmed' => 'bg-blue-100 text-blue-600',
                                                    'Diproses' => 'bg-purple-100 text-purple-600',
                                                    'Selesai' => 'bg-green-100 text-green-600',
                                                    'Cancelled' => 'bg-red-100 text-red-600',
                                                    default => 'bg-gray-100 text-gray-600'
                                                };
                                            @endphp
                                            <span class="{{ $statusColor }} px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <div class="flex justify-center gap-2">
                                                {{-- Tombol Batalkan (Hanya jika Pending) --}}
                                                @if($order->status === 'Pending')
                                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan?')">
                                                        @csrf
                                                        <button type="submit" class="bg-red-50 text-red-500 border border-red-200 px-3 py-1 rounded-full text-[10px] font-bold hover:bg-red-500 hover:text-white transition uppercase">
                                                            Batal
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Tombol Bayar (Jika Confirmed & Belum Upload Bukti) --}}
                                                @if($order->status === 'Confirmed' && !$order->payment_proof)
                                                    <button onclick="openUploadModal('{{ $order->id }}')" class="bg-pink-500 text-white px-3 py-1 rounded-full text-[10px] font-bold hover:bg-pink-600 transition shadow-sm uppercase">
                                                        Bayar 📸
                                                    </button>
                                                @elseif($order->payment_proof)
                                                    <span class="text-[10px] text-blue-500 font-bold italic">Dibayar ✅</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-20 text-center text-gray-400 italic">Belum ada riwayat pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD --}}
    <div id="uploadModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border-4 border-pink-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-pink-600 italic">Konfirmasi Pembayaran</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Bank Pengirim / Atas Nama</label>
                    <input type="text" name="bank_name" placeholder="Contoh: BCA - Asep" class="w-full border-pink-100 rounded-xl focus:ring-pink-500 text-sm" required>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Bukti Transfer (Image)</label>
                    <input type="file" name="payment_proof" class="w-full text-sm text-gray-500" required>
                </div>
                <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-2xl font-bold hover:bg-pink-600 transition">
                    KIRIM BUKTI 🚀
                </button>
            </form>
        </div>
    </div>

    <script>
        function openUploadModal(orderId) {
            const modal = document.getElementById('uploadModal');
            const form = document.getElementById('uploadForm');
            // Sesuaikan route dengan name di routes/web.php kamu (misal: orders.confirmPayment)
            form.action = `/orders/${orderId}/confirm-payment`; 
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>