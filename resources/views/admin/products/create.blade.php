<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="py-12 bg-pink-50/30 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 shadow-xl rounded-3xl border border-pink-100">
                <h2 class="text-3xl font-serif text-pink-700 italic mb-8">Tambah Rangkaian Baru</h2>
                
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Bunga</label>
                        <input type="text" name="name" class="w-full border-gray-200 rounded-2xl focus:ring-pink-500 focus:border-pink-500 p-4 shadow-sm" placeholder="Contoh: Buket Mawar Merah" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Harga (Rupiah)</label>
                        <input type="number" name="price" class="w-full border-gray-200 rounded-2xl focus:ring-pink-500 focus:border-pink-500 p-4 shadow-sm" placeholder="Contoh: 150000" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Jumlah Stok</label>
                        <input type="number" name="stock" class="w-full border-gray-200 rounded-2xl focus:ring-pink-500 focus:border-pink-500 p-4 shadow-sm" placeholder="Contoh: 10" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Foto Produk</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-pink-400 transition">
                            <input type="file" name="image" class="cursor-pointer" required>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 bg-pink-500 text-white py-4 rounded-2xl font-bold hover:bg-pink-600 shadow-lg transition">SIMPAN PRODUK</button>
                        <a href="{{ route('admin.products') }}" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold hover:bg-gray-200 text-center transition">BATAL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>