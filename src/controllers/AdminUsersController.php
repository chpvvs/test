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
use App\User;

class AdminUsersController  extends Controller {

	function __construct() {
        $this->middleware('auth:account');
		$this->avatar_path = Config::get('admin.avatar_path');
	}

	public function list() {
		View::share('page_title', Lang::get('admin::main.navigation.users'));

		$users = User::with(['orders' => function($query){
			$query->with('goods.lang');
		}])->get();

		$data=[
            'avatar_path'=> $this->avatar_path,
            'users'=>$users,
            'alias_for_menu'=>"users",
        ];

		return View::make('admin::users.index',$data);
	}



}
