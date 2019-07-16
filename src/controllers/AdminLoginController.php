<?php
namespace Demos\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Lang;
use Session;
use Hash;
use Redirect;
use Mail;
use View;

class AdminLoginController extends Controller {
    public function __construct() {
        $this->middleware('guest:account')->except('logout');
    }

    public function showLoginForm() {
        View::share('page_title', Lang::get('admin::main.login.wellcome'));
        return view('admin::admin-login');
    }

    public function login(Request $request) {
        $this->validate($request, [
            'login'   => 'required',
            'password' => 'required|min:6'
        ]);
        if (Auth::guard('account')->attempt(['login' => $request->login, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('admin.index'));
        }
        return redirect()->back()->withInput($request->only('login', 'remember'))->withErrors(['password' => Lang::get('admin::main.login.wrong_password')]);
    }

    public function passwordReset(Request $request) {
        $this->validate($request, [
            'email'   => 'required'
        ]);

        $email = $request->email;
        $account = Account::where('email', $email)->first();

        if ($account) {

            try {
                $data['email'] = $account->email;
                $data['new_password'] = str_random(10);
                $account->password = Hash::make($data['new_password']);
                $account->save();

                Mail::send('admin::email.password_reset', $data, function($message) use($data) {
                    $message->subject(Lang::get('admin::main.login.password_reset'));
                    $message->from('support@cms', 'CMS');
                    $message->to($data['email']);
                });

            } catch (Exception $e) {                
                return redirect()->back()->withErrors(['email' => Lang::get('admin::main.login.email_error')]);
            }

        } else {            
            return redirect()->back()->withErrors(['email' => Lang::get('admin::main.login.no_email')]);
        }

        Session::flash('login_success', Lang::get('admin::main.login.reset_password_success'));
        return Redirect::back();
    }

    public function logout() {
        Auth::guard('account')->logout();
        return redirect()->route('admin.index');
    }
}