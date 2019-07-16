<?php

namespace Demos\Admim\Facades;

use Illuminate\Support\Facades\Facade;

class AdminLib extends Facade {
	protected static function getFacadeAccessor() { return 'Demos\Admin\AdminLib'; }
}