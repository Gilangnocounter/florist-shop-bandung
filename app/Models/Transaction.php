<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id', 'product_name', 'sender_name', 'receiver_name', 
    'phone', 'category', 'greeting', 'address', 'delivery_date', 
    'delivery_time', 'logo', 'status',
    'bank_name',      
    'payment_proof'   
];

    public function user() {
        return $this->belongsTo(User::class);
    }
}