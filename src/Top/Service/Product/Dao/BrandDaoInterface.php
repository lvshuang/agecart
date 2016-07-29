<?php
namespace Top\Service\Product\Dao;

interface BrandDaoInterface
{

    public function add(array $brand);

    public function getById($id, $fields = '*');

    public function getByCategoryId($categoryId, $fields = '*');

    public function getByCondition(array $condition, $fields = '*', $orderBy = null, $start = 0, $limit = null);

    public function getCountByCondition(array $condition);

}
