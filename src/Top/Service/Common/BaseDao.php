<?php
namespace Top\Service\Common;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

abstract class BaseDao
{

	protected static $instance = array();
	protected $dbConnection;

	protected $primaryKey = 'id';


	public static function instance(Connection $dbConnection)
	{
		$class = get_called_class();
		if (!isset(self::$instance[$class])) {
			self::$instance[$class] = new $class($dbConnection);
		}
		$tableName = self::$instance[$class]->getTableName();
		if (empty($tableName)) {
			throw new DBALException('table name not set');
		}
		$primaryKey = self::$instance[$class]->getPrimaryKey();
		if (empty($primaryKey)) {
			throw new DBALException('primary key name not set');
		}
		return self::$instance[$class];
	}

	protected function __construct(Connection $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function setDbConnection(Connection $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getDbConnection()
	{
		return $this->dbConnection;
	}

	public function insert(array $data)
	{
		return $this->dbConnection->insert($this->tableName, $data);
	}

	public function update($id, array $updateData)
	{
		$cond = array($this->primaryKey => $id);
		return $this->dbConnection->update($this->tableName, $updateData, $cond);
	}

	public function fetchRow($id)
	{
		$sqlBuilder = $this->dbConnection->createQueryBuilder()
			->select('a.*')
			->from($this->tableName, 'a')
			->where($this->primaryKey . ' = ?');
		$sql = $sqlBuilder->getSQL();
		return $this->dbConnection->fetchAssoc($sql, array($id));
	}

	public function fetchAll(array $conditon, array $params = array(), $fields = '*', $orderBy = null, $start = 0, $limit = null)
	{
		
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

}
