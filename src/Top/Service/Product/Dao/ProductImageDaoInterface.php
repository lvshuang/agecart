<?php
namespace Top\Service\Product\Dao;

interface ProductImageDaoInterface
{

    public function addMutil(array $productImages);

    public function getByProductId($productId, $field = '*', $orderBy = null);

    public function deleteById($id);

    public function deleteByIds(array $ids);

}