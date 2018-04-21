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
		return $this;
	}

	public function addCustomerData($data)
	{
		[$key, $value] = array_divide($data);
		$this->attributes['customer'][$key] = $value;
		return $this;
	}

	public function addItem($item, $qty = 1)
	{	
		$exist = $this->getItemIndex($item); 

		if ($exist !== false) {
			$this->attributes['items'][$exist]->addQuantity($qty);
		}

		$item = Item::make($item);
		if ($qty > 1) $item->quantity($qty);
		
		$this->attributes['items'][] = $item;

		return $this;
	}

	public function getItemIndex($newItem)
	{
		if (! isset($this->attributes['items'])) {
			return false;
		}

		foreach ($this->attributes['items'] as $index => $item) {
			$newItem = is_array($newItem) ? $newItem['id'] : $newItem;
			if ($item()['id'] === $newItem) return $index;
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
		return true;
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