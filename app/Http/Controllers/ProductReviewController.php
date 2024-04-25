<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Notification;
use App\Notifications\StatusNotification;
use App\User;
use App\Models\ProductReview;
use App\Models\Order;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = ProductReview::getAllReview();

        return view('backend.review.index')->with('reviews', $reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //sửa
     public function store(Request $request)
     {
         $this->validate($request, [
             'rate' => 'required|numeric|min:1'
         ]);
     
         $product_info = Product::getProductBySlug($request->slug);
         $product = Product::where('slug', $request->slug)->first();
     
         // Kiểm tra nếu người dùng đã đặt hàng sản phẩm
         $already_cart = Cart::where('user_id', auth()->user()->id)->where('product_id', $product->id)->first();
         $order_id = isset($already_cart->order_id) ? $already_cart->order_id : null;
     
         if ($already_cart && $order_id) {
             // Người dùng đã đặt hàng sản phẩm, tiến hành lưu đánh giá
             $data = $request->all();
             $data['product_id'] = $product_info->id;
             $data['user_id'] = $request->user()->id;
             $data['status'] = 'active';
     
             $status = ProductReview::create($data);
     
             $user = User::where('role', 'admin')->get();
             $details = [
                 'title' => 'Đánh giá sản phẩm mới!',
                 'actionURL' => route('product-detail', $product_info->slug),
                 'fas' => 'fa-star'
             ];
             Notification::send($user, new StatusNotification($details));
     
             request()->session()->flash('success', 'Cảm ơn bạn đã phản hồi');
         } else {
             // Người dùng chưa đặt hàng sản phẩm, không lưu đánh giá
             request()->session()->flash('error', 'Vui lòng đánh giá đúng sản phẩm mà bạn đã đặt');
         }
     
         return redirect()->back();     

        //sửa
        $product = Product::where('slug', $request->slug)->first();
        $already_cart = Cart::where('user_id', auth()->user()->id)->where('product_id', $product->id)->first();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = ProductReview::find($id);
        // return $review;
        return view('backend.review.edit')->with('review', $review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = ProductReview::find($id);
        if ($review) {
            $data = $request->all();
            $status = $review->fill($data)->update();

            if ($status) {
                request()->session()->flash('success', 'Cập nhật đánh giá');
            } else {
                request()->session()->flash('error', 'Hãy thử lại!');
            }
        } else {
            request()->session()->flash('error', 'Không tìm thấy đánh giá!');
        }

        return redirect()->route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = ProductReview::find($id);
        $status = $review->delete();
        if ($status) {
            request()->session()->flash('success', 'Xóa đánh giá thành công');
        } else {
            request()->session()->flash('error', 'Hãy thử lại');
        }
        return redirect()->route('review.index');
    }
}