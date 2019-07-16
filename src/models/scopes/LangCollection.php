<?php

namespace Demos\Admin;

class LangCollection extends \Illuminate\Database\Eloquent\Collection {
	
	public function offsetGet($code) {
		$item = $this->filter(function ($item) use ($code) {
			return $item->lang === $code;
		})->first();

		return ($item) ? $item : new LangDummy();
	}
}