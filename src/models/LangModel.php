<?php 

namespace Demos\Admin;
use \Eloquent;
use \Schema;

class LangModel extends Eloquent {

	protected $table = 'languages';	
	protected $guarded = array("id");
	public $timestamps = FALSE;

	public static function getLangConfig() {
		$out = array();

		if (!Schema::hasTable('languages'))
			return array('default'=>array('code'=>'ru'), 'languages'=>array('ru'));

		$out['languages'] = LangModel::where('hidden', '=', 0)
									   ->orderBy('order', 'asc')
									   ->pluck('code');

		$out['default'] = LangModel::where('default', 1)
							  		 ->first()->toArray();

		$out['default_admin'] = LangModel::where('default_admin', 1)
							  			 ->first()->toArray();

		return $out;
	}
	
}