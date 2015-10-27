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

    public function getTableName() 
    {
        return $this->tableName;
    }

    public function getPrimaryKey() 
    {
        return $this->primaryKey;
    }
    
    public function buildCondition(array $condition)
    {
        if (empty($condition)) {
            throw new DBALException('sql condition empty');
        }
        $content = '';
        $params = array();
        $maybeConnectors = array('>=', '<=', '<>', '!=', '>', '<', '=',
            ' NOT BETWEEN', ' BETWEEN', 'NOT LIKE', ' LIKE', ' IS NOT', ' NOT IN', ' IS', ' IN');
        foreach ($condition as $key => $value) {
            $keyUpper = strtoupper($key);
            $connector = '';
            foreach ($maybeConnectors as $maybeConnector) {
                $l = strlen($maybeConnector);
                if (substr($keyUpper, -$l) == $maybeConnector) {
                    $feildName = trim(substr($key, 0, -$l));
                    $connector = $maybeConnector;
                    break;
                } else {
                    $feildName = $key;
                }
            }
            if (!$connector) {
                if (is_array($value) && !empty($value)) {
                    $value = array_values($value);
                    $connector = ' IN';
                } else {
                    $connector = ' =';
                }
                
            }
            
            if (in_array(trim($connector), array('NOT BETWEEN', 'BETWEEN', 'NOT IN', 'IN')) && (!is_array($value) || empty($value))) {
                throw new DBALException('bad sql condition');
            }
            $orLen = strlen('OR ');
            if (substr($keyUpper, 0, $orLen) == 'OR ') {
                $logic = 'OR';
                $feildName = trim(substr($key, $orLen));
            } else {
                $logic = 'AND';
            }
            
            if (in_array(trim($connector), array('NOT BETWEEN', 'BETWEEN'))) {
                $value = array_values($value);
                if ($content) {
                    $content .= $logic . " (`" . $feildName . "` " . $connector . " ? AND ?) ";
                } else {
                    $content = " (`" . $feildName . "` " . $connector . " ? AND ?) ";
                }
                $params[] = $value[0];
                $params[] = $value[1];
            } else {
                if ($content) {
                    $content .= $logic . " (`" . $feildName . "` " . $connector . " ? ) ";
                } else {
                    $content = " (`" . $feildName . "` " . $connector . " ? ) ";
                }
                
                if (is_array($value)) {
                    $value = array_values($value);
                    $params[] = '(' . implode(', ', $value) . ')';
                } else {
                    $params[] = $value;
                }
            }
        }
        
        return array($content, $params);
    }

}
