<?php

declare(strict_types=1);

namespace Stu\Module\Trade\Action\CreateOffer;

use AccessViolation;
use Stu\Module\Commodity\CommodityTypeEnum;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Trade\Lib\TradeLibFactoryInterface;
use Stu\Module\Trade\View\ShowAccounts\ShowAccounts;
use Stu\Orm\Entity\CommodityInterface;
use Stu\Orm\Repository\CommodityRepositoryInterface;
use Stu\Orm\Repository\TradeOfferRepositoryInterface;
use Stu\Orm\Repository\TradeStorageRepositoryInterface;

final class CreateOffer implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_CREATE_OFFER';

    private $createOfferRequest;

    private $commodityRepository;

    private $tradeLibFactory;

    private $tradeOfferRepository;

    private $tradeStorageRepository;

    public function __construct(
        CreateOfferRequestInterface $createOfferRequest,
        CommodityRepositoryInterface $commodityRepository,
        TradeLibFactoryInterface $tradeLibFactory,
        TradeOfferRepositoryInterface $tradeOfferRepository,
        TradeStorageRepositoryInterface $tradeStorageRepository
    ) {
        $this->createOfferRequest = $createOfferRequest;
        $this->commodityRepository = $commodityRepository;
        $this->tradeLibFactory = $tradeLibFactory;
        $this->tradeOfferRepository = $tradeOfferRepository;
        $this->tradeStorageRepository = $tradeStorageRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $storage = $this->tradeStorageRepository->find($this->createOfferRequest->getStorageId());
        if ($storage === null || (int) $storage->getUserId() !== $userId) {
            throw new AccessViolation();
        }

        $trade_post = $storage->getTradePost();

        $giveGoodId = $this->createOfferRequest->getGiveGoodId();
        $giveAmount = $this->createOfferRequest->getGiveAmount();
        $wantedGoodId = $this->createOfferRequest->getWantedGoodId();
        $wantedAmount = $this->createOfferRequest->getWantedAmount();
        $offerAmount = $this->createOfferRequest->getOfferAmount();

        if ($giveGoodId === $wantedGoodId) {
            return;
        }
        if ($giveAmount < 1 || $wantedAmount < 1) {
            return;
        }

        $storageManager = $this->tradeLibFactory->createTradePostStorageManager($trade_post, $userId);

        if ($storageManager->getFreeStorage() <= 0) {
            $game->setView(ShowAccounts::VIEW_IDENTIFIER);
            $game->addInformation("Dein Warenkonto auf diesem Handelsposten ist überfüllt - Angebot kann nicht erstellt werden");
            return;
        }

        if ($giveGoodId == CommodityTypeEnum::GOOD_DILITHIUM) {
            $result = array_filter(
                $this->commodityRepository->getViewable(),
                function (CommodityInterface $commodity) use ($wantedGoodId): bool {
                    return $commodity->getId() === $wantedGoodId;
                }
            );
            if ($result === []) {
                return;
            }
        } else {
            if ($wantedGoodId != CommodityTypeEnum::GOOD_DILITHIUM) {
                return;
            }
        }
        if ($offerAmount < 1 || $offerAmount > 99) {
            $offerAmount = 1;
        }
        if ($offerAmount * $giveAmount > $storage->getAmount()) {
            $offerAmount = floor($storage->getAmount() / $giveAmount);
        }
        if ($offerAmount < 1) {
            return;
        }
        $offer = $this->tradeOfferRepository->prototype();
        $offer->setUserId($userId);
        $offer->setTradePost($trade_post);
        $offer->setDate(time());
        $offer->setOfferedCommodity($this->commodityRepository->find($giveGoodId));
        $offer->setOfferedGoodCount((int) $giveAmount);
        $offer->setWantedCommodity($this->commodityRepository->find($wantedGoodId));
        $offer->setWantedGoodCount((int) $wantedAmount);
        $offer->setOfferCount((int) $offerAmount);

        $this->tradeOfferRepository->save($offer);

        $storageManager->lowerStorage($giveGoodId, (int) $offerAmount * $giveAmount);

        $game->addInformation('Das Angebot wurde erstellt');
    }

    public function performSessionCheck(): bool
    {
        return false;
    }
}
