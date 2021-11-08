@extends('layouts.layout_front')
@section('title')
    Cám Ơn Đã Đặt Hàng
@endsection
@section('search')
    <div class="wp-search-all-product">
        {{--            {{ dd(session()->get('search_diana')) }}--}}
        <input type="text" placeholder="Tìm kiếm sản phẩm ..." id="header_input_search" oninput="searchDiana(this)" value="{{ session()->get('search_diana') }}">
        <div class="result-search-diana"></div>
    </div>
@endsection

@section('content')
    <div class="small-container" style="min-height: 400px; margin-top: 80px;">
        <h3><b>Đặt hàng thành công !</b></h3>
        <p>Vui lòng để ý điện thoại và Email, chúng tôi sẽ liên hệ với bạn sớm nhất !</p>
        <p>Bấm vào <a href="{{ route('homeFront') }}" style="color: #4da6dc;">đây</a> để quay lại Trang Chủ</p>
    </div>
@endsection
