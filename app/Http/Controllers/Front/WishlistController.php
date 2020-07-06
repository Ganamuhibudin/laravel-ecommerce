<?php

namespace App\Http\Controllers\Front;

use App\Shop\Wishlists\Wishlist;
use App\Http\Controllers\Controller;
use DB;

class WishlistController extends Controller
{
    public function store()
    {
        $customerID = request()->input('customer_id');
        $productID = request()->input('product_id');

        $checkWishList = DB::table('wishlist')
            ->where('customer_id', $customerID)
            ->where('product_id', $productID)
            ->count();

        if ($checkWishList > 0) {
            return response()->json(array(
                'message' => 'Product already on your wishlist !'
            ));
        }

        $whishList = new Wishlist;
        $whishList->customer_id = $customerID;
        $whishList->product_id = request()->input('product_id');
        $whishList->save();

        return response()->json(array(
            'message' => 'Success add product to your wishlist'
        ));
    }

    public function delete($id) {
        $wishlist = Wishlist::find($id);
        $wishlist->delete();

        return response()->json(array(
            'message' => 'Success remove product from your wishlist '
        ));
    }
}