<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">Edit Detail Bunga</h2>
    </x-slot>

    <div class="py-12 bg-pink-50/20 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 shadow-xl rounded-[2rem] border border-pink-100">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Nama Bunga</label>
                        <input type="text" name="name" value="{{ $product->name }}" 
                               class="w-full border-gray-200 rounded-2xl p-4 focus:ring-pink-500 focus:border-pink-500 shadow-sm" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ $product->price }}" 
                                   class="w-full border-gray-200 rounded-2xl p-4 focus:ring-pink-500 focus:border-pink-500 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Jumlah Stok</label>
                            <input type="number" name="stock" value="{{ $product->stock }}" 
                                   class="w-full border-gray-200 rounded-2xl p-4 focus:ring-pink-500 focus:border-pink-500 shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Ganti Foto (Kosongkan jika tidak diubah)</label>
                        <div class="mt-2 flex items-center gap-4">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 object-cover rounded-xl border border-pink-100">
                            <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        </div>
                    </div>

                    <hr class="border-pink-50 my-6">

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-pink-500 text-white font-bold py-4 rounded-2xl hover:bg-pink-600 transition shadow-lg shadow-pink-200">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.products') }}" class="px-8 bg-gray-100 text-gray-500 font-bold py-4 rounded-2xl hover:bg-gray-200 transition text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>