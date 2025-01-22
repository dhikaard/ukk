<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\CtgrProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ManageProductController extends Controller
{
    public function addProduct(AddProductRequest $request)
    {
        $data = $request->validated();

        if ($data['brandId'] == -99) {
            $brand = ProductBrand::create(['brand_name' => $data['brandName'], 'active' => 'Y']);
            $data['product_brand_id'] = $brand->id;
        } else {
            $data['product_brand_id'] = $data['brandId'];
        }

        if ($data['ctgrId'] == -99) {
            $category = CtgrProduct::create(['ctgr_product_name' => $data['ctgrName']]);
            $data['ctgr_product_id'] = $category->id;
        } else {
            $data['ctgr_product_id'] = $data['ctgrId'];
        }

        if ($request->hasFile('urlImg')) {
            $path = $request->file('urlImg')->store('public/images');
            $data['url_img'] = Storage::url($path);
        } else {
            $data['url_img'] = '';
        }

        $product = Product::create([
            'product_code' => Str::uuid()->toString(15),
            'product_name' => $data['productName'],
            'ctgr_product_id' => $data['ctgr_product_id'],
            'product_brand_id' => $data['product_brand_id'],
            'desc' => $data['desc'],
            'stock' => $data['stock'],
            'price' => $data['price'],
            'fine_bill' => $data['fineBill'],
            'url_img' => $data['url_img'],
            'active' => 'Y'
        ]);

        return response()->json(['product' => [$product]], 200);
    }
}
