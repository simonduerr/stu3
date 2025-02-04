<?php

declare(strict_types=1);

namespace Stu\Module\Ship\View\ShowTradeMenu;

use AccessViolation;
use request;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Module\Ship\Lib\ShipLoaderInterface;
use Stu\Module\Trade\Lib\TradeLibFactoryInterface;
use Stu\Orm\Entity\TradePostInterface;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use Stu\Orm\Repository\TradePostRepositoryInterface;

final class ShowTradeMenu implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_TRADEMENU';

    private $shipLoader;

    private $tradeLicenseRepository;
    /**
     * @var TradeLibFactoryInterface
     */
    private $tradeLibFactory;

    private $tradePostRepository;

    public function __construct(
        ShipLoaderInterface $shipLoader,
        TradeLicenseRepositoryInterface $tradeLicenseRepository,
        TradeLibFactoryInterface $tradeLibFactory,
        TradePostRepositoryInterface $tradePostRepository
    ) {
        $this->shipLoader = $shipLoader;
        $this->tradeLicenseRepository = $tradeLicenseRepository;
        $this->tradeLibFactory = $tradeLibFactory;
        $this->tradePostRepository = $tradePostRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $ship = $this->shipLoader->getByIdAndUser(
            request::indInt('id'),
            $userId
        );

        /**
         * @var TradePostInterface $tradepost
         */
        $tradepost = $this->tradePostRepository->find((int) request::indInt('postid'));
        if ($tradepost === null) {
            return;
        }

        if (!checkPosition($ship, $tradepost->getShip())) {
            new AccessViolation();
        }

        $game->setPageTitle(sprintf(_('Handelsposten: %s'), $tradepost->getName()));
        $game->setTemplateFile('html/ajaxwindow.xhtml');
        $game->setMacro('html/shipmacros.xhtml/trademenu');

        $databaseEntryId = $tradepost->getShip()->getDatabaseId();

        if ($databaseEntryId > 0) {
            $game->checkDatabaseItem($databaseEntryId);
        }

        $game->setTemplateVar('TRADEPOST', $this->tradeLibFactory->createTradeAccountTal($tradepost, $userId));
        $game->setTemplateVar('SHIP', $ship);
        $game->setTemplateVar(
            'HAS_LICENSE',
            $this->tradeLicenseRepository->hasLicenseByUserAndTradePost($userId, (int) $tradepost->getId())
        );
        $game->setTemplateVar(
            'CAN_BUY_LICENSE',
            $this->tradeLicenseRepository->getAmountByUser($userId) < MAX_TRADELICENCE_COUNT
        );
    }
}
