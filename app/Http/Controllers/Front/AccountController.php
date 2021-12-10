<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Front\AccountClient;
use App\Model\Front\OrderDetail;
use App\Model\Front\OrderMaster;
use App\Model\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function index() {
        return view('front.account.account');
    }
    public function register(Request $request) {
        $res = ['success' => false];
        $data = $request->input();
        //validate
        $validated = $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
                'email' => 'required|email:rfc,dns'
            ],
            [],
            [
                'username' => 'Username',
                'password' => 'Password',
                'email' => 'Email'
            ]
        );
        if (!$validated) {
//            dd($validated);
            $res['errors'] = $validated['errors'];
        } else {
//            dd('success');
            $account_client = AccountClient::query()->where('email', '=', $data['email'])->first();
            if (!$account_client) {
                $account_client = new AccountClient();
                $account_client->username = $data['username'];
                $account_client->password = md5($data['password']);
                $account_client->email = $data['email'];
                $account_client->save();
                $res['success'] = true;
            } else {
                $res['mess_dup'] = 'Email đã được đăng ký trên hệ thống Diana Authentic !!!';
            }
        }
        return response()->json($res);
    }

    public function login(Request $request) {
//        $request->session()->forget('client_login');
//        $request->session()->forget('is_login');
//        $request->session()->flush();
//        die;
//        dd($request->session()->all());
        $res = ['success' => false];
        $data = $request->input();
        //validate
        $validated = $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [],
            [
                'username' => 'Username',
                'password' => 'Password',
            ]
        );
        if (!$validated) {
            $res['errors'] = $validated['errors'];
        } else {
            $account_client = AccountClient::query()
                ->where('username', '=', $data['username'])
                ->where('password', '=', md5($data['password']))
                ->first();
            if ($account_client) {
                //set session for client
                $request->session()->put([
                    'client_login' => [
                        'username' => $account_client->username,
                        'email' => $account_client->email,
                        'id' => $account_client->id,
                        'is_login' => true
                    ]
                ]);
                $res['success'] = true;
            } else {
                $res['message_error_account'] = "Tên đăng nhập hoặc mật khẩu không chính xác";
            }
        }
        return response()->json($res);
    }

    public function logout(Request $request) {
        $res = ['success' => false];
//        dd($request->session()->all());
        Session::forget('client_login');
        $res['success'] = true;
        return response()->json($res);
    }

    public function restorePassword() {
        return view('front.account.restart_password');
    }

    public function restoreUpdatePassword(Request $request) {
        $res = ['success' => false];
        $data = $request->input();
        //validate
        $validated = $request->validate(
            [
                'password' => 'required|min:6',
                'confirm_password' => 'required'
            ],
            [],
            [
                'password' => 'Password',
                'confirm_password' => 'Confirm Password'
            ]
        );
        if (!$validated) {
//            dd($validated);
            $res['errors'] = $validated['errors'];
        } else {
            //check confirm password
            if ($data['confirm_password'] != $data['password']) {
                $res['error_confirm'] = 'Xác nhận mật khẩu sai';
                $res['success'] = false;
                return response()->json($res);
            }

            $account_client = AccountClient::query()->where('email','=', $data['email'])->where('id','=',$data['id'])->first();
            if ($account_client) {
                $account_client->password = md5($data['password']);
                $account_client->save();

                //login cho khách hàng vừa request luôn
                //set sesion for client
                $request->session()->put([
                    'client_login' => [
                        'username' => $account_client->username,
                        'email' => $account_client->email,
                        'id' => $account_client->id,
                        'is_login' => true,
                    ]
                ]);
                $res['success'] = true;
            }
        }

        return response()->json($res);
    }

    public function detail(Request $request) {
        if ($request->session()->has('client_login')) {
            //đã login
            $info_account = $request->session()->get('client_login');
            $account_client = AccountClient::query()->find($info_account['id']);
            $data_response = [];

            $provinces = DB::table('provinces')->get();
            $districts = DB::table('districts')->where('province_id', '=', $account_client->province_id)->get();
            $wards = DB::table('wards')->where('district_id', '=', $account_client->district_id)->get();
            if ($account_client) {
                $data_response['account_client'] = $account_client;
                $data_response['provinces'] = $provinces;
                $data_response['districts'] = $districts;
                $data_response['wards'] = $wards;

                //Lấy tất cả thông tin các Order mà hội viên này đã đặt hàng trên hệ thống
                $order_master = OrderMaster::query()->where('from_member', '=', $info_account['id'])->get();
                if ($order_master->count() > 0) {
                    $data_response['orders_client'] = $order_master;
                }
            }
            return view('front.account.detail', $data_response);
        } else {
            //chưa login
            return redirect()->route('client.account.client')->with('status', 'Bạn chưa đăng nhập !');
        }
     }

     public function updateAccountClient(Request $request) {
         if ($request->session()->has('client_login')) {
             //đã login
//             $request->validate(
//                 [
//                     'phone' => 'required|regex:/(0)[0-9]{9}/'
//                 ],
//                 [],
//                 [
//                     'phone' => 'Phone'
//                 ]
//             );

             $info_account = $request->session()->get('client_login');
             $account_client = AccountClient::query()->find($info_account['id']);
             $data_response = [];

//             dd($request->input());
             $data_request = $request->input();

             //xử lý địa chỉ của khách hàng trước khi save vào order master
             $customer_province = DB::table('provinces')->where('id', '=', intval($data_request['province']))->first()->name;
             $customer_district = DB::table('districts')->where('id', '=', intval($data_request['district']))->first()->name;
             $customer_ward = DB::table('wards')->where('id', '=', intval($data_request['ward']))->first()->name;
             $customer_address_plus = $data_request['address_plus'] ? $data_request['address_plus'] : '';

             $merge_address = trim($customer_address_plus . ' ' . $customer_ward . ', '. $customer_district . ', ' . $customer_province);

             $birth_day = strtotime($data_request['birth_day']);
             $birth_day_format = date('Y-m-d',$birth_day);

             $account_client->name = $data_request['name'];
             $account_client->date_of_birth = $birth_day_format;
             $account_client->phone = $data_request['phone'];
             $account_client->province_id = $data_request['province'];
             $account_client->ward_id = $data_request['ward'];
             $account_client->district_id = $data_request['district'];
             $account_client->address = $merge_address;
             $account_client->address_plus = $data_request['address_plus'];
             $account_client->save();

             return back();
         } else {
             //chưa login
             return redirect()->route('client.account.client')->with('status', 'Bạn chưa đăng nhập !');
         }
     }

     public function changePassword(Request $request) {
         if ($request->session()->has('client_login')) {
             $info_account = $request->session()->get('client_login');
             $account_client = AccountClient::query()->find($info_account['id']);

             $data_response = [];
             $data_response['account_client'] = $account_client;
             return view('front.account.change_password', $data_response);
         } else {
             //chưa login
             return redirect()->route('client.account.client')->with('status', 'Bạn chưa đăng nhập !');
         }
     }

     //Đợi làm view => dùng ajax gọi ra order detail rồi render ra hồ sơ của hội viên
     public function renderOrderDetail(Request $request) {
        $res = ['success' => false];
        $order_id = intval($request->input('order_id'));
        $order_details = OrderDetail::query()->where('order_id','=', $order_id)->get();
        if ($order_details->count() > 0) {
            $res['order_details'] = $order_details;
            $res['success'] = true;
        }
        return response()->json($res);
     }

     public function cancleOrderMaster(Request $request) {
        $res = ['success' => false];

//        $account_client = AccountClient::query()->find($request->session()->get('client_login')->id);

        $order_id = intval($request->input('order_id'));
        $order_master = OrderMaster::query()->find($order_id);
        if ($order_master) {
            if (in_array($order_master->status, [3,4,5])) { //đang giao hàng, hoàn thành, hủy
                $res['mess'] = 'Bạn không thể hủy đơn hàng này !';
            } else {
                $order_master->status = 5;
                $order_master->save();

                //Cộng số lượng sản phẩm mà khách đã hủy order vào kho lại
                $this->plusQtyProduct($order_master->id);

                $res['success'] = true;
            }
        } else {
            $res['mess'] = 'Đã xảy ra lỗi !';
        }

        return response()->json($res);
     }

     private function plusQtyProduct($order_id) {
        $order_details = OrderDetail::query()->where('order_id','=', $order_id)->get();
        if ($order_details->count() > 0) {
            foreach ($order_details as $order_detail) {
                $product_variant = ProductVariant::query()->where('id', '=', $order_detail->product_variant_id)->first();
                if ($product_variant) {
                    $product_variant->qty = $product_variant->qty + $order_detail->qty;
                    $product_variant->save();
                }
            }
        }
     }

     public function hideOrderMaster(Request $request) {
        $res = ['success' => false];
        $order_id = intval($request->input('order_id'));
        $order_master = OrderMaster::query()->find($order_id);

        if ($order_master) {
            if (in_array($order_master->status, [4,5])) { //order đã hoàn thành, order đã hủy
                $order_master->is_show_client = 0;
                $order_master->save();
            } else {
                $res['mess'] = 'Đơn hàng đang được xử lý, bạn không được ẩn đơn hàng !';
            }
        } else {
            $res['mess'] = 'Đã xảy ra lỗi !';
        }
     }
}
