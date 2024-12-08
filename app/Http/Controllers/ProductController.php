<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index(Request $request)
{
    $categoryName = $request->query('category'); 

    $products = Product::query()
        ->when($categoryName, function ($query, $categoryName) {
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName); 
            });
        })
        ->with(['category:id,name']) 
        ->get()
        ->makeHidden('category_id'); 

    return response()->json($products);
}

    
    public function show($id)
{
    $product = Product::find($id);
    
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }
    

    $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
    
    $product->images = $images;

    $product->makeHidden('category_id'); 

    return response()->json(['product' => $product]); 
}


    
    
    
//     public function show($id)
// {
//     $product = Product::find($id); // Atau query lain yang sesuai
//     return response()->json($product);
// }




}
