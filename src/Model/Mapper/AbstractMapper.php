<?php

namespace App\Model\Mapper;

use App\Model\Source\DBAdapterInterface;

abstract class AbstractMapper
{
    /**
     * @var DBAdapterInterface
     */
    protected $dbAdapter;

    /**
     * @var string
     */
    protected $entityTable;

    /**
     * @var string
     */
    protected $primaryKey;

    /**
     * AbstractMapper constructor.
     * @param DBAdapterInterface $dbAdapter
     */
    public function __construct(DBAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * @param $primaryKey
     * @return mixed
     */
    public function findByPrimary($primaryKey)
    {
        $this->dbAdapter->select($this->entityTable,
            array($this->primaryKey => $primaryKey));

        if (!$row = $this->dbAdapter->fetch()) {
            return null;
        }

        return $this->buildEntity($row);
    }

    /**
     * @param array $conditions
     * @return array|null
     */
    public function find(array $conditions = array())
    {
        $entities = array();
        $this->dbAdapter->select($this->entityTable, $conditions);
        $rows = $this->dbAdapter->fetchAll();

        if($rows) {
            foreach ($rows as $row) {
                $entities[] = $this->buildEntity($row);
            }
            return $entities;
        }

        return null;
    }

    /**
     * @param array $row
     * @return mixed
     */
    abstract protected function buildEntity(array $row);
}