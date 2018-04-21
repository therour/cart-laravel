<?php

namespace Therour\Cart\App;

use Illuminate\Container\Container as App;

class CartSession
{
	protected $session;

	public function __construct()
	{
		$this->session = App::getInstance()->make('session');
	}

	public function set($value)
	{
		$this->session->put('therour.cart', $value);
		return $this;
	}

	public function get()
	{
		return $this->session->get('therour.cart', []);
	}

	public function setCustomer($value)
	{
		$this->session->put('therour.cart.customer', $value);
		return $this;
	}

	public function getCustomer()
	{
		return $this->session->get('therour.cart.customer');
	}

	public function setItems($value)
	{
		$this->session->put('therour.cart.items', $value);
		return $this;
	}
	
	public function getItems()
	{
		return $this->session->get('therour.cart.items');
	}
}