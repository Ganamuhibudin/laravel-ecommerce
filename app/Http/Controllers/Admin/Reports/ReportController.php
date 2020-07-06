<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\Interfaces\OrderProductRepositoryInterface;

class ReportController extends Controller
{
    /**
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param OrderProductRepositoryInterface $opRepository
     */
    public function __construct(OrderProductRepositoryInterface $opRepository)
    {
        $this->orderProduct = $opRepository;
        // dd($this->orderProduct->listTopOrderedProducts());
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function orders()
    {
        return view('admin.reports.orders');
    }

    public function generateReportOrders()
    {
        $date = request()->input('dates');
        $expDate = explode(" - ", $date);
        $startDate = $expDate[0] . ' 00:00:00';
        $endDate = $expDate[1] . ' 23:59:59';

        $orders = Order::select('*', 'customers.name as customer_name', 'order_statuses.name as status_name', 'order_statuses.color as status_color')
            ->leftJoin('customers', 'customers.id', 'orders.customer_id')
            ->leftJoin('order_statuses', 'order_statuses.id', 'orders.order_status_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->get();

        return view('admin.reports.orders', [
            'orders' => $orders
        ]);
    }

    public function products()
    {
        $products = $this->orderProduct->listTopOrderedProducts();
        // dd($products);
        return view('admin.reports.products', [
            'products' => $products
        ]);
    }
}
