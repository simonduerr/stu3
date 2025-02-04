<?php

declare(strict_types=1);

namespace Stu\Module\Maindesk\View\ShowColonyList;

use AccessViolation;
use Colony;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;

final class ShowColonyList implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_COLONYLIST';

    public function handle(GameControllerInterface $game): void
    {
        if ((int)$game->getUser() !== 1) {
            throw new AccessViolation();
        }
        $game->setTemplateFile("html/maindesk_colonylist.xhtml");
        $game->setPageTitle("Kolonie gründen");
        $game->appendNavigationPart(
            sprintf(
                '?%s=1',
                static::VIEW_IDENTIFIER
            ),
            _('Kolonie gründen')
        );

        $game->setTemplateVar(
            'FREE_PLANET_LIST',
            Colony::getFreeColonyList($game->getUser()->getFaction())
        );
    }
}
