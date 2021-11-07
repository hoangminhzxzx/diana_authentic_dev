<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CategoryController extends Controller
{
    public function listProductFollowCategory(Request $request,$slug)
    {
//        dd($request->input());
        $category = Category::query()->where('slug', '=', $slug)->first();
        if ($category) {
            $filter_category = intval($request->query('filter_category'));
            $filter_range_price = json_decode($request->query('filter_range_price'));

            $category_title = $category->title;
            $list_category = [];
            $category_childs = $category->childs;
            $list_category[] = $category->id;
            $list_category_filter_ids = [];
            if ($category_childs->count() > 0) {
                foreach ($category_childs as $category_child) {
                    $list_category[] = $category_child->id;
                    $list_category_filter_ids[] = $category_child->id;
                }
            }

            $limit = 4;
            if (isset($_GET['limit']) && $_GET['limit']) {
                $limit = $_GET['limit'];
            }
            $orderBy = 'asc';
            if (isset($_GET['orderBy']) && $_GET['orderBy']) {
                $orderBy = $_GET['orderBy'];
            }
            $list_product = Product::query()
                ->when($filter_category, function (\Illuminate\Database\Eloquent\Builder $query) use ($filter_category) {
                    return $query->where('category_id', '=', $filter_category);
                })
                ->when($filter_range_price, function (Builder $query) use ($filter_range_price) {
                    return $query->where('price', '>=', $filter_range_price[0])->where('price', '<', $filter_range_price[1]);
                })
                ->whereIn('category_id', $list_category)
                ->where('is_publish', '=', 1)
                ->orderBy('price', $orderBy)
                ->paginate($limit);

            if (!empty($list_category_filter_ids)) {
                $list_category_filter = Category::query()->whereIn('id', $list_category_filter_ids)->get();
            }

            return view('front.category.list_product',
                [
                    'list_product' => $list_product,
                    'category_title' => $category_title,
                    'slug' => $slug,
                    'orderBy' => $orderBy,
                    'limit' => $limit,
                    'list_category_filter' => $list_category_filter,
                    'filter_category' => $filter_category,
                    'filter_range_price' => $request->query('filter_range_price'),
                ]
            );
//            }
        }
    }
}
