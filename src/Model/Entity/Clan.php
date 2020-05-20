<?php

namespace App\Model\Entity;

class Clan
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
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $clanLeader;

    /**
     * Clan constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param int $clanLeader
     */
    public function __construct($id, $name, $description, $clanLeader)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->clanLeader = $clanLeader;
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getClanLeader(): int
    {
        return $this->clanLeader;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }


}