<?php
namespace Demos\Admin;
use App\Http\Controllers\Controller;
use View;
use Lang;
use Response;
use Exception;
use DB;
use Illuminate\Http\Request;
use Auth;

class AdminSettingsController extends Controller {
    public function __construct() {
        $this->middleware('auth:account');
    }

    public function index() {
        AdminLib::checkAccess(Auth::id());
        View::share('page_title', Lang::get('admin::main.navigation.settings'));
        $params = DB::table('params')->first();
        $params->permitted_ip = implode("\r\n", explode(',', $params->permitted_ip));
        $page_data = [
            'languages' => app('langSettings')->allLangs,
            'params' => $params,
            'alias_for_menu'=>"settings",
        ];
        return view('admin::settings.index', $page_data);
    }

    public function saveParams(Request $request) {
        AdminLib::checkAccess(Auth::id());
        try {
            $value = $request->value;
            if ($request->field == 'permitted_ip') {
                $value = implode(',', $this->multiexplode(array(" ","\n", "\t", "\r", "\0", "\x0B"), $value));
            }
            DB::table('params')->update([$request->field => $value]);
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
        return Response::make("ok", 200);
    }


    public function addLanguage(Request $request) {
        AdminLib::checkAccess(Auth::id());
        try {
            $check_lang = LangModel::where('code', '=', $request->code)->first();
            if (!$check_lang){
                $order = LangModel::max('order')+1;
                $language = new LangModel([
                        'code' => $request->code,
                        'name' => $request->name,
                        'hidden' => $request->hidden,
                        'default' => 0,
                        'default_admin' => 0,
                        'order' => $order
                    ]);
                $language->save();
            } else {
                return Response::make("exist", 200);
            }
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
        return Response::make("ok", 200);
    }

    public function saveLanguage(Request $request) {
        AdminLib::checkAccess(Auth::id());
        try {
            $language = LangModel::find($request->id);
            $field = $request->field;
            $language->$field = $request->value;
            $language->save();
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
        return Response::make("ok", 200);
    }

    public function ordLanguages(Request $request) {
        AdminLib::checkAccess(Auth::id());
        $data = $request->ord;
        try {
            foreach ($data as $order => $id) {
                $id = substr($id, 3);
                DB::table('languages')->where("id", "=", $id)->update( array("order" => $order) );
            }
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
        return Response::make("ok", 200);
    }

    public function multiexplode ($delimiters, $string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return array_values(array_filter($launch));
    }


}
