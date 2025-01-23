<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ManageProductController extends Controller
{
    public function addProduct(AddProductRequest $request)
    {
        $data = $request->validated();

        if ($data['brandId'] == -99) {
            $existingBrand = DB::table('product_brand')->where('brand_name', $data['brandName'])->first();
            if ($existingBrand) {
                $data['product_brand_id'] = $existingBrand->product_brand_id;
            } else {
                $brand = DB::table('product_brand')->insertGetId(['brand_name' => $data['brandName'], 'active' => 'Y']);
                $data['product_brand_id'] = $brand;
            }
        } else {
            $data['product_brand_id'] = $data['brandId'];
        }

        if ($data['ctgrId'] == -99) {
            $existingCategory = DB::table('ctgr_products')->where('ctgr_product_name', $data['ctgrName'])->first();
            if ($existingCategory) {
                $data['ctgr_product_id'] = $existingCategory->ctgr_product_id;
            } else {
                $category = DB::table('ctgr_products')->insertGetId(['ctgr_product_name' => $data['ctgrName']]);
                $data['ctgr_product_id'] = $category;
            }
        } else {
            $data['ctgr_product_id'] = $data['ctgrId'];
        }

        if ($request->hasFile('urlImg')) {
            $path = $request->file('urlImg')->store('images', 'public');
            $data['url_img'] = asset('storage/' . $path);
        } else {
            $data['url_img'] = '';
        }

        $product = DB::table('products')->insertGetId([
            'product_code' => Str::random(15),
            'product_name' => $data['productName'],
            'ctgr_product_id' => $data['ctgr_product_id'],
            'product_brand_id' => $data['product_brand_id'],
            'desc' => $data['desc'],
            'stock' => $data['stock'],
            'price' => $data['price'],
            'fine_bill' => $data['fineBill'],
            'url_img' => $data['url_img'],
            'active' => 'Y',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['product' => [$product]], 200);
    }

    public function editProduct(EditProductRequest $request)
    {
        $data = $request->validated();
        $id = $data['productId'];

        $product = DB::table('products')->where('product_id', $id)->first();

        if ($data['brandId'] == -99) {
            $existingBrand = DB::table('product_brand')->where('brand_name', $data['brandName'])->first();
            if ($existingBrand) {
                $data['product_brand_id'] = $existingBrand->product_brand_id;
            } else {
                $brand = DB::table('product_brand')->insertGetId(['brand_name' => $data['brandName'], 'active' => 'Y']);
                $data['product_brand_id'] = $brand;
            }
        } else {
            $data['product_brand_id'] = $data['brandId'];
        }

        if ($data['ctgrId'] == -99) {
            $existingCategory = DB::table('ctgr_products')->where('ctgr_product_name', $data['ctgrName'])->first();
            if ($existingCategory) {
                $data['ctgr_product_id'] = $existingCategory->ctgr_product_id;
            } else {
                $category = DB::table('ctgr_products')->insertGetId(['ctgr_product_name' => $data['ctgrName']]);
                $data['ctgr_product_id'] = $category;
            }
        } else {
            $data['ctgr_product_id'] = $data['ctgrId'];
        }

        if ($request->hasFile('urlImg')) {
            if ($product && $product->url_img) {
                $oldImagePath = str_replace(asset('storage/'), '', $product->url_img);
                Storage::disk('public')->delete($oldImagePath);
            }
            $path = $request->file('urlImg')->store('images', 'public');
            $data['url_img'] = asset('storage/' . $path);
        }

        DB::table('products')
            ->where('product_id', $id)
            ->update([
                'product_name' => $data['productName'],
                'ctgr_product_id' => $data['ctgr_product_id'],
                'product_brand_id' => $data['product_brand_id'],
                'desc' => $data['desc'],
                'stock' => $data['stock'],
                'price' => $data['price'],
                'fine_bill' => $data['fineBill'],
                'url_img' => $data['url_img'] ?? $product->url_img,
                'active' => $data['active'],
                'updated_at' => now(),
            ]);

        $updatedProduct = DB::table('products')->where('product_id', $id)->first();

        return response()->json(['product' => $updatedProduct], 200);
    }

    public function getProducts(Request $request)
    {
        $keyword = $request->input('keyword');
        $ctgrId = $request->input('ctgrId');
        $brandId = $request->input('brandId');
        $priceRange = $request->input('priceRange');
        $limit = $request->input('limit', 25);
        $offset = $request->input('offset', 0);

        $query = DB::table('products as P')
            ->join('product_brand as B', 'P.product_brand_id', '=', 'B.product_brand_id')
            ->join('ctgr_products as C', 'P.ctgr_product_id', '=', 'C.ctgr_product_id')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('P.product_name', 'like', "%{$keyword}%")
                             ->orWhere('B.brand_name', 'like', "%{$keyword}%")
                             ->orWhere('C.ctgr_product_name', 'like', "%{$keyword}%");
            })
            ->when($ctgrId, function ($query, $ctgrId) {
                return $query->where('P.ctgr_product_id', $ctgrId);
            })
            ->when($brandId, function ($query, $brandId) {
                return $query->where('P.product_brand_id', $brandId);
            })
            ->when($priceRange, function ($query, $priceRange) {
                return $query->whereBetween('P.price', [$priceRange[0], $priceRange[1]]);
            })
            ->orderBy('P.product_name')
            ->orderBy('P.updated_at', 'desc');

        $totalRecords = $query->count();

        $products = $query->select('P.*', 'B.brand_name', 'C.ctgr_product_name')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return response()->json(['products' => $products, 'totalRecords' => $totalRecords], 200);
    }

    public function getBrandForAddProduct(Request $request)
    {
        $brands = DB::table('product_brand')
            ->where('active', 'Y')
            ->select('product_brand_id', 'brand_name')
            ->get();

        return response()->json(['brands' => $brands], 200);
    }

    public function getCtgrForAddProduct(Request $request)
    {
        $categories = DB::table('ctgr_products')
            ->select('ctgr_product_id', 'ctgr_product_name')
            ->get();

        return response()->json(['categories' => $categories], 200);
    }

    public function removeProduct(Request $request)
    {
        $productId = $request->input('productId');
        $product = DB::table('products')->where('product_id', $productId)->first();

        if ($product) {
            if ($product->url_img) {
                $oldImagePath = str_replace(asset('storage/'), '', $product->url_img);
                Storage::disk('public')->delete($oldImagePath);
            }
            DB::table('products')->where('product_id', $productId)->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        }

        return response()->json(['message' => 'Product not found'], 404);
    }
}
