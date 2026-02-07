<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight italic font-serif">
            {{ __('Pesanan Saya 🌸') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl text-sm font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-pink-100 p-6">
                @if($transactions->isEmpty())
                    <div class="text-center py-12">
                        <span class="text-5xl">🛒</span>
                        <p class="mt-4 text-gray-500 italic">Kamu belum pernah memesan bunga.</p>
                        <a href="{{ route('dashboard') }}" class="mt-4 inline-block text-pink-500 font-bold underline">Lihat Katalog</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($transactions as $trx)
                            <div class="border border-pink-50 rounded-2xl p-5 bg-white shadow-sm hover:shadow-md transition">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-pink-100 p-3 rounded-xl text-2xl">💐</div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $trx->product_name }}</h4>
                                            <p class="text-xs text-gray-400 italic">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-6 py-1 rounded-full text-[10px] font-bold 
                                            @if($trx->status == 'Pending') bg-gray-100 text-gray-600
                                            @elseif($trx->status == 'Confirmed') bg-blue-100 text-blue-600
                                            @elseif($trx->status == 'Selesai') bg-green-100 text-green-600
                                            @else bg-pink-100 text-pink-600 @endif shadow-sm">
                                            {{ strtoupper($trx->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 border-t border-pink-50 pt-4">
                                    {{-- CASE 1: SUDAH UPLOAD BUKTI (Tampilkan status tunggu) --}}
                                    @if($trx->payment_proof)
                                        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex flex-col md:flex-row items-center justify-between gap-3">
                                            <div class="flex items-center gap-3 text-blue-600">
                                                <div class="animate-pulse bg-blue-400 h-2 w-2 rounded-full"></div>
                                                <div>
                                                    <p class="text-[11px] font-bold italic">Bukti pembayaran telah kami terima!</p>
                                                    <p class="text-[9px] opacity-70">Admin sedang melakukan verifikasi bank. Mohon ditunggu ya ✨</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $trx->payment_proof) }}" target="_blank" class="text-[9px] bg-white text-blue-600 px-4 py-1.5 rounded-full font-black border border-blue-200 hover:bg-blue-50 transition shadow-sm uppercase">
                                                Lihat Bukti Saya
                                            </a>
                                        </div>

                                    {{-- CASE 2: STATUS CONFIRMED TAPI BELUM UPLOAD (Tampilkan tombol bayar) --}}
                                    @elseif($trx->status == 'Confirmed')
                                        <div class="flex flex-col md:flex-row justify-between items-center bg-pink-50/50 p-4 rounded-2xl border border-pink-100 gap-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg">💰</span>
                                                <p class="text-[11px] text-pink-700 font-medium">Pesanan disetujui! Silakan selesaikan pembayaran agar segera kami proses.</p>
                                            </div>
                                            <button onclick="openUploadModal('{{ $trx->id }}')" class="bg-pink-500 text-white px-8 py-2 rounded-full text-[10px] font-bold hover:bg-pink-600 transition shadow-lg shadow-pink-200 whitespace-nowrap">
                                                BAYAR & UPLOAD BUKTI 📸
                                            </button>
                                        </div>

                                    {{-- CASE 3: MASIH PENDING --}}
                                    @elseif($trx->status == 'Pending')
                                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center">
                                            <p class="text-[10px] text-gray-400 italic font-medium">Pesanan kamu sedang ditinjau oleh Admin. Instruksi pembayaran akan muncul di sini setelah disetujui.</p>
                                        </div>

                                    {{-- CASE 4: BATAL --}}
                                    @elseif($trx->status == 'Cancelled')
                                        <div class="bg-red-50 p-3 rounded-xl border border-red-100 text-center">
                                            <p class="text-[10px] text-red-400 font-bold italic uppercase tracking-widest">Pesanan ini telah dibatalkan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL PEMBAYARAN --}}
    <div id="uploadModal" class="fixed inset-0 bg-black/60 hidden z-[9999] items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border-4 border-pink-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-pink-600 italic font-serif">Konfirmasi Bayar</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-red-500 text-3xl">&times;</button>
            </div>

            <div class="mb-6 p-5 bg-gradient-to-br from-pink-50 to-white rounded-2xl border-2 border-pink-200 shadow-sm">
                <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest mb-3">Tujuan Transfer:</p>
                <div class="flex items-center gap-4">
                    <div class="bg-white p-2 rounded-xl shadow-sm text-2xl">🏦</div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Bank BCA</p>
                        <p class="text-xl font-black text-pink-600 tracking-tighter">123-456-7890</p>
                        <p class="text-[10px] text-gray-500 italic uppercase">a/n Florist Shop Bandung</p>
                    </div>
                </div>
            </div>
            
            <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Bank / Atas Nama Kamu</label>
                    <input type="text" name="bank_name" placeholder="Misal: BCA - Siti Aminah" class="w-full border-pink-100 rounded-2xl focus:ring-pink-500 text-sm p-3 bg-pink-50/30 shadow-sm" required>
                </div>

                <div class="mb-8">
                    <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Upload Bukti (JPG/PNG)</label>
                    <input type="file" name="payment_proof" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-pink-100 file:text-pink-600 cursor-pointer" required>
                </div>

                <button type="submit" class="w-full bg-pink-500 text-white py-4 rounded-2xl font-black shadow-lg shadow-pink-200 hover:scale-[1.02] transition uppercase tracking-widest text-[10px]">
                    KIRIM KONFIRMASI 🚀
                </button>
            </form>
        </div>
    </div>

    <script>
        function openUploadModal(trxId) {
            const modal = document.getElementById('uploadModal');
            const form = document.getElementById('uploadForm');
            // Menyesuaikan dengan route: /orders/{id}/confirm-payment
            form.action = `/orders/${trxId}/confirm-payment`; 
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('uploadModal');
            if (event.target == modal) closeUploadModal();
        }
    </script>
</x-app-layout>