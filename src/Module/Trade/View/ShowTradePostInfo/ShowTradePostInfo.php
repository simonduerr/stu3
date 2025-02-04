<?php

declare(strict_types=1);

namespace Stu\Module\Trade\View\ShowTradePostInfo;

use AccessViolation;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Module\Trade\Lib\TradeLibFactoryInterface;
use Stu\Orm\Repository\TradeLicenseRepositoryInterface;
use Stu\Orm\Repository\TradePostRepositoryInterface;

final class ShowTradePostInfo implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_TRADEPOST_INFO';

    private $showTradePostInfoRequest;

    private $tradeLicenseRepository;

    private $talFactory;

    private $tradePostRepository;

    public function __construct(
        ShowTradePostInfoRequestInterface $showTradePostInfoRequest,
        TradeLicenseRepositoryInterface $tradeLicenseRepository,
        TradeLibFactoryInterface $talFactory,
        TradePostRepositoryInterface $tradePostRepository
    ) {
        $this->showTradePostInfoRequest = $showTradePostInfoRequest;
        $this->tradeLicenseRepository = $tradeLicenseRepository;
        $this->talFactory = $talFactory;
        $this->tradePostRepository = $tradePostRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $trade_post = $this->tradePostRepository->find($this->showTradePostInfoRequest->getTradePostId());
        if ($trade_post === null) {
            return;
        }

        if (!$this->tradeLicenseRepository->hasLicenseByUserAndTradePost($userId, (int) $trade_post->getId())) {
            throw new AccessViolation();
        }

        $game->setTemplateFile('html/ajaxwindow.xhtml');
        $game->setMacro('html/trademacros.xhtml/tradepostinfo');
        $game->setPageTitle(_('Handelsposten Details'));

        $game->setTemplateVar(
            'TRADE_POST_INFO',
            $this->talFactory->createTradeAccountTal($trade_post, $userId)
        );
    }
}