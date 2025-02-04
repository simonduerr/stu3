<?php

namespace Stu\Module\Maintenance;

use Stu\Lib\DbInterface;
use Stu\Orm\Repository\MapRepositoryInterface;
use User;
use UserData;

final class MapCycle implements MaintenanceHandlerInterface
{
    private $db;

    private $mapRepository;

    public function __construct(
        DbInterface $db,
        MapRepositoryInterface $mapRepository
    ) {
        $this->db = $db;
        $this->mapRepository = $mapRepository;
    }

    public function handle(): void
    {
        $fieldcount = $this->mapRepository->count([]);
        $list = User::getListBy("WHERE maptype=" . MAPTYPE_INSERT);
        foreach ($list as $key => $user) {
            if ($this->db->query("SELECT COUNT(*) FROM stu_user_map WHERE user_id=" . $user->getId()) >= $fieldcount) {
                $this->cycle($user);
            }
        }
    }

    private function cycle(UserData $user)
    {
        $user->setMapType(MAPTYPE_DELETE);
        $user->save();

        $fields = $this->db->query("SELECT cx,cy,id FROM stu_map WHERE id NOT IN (SELECT map_id FROM stu_user_map WHERE user_id=" . $user->getId() . ")");
        $this->db->query("DELETE FROM stu_user_map WHERE user_id=" . $user->getId());
        while ($data = mysqli_fetch_assoc($fields)) {
            $this->db->query("INSERT INTO stu_user_map (cx,cy,user_id,map_id) VALUES ('" . $data['cx'] . "','" . $data['cy'] . "','" . $user->getId() . "','" . $data['id'] . "')");
        }

    }
}
