<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
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
            $brand = DB::table('product_brand')->insertGetId(['brand_name' => $data['brandName'], 'active' => 'Y']);
            $data['product_brand_id'] = $brand;
        } else {
            $data['product_brand_id'] = $data['brandId'];
        }

        if ($data['ctgrId'] == -99) {
            $category = DB::table('ctgr_products')->insertGetId(['ctgr_product_name' => $data['ctgrName']]);
            $data['ctgr_product_id'] = $category;
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
            'product_code' => Str::uuid()->toString(15),
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

    public function getProducts(Request $request)
    {
        $keyword = $request->input('keyword');

        $products = DB::table('products as P')
            ->join('product_brand as B', 'P.product_brand_id', '=', 'B.product_brand_id')
            ->join('ctgr_products as C', 'P.ctgr_product_id', '=', 'C.ctgr_product_id')
            ->where('P.product_name', 'like', "%{$keyword}%")
            ->orWhere('B.brand_name', 'like', "%{$keyword}%")
            ->orWhere('C.ctgr_product_name', 'like', "%{$keyword}%")
            ->select('P.*', 'B.brand_name', 'C.ctgr_product_name')
            ->get();

        return response()->json(['products' => $products], 200);
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
}
