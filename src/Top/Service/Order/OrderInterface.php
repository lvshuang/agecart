<?php
namespace Top\Service\Order;

interface OrderInterface
{
    
    public function createOrder(array $orderInfo, array $productItems);
    
    public function getOrder($id);
    
    public function getOrderAndItems($id);
    
    public function getUserOrders($userId, $orderBy, $start = 0, $limit = null);
    
    public function updateOrder($orderId, array $updateFields);
    
}
