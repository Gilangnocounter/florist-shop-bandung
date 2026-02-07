<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FLORIST SHOP BANDUNG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:italic,wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-50 font-['Inter']">

    <nav class="p-6 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-stone-100">
        <h1 class="text-2xl font-['Playfair_Display'] text-pink-600 italic">FLORIST SHOP BANDUNG</h1>
        <div>
            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-pink-600 px-4 font-medium transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="bg-pink-500 text-white px-6 py-2 rounded-full hover:bg-pink-600 transition shadow-sm">Masuk</a>
            @endauth
        </div>
    </nav>

    <header class="py-20 px-6 text-center">
        <h2 class="text-5xl md:text-6xl font-['Playfair_Display'] text-gray-800 mb-4 leading-tight">
            Rangkaian Bunga Segar <br> Untuk Momen Berharga
        </h2>
        <p class="text-gray-500 max-w-lg mx-auto text-lg">
            Kirimkan kebahagiaan melalui buket bunga pilihan yang dirangkai dengan sepenuh hati oleh florist profesional kami.
        </p>
    </header>

    <main class="max-w-7xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-stone-100">
                <div class="aspect-[4/5] overflow-hidden relative">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition duration-500"></div>
                </div>

                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-1 capitalize">{{ $product->name }}</h3>
                    <p class="text-pink-600 font-bold text-lg mb-4">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    
                    @php
                        $nomorWA = '085176841868'; // <--- GANTI JADI NOMOR WA KAMU
                        $pesan = "Halo Bloom & Glow, saya ingin memesan *" . $product->name . "* seharga *Rp " . number_format($product->price, 0, ',', '.') . "*. Apakah stok masih tersedia?";
                    @endphp

                    <a href="https://wa.me/{{ $nomorWA }}?text={{ urlencode($pesan) }}" 
                       target="_blank" 
                       class="flex items-center justify-center gap-2 w-full py-3 bg-stone-900 text-white rounded-xl hover:bg-green-600 transition-all duration-300 font-semibold shadow-lg hover:shadow-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.48 5.228 3.48 8.404c0 6.556-5.332 11.888-11.888 11.888-2.01 0-3.987-.51-5.743-1.478l-6.24 1.701zm6.086-5.309l.395.234c1.26.748 2.737 1.14 4.266 1.14 4.659 0 8.451-3.792 8.451-8.451 0-2.257-.879-4.378-2.475-5.974-1.596-1.595-3.717-2.474-5.976-2.474-4.659 0-8.451 3.792-8.451 8.451 0 1.62.463 3.203 1.341 4.583l.257.401-1.01 3.692 3.784-1.03c-.001 0-.001 0 0 0z"/>
                        </svg>
                        Pesan Sekarang
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border-2 border-dashed border-stone-200">
                <p class="text-stone-400 text-lg italic">Belum ada koleksi bunga saat ini. Silakan kembali lagi nanti.</p>
            </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-white py-16 text-center border-t border-stone-100">
        <h4 class="text-xl font-['Playfair_Display'] text-pink-600 italic mb-4">FLORIST SHOP BANDUNG</h4>
        <p class="text-stone-400 text-sm italic">&copy; 2026 FLORIST SHOP BANDUNG. Crafted with Love for Beautiful Moments.</p>
    </footer>

</body>
</html>