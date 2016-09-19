<?php
namespace Top\Service\Order\Dao;

class OrderDao extends \Top\Service\Common\BaseDao implements OrderDaoInterface
{

    const TABLE_NAME = 'order';

    public function getOrderById($id, $fields = "*")
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(['id' => $id])
                ->fetchRow();
    }

    public function updateOrder($id, array $updateFeild)
    {
        return $this->update(self::TABLE_NAME, ['id' => $id], $updateFeild);
    }

    public function getOrdersByUserId($userId, $fields = "*")
    {
        return $this->select($fields)
                ->from(self::TABLE_NAME)
                ->where(['user_id' => $userId])
                ->fetchAll();
    }

}
