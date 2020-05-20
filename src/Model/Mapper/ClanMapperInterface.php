<?php

namespace App\Model\Mapper;

interface ClanMapperInterface
{
    public function findByPrimary($primaryKey);
    public function find(array $conditions = array());

    public function insert($clanRow);
    public function update($clan);
    public function delete($id);
}