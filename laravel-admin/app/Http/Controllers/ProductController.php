<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view','products');
        try {
            $products = Product::paginate(10);
            return ProductResource::collection($products);
        } catch (\Throwable $th) {
            Log::error('Error retrieving products: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        Gate::authorize('edit','products');
        $file = $request->file('image');
        $name = uuid_create() . '.'
            . $file->getClientOriginalExtension();
            $url = env('APP_URL').Storage::putFileAs('images', $file, $name);


        try {
            $product = Product::create($request->only('title', 'description', 'price') + ['image' => $url]);
            return response(new ProductResource($product), HttpResponse::HTTP_CREATED);
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
        Gate::authorize('view','products');
        try {
            $product = Product::find($id);
            if (!$product) {
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }
            return response(new ProductResource($product));
        } catch (\Throwable $th) {
            Log::error('Error retrieving a product: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        Gate::authorize('edit','products');
        try {
            $product = Product::find($id);
            if (!$product) {
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }

            $image = $request->file('image');
            if ($image) {
                $name = uuid_create() . '.' . $image->getClientOriginalExtension();
                $url = env('APP_URL').Storage::putFileAs('images', $image, $name);
            }

            $data = $image
                ? array_merge($request->only('title', 'description', 'price'), ['image' => $url])
                : $request->only('title', 'description', 'price');

            $product->update($data);
            return response(new ProductResource($product), HttpResponse::HTTP_ACCEPTED);
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
        Gate::authorize('edit','products');
        try {
            $product = Product::find($id);
            if (!$product) {
                return response('Not Found', HttpResponse::HTTP_NOT_FOUND);
            }
            $product->delete();
            return response(null, HttpResponse::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error deleting a product: ' . $th->getMessage());
            return response('Unexpected Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
