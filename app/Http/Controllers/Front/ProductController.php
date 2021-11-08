<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Front\AccountClient;
use App\Model\Product;
use App\Model\ProductOption;
use App\Model\ProductVariant;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

//use function GuzzleHttp\Promise\all;

class ProductController extends Controller
{
    public function detailProduct($slug) {
        $product = Product::query()->where('slug', '=', $slug)->first();
        $is_accessory = $product->category->is_accessory;

        $data_response = [
            'product' => $product,
            'is_accessory' => $is_accessory
        ];

        if ($product) {
            $productVariants = ProductVariant::query()->where('product_id', '=', $product->id)->get();
            $colorIds = ProductVariant::query()->where('product_id', '=', $product->id)->pluck('color_id')->toArray();
            $sizeIds = ProductVariant::query()->where('product_id', '=', $product->id)->pluck('size_id')->toArray();

            $colorIds = array_unique($colorIds);
            $sizeIds = array_unique($sizeIds);

            $list_variants = [];
            $list_options = [];
            if (count($productVariants) > 0 && $productVariants) {
                foreach ($productVariants as $k=>$productVariant) {
//                    foreach ($sizeIds as $sizeId) {
//                        if ($sizeId == $productVariant->size->id) {
//                            $list_options[$sizeId]['name'] = $productVariant->size->name;
//                            $list_options[$sizeId]['colors'][] = $productVariant->color->id;
//                        }
//                    }
                    $list_variants['sizes'][] = $productVariant->size;
                }
                $data_response['sizes'] = array_unique($list_variants['sizes']);
//                $data_response['list_options'] = $list_options;
            }
//            dd($list_options);

            $category_slug = Category::query()->where('id', '=', $product->category_id)->first()->slug;
            $data_response['category_slug'] = $category_slug;

            $list_product_more = Product::query()->where('category_id', '=', $product->category_id)->where('slug', '!=', $slug)->take(4)->get();
            $data_response['list_product_more'] = $list_product_more;
//        dd($data_response);
//                Cart::destroy();

            $product->total_view = $product->total_view + 1;
            $product->save();
            return view('front.product.detail', $data_response);
        }
    }

    public function chooseSize(Request $request) {
        $response = ['success' => false];
        $data = $request->input();

        $product_variants = ProductVariant::query()->where('product_id', '=', $data['product_id'])->where('size_id', '=', $data['size_id'])
            ->where('is_out_stock', '=', 0)
            ->get();
        if ($product_variants) {
            $list_color = [];
            foreach ($product_variants as $product_variant) {
                $color = ProductOption::query()->where('id', '=', $product_variant->color_id)->first();
                $list_color[] = $color;
            }
        }
        $response['html'] = view('front.product.render_list_color', [
            'colors' => $list_color
        ])->toHtml();
        $response['success'] = true;
        return response()->json($response);
    }

    public function chooseColor(Request $request, $id) {
        $data = $request->input();
        $size_name_choose = ProductOption::query()->find($data['size_id']);
        $product = Product::query()->find($id);
        $option_colors = ProductVariant::query()->where('size_id', '=', $data['size_id'])->get();
        return response()->json(
            [
                'success' => true,
                'product' => $product,
                'colors' => $option_colors,
                'redirect' => route('client.product.detail.step2', ['id' => $product->id, 'size_id' => $size_name_choose->id])
            ]
        );
    }

    public function addToCart(Request $request) {
        $data = $request->input();
        if (intval($data['is_accessory']) == 1) { //kiểu phụ kiện
            $product = Product::query()->find($data['product_id']);
            $options = [
                'thumbnail' => $product->thumbnail,
                'slug' => $product->slug
            ];
            $addToCart = Cart::add($product->id, $product->title, $data['qty'], $product->price, 0, $options);
        } else { //kiểu sản phẩm có màu, size
            $variantProduct = ProductVariant::query()->where('size_id', '=', $data['size_id'])->where('color_id', '=', $data["color_id"])->first();
            $color = ProductOption::query()->find($data['color_id']);
            $size = ProductOption::query()->find($data['size_id']);
            $product = $variantProduct->product;
            $options = [
                'size' => $size->name,
                'color' => $color->name,
                'thumbnail' => $product->thumbnail,
                'slug' => $product->slug,
                'product_variant_id' => $variantProduct->id,
                'qty_in_stock' => $variantProduct->qty,
            ];

            $qty_atc = $data['qty'];
            if ($variantProduct->qty < $data['qty']) {
                $qty_atc = $variantProduct->qty;
            }

            $addToCart = Cart::add($product->id, $product->title, $qty_atc, $product->price, 0, $options);
        }

        if ($addToCart) {
            $response['success'] = true;
            $response['redirect'] = route('client.cart');
            return response()->json($response);
        }
//        Cart::destroy();
//        dd(Cart::content());
//        dd($product);
//        return $this->renderCartInfo();
    }

    public function renderCartInfo() {
//        dd(Cart::content());
        return view('front.cart_info');
    }

    public function removeProduct(Request $request) {
        $response = ['success' => false];
        $rowId = $request->input('rowId');
        if ($rowId) {
            Cart::remove($rowId);
            $response['success'] = true;
        }

        $response['count_item'] = Cart::count();
        if (Cart::count() > 0) {
//            return back();
            return response()->json($response);
        } else {
//            $response['redirect'] = route('homeFront');
            $response['cart_empty'] = true;
            $response['html'] = view('front.ajax_render.cart_empty')->toHtml();
            return response()->json($response);
        }
    }

    public function updateQty(Request $request,$rowId) {
        $res = ['success' => false];
        $data = $request->input();
//        dd(Cart::get($rowId)->options->product_variant_id);
        $product_variant_id = Cart::get($rowId)->options->product_variant_id;
        $product_variant = ProductVariant::query()->find($product_variant_id);
//        $total_stock = $product_variant->qty;
        if ($data['qty']) {
//            if ($total_stock < $data['qty']) {
//                $res['success'] = false;
//                $res['qty_stock'] = $product_variant->qty;
//            } else {
                Cart::update($rowId, $data['qty']);
                $qtyNewItem = Cart::get($rowId)->qty;
                $priceItem = Cart::get($rowId)->price;
                $totalCart = Cart::total();
//                $res['qtyNewItem'] = $qtyNewItem;
//                $res['priceItem'] = $priceItem;
                $res['subTotalNew'] = number_format($qtyNewItem * $priceItem, 0, '.', '.');

                $res['totalCart'] = $totalCart;
                $res['success'] = true;
//            return response()->json(['success' => true, 'qtyNewItem' => $qtyNewItem, 'totalCart' => $totalCart, 'priceItem' => $priceItem]);
//            }
        }

        $total_item_cart = Cart::count();
        $res['total_item_cart'] = $total_item_cart;
        return response()->json($res);
    }

    public function checkOutGet(Request $request) {
        $provinces = DB::table('provinces')->get();

        $data = [
            'provinces' => $provinces,
        ];

        if ($request->session()->has('client_login')) {
            $info_account = $request->session()->get('client_login');
            $account_client = AccountClient::query()->find($info_account['id']);
            $districts = DB::table('districts')->where('province_id', '=', $account_client->province_id)->get();
            $wards = DB::table('wards')->where('district_id', '=', $account_client->district_id)->get();
            $data['account_client'] = $account_client;
            $data['districts'] = $districts;
            $data['wards'] = $wards;
        }
        return view('front.checkout', $data);
    }

    public function selectProvince(Request $request) {
        $response = ['success' => false];
        $province_id = $request->input('province');
        $districts = DB::table('districts')->where('province_id', '=', $province_id)->get();
        if (count($districts) > 0) {
            $response['success'] = true;
            $response['html'] = view('front.ajax_render.render_districts', [
                'districts' => $districts
            ])->toHtml();
            return response()->json($response);
        } else {
            $response['mes'] = 'Kiểm tra lại dữ liệu';
        }
    }

    public function selectDistrict(Request $request) {
        $response = ['success' => false];
        $district_id = $request->input('district');
        $wards = DB::table('wards')->where('district_id', '=', $district_id)->get();
        if (count($wards) > 0) {
            $response['success'] = true;
            $response['html'] = view('front.ajax_render.render_wards', [
                'wards' => $wards
            ])->toHtml();
            return response()->json($response);
        } else {
            $response['mes'] = 'Kiểm tra lại dữ liệu';
        }
    }

    public function searchDianaAuthentic(Request $request) {
        $res = ['success' => false];
        $keyword = $request->query('keyword');
        if ($keyword) {
            $products = Product::query()->where('title', 'LIKE', '%'.$keyword.'%')->get();
//        dd($products);
            if ($products->count() > 0) {
                $res['html'] = view('layouts.search_diana_ajax', ['products' => $products])->toHtml();
                $res['success'] = true;

//                $request->session()->put([
//                    'search_diana' => $keyword
//                ]);
            }
        }

        return response()->json($res);
    }
}
