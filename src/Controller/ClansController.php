<?php

namespace App\Controller;

use App\Model\Builder\ClanBuilder;

class ClansController
{
    /**
     * @var ClanBuilder
     */
    protected $clanBuilder;

    /**
     * ClansController constructor.
     * @param ClanBuilder $ClanBuilder
     */
    public function __construct(ClanBuilder $ClanBuilder)
    {
        $this->clanBuilder = $ClanBuilder;
    }

    /**
     * $clanRows this is an array containing the name and description of the clan
     * and id of the authorized user. There should be something like a PSR-7 request.
     *
     * @param array $clanRow
     */
    public function store(array $clanRow)
    {
        $this->clanBuilder->createClan($clanRow);
    }

    /**
     * ID of the authorized user
     *
     * @param int $id
     */
    public function delete($id)
    {
        $this->clanBuilder->deleteClan($id);
    }

    /**
     * @param array $request
     */
    public function addUser(array $request)
    {
        $this->clanBuilder->addUser($request['addUser'], $request['authUser']);
    }

    /**
     * @param array $request
     */
    public function upUser(array $request)
    {
        $this->clanBuilder->upUser($request['upUser'], $request['authUser']);
    }

    /**
     * @param array $request
     */
    public function downUser(array $request)
    {
        $this->clanBuilder->downUser($request['downUser'], $request['authUser']);
    }

    /**
     * @param array $request
     */
    public function deleteUser(array $request)
    {
        $this->clanBuilder->deleteUser($request['deleteUser'], $request['authUser']);
    }

    /**
     * @param array $request
     */
    public function updateClanDescription(array $request)
    {
        $this->clanBuilder->updateClanDescription($request['description'], $request['authUser']);
    }
}