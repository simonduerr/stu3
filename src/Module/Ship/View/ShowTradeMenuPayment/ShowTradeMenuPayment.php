<?php

declare(strict_types=1);

namespace Stu\Module\Ship\View\ShowTradeMenuPayment;

use AccessViolation;
use request;
use Ship;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Module\Ship\Lib\ShipLoaderInterface;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use TradePost;
use TradeStorage;

final class ShowTradeMenuPayment implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_TRADEMENU_CHOOSE_PAYMENT';

    private $shipLoader;

    private $tradeLicenseRepository;

    public function __construct(
        ShipLoaderInterface $shipLoader,
        TradeLicenseRepositoryInterface $tradeLicenseRepository
    ) {
        $this->shipLoader = $shipLoader;
        $this->tradeLicenseRepository = $tradeLicenseRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $ship = $this->shipLoader->getByIdAndUser(
            request::indInt('id'),
            $userId
        );

        /**
         * @var TradePost $tradepost
         */
        $tradepost = ResourceCache()->getObject('tradepost', request::getIntFatal('postid'));
        if (!$ship->canInteractWith($tradepost->getShip())) {
            throw new AccessViolation();
        }

        $game->showMacro('html/shipmacros.xhtml/trademenupayment');

        $game->setTemplateVar('TRADEPOST', $tradepost);
        $game->setTemplateVar('SHIP', $ship);

        if (!$this->tradeLicenseRepository->hasLicenseByUserAndTradePost($userId, (int) $tradepost->getId())) {
            $licenseCostGood = $tradepost->getLicenceCostGood();
            $licenseCost = $tradepost->calculateLicenceCost();

            $game->setTemplateVar(
                'DOCKED_SHIPS_FOR_LICENSE',
                Ship::getObjectsBy(
                    'WHERE user_id=' . $userId . ' AND dock=' . $tradepost->getShipId() . ' AND id IN (SELECT ships_id FROM stu_ships_storage WHERE goods_id=' . $licenseCostGood->getId() . ' AND count>=' . $licenseCost . ')'
                )
            );
            $game->setTemplateVar(
                'ACCOUNTS_FOR_LICENSE',
                TradeStorage::getAccountsByGood(
                    $licenseCostGood->getId(),
                    $userId,
                    $licenseCost,
                    $tradepost->getTradeNetwork()
                )
            );
        }
    }
}
