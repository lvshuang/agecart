<?php
namespace Top\Service\Product;

interface BrandInterface
{

    public function addBrand(array $data);

    public function validateBrand(array $brand);

    public function getBrandById($id, $fields = '*');

    public function getBrandsByCategoryId($categoryId, $fields = '*');

    public function getBrandsCountByCondition(array $condition);

    public function getBrandsByCondition(array $condition, $fields = '*', $orderBy = null, $start = 0, $limit = null);

    public function isBrandExist($id);

}