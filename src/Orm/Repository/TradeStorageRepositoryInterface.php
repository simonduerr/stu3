<?php

namespace Stu\Orm\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Stu\Orm\Entity\TradeStorageInterface;

/**
 * @method null|TradeStorageInterface find(integer $id)
 */
interface TradeStorageRepositoryInterface extends ObjectRepository
{
    public function prototype(): TradeStorageInterface;

    public function save(TradeStorageInterface $post): void;

    public function delete(TradeStorageInterface $post): void;

    public function truncateByUser(int $userId): void;

    public function getSumByTradePostAndUser(int $tradePostId, int $userId): int;

    public function getByTradeNetworkAndUserAndCommodityAmount(
        int $tradeNetwork,
        int $userId,
        int $commodityId,
        int $amount
    ): ?TradeStorageInterface;

    public function getByTradepostAndUserAndCommodity(
        int $tradePostId,
        int $userId,
        int $commodityId
    ): ?TradeStorageInterface;

    /**
     * @return TradeStorageInterface[]
     */
    public function getByTradePostAndUser(int $tradePostId, int $userId): array;
}