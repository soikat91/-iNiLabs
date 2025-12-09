<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = $request->only('q');

        $products = $this->service->list($filters, $perPage);

        return ProductResource::collection($products)
            ->additional(['meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->service->create($request->validated());
        return new ProductResource($product);
    }

    public function show($id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not Found'], 404);
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not Found'], 404);
        $product = $this->service->update($product, $request->validated());
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not Found'], 404);
        $this->service->delete($product);
        return response()->json(['message' => 'Deleted'], 200);
    }
}
