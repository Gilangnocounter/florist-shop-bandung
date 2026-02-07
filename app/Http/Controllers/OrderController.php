<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar transaksi untuk halaman Admin
     */
    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Menyimpan pesanan baru dari pelanggan
     */
    public function store(Request $request)
    {
        $product = Product::where('name', $request->product_name)->first();

        if (!$product || $product->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, stok bunga ini sedang kosong!'
            ], 422);
        }

        $request->validate([
            'product_name'  => 'required',
            'sender_name'   => 'required',
            'phone'         => 'required',
            'receiver_name' => 'required',
            'category'      => 'required',
            'greeting'      => 'required',
            'address'       => 'required',
            'delivery_date' => 'required',
            'session'       => 'required', 
            'specific_time' => 'required', 
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status']  = 'Pending'; 
        $data['delivery_time'] = $request->session . ' - ' . $request->specific_time;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Transaction::create($data);

        return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil dikirim ke Admin!']);
    }

    /**
     * Mengupdate status pesanan (Admin) dan mengirim notifikasi WhatsApp
     */
    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate(['status' => 'required']);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // LOGIKA STOK: Berkurang saat Admin mulai 'Diproses'
        if ($newStatus === 'Diproses' && $oldStatus !== 'Diproses') {
            $product = Product::where('name', $transaction->product_name)->first();
            if ($product) {
                if ($product->stock > 0) {
                    $product->decrement('stock', 1);
                } else {
                    return back()->with('error', 'Stok bunga di gudang sudah habis!');
                }
            }
        }

        $transaction->update(['status' => $newStatus]);

        // LOGIKA WHATSAPP
        $phone = $transaction->phone;
        if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
        elseif (str_starts_with($phone, '8')) { $phone = '62' . $phone; }

        $message = match($newStatus) {
            'Confirmed' => "Kabar Baik! Pesanan bunga Anda *{$transaction->product_name}* sudah kami KONFIRMASI. ✅\n\nSilakan cek menu 'Pesanan Saya' di website untuk melakukan pembayaran ya! Terimakasih 🌸",
            'Diproses'  => "Pembayaran terverifikasi! Pesanan Anda sedang kami *RANGKAI* dengan cinta. 🎀",
            'Dikirim'   => "Pesanan bunga Anda sedang dalam *PENGIRIMAN* ke alamat tujuan. 🚚",
            'Selesai'   => "Pesanan bunga Anda telah *SELESAI*. Terima kasih telah mempercayai Florist Shop! 😊💐",
            'Cancelled' => "Mohon maaf, pesanan Anda telah *DIBATALKAN*.",
            default     => null,
        };

        if ($message) {
            $waUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
            return redirect()->away($waUrl);
        }

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    /**
     * Menampilkan riwayat pesanan pelanggan (User Side)
     */
    public function history()
    {
        // Variabel disamakan jadi $transactions agar sinkron dengan @foreach di customer/history.blade.php
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        
        // Diarahkan ke folder customer/history sesuai struktur folder kamu
        return view('customer.history', compact('transactions'));
    }

    /**
     * Membatalkan pesanan (Hanya jika masih Pending)
     */
    public function cancel($id)
    {
        $transaction = Transaction::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        if ($transaction->status !== 'Pending') {
            return back()->with('error', 'Pesanan yang sudah dikonfirmasi tidak bisa dibatalkan.');
        }

        $transaction->update(['status' => 'Cancelled']);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Konfirmasi Pembayaran oleh User
     */
   public function confirmPayment(Request $request, $id)
{
    // 1. Validasi input
    $request->validate([
        'bank_name' => 'required|string|max:255',
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
    ]);

    $transaction = Transaction::findOrFail($id);

    // 2. Cek apakah file benar-benar dikirim
    if ($request->hasFile('payment_proof')) {
        
        // Simpan file ke folder: storage/app/public/payments
        $file = $request->file('payment_proof');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('payments', $fileName, 'public');
        
        // 3. Update database
        $transaction->update([
            'bank_name' => $request->bank_name,
            'payment_proof' => $path, // Menyimpan path: payments/nama_file.jpg
        ]);

        return redirect()->back()->with('success', 'Bukti bayar berhasil diunggah! Admin akan segera memverifikasi.');
    }

    return redirect()->back()->with('error', 'Gagal membaca file bukti transfer. Silakan coba lagi.');
}
}