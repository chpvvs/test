<?php

namespace Demos\Admin;

use \Illuminate\Database\Eloquent\Scope;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Query\Builder as BaseBuilder;
use \App;

class LangScope implements Scope{

	/**
	* Apply scope on the query.
	*
	* @param \Illuminate\Database\Eloquent\Builder  $builder
	* @return void
	*/
	public function apply(Builder $builder, Model $model) {
		$lang = App::getLocale();
		$def_lang = app('langSettings')->defaultLang;
		$fallback_lang = app('langSettings')->fallbackLang;
		$db_langs = \DB::table('languages')->pluck('code')->toArray();
		$langs = [$lang, $def_lang];
		if ($lang != $fallback_lang)
			$langs[] = $fallback_lang;

		$langs = array_unique(array_merge($langs,$db_langs));
		if ($lang)
			$builder->whereIn($builder->getModel()->getTable().'.lang', $langs)->orderByRaw('FIELD('.$builder->getModel()->getTable().'.lang,"'.implode('","', $langs).'")');

		$this->addAllLangs($builder);
		$this->addHiddenLangs($builder);
	}

 
	/**
	* Remove scope from the query.
	*
	* @param  Builder $builder
	* @return void
	*/
	public function remove(Builder $builder) {
		$query = $builder->getQuery();
 
 		$bindingKey = 0;

 		$column = $builder->getModel()->getTable().'.lang';

 		unset($query->orders);

		foreach ((array) $query->wheres as $key => $where) {
			if ($this->isLangConstraint($where, $column)) {

				$this->removeWhere($query, $key);
 
				// Here SoftDeletingScope simply removes the where
				// but since we use Basic where (not Null type)
				// we need to get rid of the binding as well
				$this->removeBinding($query, $bindingKey);

				$langs = app('langSettings')->langs->pluck('code');

				$query->whereIn($column, $langs)
					  ->orderByRaw('FIELD('.$column.',"'.implode('","', $langs).'")');
			}
		}
	}


	/**
	* Remove scope from the query and replace it with array of shown langs
	*
	* @param  Builder $builder
	* @return void
	*/

	public function removeNotHidden(Builder $builder) {
		$this->remove($builder);
	}
 
	/**
	 * Remove scope constraint from the query.
	 *
	 * @param  \Illuminate\Database\Query\Builder  $builder
	 * @param  int  $key
	 * @return void
	 */
	protected function removeWhere(BaseBuilder $query, $key) {
		unset($query->wheres[$key]); 
		$query->wheres = array_values($query->wheres);
	}
 
	/**
	 * Remove scope constraint from the query.
	 *
	 * @param  \Illuminate\Database\Query\Builder  $builder
	 * @param  int  $key
	 * @return void
	 */
	protected function removeBinding(BaseBuilder $query, $key) {
		$bindings = $query->getRawBindings()['where'];
 
		unset($bindings[0]);
		unset($bindings[1]);
 
		$query->setBindings($bindings);
	}
 
	/**
	 * Check if given where is the scope constraint.
	 *
	 * @param  array   $where
	 * @param  string  $column
	 * @return boolean
	 */
	protected function isLangConstraint(array $where, $column) {

		$langs = app('langSettings')->langs->pluck('code');

		if (isset($where['values'])) {
			$check_values = array_diff($where['values'], $langs);
			return ( $where['type'] == 'In' && $where['column'] == $column && empty($check_values) );
		} else {
			return ($where['type'] == 'Basic' && $where['column'] == $column && in_array($where['value'], $langs));
		}	
		
	}
 
	/**
	 * Extend Builder with custom method.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 */
	protected function addAllLangs(Builder $builder) {
		$builder->macro('allLangs', function(Builder $builder) {
			//$this->remove($builder);

			return $builder;
		});
	}


	/**
	 * Extend Builder with custom method.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder  $builder
	 */
	protected function addHiddenLangs(Builder $builder) {
		$builder->macro('hiddenLangs', function(Builder $builder) {
			//$this->removeNotHidden($builder);

			return $builder;
		});
	}


	
 
}