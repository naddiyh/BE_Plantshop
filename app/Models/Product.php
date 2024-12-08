<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'images', 
        'category_id', 
        'rating', 
        'sold', 
        'status', 
        'reviews_count',
        'published_at'
    ];
    protected $casts = [
        'images' => 'array', 
    ];
    public function reviews() {
        return $this->hasMany(Review::class);
    }

    
    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}


    public static function createProduct(array $data)
    {
        $product = self::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'status' => $data['status'],
            'category_id' => $data['category_id'],
            'rating' => $data['rating'],
            'sold' => $data['sold'],
            'published_at' => $data['published_at'],
        ]);

        if (isset($data['images'])) {
            $imagePaths = [];

            foreach ($data['images'] as $image) {
             
                $imagePaths[] = $image->store('products', 'public');
            }

        
            $product->update(['images' => $imagePaths]);
        }

        return $product;
    }
}
