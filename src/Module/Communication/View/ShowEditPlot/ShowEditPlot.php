<?php

declare(strict_types=1);

namespace Stu\Module\Communication\View\ShowEditPlot;

use AccessViolation;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Orm\Entity\RpgPlotInterface;
use Stu\Orm\Repository\RpgPlotRepositoryInterface;

final class ShowEditPlot implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_EDIT_PLOT';

    private $showEditPlotRequest;

    private $rpgPlotRepository;

    public function __construct(
        ShowEditPlotRequestInterface $showEditPlotRequest,
        RpgPlotRepositoryInterface $rpgPlotRepository
    ) {
        $this->showEditPlotRequest = $showEditPlotRequest;
        $this->rpgPlotRepository = $rpgPlotRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        /** @var RpgPlotInterface $plot */
        $plot = $this->rpgPlotRepository->find($this->showEditPlotRequest->getPlotId());
        if ($plot === null || $plot->getUserId() != $game->getUser()->getId()) {
            throw new AccessViolation();
        }

        $game->setTemplateFile('html/editplot.xhtml');
        $game->appendNavigationPart('comm.php', _('KommNet'));
        $game->appendNavigationPart(
            sprintf('comm.php?%s=1&plotid=%d', static::VIEW_IDENTIFIER, $plot->getId()),
            _('Plot editiren')
        );
        $game->setPageTitle(_('Plot editieren'));

        $game->setTemplateVar('PLOT', $plot);
    }
}
