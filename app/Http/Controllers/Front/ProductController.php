<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;

class ProductController extends Controller
{
    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        // if (request()->has('q') && request()->input('q') != '') {
        if (request()->has('q') && request()->input('q') != '') {
            $q = request()->input('q');
        } else {
            $q = "";
        }
        if (request()->has('brand') && request()->input('brand') != '') {
            $brand = request()->input('brand');
        } else {
            $brand = "";
        }

        if (request()->has('size') && request()->input('size') != '') {
            $size = request()->input('size');
        } else {
            $size = "";
        }

        if (request()->has('color') && request()->input('color') != '') {
            $color = request()->input('color');
        } else {
            $color = "";
        }

        if (request()->has('price_min') && request()->input('price_min') != '') {
            $priceMin = request()->input('price_min');
        } else {
            $priceMin = "";
        }

        if (request()->has('price_max') && request()->input('price_max') != '') {
            $priceMax = request()->input('price_max');
        } else {
            $priceMax = "";
        }

        if ($q == "" && $brand == "" && $size == "" && $color == "" && $priceMin == "" && $priceMax == "") {
            $list = $this->productRepo->listProducts();
        } else {
            $searchParam = $q . "|" . $brand . "|" . $size . "|" . $color . "|" . $priceMin . "|" . $priceMax;
            $list = $this->productRepo->searchProduct($searchParam);
        }
        // } else {
        //     $list = $this->productRepo->listProducts();
        // }

        $products = $list
            ->where('status', 1)
            ->where('quantity', '<>', 0)
            ->map(function (Product $item) {
                return $this->transformProduct($item);
            });

        return view('front.products.product-search', [
            'products' => $this->productRepo->paginateArrayResults($products->all(), 10)
        ]);
    }

    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {
        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $images = $product->images()->get();
        $category = $product->categories()->first();
        $productAttributes = $product->attributes;

        return view('front.products.product', compact(
            'product',
            'images',
            'productAttributes',
            'category',
            'combos'
        ));
    }
}
