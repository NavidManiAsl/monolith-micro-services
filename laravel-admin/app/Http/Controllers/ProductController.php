<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Product::all();
        } catch (\Throwable $th) {
            Log::error('Error retrieving products: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $product = Product::create($request->only(['title', 'description', 'image', 'price']));
            return response($product, HttpResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error store a product: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);
            if(!$product) {
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }
            return response($product);
        } catch (\Throwable $th) {
            Log::error('Error retrieving a product: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::find($id);
            if(!$product){
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }
            $product->update($request->only(['title', 'description', 'image', 'price']));
            return response($product, HttpResponse::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            Log::error('Error updating a product: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            if(!$product){
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }
            $product->delete();
            return response($product, HttpResponse::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error deleting a product: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
