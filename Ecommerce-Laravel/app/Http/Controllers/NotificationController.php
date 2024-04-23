<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmEmail;
use Illuminate\Http\Request;
//use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    function confirmemail(){
        Notification::route('mail', "linyeol2712@gmail.com")
      //  $user->notify(new ConfirmEmail());
       ->notify(new ConfirmEmail());
       return "Notification sent successfully";
    }

      /*  public function index(){
        return view('backend.notification.index');
    }
    public function show(Request $request){
        $notification=Auth()->user()->notifications()->where('id',$request->id)->first();
        if($notification){
            $notification->markAsRead();
            return redirect($notification->data['actionURL']);
        }
    }
    public function delete($id){
        $notification=Notification::find($id);
        if($notification){
            $status=$notification->delete();
            if($status){
                request()->session()->flash('success','Notification deleted successfully');
                return back();
            }
            else{
                request()->session()->flash('error','Error please try again');
                return back();
            }
        }
        else{
            request()->session()->flash('error','Notification not found');
            return back();
        }
    }*/
}
