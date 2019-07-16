<?php

namespace Demos\Admin;

trait LangTrait {

	/**
	* Boot the scope.
	*
	* @return void
	*/
	public static function bootLangTrait() {
		static::addGlobalScope(new LangScope);
	}
  
	/**
	* Get the query builder without the scope applied.
	*
	* @return \Illuminate\Database\Eloquent\Builder
	*/
	public static function allLangs() {
		return with(new static)->newQueryWithoutScope(new LangScope);
	}

	
	public static function hiddenLangs() {
		return with(new static)->newQueryWithoutScope(new LangScope);
	}

	public function newCollection(array $models = array()) {
		return new LangCollection($models);
	}

}