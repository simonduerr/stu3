<?php

declare(strict_types=1);

namespace Stu\Module\Trade\Action\TransferGoods;

use AccessViolation;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Trade\Lib\TradeLibFactoryInterface;
use Stu\Module\Trade\View\ShowAccounts\ShowAccounts;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use Stu\Orm\Repository\TradePostRepositoryInterface;
use Stu\Orm\Repository\TradeStorageRepositoryInterface;
use Stu\Orm\Repository\TradeTransferRepositoryInterface;

final class TransferGoods implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_TRANSFER';

    private $transferGoodsRequest;

    private $tradeTransferRepository;

    private $tradeLicenseRepository;

    private $tradeLibFactory;

    private $tradePostRepository;

    private $tradeStorageRepository;

    public function __construct(
        TransferGoodsRequestInterface $transferGoodsRequest,
        TradeTransferRepositoryInterface $tradeTransferRepository,
        TradeLicenseRepositoryInterface $tradeLicenseRepository,
        TradeLibFactoryInterface $tradeLibFactory,
        TradePostRepositoryInterface $tradePostRepository,
        TradeStorageRepositoryInterface $tradeStorageRepository
    ) {
        $this->transferGoodsRequest = $transferGoodsRequest;
        $this->tradeTransferRepository = $tradeTransferRepository;
        $this->tradeLicenseRepository = $tradeLicenseRepository;
        $this->tradeLibFactory = $tradeLibFactory;
        $this->tradePostRepository = $tradePostRepository;
        $this->tradeStorageRepository = $tradeStorageRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setView(ShowAccounts::VIEW_IDENTIFIER);

        $userId = $game->getUser()->getId();
        $amount = $this->transferGoodsRequest->getAmount();
        $destinationTradePostId = $this->transferGoodsRequest->getDestinationTradePostId();

        $selectedStorage = $this->tradeStorageRepository->find($this->transferGoodsRequest->getStorageId());
        if ($selectedStorage === null || $selectedStorage->getUserId() !== $userId) {
            throw new AccessViolation();
        }

        $tradepost = $selectedStorage->getTradePost();
        $tradePostId = $tradepost->getId();

        if ($selectedStorage->getAmount() < $amount) {
            $amount = $selectedStorage->getAmount();
        }
        if ($amount < 1) {
            return;
        }

        $usedTransferCapacity = $this->tradeTransferRepository->getSumByPostAndUser($tradePostId, $userId);
        $freeTransferCapacity = $tradepost->getTransferCapacity() - $usedTransferCapacity;

        if ($freeTransferCapacity <= 0) {
            $game->addInformation(_('Du hast an diesem Posten derzeit keine freie Transferkapaziztät'));
            return;
        }

        $targetpost = $this->tradePostRepository->find($destinationTradePostId);

        if ($targetpost === null) {
            return;
        }

        if (!$this->tradeLicenseRepository->hasLicenseByUserAndTradePost($userId, $tradePostId)) {
            return;
        }
        if ($targetpost->getTradeNetwork() != $tradepost->getTradeNetwork()) {
            return;
        }

        $storageManager = $this->tradeLibFactory->createTradePostStorageManager($tradepost, $userId);
        $storageManagerTarget = $this->tradeLibFactory->createTradePostStorageManager($targetpost, $userId);

        $freeStorage = $storageManager->getFreeStorage();

        if ($freeStorage <= 0) {
            return;
        }
        $amount = (int) min(min($freeStorage, $amount), $freeTransferCapacity);

        $storageManagerTarget->upperStorage((int) $selectedStorage->getGoodId(), $amount);
        $storageManager->lowerStorage((int) $selectedStorage->getGoodId(), $amount);

        $transfer = $this->tradeTransferRepository->prototype();
        $transfer->setTradePost($tradepost);
        $transfer->setUserId($userId);
        $transfer->setAmount($amount);
        $transfer->setDate(time());

        $this->tradeTransferRepository->save($transfer);

        $game->addInformation(
            sprintf(
                _('Es wurde %d %s zum %s transferiert'),
                $amount,
                $selectedStorage->getGood()->getName(),
                $targetpost->getName()
            )
        );
    }

    public function performSessionCheck(): bool
    {
        return false;
    }
}
