<?php

declare(strict_types=1);

namespace Stu\Module\Colony\View\ShowAirfield;

use ColonyMenu;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Module\Colony\Lib\ColonyGuiHelperInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Orm\Repository\ShipRumpRepositoryInterface;

final class ShowAirfield implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_AIRFIELD';

    private $colonyLoader;

    private $colonyGuiHelper;

    private $showAirfieldRequest;

    private $shipRumpRepository;

    public function __construct(
        ColonyLoaderInterface $colonyLoader,
        ColonyGuiHelperInterface $colonyGuiHelper,
        ShowAirfieldRequestInterface $showAirfieldRequest,
        ShipRumpRepositoryInterface $shipRumpRepository
    ) {
        $this->colonyLoader = $colonyLoader;
        $this->colonyGuiHelper = $colonyGuiHelper;
        $this->showAirfieldRequest = $showAirfieldRequest;
        $this->shipRumpRepository = $shipRumpRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $colony = $this->colonyLoader->byIdAndUser(
            $this->showAirfieldRequest->getColonyId(),
            $userId
        );

        $this->colonyGuiHelper->register($colony, $game);

        $game->showMacro('html/colonymacros.xhtml/cm_airfield');

        $game->setTemplateVar('COLONY', $colony);
        $game->setTemplateVar('COLONY_MENU_SELECTOR', new ColonyMenu(MENU_AIRFIELD));
        $game->setTemplateVar(
            'STARTABLE_SHIPS',
            $this->shipRumpRepository->getStartableByUserAndColony($userId, (int) $colony->getId())
        );
        $game->setTemplateVar(
            'BUILDABLE_SHIPS',
            $this->shipRumpRepository->getBuildableByUserAndBuildingFunction($userId,BUILDING_FUNCTION_AIRFIELD)
        );
    }
}
