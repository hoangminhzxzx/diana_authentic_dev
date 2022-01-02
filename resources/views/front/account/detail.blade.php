@extends('layouts.layout_front')
@section('title')
    Đặt hàng | Diana Authentic
@endsection
@section('content')
    {{--    {{ dd(Cart::content()) }}--}}
    <style>
        .text-danger {
            color: red;
        }
        .mb-2 {
            margin-bottom: 2rem;
        }
        .wrapper-order-status {
            align-self: flex-start;
        }
    </style>
    <div class="small-container" style="min-height: 400px; margin-top: 80px;">
        <div class="info-client">
            <form action="{{ route('client.update.account') }}" method="post">
                @csrf
                <div class="row" style="margin-bottom: 4rem;">
                    <div class="col-3">
                        <h3>Cập nhật thông tin <span>{{ $account_client->name }}</span></h3>

                        <input type="text" name="name" placeholder="Họ tên" id="name" value="{{ $account_client->name }}">
                        @error('name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror

                        <input type="email" name="email" placeholder="Email" id="email" disabled value="{{ $account_client->email }}">
                        @error('email')
                        <small class="text-danger">{{$message}}</small>
                        @enderror

                        <input type="date" name="birth_day" value="{{ $account_client->date_of_birth }}">

                        <input type="text" name="phone" placeholder="Số điện thoại" id="phone" value="{{ $account_client->phone ? $account_client->phone : old('phone') }}">
                        @error('phone')
                        <small class="text-danger">{{$message}}</small>
                        @enderror

                        <div class="address_vn" style="margin-top: 10px;">
                            <select name="province" id="province" class="select_checkout" onchange="selectProvince(this)">
                                <option value="" disabled="disabled" selected="" value="null">Tỉnh/Thành Phố</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" @if($province->id == $account_client->province_id) selected @endif>{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($account_client->district_id)
                            <select name="district" id="district" class="select_checkout" onchange="selectDistrict(this)">
                                <option value="" disabled="disabled" selected="" value="null">Quận/Huyện</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" @if($district->id == $account_client->district_id) selected @endif>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if($account_client->ward_id)
                            <select name="ward" id="ward" class="select_checkout">
                                <option value="" disabled="disabled" selected="" value="null">Phường/Xã</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" @if($ward->id == $account_client->ward_id) selected @endif>{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        <input type="text" name="address_plus" placeholder="Số nhà, đường, ..." value="{{ $account_client->address_plus }}">

                        <button type="submit" class="btn">Lưu</button>
                    </div>
                    <div class="wrapper-order-status">
                        <h3 class="mb-2">Trạng thái đơn hàng</h3>
                        <div class="warpper">
                            <input class="radio" id="one" name="group" type="radio" checked>
                            <input class="radio" id="two" name="group" type="radio">
                            <input class="radio" id="three" name="group" type="radio">
                            <input class="radio" id="four" name="group" type="radio">
                            <div class="tabs">
                            <label class="tab" id="one-tab" for="one">Tất cả</label>
                            <label class="tab" id="two-tab" for="two">Đặt hàng</label>
                            <label class="tab" id="three-tab" for="three">Đang giao</label>
                            <label class="tab" id="four-tab" for="four">Đã hủy</label>
                              </div>
                            <div class="panels">
                            <div class="panel" id="one-panel">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Mã</th>
                                            <th>Tổng bill</th>
                                            <th>Trạng thái order</th>
                                            <th>Thông tin</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order_masters as $order_master)
                                            <tr>
                                                <td>{{ $order_master->order_code }}</td>
                                                <td>{{ number_format($order_master->total_price, 0, '', '.') }}đ</td>
                                                <td>
                                                    @php
                                                        switch ($order_master->status) {
                                                            case 1:
                                                                echo  'Đặt hàng';
                                                                break;
                                                            case 2:
                                                                echo  'Check đơn';
                                                                break;
                                                            case 3:
                                                                echo  'Đang giao hàng';
                                                                break;
                                                            case 4:
                                                                echo  'Hoàn thành';
                                                                break;
                                                            case 5:
                                                                echo  'Đã hủy';
                                                                break;
                                                        }
                                                    @endphp
                                                </td>
                                                <td style="cursor: pointer;" class="view-order-detail" data-order-id="{{ $order_master->id }}">Xem chi tiết</td>
                                                @if(in_array($order_master->status, [1,2]))
                                                    <td>Cancle</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel" id="two-panel">
                              <div class="panel-title">Take-Away Skills</div>
                              <p>You will learn many aspects of styling web pages! You’ll be able to set up the correct file structure, edit text and colors, and create attractive layouts. With these skills, you’ll be able to customize the appearance of your web pages to suit your every need!</p>
                            </div>
                            <div class="panel" id="three-panel">
                              <div class="panel-title">Note on Prerequisites</div>
                              <p>We recommend that you complete Learn HTML before learning CSS.</p>
                            </div>
                            <div class="panel" id="four-panel">
                                <div class="panel-title">Note on Prerequisites</div>
                                <p>We recommend that you complete Learn HTML before learning CSS.</p>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
