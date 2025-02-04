<?php

declare(strict_types=1);

namespace Stu\Module\Colony\View\ShowBuildingManagement;

use Colfields;
use ColonyMenu;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Module\Colony\Lib\ColonyGuiHelperInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Orm\Repository\CommodityRepositoryInterface;

final class ShowBuildingManagement implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_BUILDING_MGMT';

    private $colonyLoader;

    private $colonyGuiHelper;

    private $showBuildingManagementRequest;

    private $commodityRepository;

    public function __construct(
        ColonyLoaderInterface $colonyLoader,
        ColonyGuiHelperInterface $colonyGuiHelper,
        ShowBuildingManagementRequestInterface $showBuildingManagementRequest,
        CommodityRepositoryInterface $commodityRepository
    ) {
        $this->colonyLoader = $colonyLoader;
        $this->colonyGuiHelper = $colonyGuiHelper;
        $this->showBuildingManagementRequest = $showBuildingManagementRequest;
        $this->commodityRepository = $commodityRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $colony = $this->colonyLoader->byIdAndUser(
            $this->showBuildingManagementRequest->getColonyId(),
            $userId
        );

        $this->colonyGuiHelper->register($colony, $game);

        $list = Colfields::getListBy('colonies_id=' . $colony->getId() . ' AND buildings_id>0');
        usort($list, 'compareBuildings');

        $game->showMacro('html/colonymacros.xhtml/cm_building_mgmt');

        $game->setTemplateVar('COLONY', $colony);
        $game->setTemplateVar('COLONY_MENU_SELECTOR', new ColonyMenu(MENU_BUILDINGS));
        $game->setTemplateVar('BUILDING_LIST', $list);
        $game->setTemplateVar('USEABLE_GOOD_LIST', $this->commodityRepository->getByBuildingsOnColony((int) $colony->getId()));
    }
}
