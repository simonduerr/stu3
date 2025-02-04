<?php

declare(strict_types=1);

namespace Stu\Module\Communication\View\ShowPmCategoryList;

use PMCategory;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;

final class ShowPmCategoryList implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_CAT_LIST';

    public function handle(GameControllerInterface $game): void
    {
        $game->showMacro('html/commmacros.xhtml/pmcategorylist_ajax');

        $game->setTemplateVar('markcat', true);
        $game->setTemplateVar('PM_CATEGORIES', PMCategory::getCategoryTree($game->getUser()->getId()));
    }
}
