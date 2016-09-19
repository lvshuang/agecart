<?php
namespace Top\Service\Order\Dao;

class OrderItemDao extends \Top\Service\Common\BaseDao implements OrderItemDaoInterface
{   
    const TABLE_NAME = 'order_item';
    
    public function getItemsByOrderId($orderId, $fields = '*') 
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(['order_id' => $orderId])
                ->fetchAll();
    }
    
}

