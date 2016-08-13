<?php
namespace Top\Service\Product\Dao;

interface ProductDaoAttrInterface
{

    public function add(array $attr);

    public function getAllByProductId($productId, $fields = '*');

    public function addMutil(array $attrs);

}