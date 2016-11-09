<?php
namespace Top\Service\Product\Dao;

class ProductImageDao extends \Top\Service\Common\BaseDao implements \Top\Service\Product\Dao\ProductImageDaoInterface
{
    const TABLE_NAME = 'product_image';

    public function addMutil(array $productImages)
    {
        return $this->mutilInsert(self::TABLE_NAME, $productImages);
    }

    public function getByProductId($productId, $field = '*', $orderBy = null)
    {
        $stat = $this->select($field)
            ->from(self::TABLE_NAME)
            ->where(array('product_id' => $productId));
        if ($orderBy) {
            $stat = $stat->orderBy($orderBy);
        }
        return $stat->fetchAll();
    }

    public function getByProductIds(array $productIds, $field = '*', $orderBy = null)
    {
        $stat = $this->select($field)
            ->from(self::TABLE_NAME)
            ->where(array('product_id' => $productIds));
        if ($orderBy) {
            $stat = $stat->orderBy($orderBy);
        }
        return $stat->fetchAll();
    }

    public function deleteById($id)
    {

    }

    public function deleteByIds(array $ids)
    {

    }

}