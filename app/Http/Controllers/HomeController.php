<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;

use App\Models\User;

use App\Models\Cart;

use App\Models\Order;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::where('usertype', 'user')->get()->count();

        $product = Product::all()->count();

        $order = Order::all()->count();

        $delivered = Order::where('status', 'Diterima')->get()->count();

        return view('admin.index', compact('user', 'product', 'order', 'delivered'));
    }

    public function home()
    {
        $product = Product::all();

        if (Auth::id()) {
            $user = Auth::user();
            $userid = $user->id;
            $count = Cart::where('user_id', $userid)->count();
        } else {
            $count = '';
        }

        return view('home.index', compact('product', 'count'));
    }

    public function login_home()
    {
        $product = Product::all();

        if (Auth::id()) {
            $user = Auth::user();
            $userid = $user->id;
            $count = Cart::where('user_id', $userid)->count();
        } else {
            $count = '';
        }

        return view('home.index', compact('product', 'count'));
    }

    public function product_details($id)
    {
        $data = Product::find($id);

        if (Auth::id()) {
            $user = Auth::user();
            $userid = $user->id;
            $count = Cart::where('user_id', $userid)->count();
        } else {
            $count = '';
        }

        return view('home.product_details', compact('data', 'count'));

    }

    public function add_cart($id)
    {
        $product_id = $id;
        $user_id = Auth::id();

        // Check if the product is already in the cart

        $newCartItem = new Cart;
        $newCartItem->user_id = $user_id;
        $newCartItem->product_id = $product_id;
        $newCartItem->save();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Added to the Cart Successfully');


        return redirect()->back();
    }

    public function mycart()
    {
        if (Auth::id()) {
            $user = Auth::user();
            $userid = $user->id;

            // Ambil semua produk unik dalam keranjang dengan mengelompokkannya berdasarkan product_id
            // $cart = Cart::where('user_id', $userid)
            //     ->selectRaw('product_id, count(*) as quantity')
            //     ->groupBy('product_id')
            //     ->get();

            $cart = DB::select("SELECT user_id , product_id , COUNT(product_id) as qty,  p.title, p.image, p.price, SUM(p.price) as total FROM carts JOIN products p on product_id = p.id  WHERE user_id = 1
            GROUP BY product_id , p.price
            ");

            $hitung = DB::selectOne("SELECT COUNT(*)  as total FROM carts");

            $count = $hitung->total;
        }

        return view('home.mycart', compact('count', 'cart'));
    }


    public function confirm_order(Request $request)
    {
        $name = $request->name;
        $address = $request->address;
        $phone = $request->phone;
        $total_payment = $request->total_payment;
        $userid = Auth::user()->id;

        $cart = Cart::where('user_id', $userid)->get();
        $qty = DB::selectOne("SELECT COUNT(*) as jml FROM carts WHERE user_id = $userid");

        // Create a new order
        $order = new Order;
        $order->name = $name;
        $order->rec_address = $address;
        $order->phone = $phone;
        $order->total_payment = $total_payment;
        $order->user_id = $userid;
        $order->save();

        // Save each product in the order
        foreach ($cart as $cartItem) {
            // Create a new OrderProduct entry
            $orderProduct = new OrderProduct;
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartItem->product_id;
            $orderProduct->quantity = $qty->jml;
            $orderProduct->save();
        }

        Cart::where('user_id', $userid)->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Produk Berhasil di Pesan');

        return redirect()->back();
    }


    public function myorders()
    {
        $user = Auth::user();
        $orders = Order::with('products')->where('user_id', $user->id)->get();
        $count = Cart::where('user_id', $user->id)->count();

        return view('home.order', compact('count', 'orders'));
    }

    public function delete_cart($id)
    {

        $data = Cart::find($id);

        $data->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Cart Deleted Successfully');

        return redirect()->back();

    }

    public function removeItem($product_id)
    {
        $user_id = Auth::id();
        Cart::where('user_id', $user_id)->where('product_id', $product_id)->delete();

        return redirect()->back()->with('success', 'Item removed from cart successfully');
    }





}
