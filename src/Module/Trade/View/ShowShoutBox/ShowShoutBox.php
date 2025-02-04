<?php

declare(strict_types=1);

namespace Stu\Module\Trade\View\ShowShoutBox;

use AccessViolation;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use Stu\Orm\Repository\TradeShoutboxRepositoryInterface;

final class ShowShoutBox implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_SHOUTBOX';

    private $showShoutBoxRequest;

    private $tradeShoutboxRepository;

    private $tradeLicenseRepository;

    public function __construct(
        ShowShoutBoxRequestInterface $showShoutBoxRequest,
        TradeShoutboxRepositoryInterface $tradeShoutboxRepository,
        TradeLicenseRepositoryInterface $tradeLicenseRepository
    ) {
        $this->showShoutBoxRequest = $showShoutBoxRequest;
        $this->tradeShoutboxRepository = $tradeShoutboxRepository;
        $this->tradeLicenseRepository = $tradeLicenseRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $tradeNetworkId = $this->showShoutBoxRequest->getTradeNetworkId();

        if (!$this->tradeLicenseRepository->hasLicenseByUserAndNetwork($game->getUser()->getId(), $tradeNetworkId)) {
            throw new AccessViolation();
        }

        $game->setTemplateFile('html/ajaxwindow.xhtml');
        $game->setMacro('html/trademacros.xhtml/shoutbox');
        $game->setPageTitle(_('Schwarzes Brett'));
        $game->setTemplateVar('NETWORK', $tradeNetworkId);
        $game->setTemplateVar('SHOUTBOX', $this->tradeShoutboxRepository->getByTradeNetwork($tradeNetworkId));
    }
}