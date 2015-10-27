<?php
namespace Top\Service\Auth\Dao;

use \Top\Service\Common\BaseDao;
use \Top\Service\Auth\Dao\AccessTokenDao;

class AccessTokenDaoImpl extends BaseDao implements AccessTokenDao
{

    protected $tableName = 'access_token';

    public function add(array $data)
    {
	return $this->insert($data);
    }

    public function getByAppId($appId)
    {
	return $this->fetchRow($appId);
    }

    public function deleteById($id)
    {
	return $this->delete($id);
    }

    public function deleteByAppid($appid)
    {

    }

    public function updateById($id, array $updateFields)
    {
        return $this->update($id, $updateFields);
    }

}
