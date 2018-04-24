<?php

namespace Therour\Cart\App;

use Therour\Cart\Exceptions\CartException;

class Cart
{
	protected $session;

	protected $attributes = [];

	public function __construct()
	{
		$this->session = new CartSession;
		$this->attributes = $this->session->get();
	}

	public static function getInstance()
	{
		return new Cart;
	}
	
	public function getData()
	{
		$data = $this->attributes;
		if (! isset($data['items'])) {
			return $data;
		}

		$data['items'] = collect($data['items'])->map( function ($item, $key) {
			return $item();
		})->all();

		return $data;
	}

	public function setCustomerData($data = array())
	{
		$this->attributes['customer'] = $data;
		return $this->saveCustomer();
	}

	public function addCustomerData($data)
	{
		[$key, $value] = array_divide($data);
		$this->attributes['customer'][$key] = $value;
		return $this->saveCustomer();
	}

	public function addItem($item, $qty = 1)
	{	
		$item = Item::make($item);

		if (($exist = $this->getItemIndex($item)) !== false) {

			$added = $this->attributes['items'][$exist]->addQuantity($qty);
		}

		$added = $this->attributes['items'][] = $item->addQuantity($qty - 1);
		
		$this->saveItems();
		return $added;
	}

	public function getItemIndex($newItem)
	{
		if (! isset($this->attributes['items'])) {
			return false;
		}

		foreach ($this->attributes['items'] as $index => $item) {
			if ($item->isIdentic($newItem)) return $index;
		}

		return false;
	}

	protected function isItemExist($newItem)
	{
		return $this->getItemIndex($newItem) !== false;
	}

	public function save()
	{
		$this->session->set($this->attributes);
		return $this;
	}

	protected function saveCustomer()
	{
		$this->session->setCustomer($this->attributes['customer']);
		return $this;
	}

	protected function saveItems()
	{
		$this->session->setItems($this->attributes['items']);
		return $this;
	}

	private function getItemsWeight()
	{
		$total = 0;
		foreach ($this->attributes['items'] as $item) {
			if (isset($item['weight'])) {
				if (isset($item['qty'])) {
					$total += $items['weight'] * $items['qty'];
				} else {
					$total += $items['weight'];
				}
			}
		}

		return $total;
	}

	private function getItemsPrice()
	{
		$total = 0;
		foreach ($this->attributes['items'] as $item) {
			if (isset($item['weight'])) {
				$total += $items['weight'];
			}
		}

		return $total;
	}

	public function __toString()
	{
		
		return json_encode($this->getData());
	}
}