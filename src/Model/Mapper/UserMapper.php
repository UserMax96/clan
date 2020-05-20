<?php

namespace App\Model\Mapper;

use App\Model\Entity\User;

class UserMapper extends AbstractMapper implements UserMapperInterface
{
    /**
     * @var string
     */
    protected $entityTable = "users";

    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * @param array $row
     * @return mixed
     */
    protected function buildEntity(array $row)
    {
        // TODO: Implement buildEntity() method.
    }

    /**
     * @param $user
     * @return mixed
     */
    public function insert($user)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user)
    {
        $this->dbAdapter->update($this->entityTable,
            array("name" => $user->getName(),
                "clan" => $user->getClanId(),
                "role" => $user->getRole()), $this->primaryKey . " = " . $user->getId());
        return $user;
    }

    /**
     * @param User|int $id
     * @return int
     */
    public function delete($id)
    {
        if ($id instanceof User) {
            $id = $id->getId();
        }

        return $this->dbAdapter->delete($this->entityTable, $this->primaryKey . " = $id");
    }
}