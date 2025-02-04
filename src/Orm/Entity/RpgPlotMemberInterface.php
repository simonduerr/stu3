<?php

namespace Stu\Orm\Entity;

use User;

interface RpgPlotMemberInterface
{
    public function getId(): int;

    public function getPlotId(): int;

    public function setPlotId(int $plotId): RpgPlotMemberInterface;

    public function getUserId(): int;

    public function setUserId(int $userId): RpgPlotMemberInterface;

    public function getRpgPlot(): RpgPlotInterface;

    public function setRpgPlot(RpgPlotInterface $rpgPlot): RpgPlotMemberInterface;

    public function getUser(): User;
}