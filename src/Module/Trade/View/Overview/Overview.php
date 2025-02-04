<?php

declare(strict_types=1);

namespace Stu\Module\Trade\View\Overview;

use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use Stu\Orm\Repository\TradeOfferRepositoryInterface;

final class Overview implements ViewControllerInterface
{
    private $tradeLicenseRepository;

    private $tradeOfferRepository;

    public function __construct(
        TradeLicenseRepositoryInterface $tradeLicenseRepository,
        TradeOfferRepositoryInterface $tradeOfferRepository
    ) {
        $this->tradeLicenseRepository = $tradeLicenseRepository;
        $this->tradeOfferRepository = $tradeOfferRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $game->appendNavigationPart(
            'trade.php',
            _('Handel')
        );
        $game->setPageTitle(_('/ Handel'));
        $game->setTemplateFile('html/trade.xhtml');

        $game->setTemplateVar(
            'TRADE_LICENSE_COUNT',
            $this->tradeLicenseRepository->getAmountByUser($userId)
        );
        $game->setTemplateVar('MAX_TRADE_LICENSE_COUNT', MAX_TRADELICENCE_COUNT);
        $game->setTemplateVar('OFFER_LIST', $this->tradeOfferRepository->getByUserLicenses($userId));
    }
}