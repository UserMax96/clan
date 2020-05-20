<?php

namespace App\Model\Mapper;

use App\Model\Entity\Clan;
use App\Model\Source\DBAdapterInterface;

class ClanMapper extends AbstractMapper implements ClanMapperInterface
{
    /**
     * @var string
     */
    protected $entityTable = "clans";

    /**
     * @var string
     */
    protected $primaryKey = "id";

    public function __construct(DBAdapterInterface $dbAdapter)
    {
        parent::__construct($dbAdapter);
    }

    /**
     * @param array $clanData
     * @return Clan
     */
    public function buildEntity(array $clanData)
    {
        return new Clan($clanData['id'],
            $clanData['name'],
            $clanData['description'],
            $clanData['clanLeader']
        );
    }

    /**
     * @param array $clanRow
     * @return int
     */
    public function insert($clanRow)
    {
        return $this->dbAdapter->insert($this->entityTable,
            array("name"   => $clanRow['name'],
                "description" => $clanRow['description'],
                "clan_leader" => $clanRow['user_id']));
    }

    /**
     * @param Clan $clan
     * @return Clan
     */
    public function update(Clan $clan)
    {
        $this->dbAdapter->update($this->entityTable,
            array("name" => $clan->getName(),
                "description" => $clan->getDescription()), $this->primaryKey . " = " . $clan->getId());
        return $clan;
    }

    /**
     * @param Clan|int $id
     * @return int
     */
    public function delete($id)
    {
        if ($id instanceof Clan) {
            $id = $id->getId();
        }

        return $this->dbAdapter->delete($this->entityTable, $this->primaryKey . " = $id");
    }
}