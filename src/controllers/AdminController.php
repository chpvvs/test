<?php
namespace Demos\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use \View;
use \App;
use \Auth;
use \Config;
use \Session;
use \Lang;
use \Response;

 
class AdminController extends Controller {

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:account');
        $this->avatar_path = Config::get('admin.avatar_path');
    }
 	
 	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $account = Account::find(Auth::id());

    	$page_data = [
            'avatar_path'=> $this->avatar_path,
    		'page_title' => 'Admin index',
            'account'=>$account
    	];

    	return View::make('admin::index',$page_data);
    }

    public function crop(Request $request) {
        $data = [];
        $options = Config::get('admin.images');

        $data['aspect'] = ($request->aspect) ? $request->aspect : Session::get('aspect', $options['aspects'][0]);
        Session::put('aspect', $data['aspect']);
        list($data['original_width'], $data['original_height']) = getimagesize($request->img_big);

        $data['img_big'] = ($request->img_big) ? $request->img_big : "";
        $data['img_small'] = ($request->img_small) ? $request->img_small : "";
        $data['img_thumb'] = ($request->img_thumb) ? $request->img_thumb : "";

        $data['aspectable'] = ($request->aspectable) ? $request->aspectable : 1;
        $data['back_url'] = ($request->back_url) ? $request->back_url : "admin";

        View::share('page_title', Lang::get('admin::main.crop'));
        return View::make('admin::crop', ['data' => $data, 'options' => $options]);
    }

    public function doCrop(Request $request) {
        return (AdminLib::doCrop($request->data)) ? Response::make("ok", 200) : Response::make('error_do_crop', 500);
    }

 
}
?>