<x-app-layout>
    <x-slot name="header"></x-slot>

    @if(Auth::user()->role !== 'admin')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map { height: 400px; width: 100%; border-radius: 20px; z-index: 1; }
        </style>
    @endif
    
    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Panel --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-pink-100 mb-8">
                <div class="p-8 text-center md:text-left flex justify-between items-center">
                    <div>
                        <h3 class="text-3xl font-serif text-pink-600 mb-2 italic">Halo, {{ Auth::user()->name }}! 🌸</h3>
                        <p class="text-gray-500 uppercase tracking-widest text-xs font-bold">Panel {{ Auth::user()->role }} FLORIST SHOP</p>
                        
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('orders.history') }}" class="inline-block mt-4 px-6 py-2 bg-pink-500 text-white text-xs font-bold rounded-full hover:bg-pink-600 transition shadow-md">
                                Lihat Pesanan Saya 🛍️
                            </a>
                        @endif
                    </div>
                    @if(Auth::user()->role === 'admin')
                        <span class="bg-pink-100 text-pink-600 px-4 py-1 rounded-full text-xs font-bold">ADMIN MODE</span>
                    @endif
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
                {{-- Layout Admin --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <a href="{{ route('admin.products.create') }}" class="p-8 bg-white border-2 border-pink-100 rounded-3xl hover:border-pink-500 transition-all group text-center shadow-sm">
                        <span class="text-4xl mb-3 block">✨</span>
                        <span class="font-bold text-lg text-gray-800 group-hover:text-pink-600 transition">Tambah Produk</span>
                        <p class="text-xs text-gray-400 mt-2 italic">Input koleksi bunga baru</p>
                    </a>

                    <a href="{{ route('admin.products') }}" class="p-8 bg-white border-2 border-pink-100 rounded-3xl hover:border-pink-500 transition-all group text-center shadow-sm">
                        <span class="text-4xl mb-3 block">📋</span>
                        <span class="font-bold text-lg text-gray-800 group-hover:text-pink-600 transition">Daftar Produk</span>
                        <p class="text-xs text-gray-400 mt-2 italic">Edit & Hapus data stok</p>
                    </a>

                    <a href="{{ route('admin.transactions') }}" class="p-8 bg-white border-2 border-pink-100 rounded-3xl hover:border-pink-500 transition-all group text-center shadow-sm">
                        <span class="text-4xl mb-3 block">💰</span>
                        <span class="font-bold text-lg text-gray-800 group-hover:text-pink-600 transition">Data Transaksi</span>
                        <p class="text-xs text-gray-400 mt-2 italic">Lihat riwayat pesanan & logo</p>
                    </a>
                </div>
            @else
                {{-- Layout Customer: Lokasi Toko --}}
                <div class="bg-white p-8 rounded-3xl border border-pink-100 shadow-sm mb-12">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/3 text-center md:text-left">
                            <h4 class="text-2xl font-serif text-gray-800 mb-4 italic border-b-2 border-pink-200 inline-block">Lokasi Toko</h4>
                            <div class="text-gray-600 space-y-2 mb-6 mt-4">
                                <p class="font-bold text-pink-600">FLORIST SHOP BANDUNG</p>
                                <p class="text-sm">Jl. Otto Iskandar Dinata Los 11 - 12 Komplek Tegalega, Bandung</p>
                            </div>
                            <a href="https://www.openstreetmap.org/?mlat=-6.93282&mlon=107.60334" target="_blank" class="inline-block px-6 py-2 bg-pink-500 text-white rounded-full text-xs font-bold hover:bg-pink-600 transition shadow-md">BUKA PETA</a>
                        </div>
                        <div class="md:w-2/3">
                            <div id="map" class="shadow-inner border border-stone-100"></div>
                        </div>
                    </div>
                </div>

                {{-- Katalog Produk --}}
                <div class="mb-6">
                    <h4 class="text-2xl font-serif text-gray-800 mb-6 border-b border-pink-100 pb-2 italic">Katalog Bunga Terbaru</h4>
                    @php $products = \App\Models\Product::latest()->get(); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($products as $product)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-stone-100 group relative">
                                @if($product->stock <= 0)
                                    <div class="absolute top-4 right-4 z-10 bg-red-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg uppercase">Stok Habis</div>
                                @endif
                                <div class="aspect-[4/5] overflow-hidden {{ $product->stock <= 0 ? 'grayscale opacity-60' : '' }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold text-gray-800 uppercase tracking-tight">{{ $product->name }}</h3>
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="text-pink-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        <span class="text-[10px] font-bold {{ $product->stock > 0 ? 'text-green-500' : 'text-red-500' }}">Sisa: {{ $product->stock }}</span>
                                    </div>
                                    @if($product->stock > 0)
                                        <button onclick="openOrderModal('{{ $product->id }}', '{{ $product->name }}')" class="w-full text-center bg-stone-900 text-white py-2 rounded-xl hover:bg-green-600 transition font-bold text-sm shadow-md">Pesan Sekarang</button>
                                    @else
                                        <button disabled class="w-full text-center bg-gray-200 text-gray-400 py-2 rounded-xl cursor-not-allowed font-bold text-sm">Tidak Tersedia</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-stone-200 text-gray-400 italic">Belum ada koleksi bunga tersedia.</div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Order --}}
    @if(Auth::user()->role !== 'admin')
    <div id="orderModal" class="fixed inset-0 z-[999] hidden bg-stone-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <form id="orderForm" enctype="multipart/form-data" class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            @csrf
            <input type="hidden" name="product_name" id="hiddenProductName">
            
            <div class="p-6 bg-pink-500 text-white sticky top-0 z-10">
                <h3 class="text-xl font-serif italic">Detail Pesanan Bunga 🌸</h3>
                <p id="modalProductName" class="text-xs opacity-80 mt-1 font-bold"></p>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Nama Pengirim</label>
                        <input type="text" name="sender_name" id="senderName" value="{{ Auth::user()->name }}" class="w-full border-stone-100 rounded-xl focus:ring-pink-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Nomor WA Anda</label>
                        <input type="number" name="phone" id="customerPhone" class="w-full border-stone-100 rounded-xl focus:ring-pink-500 text-sm" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Nama Penerima</label>
                    <input type="text" name="receiver_name" id="receiverName" class="w-full border-stone-100 rounded-xl focus:ring-pink-500 text-sm" placeholder="Nama Penerima di Papan">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Kategori Ucapan</label>
                    <select name="category" id="greetingCategory" class="w-full border-stone-100 rounded-xl focus:ring-pink-500 text-sm">
                        <option value="Selamat & Sukses">Selamat & Sukses</option>
                        <option value="Happy Wedding">Happy Wedding</option>
                        <option value="Congratulations">Congratulations</option>
                        <option value="Turut Berdukacita">Turut Berdukacita</option>
                        <option value="Custom">Lainnya (Custom)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-400 uppercase mb-2">Tambahkan Logo?</label>
                    <div class="flex gap-4 mb-2">
                        <label class="flex items-center text-sm cursor-pointer">
                            <input type="radio" name="useLogo" value="tidak" checked onclick="toggleLogoUpload(false)" class="text-pink-500 mr-2"> Tidak
                        </label>
                        <label class="flex items-center text-sm cursor-pointer">
                            <input type="radio" name="useLogo" value="iya" onclick="toggleLogoUpload(true)" class="text-pink-500 mr-2"> Iya
                        </label>
                    </div>

                    <div id="logoUploadSection" class="hidden border border-dashed border-pink-200 rounded-2xl p-4 bg-pink-50/50">
                        <input type="file" name="logo" id="logoFile" accept="image/*" class="w-full text-[10px] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-pink-100 file:text-pink-700">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Isi Ucapan</label>
                    <textarea name="greeting" id="greetingText" rows="2" class="w-full border-stone-100 rounded-xl focus:ring-pink-500 text-sm" placeholder="Isi ucapan di papan..."></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Alamat Lengkap</label>
                    <textarea name="address" id="deliveryAddress" rows="2" class="w-full border-stone-100 rounded-xl focus:ring-pink-500 text-sm" placeholder="Alamat pengiriman..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Sesi</label>
                            <select name="session" id="deliveryTimeSession" class="w-full border-stone-100 rounded-xl text-xs">
                                <option value="Pagi">Pagi</option>
                                <option value="Siang">Siang</option>
                                <option value="Malam">Malam</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Jam</label>
                            <input type="text" name="specific_time" id="specificTime" placeholder="09:00" class="w-full border-stone-100 rounded-xl text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-stone-400 uppercase mb-1">Tanggal</label>
                        <input type="date" name="delivery_date" id="deliveryDate" class="w-full border-stone-100 rounded-xl text-xs">
                    </div>
                </div>
            </div>

            <div class="p-6 bg-stone-50 flex gap-3">
                <button type="button" onclick="closeOrderModal()" class="flex-1 text-stone-400 font-bold text-sm">BATAL</button>
                <button type="submit" id="btnSubmitOrder" class="flex-1 bg-green-500 text-white rounded-xl font-bold text-sm py-3 shadow-lg hover:bg-green-600 transition uppercase tracking-wider">Kirim & Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let currentProductName = '';

        // Peta
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([-6.93282, 107.60334], 17);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
            L.marker([-6.93282, 107.60334]).addTo(map).bindPopup("<b>Bloom & Glow Bandung</b>").openPopup();
        });

        function toggleLogoUpload(show) {
            const section = document.getElementById('logoUploadSection');
            section.classList.toggle('hidden', !show);
            if (!show) document.getElementById('logoFile').value = "";
        }

        function openOrderModal(id, name) {
            currentProductName = name;
            document.getElementById('hiddenProductName').value = name;
            document.getElementById('modalProductName').innerText = "Produk: " + name;
            document.getElementById('orderModal').classList.remove('hidden');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        // Logic Submit
        document.getElementById('orderForm').onsubmit = async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnSubmitOrder');
            const originalText = btn.innerText;
            
            // PROTEKSI TOMBOL
            btn.innerText = "SEDANG MENYIMPAN...";
            btn.disabled = true;

            let formData = new FormData(this);

            try {
                const response = await fetch("{{ route('orders.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Hanya panggil WhatsApp JIKA simpan database sukses
                    sendToWhatsApp();
                } else {
                    alert('Gagal: ' + (result.message || 'Cek kembali form Anda.'));
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                alert('Gagal terhubung ke server.');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        };

        function sendToWhatsApp() {
            const sender = document.getElementById('senderName').value;
            const customerWa = document.getElementById('customerPhone').value;
            const receiver = document.getElementById('receiverName').value;
            const greeting = document.getElementById('greetingText').value;
            const useLogo = document.querySelector('input[name="useLogo"]:checked').value;
            
            // Format Pesan (Pastikan variabel bersih)
            const textMessage = `*PESANAN BARU (TERCATAT DI SISTEM)* 💐\n` +
                                `--------------------------------\n` +
                                `🌸 *Produk:* ${currentProductName}\n` +
                                `🖼️ *Pakai Logo:* ${useLogo.toUpperCase()}\n` +
                                `✍️ *Isi Ucapan:* "${greeting}"\n\n` +
                                `👤 *Pengirim:* ${sender}\n` +
                                `📱 *WA Pengirim:* ${customerWa}\n` +
                                `🎁 *Penerima:* ${receiver}\n` +
                                `--------------------------------\n` +
                                `_Detail lengkap & file logo silakan cek di Dashboard Admin._`;

            const waUrl = `https://wa.me/6281221648552?text=${encodeURIComponent(textMessage)}`;
            
            // Redirect & Tutup Modal
            window.open(waUrl, '_blank');
            closeOrderModal();
            
            // Reset form agar tidak kirim ulang saat modal dibuka lagi
            document.getElementById('orderForm').reset();
            const btn = document.getElementById('btnSubmitOrder');
            btn.innerText = "KIRIM & SIMPAN";
            btn.disabled = false;
        }
    </script>
    @endif
</x-app-layout>