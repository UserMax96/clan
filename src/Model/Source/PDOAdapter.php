<?php

namespace App\Model\Source;

class PDOAdapter implements DBAdapterInterface
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var \PDOStatement
     */
    protected $statement;

    /**
     * @var int
     */
    protected $fetchMode = \PDO::FETCH_ASSOC;

    /**
     * PDOAdapter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        try {
            $this->db = new \PDO(
                $config["dsn"],
                $config["username"],
                $config["password"],
                $config["driverOptions"]);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(
                \PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * @return \PDOStatement
     */
    protected function getStatement()
    {
        if ($this->statement === null) {
            throw new \PDOException("There is no PDOStatement object for use.");
        }
        return $this->statement;
    }

    /**
     * @param $sql
     * @param array $options
     * @return $this
     */
    protected function prepare($sql, array $options = array())
    {
        try {
            $this->statement = $this->db->prepare($sql,
                $options);
            return $this;
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    /**
     * @param array $parameters
     * @return $this
     */
    protected function execute(array $parameters = array())
    {
        try {
            $this->getStatement()->execute($parameters);
            return $this;
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    /**
     * @return int
     */
    protected function countAffectedRows()
    {
        try {
            return $this->getStatement()->rowCount();
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    /**
     * @param null $name
     * @return int|string
     */
    protected function getLastInsertId($name = null)
    {
        return $this->db->lastInsertId($name);
    }

    /**
     * @param null $fetchStyle
     * @param null $cursorOrientation
     * @param null $cursorOffset
     * @return mixed
     */
    public function fetch($fetchStyle = null, $cursorOrientation = null, $cursorOffset = null)
    {
        if ($fetchStyle === null) {
            $fetchStyle = $this->fetchMode;
        }

        try {
            return $this->getStatement()->fetch($fetchStyle,
                $cursorOrientation, $cursorOffset);
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    /**
     * @param null $fetchStyle
     * @param int $column
     * @return array
     */
    public function fetchAll($fetchStyle = null, $column = 0)
    {
        if ($fetchStyle === null) {
            $fetchStyle = $this->fetchMode;
        }

        try {
            return $fetchStyle === \PDO::FETCH_COLUMN
                ? $this->getStatement()->fetchAll($fetchStyle, $column)
                : $this->getStatement()->fetchAll($fetchStyle);
        } catch (\PDOException $e) {
            throw new \RunTimeException($e->getMessage());
        }
    }

    /**
     * @param string $table
     * @param array $bind
     * @param string $boolOperator
     * @return $this
     */
    public function select($table, array $bind = array(), $boolOperator = "AND")
    {
            if ($bind) {
            $where = array();
            foreach ($bind as $col => $value) {
                unset($bind[$col]);
                $bind[":" . $col] = $value;
                $where[] = $col . " = :" . $col;
            }
        }

        $sql = "SELECT * FROM " . $table
            . (($bind) ? " WHERE "
                . implode(" " . $boolOperator . " ", $where) : " ");
        $this->prepare($sql)
            ->execute($bind);
        return $this;
    }

    /**
     * @param string $table
     * @param array $bind
     * @return int
     */
    public function insert($table, array $bind)
    {
        $cols = implode(", ", array_keys($bind));
        $values = implode(", :", array_keys($bind));
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
        }

        $sql = "INSERT INTO " . $table
            . " (" . $cols . ")  VALUES (:" . $values . ")";
        return (int) $this->prepare($sql)
            ->execute($bind)
            ->getLastInsertId();
    }

    /**
     * @param string $table
     * @param array $bind
     * @param string $where
     * @return int
     */
    public function update($table, array $bind, $where = "")
    {
        $set = array();
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
            $set[] = $col . " = :" . $col;
        }

        $sql = "UPDATE " . $table . " SET " . implode(", ", $set)
            . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute($bind)
            ->countAffectedRows();
    }

    /**
     * @param string $table
     * @param string $where
     * @return int
     */
    public function delete($table, $where = "")
    {
        $sql = "DELETE FROM " . $table . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute()
            ->countAffectedRows();
    }

    public function getDBBuild()
    {
        $sql = "
            CREATE TABLE clans (
              id                INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
              name              VARCHAR(12) UNIQUE,
              description       VARCHAR(30),
              clan_leader       INTEGER NOT NULL,

              PRIMARY KEY (id),
              FOREIGN KEY (clan_leader) REFERENCES users(id) ON DELETE CASCADE
            );
            CREATE TABLE users (
              id               INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
              name             VARCHAR(12) UNIQUE,
              clan             INTEGER DEFAULT NULL,
              role             INTEGER DEFAULT NULL,

              PRIMARY KEY (id),
              FOREIGN KEY (clan) REFERENCES clans(id) ON DELETE SET NULL
            );
        ";
        return $sql;
    }
}