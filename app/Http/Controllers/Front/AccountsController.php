<?php

namespace App\Http\Controllers\Front;

use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\Wishlists\Wishlist;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\OrderStatuses\Repositories\OrderStatusRepository;

use Illuminate\Http\Request;
use Validator;

class AccountsController extends Controller
{
    use OrderTransformable;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * AccountsController constructor.
     *
     * @param CourierRepositoryInterface $courierRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CourierRepositoryInterface $courierRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepo = $customerRepository;
        $this->courierRepo = $courierRepository;
    }

    public function index()
    {
        $customer = $this->customerRepo->findCustomerById(auth()->user()->id);

        $customerRepo = new CustomerRepository($customer);
        $orders = $customerRepo->findOrders(['*'], 'created_at');

        $orders->transform(function (Order $order) {
            return $this->transformOrder($order);
        });
        // dd($orders->toArray());

        $addresses = $customerRepo->findAddresses();

        $wishlist = Wishlist::select('wishlist.*', 'products.*')
            ->where('customer_id', $customer->id)
            ->join('products', 'products.id', 'wishlist.product_id')
            ->get();

        return view('front.accounts', [
            'customer' => $customer,
            'orders' => $this->customerRepo->paginateArrayResults($orders->toArray(), 15),
            'addresses' => $addresses,
            'wishlist' => $wishlist,
        ]);
    }

    public function uploadBankReceipt(Request $request)
    {
        $directory = 'orders/';
        $orderID = $request->input('id');

        $validation = Validator::make($request->all(), [
            'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validation->passes()) {
            $orderStatusRepo = new OrderStatusRepository(new OrderStatus);
            $os = $orderStatusRepo->findByName('waiting for payment confirmation');

            $image = $request->file('select_file');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $filestore = storage_path().'/app/public/'.$directory;
            $image->move($filestore, $new_name);
            $save_path = $directory . $new_name;
            $urlFile = asset('storage/' . $save_path);

            $order = Order::find($orderID);
            $order->bank_receipt = $save_path;
            $order->order_status_id = $os->id;
            $order->save();

            return response()->json([
                'message'   => 'Image Upload Successfully ',
                'uploaded_image' => '<img src="' . $urlFile . '" class="img-thumbnail" width="300" />',
                'class_name'  => 'alert-success'
            ]);
        } else {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name'  => 'alert-danger'
            ]);
        }
    }

    public function orderConfirmation($id)
    {
        $orderStatusRepo = new OrderStatusRepository(new OrderStatus);
        $os = $orderStatusRepo->findByName('delivered');

        $order = Order::find($id);
        $order->order_status_id = $os->id;
        $order->save();

        return response()->json([
            'message'   => 'Update Order Status Successfully'
        ]);
    }

    public function returOrder($id)
    {
        $orderStatusRepo = new OrderStatusRepository(new OrderStatus);
        $os = $orderStatusRepo->findByName('retur');

        $order = Order::find($id);
        $order->order_status_id = $os->id;
        $order->save();

        return response()->json([
            'message'   => 'Update Order Status Successfully'
        ]);
    }

    public function cancelOrder($id)
    {
        $orderStatusRepo = new OrderStatusRepository(new OrderStatus);
        $os = $orderStatusRepo->findByName('canceled');

        $order = Order::find($id);
        $order->order_status_id = $os->id;
        $order->save();

        return response()->json([
            'message'   => 'Cancel Order Successfully'
        ]);
    }
}
