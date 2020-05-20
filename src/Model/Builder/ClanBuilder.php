<?php

namespace App\Model\Builder;

use App\Model\Entity\Clan;
use App\Model\Mapper\ClanMapperInterface;
use App\Model\Mapper\UserMapperInterface;

class ClanBuilder
{
    /**
     * @var ClanMapperInterface
     */
    protected $clanMapper;

    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    protected const LEADER_ROLE = 1;

    protected const DEPUTY_ROLE = 2;

    protected const SOLDIER_ROLE = 3;

    /**
     * ClanBuilder constructor.
     * @param ClanMapperInterface $clanMapper
     * @param UserMapperInterface $userMapper
     */
    public function __construct(ClanMapperInterface $clanMapper, UserMapperInterface $userMapper)
    {
        $this->clanMapper = $clanMapper;
        $this->userMapper = $userMapper;
    }

    /**
     * $clanRows this is an array containing the name and description of the clan
     * and id of the authorized user
     *
     * @param array $clanRows
     */
    public function createClan(array $clanRows)
    {
        if (!$this->validateClanName($clanRows['name'])
            || !$this->validateClanDescription($clanRows['description'])) {
            throw new \InvalidArgumentException('Invalid clan data');
        }

        $clanId = $this->clanMapper->insert($clanRows);
        $clanLeader = $this->userMapper->findByPrimary($clanRows);
        $clanLeader->setClan($clanId);
        $this->userMapper->update($clanLeader);
    }

    /**
     * @param int $clanLeader
     */
    public function deleteClan($clanLeader)
    {
        $clanLeader = $this->userMapper->findByPrimary($clanLeader);
        $clan = $this->clanMapper->findByPrimary($clanLeader->getClanId());
        if (!empty($clan) && $clanLeader->getId() === $clan->getClanLeader()) {
            $this->clanMapper->delete($clan->getId());
        } else {
            throw new \LogicException('You are not a clan leader');
        }
    }

    /**
     * @param array $users
     * @param int $clanLeader
     */
    public function addUser(array $users, $clanLeader)
    {
        $clanLeader = $this->userMapper->findByPrimary($clanLeader);
        $clan = $this->clanMapper->findByPrimary($clanLeader->getClanId());
        if (!empty($clan) && $clanLeader->getId() === $clan->getClanLeader()) {
            foreach ($users as $user) {
                $user = $this->userMapper->findByPrimary($user['id']);
                if ($user->getClanId == null) {
                    $user->setClan($clanLeader->getClanId());
                    $user->setRole(self::SOLDIER_ROLE);
                    $this->userMapper->update($user);
                }
            }
        } else {
            throw new \LogicException('You are not a clan leader');
        }
    }

    /**
     * @param int $upUser
     * @param int $authUser
     */
    public function upUser($upUser, $authUser)
    {
        $authUser = $this->userMapper->findByPrimary($authUser);
        $upUser = $this->userMapper->findByPrimary($upUser);

        if ($authUser->getClanId() === $upUser->getClanId() && $authUser->getRole() < self::SOLDIER_ROLE) {
            $upUser->setRole(self::DEPUTY_ROLE);
        } else {
            throw new \LogicException('Access denied');
        }
    }

    /**
     * @param int $downUser
     * @param int $clanLeader
     */
    public function downUser($downUser, $clanLeader)
    {
        $clanLeader = $this->userMapper->findByPrimary($clanLeader);
        $clan = $this->clanMapper->findByPrimary($clanLeader->getClanId());
        $downUser = $this->userMapper->findByPrimary($downUser);

        if (!empty($clan) && $clanLeader->getId() === $clan->getClanLeader()
            && $downUser->getClanId() === $clanLeader->getClanId()) {
            $downUser->setRole(self::SOLDIER_ROLE);
            $this->userMapper->update($downUser);
        } else {
            throw new \LogicException('Access denied');
        }
    }

    /**
     * @param $deletedUser
     * @param $clanLeader
     */
    public function deleteUser($deletedUser, $clanLeader)
    {
        $clanLeader = $this->userMapper->findByPrimary($clanLeader);
        $clan = $this->clanMapper->findByPrimary($clanLeader->getClanId());
        $deletedUser = $this->userMapper->findByPrimary($deletedUser);

        if (!empty($clan) && $clanLeader->getId() === $clan->getClanLeader()
            && $deletedUser->getClanId() === $clanLeader->getClanId() && $deletedUser->getRole() === self::SOLDIER_ROLE) {
            $deletedUser->setClan(null);
            $this->userMapper->update($deletedUser);
        } else {
            throw new \LogicException('Access denied');
        }
    }

    /**
     * @param string $description
     * @param int $user
     */
    public function updateClanDescription($description, $user)
    {
        $user = $this->userMapper->findByPrimary($user);
        $clan = $this->clanMapper->findByPrimary($user->getClanId());
        if (!empty($clan) && $user->getRole() < self::SOLDIER_ROLE) {
            if ($this->validateClanDescription($description)) {
                $clan->setDescription($description);
                $this->clanMapper->update($clan);
            } else {
                throw new \InvalidArgumentException('Invalid clan description');
            }
        } else {
            throw new \LogicException('Access denied');
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function validateClanName(string $name)
    {
        return preg_match("/^[A-Za-z0-9]{3,12}$/", $name);
    }

    /**
     * @param string $description
     * @return bool
     */
    protected function validateClanDescription(string $description)
    {
        return preg_match("/^[A-Za-z0-9]{3,30}$/", $description);
    }


}