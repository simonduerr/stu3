<?php

namespace Stu\Orm\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Stu\Orm\Entity\RpgPlotMemberInterface;

interface RpgPlotMemberRepositoryInterface extends ObjectRepository
{
    public function getByPlotAndUser(int $plotId, int $userId): ?RpgPlotMemberInterface;

    public function prototype(): RpgPlotMemberInterface;

    public function save(RpgPlotMemberInterface $rpgPlotMember): void;

    public function delete(RpgPlotMemberInterface $rpgPlotMember): void;

    /**
     * @return RpgPlotMemberInterface[]
     */
    public function getByUser(int $userId): array;

    /**
     * @return RpgPlotMemberInterface[]
     */
    public function getByPlot(int $plotId): array;

    public function getAmountByPlot(int $plotId): int;
}