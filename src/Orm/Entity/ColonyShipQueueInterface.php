<?php

namespace Stu\Orm\Entity;

interface ColonyShipQueueInterface
{
    public function getId(): int;

    public function getColonyId(): int;

    public function setColonyId(int $colonyId): ColonyShipQueueInterface;

    public function getUserId(): int;

    public function setUserId(int $userId): ColonyShipQueueInterface;

    public function getRumpId(): int;

    public function setRumpId(int $shipRumpId): ColonyShipQueueInterface;

    public function getBuildtime(): int;

    public function setBuildtime(int $buildtime): ColonyShipQueueInterface;

    public function getFinishDate(): int;

    public function setFinishDate(int $finishDate): ColonyShipQueueInterface;

    public function getStopDate(): int;

    public function setStopDate(int $stopDate): ColonyShipQueueInterface;

    public function getBuildingFunctionId(): int;

    public function setBuildingFunctionId(int $buildingFunctionId): ColonyShipQueueInterface;

    public function getRump(): ShipRumpInterface;

    public function setRump(ShipRumpInterface $shipRump): ColonyShipQueueInterface;

    public function getShipBuildplan(): ShipBuildplanInterface;

    public function setShipBuildplan(ShipBuildplanInterface $shipBuildplan): ColonyShipQueueInterface;
}