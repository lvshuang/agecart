<?php
namespace Top\Component\Cart;

class CartItem implements ArrayInterface
{
    protected $id;
    protected $name;
    protected $quantity;
    protected $price;
    
    protected $cart;

    public function __construct($id, $name, $quantity, $price) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
    }
    
    public function setCart(\Top\Component\Cart\Cart $cart)
    {
        $this->cart = $cart;
    }
    
    public function getItemId()
    {
        return $this->id;
    }
    
    public function addQuantity($quantity)
    {
        $this->quantity += $quantity;
        return $this;
    }
    
    public function updatePrice($price)
    {
        $this->price = $price;
    }
    
    public function getId() 
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function getTotalPrice()
    {
        return $this->price * $this->quantity;
    }

    public function toArray() 
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->getTotalPrice(),
        ];
    }
    
}