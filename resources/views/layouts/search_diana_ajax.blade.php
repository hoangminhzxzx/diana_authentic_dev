{{--<div class="result-search-diana">--}}
    <ul>
        @foreach($products as $product)
        <li>
            <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}" class="item-link-search">
                <div class="item-box-search">
                    <div class="thumb-item-search-diana">
                        <img src="{{ url($product->thumbnail) }}" alt="" width="50px">
                    </div>

                    <div class="info-item-search-diana">
                        <p>{{ $product->title }}</p>
                        <span>{{ number_format($product->price, 0, '.', '.') }}đ</span>
                    </div>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
{{--</div>--}}
