@extends('layouts.layout_front')
@section('title')
    Chi tiết sản phẩm
@endsection
@section('content')
    <style>
        .active_color {
            border: 2px solid blue;
        }
    </style>
    <!-- -------------- single product --------------------->
    <div class="small-container single-product">
        <input type="hidden"
               value="@php if (isset($is_accessory) && $is_accessory == 1) { echo $is_accessory; } else echo 0; @endphp"
               name="is_accessory" id="is_accessory">
        <div class="row" style="align-items: unset;">
            <div class="col-2">
                <div style="text-align: center;">
                    <img src="{{ url($product->thumbnail) }}" id="ProductImg">
                </div>
                @if($product->images)
                    <div class="small-img-row thumb-image-slider">
                        {{--                    <div class="small-img-col">--}}
                        {{--                        <img src="{{ url($product->thumbnail) }}" width="100%" class="small-img" onclick="changeImagePreview(this)" style="height: 96%;">--}}
                        {{--                        <img src="{{ url($product->thumbnail) }}" width="100%" class="small-img">--}}
                        {{--                    </div>--}}
                        {{--                    @if($product->images)--}}
                        {{--                        @foreach(json_decode($product->images) as $image)--}}
                        {{--                            <div class="small-img-col">--}}
                        {{--                                <img src="{{ url($image) }}" width="100%" class="small-img">--}}
                        {{--                            </div>--}}
                        {{--                        @endforeach--}}
                        {{--                    @endif--}}
                        @if(count(json_decode($product->images)) > 0)
                        <div class="splide">
                            <div class="splide__track">
                                <ul class="splide__list">
{{--                                    @if(count(json_decode($product->images)) > 0)--}}
                                    <li class="splide__slide">
                                        <img src="{{ url($product->thumbnail) }}" width="100%" class="small-img">
                                    </li>
                                    @foreach(json_decode($product->images) as $image)
                                        <li class="splide__slide">
                                            <img src="{{ url($image) }}" class="small-img">
                                        </li>
                                    @endforeach
{{--                                    @endif--}}
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-2">
                {{--                <form action="{{ route('client.product.detail.step2', ['id'=>$product->id]) }}" method="POST">--}}
                {{--                    @csrf--}}
                <input type="hidden" id="product_id" value="{{ $product->id }}">
                <p>Trang chủ / {{ $product->category->title }}</p>
                <h1>{{ $product->title }}</h1>
                <h4>{{ number_format($product->price, 0, '.', '.') }} VNĐ</h4>
                @if($is_accessory == 0)
                    <input type="hidden" value="" id="valueSize" name="size">
                    <select id="chooseSize" onchange="chooseSize(this)" style="border: 1px solid #ccc;">
                        <option value="nothing">Chọn size</option>
                        @if(isset($sizes) && $sizes)
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        @endif
                    </select>
                @endif
                @error('size')
                <small style="color: indianred;">{{$message}}</small>
                @enderror
                <input type="number" value="1" min="1" max="" name="qty"
                       style="margin-top: 1rem; border: 1px solid #000; border: 1px solid #ccc; display: none;">
                <input type="button" onclick="addToCart(this)" class="btn" value="Thêm vào giỏ hàng" id=""
                       style="width: 100%;">
                @if($product->content)
                    <h3>Chi tiết sản phẩm <i class="fa fa-indent"></i></h3>
                @endif
                {{--                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae nesciunt odit beatae veniam autem--}}
                {{--                    iste id est possimus laborum iusto.</p>--}}
                <div>{!! $product->content !!}</div>
                {{--                </form>--}}
            </div>
        </div>
    </div>

    <!-- ---------------title------------- -->
    <div class="small-container">
        <div class="row row-2" style="margin-bottom: 0px;">
            <h2>Sản phẩm liên quan</h2>
            <a href="{{ route('client.category.list.product', ['slug' => $category_slug]) }}"
               style="text-decoration: underline;">Xem thêm</a>
        </div>
        <div class="row" style="justify-content: unset;">
            @foreach($list_product_more as $item)
                <div class="col-4">
                    <a href="{{ route('client.product.detail', ['slug'=>$item->slug]) }}"><img
                            src="{{url($item->thumbnail)}}" style="max-width: 200px;"></a>
                    <h4 class="product-title text-center">{{ $item->title }}</h4>
                    <p>{{ number_format($item->price, 0, '.', '.') }}đ</p>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@section('scripts')
     <script>
        $(document).ready(function () {
            var splide = new Splide('.splide', {
                type: 'loop',
                perPage: 3,
                perMove: 1,
                pagination: false,
                autoplay: true,
            });

            splide.mount();

            // splide.on('active', function (index) {
            //     // do something
            //     let image_main = document.getElementById('ProductImg');
            //     let slide_active = index.slide,
            //         src_slide_active = slide_active.firstElementChild.getAttribute('src');
            //     image_main.setAttribute('src', src_slide_active);
            // });

            splide.on('click', function (index) {
                // do something
                let image_main = document.getElementById('ProductImg');
                let slide_active = index.slide,
                    src_slide_active = slide_active.firstElementChild.getAttribute('src');
                image_main.setAttribute('src', src_slide_active);
            });
        })
    </script>
@endsection
