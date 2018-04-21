<?php

namespace Therour\Cart\App;

class Item
{
	protected $id;
	protected $name;
	protected $weight;
	protected $price;
	protected $quantity;

	public function __construct($id, $name, $quantity = 1, $weight = 0, $price = 0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->weight = $weight;
		$this->price = $price;
		$this->quantity = $quantity;
	}

	public function __invoke()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'weight' => $this->weight,
			'price' => $this->price,
			'quantity' => $this->quantity,
		];
	}

	public function addQuantity($quantity)
	{
		$this->quantity += $quantity;
		return $this;
	}

	public function quantity()
	{
		if ($numargs = func_num_args()) {
			return $this->quantity = func_get_arg(0);
		}

		return $this->quantity;
	}

	public function name()
	{
		if ($numargs = func_num_args()) {
			return $this->name = func_get_arg(0);
		}
		
		return $this->name;
	}

	public function weight()
	{
		if ($numargs = func_num_args()) {
			return $this->weight = func_get_arg(0);
		}
		
		return $this->weight;
	}

	public function price()
	{
		if ($numargs = func_num_args()) {
			return $this->price = func_get_arg(0);
		}
		
		return $this->price;
	}

	public function update($data = array())
	{
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}

		return $this;
	}

	public static function make($item)
	{
		if (is_array($item)) {
			$id = $item['id'];
			$name = $item['name'];
			$itemObj = new Item($item['id'], $item['name']);
			if (isset($item['weight'])) $itemObj->weight($item['weight']);
			if (isset($item['price'])) $itemObj->price($item['price']);
			if (isset($item['quantity'])) $itemObj->quantity($item['quantity']);
			return $itemObj;
		}

		if (is_numeric($item)) {
			return new Item($item, "Item ".$item);
		}

		if (is_string($item)) {
			return new Item($item.time(), $item);
		}
	}
}