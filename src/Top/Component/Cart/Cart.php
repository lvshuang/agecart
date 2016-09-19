<?php
namespace Top\Component\Cart;

class Cart implements \Top\Component\Cart\ArrayInterface
{

    protected $cartId;
    protected $cartItems = array();

    protected function __construct($id)
    {
        $this->cartId = $id;
    }
    
    public function addItem(\Top\Component\Cart\CartItem $item)
    {
        $itemId = $item->getItemId();
        $this->cartItems[$itemId] = $item;
    }

    public function getItemById($id)
    {
        if (!isset($this->cartItems[$id])) {
            return null;
        }
        return $this->cartItems[$id];
    }

    public function hasItem($id)
    {
        return isset($this->cartItems[$id]);
    }

    public function addItemQuantity($id, $quantity)
    {
        if (!filter_var($quantity, FILTER_VALIDATE_INT)) {
            throw new \Exception('INVALID_QUANTITY');
        }
        $newItem = $this->cartItems[$id]->addQuantity($quantity);
        $this->cartItems[$id] = $newItem;
        return $newItem;
    }
    
    public function updateItemPrice($id, $price)
    {
        if (!filter_var($price, FILTER_VALIDATE_FLOAT)) {
            throw new \Exception('INVALID_PRICE');
        }
        $newItem = $this->cartItems[$id]->updatePrice($price);
        $this->cartItems[$id] = $newItem;
        return $newItem;
    }

    public function getItems()
    {
        return $this->cartItems;
    }

    public function getTotalPrice()
    {
        $totalPrice = 0.00;
        foreach ($this->cartItems as $item) {
            $totalPrice += $item->getTotalPrice();
        }
        return $totalPrice;
    }

    public function toArray()
    {
        return [
            'cart_id' => $this->cartId,
            'items' => array_map(function($item) {
                return $item->toArray();
            }, $this->cartItems),
        ];
    }

}