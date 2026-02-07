<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-serif text-pink-700 italic">Daftar Koleksi Bunga</h2>
                <a href="{{ route('admin.products.create') }}" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2 rounded-full shadow-lg transition font-bold text-sm">
                    + TAMBAH BUNGA
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-3xl border border-pink-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-pink-50 text-pink-700 uppercase text-xs tracking-widest">
                            <th class="p-6">Produk</th>
                            <th class="p-6 text-center">Stok</th> <th class="p-6">Harga</th>
                            <th class="p-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50">
                        @forelse($products as $product)
                        <tr class="hover:bg-pink-50/20 transition">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-cover rounded-2xl shadow-sm">
                                    <span class="font-bold text-gray-800 uppercase">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="p-6 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $product->stock > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="p-6 font-bold text-pink-600">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="p-6">
                                <div class="flex justify-center gap-4">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:scale-110 transition font-bold text-sm">EDIT</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus bunga ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:scale-110 transition font-bold text-sm">HAPUS</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-400 italic">Belum ada koleksi bunga.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-pink-500 transition text-sm font-bold">← KEMBALI KE DASHBOARD</a>
            </div>
        </div>
    </div>
</x-app-layout>