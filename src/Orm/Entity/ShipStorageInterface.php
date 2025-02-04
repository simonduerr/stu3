<?php

namespace Stu\Orm\Entity;

interface ShipStorageInterface
{
    public function getId(): int;

    public function getShipId(): int;

    public function setShipId(int $shipId): ShipStorageInterface;

    public function getCommodityId(): int;

    public function setCommodityId(int $commodityId): ShipStorageInterface;

    public function getAmount(): int;

    public function setAmount(int $amount): ShipStorageInterface;

    public function getCommodity(): CommodityInterface;

    public function setCommodity(CommodityInterface $commodity): ShipStorageInterface;
}