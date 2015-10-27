<?php
namespace Top\Service\Auth\Dao;

use \Top\Service\Common\BaseDao;
use \Top\Service\Auth\Dao\AccessTokenDao;

class AccessTokenDaoImpl extends BaseDao implements AccessTokenDao
{

    protected $tableName = 'access_token';

    public function add(array $data)
    {
	return $this->insert($this->tableName, $data);
    }
    
    public function getById($id)
    {
        return $this->select('*')
                ->from($this->tableName)
                ->where(array($this->primaryKey => $id))
                ->fetchRow();
    }

    public function getByAppId($appId)
    {
	return $this->select()
                ->from($this->tableName)
                ->where(array('appid' => $appId))
                ->fetchRow();
    }

    public function deleteById($id)
    {
	return $this->delete($this->tableName, array($this->primaryKey => $id));
    }

    public function deleteByAppid($appid)
    {
        return $this->delete($this->tableName, array('appid' => $appid));
    }

    public function updateById($id, array $updateFields)
    {
        return $this->update($this->tableName, array($this->primaryKey => $id), $updateFields);
    }

}
