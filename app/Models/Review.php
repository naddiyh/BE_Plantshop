<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Review extends Model
{

    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's plural convention
    // protected $table = 'reviews';

    // Specify the columns that are mass assignable (for security)
    protected $fillable = [
        'product_id',
        'user_id',
        'review',
        'rating',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
