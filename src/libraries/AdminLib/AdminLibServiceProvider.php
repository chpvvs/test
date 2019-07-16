<?php

namespace Demos\Admin;

use Illuminate\Support\ServiceProvider;

class AdminLibServiceProvider extends ServiceProvider {

	public function register() {
		$this->app->bind('adminLib', function() {
			return new Demos\Admin\AdminLib;
		});
	}	
}