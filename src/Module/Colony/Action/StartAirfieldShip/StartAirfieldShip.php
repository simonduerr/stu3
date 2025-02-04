<?php

declare(strict_types=1);

namespace Stu\Module\Colony\Action\StartAirfieldShip;

use request;
use Ship;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Module\Colony\View\ShowColony\ShowColony;
use Stu\Module\Crew\Lib\CrewCreatorInterface;
use Stu\Module\Ship\Lib\ShipCreatorInterface;
use Stu\Module\Ship\Lib\ShipRumpSpecialAbilityEnum;
use Stu\Orm\Repository\BuildplanHangarRepositoryInterface;
use Stu\Orm\Repository\ColonyStorageRepositoryInterface;
use Stu\Orm\Repository\CommodityRepositoryInterface;
use Stu\Orm\Repository\ShipRumpRepositoryInterface;

final class StartAirfieldShip implements ActionControllerInterface
{

    public const ACTION_IDENTIFIER = 'B_START_AIRFIELD_SHIP';

    private $colonyLoader;

    private $commodityRepository;

    private $buildplanHangarRepository;

    private $crewCreator;

    private $shipCreator;

    private $colonyStorageRepository;

    private $shipRumpRepository;

    public function __construct(
        ColonyLoaderInterface $colonyLoader,
        CommodityRepositoryInterface $commodityRepository,
        BuildplanHangarRepositoryInterface $buildplanHangarRepository,
        CrewCreatorInterface $crewCreator,
        ShipCreatorInterface $shipCreator,
        ColonyStorageRepositoryInterface $colonyStorageRepository,
        ShipRumpRepositoryInterface $shipRumpRepository
    ) {
        $this->colonyLoader = $colonyLoader;
        $this->commodityRepository = $commodityRepository;
        $this->buildplanHangarRepository = $buildplanHangarRepository;
        $this->crewCreator = $crewCreator;
        $this->shipCreator = $shipCreator;
        $this->colonyStorageRepository = $colonyStorageRepository;
        $this->shipRumpRepository = $shipRumpRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setView(ShowColony::VIEW_IDENTIFIER);

        $user = $game->getUser();
        $userId = $user->getId();

        $colony = $this->colonyLoader->byIdAndUser(
            request::indInt('id'),
            $userId
        );

        $rump_id = (int) request::postInt('startrump');
        $available_rumps = $this->shipRumpRepository->getStartableByUserAndColony($userId, (int) $colony->getId());

        if (!array_key_exists($rump_id, $available_rumps)) {
            return;
        }

        $rump = $this->shipRumpRepository->find($rump_id);

        if ($rump->hasSpecialAbility(ShipRumpSpecialAbilityEnum::COLONIZE) && Ship::countInstances(
                sprintf(
                    'WHERE user_id = %d AND rumps_id IN (SELECT rumps_id FROM stu_rumps_specials WHERE special = %d)',
                    $userId,
                    ShipRumpSpecialAbilityEnum::COLONIZE
                )
            ) > 0) {
            $game->addInformation(_('Es kann nur ein Schiff mit Kolonisierungsfunktion genutzt werden'));
            return;
        }
        $hangar = $this->buildplanHangarRepository->getByRump((int) $rump->getId());

        if ($hangar->getBuildplan()->getCrew() > $user->getFreeCrewCount()) {
            $game->addInformation(_('Es ist für den Start des Schiffes nicht genügend Crew vorhanden'));
            return;
        }
        if (Ship::countInstances('WHERE user_id=' . $userId) >= 10) {
            $game->addInformation(_('Im Moment sind nur 10 Schiffe pro Spieler erlaubt'));
            return;
        }
        // XXX starting costs
        if ($colony->getEps() < 10) {
            $game->addInformationf(
                _('Es wird %d Energie benötigt - Vorhanden ist nur %d'),
                10,
                $colony->getEps()
            );
            return;
        }
        $shipStorage = $this->colonyStorageRepository->getByColony((int) $colony->getId(), 0);
        if (!array_key_exists($rump->getGoodId(), $shipStorage)) {
            $game->addInformationf(
                _('Es wird %d %s benötigt'),
                1,
                $this->commodityRepository->find((int) $rump->getGoodId())->getName()
            );
            return;
        }

        $ship = $this->shipCreator->createBy(
            (int) $userId,
            (int) $rump_id,
            $hangar->getBuildplanId(),
            $colony
        );

        $this->crewCreator->createShipCrew($ship);

        $storage = $colony->getStorage();

        $defaultTorpedoType = $hangar->getDefaultTorpedoType();
        if ($defaultTorpedoType !== null) {
            if (array_key_exists($defaultTorpedoType->getGoodId(), $storage)) {
                $count = $ship->getMaxTorpedos();
                if ($count > $storage[$defaultTorpedoType->getGoodId()]->getAmount()) {
                    $count = $storage[$defaultTorpedoType->getGoodId()]->getAmount();
                }
                $ship->setTorpedoType($defaultTorpedoType->getId());
                $ship->setTorpedoCount($count);
                $ship->save();
                $colony->lowerStorage($defaultTorpedoType->getGoodId(), $count);
            }
        }
        if ($rump->hasSpecialAbility(ShipRumpSpecialAbilityEnum::FULLY_LOADED_START)) {
            $ship->setEps($ship->getMaxEps());
            $ship->setWarpcoreLoad($ship->getWarpcoreCapacity());
            $ship->save();
        }
        $colony->lowerEps(10);
        $colony->lowerStorage($rump->getGoodId(), 1);
        $colony->save();

        $databaseEntry = $colony->getSystem()->getDatabaseEntry();
        if ($databaseEntry !== null) {
            $game->checkDatabaseItem($databaseEntry->getId());
        }
        $databaseEntry = $colony->getSystem()->getSystemType()->getDatabaseEntry();
        if ($databaseEntry !== null) {
            $game->checkDatabaseItem($databaseEntry->getId());
        }
        if ($rump->getDatabaseId()) {
            $game->checkDatabaseItem($rump->getDatabaseId());
        }
        $game->addInformation(_('Das Schiff wurde gestartet'));
    }

    public function performSessionCheck(): bool
    {
        return false;
    }
}
