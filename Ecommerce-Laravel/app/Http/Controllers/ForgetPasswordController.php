<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;


class ForgetPasswordController extends Controller

{

function forgetPassword()
{
    return view('forget-password');
}

function forgetPasswordPost(Request $request)
{

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $request->validate([
        'email' => "required|email|exists:users,email",
    ]);
    /*
    $token = Password::createToken();
    DB::table('password_resets')->insert([
        'email' => $request->email,
        'token' => $token,
        'created_at' => Carbon::now()
    ]);*/
    $status = Password::sendResetLink(
        $request->only('email')
    );

    /*Mail::send('auth.passwords.email', ['token' => $token], function ($message) use ($request) {
        $message->to($request->email);
        $message->subject("Reset Password");
    });*/

    return redirect()->to(route("forget.password"))
        ->with("Success", "We have sent an email to reset your password.");
}
function resetPassword($token)
{
    return view('auth.passwords.reset',compact("token"));

}
function resetPasswordPost(Request $request)
{
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('home')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

}