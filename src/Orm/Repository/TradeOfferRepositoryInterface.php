<?php

declare(strict_types=1);

namespace Stu\Orm\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Stu\Orm\Entity\TradeOfferInterface;

/**
 * @method null|TradeOfferInterface find(integer $id)
 */
interface TradeOfferRepositoryInterface extends ObjectRepository
{
    public function prototype(): TradeOfferInterface;

    public function save(TradeOfferInterface $post): void;

    public function delete(TradeOfferInterface $post): void;

    public function truncateByUser(int $userId): void;

    /**
     * @return TradeOfferInterface[]
     */
    public function getByTradePostAndUserAndOfferedCommodity(
        int $tradePostId,
        int $userId,
        int $offeredCommodityId
    ): array;

    /**
     * @return TradeOfferInterface[]
     */
    public function getByUserLicenses(int $userId): array;

    public function getSumByTradePostAndUser(int $tradePostId, int $userId): int;

    public function getGroupedSumByTradePostAndUser(int $tradePostId, int $userId): array;
}