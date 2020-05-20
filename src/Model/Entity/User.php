<?php

namespace App\Model\Entity;

class User
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $clanId;

    /**
     * @var int
     */
    protected $role;

    /**
     * User constructor.
     * @param int $id
     * @param string $name
     * @param int $clanId
     * @param int $role
     */
    public function __construct($id, $name, $clanId, $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->clanId = $clanId;
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getClanId(): int
    {
        return $this->clanId;
    }

    /**
     * @return int
     */
    public function getRole():int
    {
        return $this->role;
    }

    /**
     * @param int $clanId
     */
    public function setClan($clanId): void
    {
        $this->clanId = $clanId;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}