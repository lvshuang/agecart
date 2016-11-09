<?php
namespace Top\Service\Product\Dao;

class ProductAttrDao extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\ProductDaoAttrInterface
{
    const TABLE_NAME = 'product_attr';

    public function add(array $attr)
    {
        return $this->insert(self::TABLE_NAME, $attr);
    }

    public function getAllByProductId($productId, $fields = '*')
    {
        return $this->select($fields)->from(self::TABLE_NAME)
            ->where(array('product_id' => $productId))
            ->fetchAll();
    }   

    public function addMutil(array $attrs)
    {
        return $this->mutilInsert(self::TABLE_NAME, $attrs);
    }

    public function updateByProductIdAndName($productId, $name, array $updateData)
    {
        return $this->update(self::TABLE_NAME, array('product_id' => $productId, 'name' => $name), $updateData);
    }

    public function deleteByCond(array $cond)
    {
        if (empty($cond)) {
            return false;
        }
        return $this->delete(self::TABLE_NAME, $cond);
    }

    public function updateByCond(array $cond, $updateData)
    {
        if (empty($updateData) || empty($cond)) {
            return false;
        }
        return $this->update(self::TABLE_NAME, $cond, $updateData);
    }

}