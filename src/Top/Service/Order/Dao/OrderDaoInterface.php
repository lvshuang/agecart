<?php
namespace Top\Service\Order\Dao;

interface OrderDaoInterface
{

    public function getOrderById($id, $fields = "*");

    public function updateOrder($id, array $updateFeild);

    public function getOrdersByUserId($userId, $fields = "*");

}
