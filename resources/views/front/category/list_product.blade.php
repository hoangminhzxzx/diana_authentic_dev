@extends('layouts.layout_front')
@section('title')
    {{ $category_title }}
@endsection
@section('content')
    <div class="container">
        <div class="wp-list-product">
            <div class="wp-filter-product">
                <form action="{{ route('client.category.list.product', ['slug' => $slug]) }}" id="filterForm" method="GET">
                    <a class="clean-filter">Bỏ lọc</a>
                    <div class="box-filter">
                        <h5>Theo thương hiệu</h5>
                        @if(isset($list_category_filter) && $list_category_filter)
                            @foreach($list_category_filter as $k=>$category_fillter)
                                <div class="single-filter">
                                    <input type="radio" @if(isset($filter_category) && $filter_category == $category_fillter->id) checked @endif class="filter-input remove-class-default" name="filter_category" value="{{ $category_fillter->id }}" id="filter_category_{{ $k + 1 }}">
                                    <label for="filter_category_{{ $k + 1 }}" class="filter-title">{{ $category_fillter->title }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="box-filter">
                        <h5>Theo khoảng giá</h5>
                        <div class="single-filter">
                            <input type="radio" class="filter-input remove-class-default" @if($filter_range_price == '[1000000, 2000000]') checked @endif name="filter_range_price" value="[1000000, 2000000]" id="filter_range_price_1">
                            <label for="filter_range_price_1" class="filter-title">1M - 2M</label>
                        </div>
                        <div class="single-filter">
                            <input type="radio" class="filter-input remove-class-default" @if($filter_range_price == '[2000000, 4000000]') checked @endif name="filter_range_price" value="[2000000, 4000000]" id="filter_range_price_2">
                            <label for="filter_range_price_2" class="filter-title">2M - 4M</label>
                        </div>
                    </div>
                    <input type="submit" class="btn-submit-filter" value="Áp dụng" >
                </form>
            </div>
            <div class="small-container remove-litle-small">
                <div class="row row-2" style="padding-left: 10px;">
                    <h2>{{ $category_title }} của Diana Authentic</h2>
                    <form action="{{ route('client.category.list.product', ['slug' => $slug]) }}" id="formFilterProduct">
                        {{--                @csrf--}}
                        <select name="orderBy" style="border: 1px solid #ccc;" onchange="orderProduct(this)" data-category-slug="{{ $slug }}">
                            {{--                    <option value="">Mặc định</option>--}}
                            <option value="asc" @if(isset($orderBy) && $orderBy == 'asc') selected @endif>Giá tăng dần</option>
                            <option value="desc" @if(isset($orderBy) && $orderBy == 'desc') selected @endif>Giá giảm dần</option>
                        </select>

                        <select name="limit" style="border: 1px solid #ccc;" onchange="orderProduct(this)" data-category-slug="{{ $slug }}">
                            {{--                    <option value="">Mặc định</option>--}}
                            <option value="4" @if(isset($limit) && $limit == 4) selected @endif>4</option>
                            <option value="8" @if(isset($limit) && $limit == 8) selected @endif>8</option>
                            <option value="12" @if(isset($limit) && $limit == 12) selected @endif>12</option>
                            <option value="16" @if(isset($limit) && $limit == 16) selected @endif>16</option>
                            <option value="20" @if(isset($limit) && $limit == 20) selected @endif>20</option>
                        </select>
                    </form>
                </div>
                <div class="row" style="justify-content: unset;">
                    @foreach($list_product as $product)
                        <div class="col-4 box-item-product">
                            <a class="wp-img" href="{{ route('client.product.detail', ['slug' => $product->slug]) }}"><img src="{{ url($product->thumbnail) }}" alt=""></a>
                            <div class="box-info-desc">
                                <h5>{{ $product->title }}</h5>
                                <p>{{ number_format($product->price, 0, '.', '.') }}đ</p>
                            </div>
                            {{--                <div class="rating">--}}
                            {{--                    <i class="fa fa-star" aria-hidden="true"></i>--}}
                            {{--                    <i class="fa fa-star" aria-hidden="true"></i>--}}
                            {{--                    <i class="fa fa-star" aria-hidden="true"></i>--}}
                            {{--                    <i class="fa fa-star" aria-hidden="true"></i>--}}
                            {{--                    <i class="fa fa-star-o" aria-hidden="true"></i>--}}
                            {{--                </div>--}}
                        </div>
                    @endforeach
                </div>
                {{ $list_product->render('vendor.pagination.bootstrap-4') }}
                {{--        <div class="page-btn">--}}
                {{--            <span>1</span>--}}
                {{--            <span>2</span>--}}
                {{--            <span>3</span>--}}
                {{--            <span>4</span>--}}
                {{--            <span>&#8594;</span>--}}
                {{--        </div>--}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.clean-filter').click(function () {
                let list_input_filter = $(".filter-input");
                list_input_filter.each(function (index,item) {
                    // console.log(item);
                    if (item.hasAttribute('checked')) {
                        item.removeAttribute('checked');
                    }
                })
                $("#filterForm").submit();
            })
        })
    </script>
@endsection
