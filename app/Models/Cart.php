<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity', 'price', 'total'];
    protected static function booted()
    {
        static::creating(function ($cart) {
            if (!$cart->price && $cart->product_id) {
                $product = Product::find($cart->product_id);
                if ($product) {
                    $cart->price = $product->price;
                }
            }
            // Hitung total
            $cart->total = $cart->quantity * $cart->price;
        });
    
        static::updating(function ($cart) {
            if (!$cart->price && $cart->product_id) {
                $product = Product::find($cart->product_id);
                if ($product) {
                    $cart->price = $product->price;
                }
            }
            // Hitung total
            $cart->total = $cart->quantity * $cart->price;
        });
    }
    // Relasi dengan produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
