<?php
/**
 * sku属性DAO接口.
 * 
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Top\Service\Product\Dao;

/**
 * sku属性DAO接口
 */
interface ProductSkuAttrDaoInterface
{

    public function add(array $attr);

    public function getAllBySku($sku, $fields = '*');

    public function addMutil(array $attrs);

    public function getAllBySkus(array $skus, $fields = '*');

}