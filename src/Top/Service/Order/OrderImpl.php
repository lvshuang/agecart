<?php
namespace Top\Service\Order;

use Top\Service\Common\BaseService;
use Top\Service\Order\OrderInterface;

class OrderImpl extends BaseService implements OrderInterface
{
    
    public function createOrder(array $orderInfo, array $productItems) 
    {
        $amount = $orderInfo['amount'];
        $order = [
            'amount' => $orderInfo['amount'],
            'user_id' => $orderInfo['user_id'],
            'deliver_amount' => $orderInfo['deliver_amount'],
            'discount_amount' => $orderInfo['discount_amount'],
            'address_id' => $orderInfo['address_id'],
        ];
        $this->getOrderDao()->beginTransaction();
        try {
            $this->getOrderDao()->add($order);
        } catch (\Exception $e) {
            
        }
        
    }

    public function getOrder($id) 
    {
        
    }

    public function getOrderAndItems($id) 
    {
        
    }

    public function getUserOrders($userId, $orderBy, $start = 0, $limit = null) 
    {
        
    }

    public function updateOrder($orderId, array $updateFields) 
    {
        
    }
    
    protected function getOrderDao()
    {
        return $this->createDao('Order');
    }
    
    protected function getOrderItemDao()
    {
        return $this->createDao('OrderItem');
    }
    
}
