<?php

declare(strict_types=1);

namespace Stu\Module\Tick\Ship;

use Ship;

final class ShipTickManager implements ShipTickManagerInterface
{
    private $shipTick;

    public function __construct(
        ShipTickInterface $shipTick
    ) {
        $this->shipTick = $shipTick;
    }

    public function work(): void
    {
        $ships = Ship::getObjectsBy('WHERE user_id IN (SELECT id FROM stu_user WHERE id!=' . USER_NOONE . ' AND npc_type IS NULL and plans_id > 0)');

        foreach ($ships as $ship) {
            //echo "Processing Ship ".$ship->getId()." at ".microtime()."\n";
            $this->shipTick->work($ship);
        }
        $this->handleNPCShips();
        $this->lowerTrumfieldHuell();
    }

    private function lowerTrumfieldHuell(): void
    {
        foreach (Ship::getObjectsBy('WHERE user_id=' . USER_NOONE . ' AND rumps_id IN (SELECT id FROM stu_rumps WHERE category_id=' . SHIP_CATEGORY_DEBRISFIELD . ')') as $ship) {
            $lower = rand(5, 15);
            if ($ship->getHuell() <= $lower) {
                $ship->destroyTrumfield();
                $ship->save();
                continue;
            }
            $ship->lowerHuell($lower);
            $ship->save();
        }
    }

    private function handleNPCShips(): void
    {
        foreach (Ship::getObjectsBy('WHERE user_id IN (SELECT id FROM stu_user where id!=' . USER_NOONE . ' AND npc_type IS NOT NULL)') as $ship) {
            $eps = ceil($ship->getMaxEps() / 10);
            if ($eps + $ship->getEps() > $ship->getMaxEps()) {
                $eps = $ship->getMaxEps() - $ship->getEps();
            }
            $ship->upperEps($eps);
            $ship->save();
        }
    }
}