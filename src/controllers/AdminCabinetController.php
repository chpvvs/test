<?php
namespace Demos\Admin;

use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;
use \View;
use \App;
use \Auth;
use \Config;
use Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AdminCabinetController  extends Controller {

	function __construct() {
        $this->middleware('auth:account');
		$this->avatar_path = Config::get('admin.avatar_path');
	}

	public function index() {
		View::share('page_title', Lang::get('admin::main.navigation.cabinet'));
        $account = Account::find(Auth::id());

		$data=[
            'avatar_path'=> $this->avatar_path,
            'languages'=>app('langSettings'),
						'account'=>$account,
            'alias_for_menu'=>"cabinet",
        ];

		return View::make('admin::cabinet',$data);
	}

	public function getIndex()
    {
        //View::share('page_title', Lang::get('admin::main.navigation.cabinet'));
        $account = Account::find(Auth::id());
        $data=[
            'avatar_path'=> $this->avatar_path,
            'languages'=>app('langSettings'),
            'account'=>$account,
            'alias_for_menu'=>"cabinet",
        ];

        return Response::make($data, 200);
    }


	public function postUpdate(Request $request) {
        $field=$request->input('field');
        $value=$request->input('value');
        $account_id = Auth::id();

        Account::where('id',$account_id)->update([$field=>$value]);

			return Response::make("ok", 200);
	}

	public function postUploadAvatar(Request $request) {
		$data = json_decode($request->input('data'), TRUE);

		$file = $request->file('photo');
        $account = Account::find(Auth::id());
		$avatar = $account->avatar;
		try {

			$account->avatar = AdminLib::upload($file, $this->avatar_path, $avatar);
			$account->save();
		} catch (Exception $e) {
			return Response::make($e->getMessage(), 500);
		}
		return Response::make(json_encode(array("status" => "ok")), 200);
	}

	public function postDeleteAvatar(Request $request) {
		$account_id = $request->input('id');
		$account = Account::find($account_id);
		$avatar = $account->avatar;
		try {
			AdminLib::clearFolders($this->avatar_path, $avatar);
			$account->avatar = '';
			$account->save();
		} catch (Exception $e) {
			return Response::make($e->getMessage(), 500);
		}
		return Response::make("ok", 200);
	}


	public function postChangeLogin(Request $request) {

        $account = Account::find($request->input('account_id'));
        if (Auth::guard('account')->attempt(['login' =>  $account->login, 'password' =>   $request->input('password')], $request->remember)) {

            $test_login = Account::where("login", "=", $request->input('new_login'))->get();

            if($test_login->count()){
                return Response::make('login_exist', 500);
            }else{
                $account->login = $request->input('new_login');
                if ( $account->save() ) {
                    Session::put('intended_path', $request->input   ('url'));
                    return Response::make("ok", 200);
                } else
                    return Response::make('error', 500);
            }
        }else{
            return Response::make('wrong_password', 401);
        }



	}

	public function postChangePassword(Request $request) {
        $account = Account::find($request->input('account_id'));
        if (Auth::guard('account')->attempt(['login' =>  $account->login, 'password' =>   $request->input('old_password')], $request->remember)) {

			$account->password = Hash::make($request->input('new_password'));
			if ( $account->save() ) {
				Session::put('intended_path', $request->input('url'));
				return Response::make("ok", 200);
			} else
				return Response::make('error', 500);

		} else {
			return Response::make('wrong_password', 401);
		}
	}



}
