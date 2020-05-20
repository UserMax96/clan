<?php

namespace App\Model\Mapper;

interface UserMapperInterface
{
    public function findByPrimary($primaryKey);
    public function find(array $conditions = array());

    public function insert($userRow);
    public function update($user);
    public function delete($id);
}