<?php

declare(strict_types=1);

namespace Stu\Module\Trade\View\ShowLicenseList;

use AccessViolation;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use Stu\Orm\Repository\TradePostRepositoryInterface;

final class ShowLicenseList implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_LICENSE_LIST';

    private $showLicenseListRequest;

    private $tradeLicenseRepository;

    private $tradePostRepository;

    public function __construct(
        ShowLicenseListRequestInterface $showLicenseListRequest,
        TradeLicenseRepositoryInterface $tradeLicenseRepository,
        TradePostRepositoryInterface $tradePostRepository
    ) {
        $this->showLicenseListRequest = $showLicenseListRequest;
        $this->tradeLicenseRepository = $tradeLicenseRepository;
        $this->tradePostRepository = $tradePostRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setTemplateFile('html/ajaxwindow.xhtml');
        $game->setMacro('html/trademacros.xhtml/tradelicencelist');
        $game->setPageTitle(_('Liste ausgestellter Handelslizenzen'));

        $tradepost = $this->tradePostRepository->find($this->showLicenseListRequest->getTradePostId());
        if ($tradepost === null) {
            return;
        }

        if (!$this->tradeLicenseRepository->hasLicenseByUserAndTradePost($game->getUser()->getId(), $tradepost->getId())) {
            throw new AccessViolation();
        }
        $game->setTemplateVar('LIST', $this->tradeLicenseRepository->getByTradePost($tradepost->getId()));
    }
}