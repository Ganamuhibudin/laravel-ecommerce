<?php

namespace App\Shop\Orders\Repositories;

use App\Shop\Carts\Repositories\CartRepository;
use App\Shop\Carts\ShoppingCart;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Employees\Employee;
use App\Shop\Employees\Repositories\EmployeeRepository;
use App\Events\OrderCreateEvent;
use App\Mail\sendEmailNotificationToAdminMailable;
use App\Mail\SendOrderToCustomerMailable;
use App\Shop\Orders\Exceptions\OrderInvalidArgumentException;
use App\Shop\Orders\Exceptions\OrderNotFoundException;
use App\Shop\Orders\Order;
use App\Shop\Orders\OrderProduct;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\Interfaces\OrderProductRepositoryInterface;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use DB;

class OrderProductRepository extends BaseRepository implements OrderProductRepositoryInterface
{
    use OrderTransformable;

    /**
     * OrderRepository constructor.
     * @param Order $order
     */
    public function __construct(OrderProduct $op)
    {
        parent::__construct($op);
        $this->model = $op;
    }

    /**
     * @return Collection
     */
    public function listTopOrderedProducts() : Collection
    {
        return $this->model
            ->select(DB::raw("SUM(order_product.quantity) as qty, products.*"))
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->groupBy('order_product.product_id')
            ->orderBy('qty', 'desc')
            ->limit(10)
            ->get();
    }
}
