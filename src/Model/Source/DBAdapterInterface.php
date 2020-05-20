<?php
namespace App\Model\Source;

/**
 * Interface SourceInterface
 * @package App\Model\Source
 */
interface DBAdapterInterface
{
    public function fetch($fetchStyle = null, $cursorOrientation = null, $cursorOffset = null);
    public function fetchAll($fetchStyle = null, $column = 0);

    public function select($table, array $bind, $boolOperator = "AND");
    public function insert($table, array $bind);
    public function update($table, array $bind, $where = "");
    public function delete($table, $where = "");
}
