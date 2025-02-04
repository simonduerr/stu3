<?php

namespace Stu\Orm\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Stu\Orm\Entity\TradeLicenseInterface;

/**
 * @method null|TradeLicenseInterface find(integer $id)
 */
interface TradeLicenseRepositoryInterface extends ObjectRepository
{
    public function prototype(): TradeLicenseInterface;

    public function save(TradeLicenseInterface $post): void;

    public function delete(TradeLicenseInterface $post): void;

    public function truncateByUser(int $userId): void;

    /**
     * @return TradeLicenseInterface[]
     */
    public function getByTradePost(int $tradePostId): array;

    /**
     * @return TradeLicenseInterface[]
     */
    public function getByUser(int $userId): array;

    public function getAmountByUser(int $userId): int;

    public function hasLicenseByUserAndTradePost(int $userId, int $tradePostId): bool;

    public function getAmountByTradePost(int $tradePostId): int;

    public function hasLicenseByUserAndNetwork(int $userId, int $tradeNetworkId): bool;
}