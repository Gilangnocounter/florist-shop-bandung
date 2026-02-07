<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">Edit Detail Bunga</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm rounded-2xl border border-pink-100">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nama Bunga</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="w-full border-gray-300 rounded-xl focus:ring-pink-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ $product->price }}" class="w-full border-gray-300 rounded-xl focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Jumlah Stok</label>
                            <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border-gray-300 rounded-xl focus:ring-pink-500" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Ganti Foto (Kosongkan jika tidak diubah)</label>
                        <input type="file" name="image" class="w-full text-gray-500">
                        <p class="text-xs text-gray-400 mt-2 italic">*Foto saat ini: {{ basename($product->image) }}</p>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded-xl hover:bg-pink-600">Simpan Perubahan</button>
                        <a href="{{ route('admin.products') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-xl hover:bg-gray-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>