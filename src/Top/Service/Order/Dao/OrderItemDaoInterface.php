<?php
namespace Top\Service\Order\Dao;

interface OrderItemDaoInterface
{
    
    public function getItemsByOrderId($orderId, $fields = '*');
    
}
