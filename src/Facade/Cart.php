<?php

namespace Therour\Cart\Facade;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'Cart';
	}
}