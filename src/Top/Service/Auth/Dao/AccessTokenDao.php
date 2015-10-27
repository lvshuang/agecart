<?php
namespace Top\Service\Auth\Dao;

interface AccessTokenDao
{
    public function add(array $data);

    public function deleteById($id);

    public function deleteByAppid($appid);

    public function updateById($id, array $updateFields);
	
}