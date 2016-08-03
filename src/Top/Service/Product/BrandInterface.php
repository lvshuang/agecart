<?php
namespace Top\Service\Product;

interface BrandInterface
{

    public function addBrand(array $data);

    public function updateBrand($id, array $updateData);

    public function validateBrand(array $brand);

    public function getBrandById($id, $fields = '*');

    public function getBrandsByCategoryId($categoryId, $fields = '*');

    public function getBrandsCountByCondition(array $condition);

    public function getBrandsByCondition(array $condition, $fields = '*', $orderBy = null, $start = 0, $limit = null);

    public function isBrandExist($id);

    public function isBrandNameExist($name);

    public function enable($id);

    public function disable($id);

    public function delete($id);

}