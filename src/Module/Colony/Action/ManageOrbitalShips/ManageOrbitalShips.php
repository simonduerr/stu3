<?php

declare(strict_types=1);

namespace Stu\Module\Colony\Action\ManageOrbitalShips;

use Exception;
use PM;
use request;
use Ship;
use Stu\Module\Commodity\CommodityTypeEnum;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Module\Colony\View\ShowOrbitManagement\ShowOrbitManagement;
use Stu\Module\Crew\Lib\CrewCreatorInterface;
use Stu\Orm\Repository\ShipCrewRepositoryInterface;
use Stu\Orm\Repository\TorpedoTypeRepositoryInterface;

final class ManageOrbitalShips implements ActionControllerInterface
{

    public const ACTION_IDENTIFIER = 'B_MANAGE_ORBITAL_SHIPS';

    private $colonyLoader;

    private $torpedoTypeRepository;

    private $crewCreator;

    private $shipCrewRepository;

    public function __construct(
        ColonyLoaderInterface $colonyLoader,
        TorpedoTypeRepositoryInterface $torpedoTypeRepository,
        CrewCreatorInterface $crewCreator,
        ShipCrewRepositoryInterface $shipCrewRepository
    ) {
        $this->colonyLoader = $colonyLoader;
        $this->torpedoTypeRepository = $torpedoTypeRepository;
        $this->crewCreator = $crewCreator;
        $this->shipCrewRepository = $shipCrewRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setView(ShowOrbitManagement::VIEW_IDENTIFIER);

        $userId = $game->getUser()->getId();

        $colony = $this->colonyLoader->byIdAndUser(
            request::indInt('id'),
            $userId
        );

        $ships = request::postArray('ships');
        if (count($ships) == 0) {
            $game->addInformation(_('Es wurden keine Schiffe ausgewählt'));
            return;
        }
        $msg = array();
        $batt = request::postArrayFatal('batt');
        $man = request::postArray('man');
        $unman = request::postArray('unman');
        $wk = request::postArray('wk');
        $torp = request::postArray('torp');
        $torp_type = request::postArray('torp_type');
        $storage = $colony->getStorage();
        foreach ($ships as $key => $ship) {
            /**
             * @var Ship $shipobj
             */
            $shipobj = ResourceCache()->getObject('ship', intval($ship));
            if ($shipobj->cloakIsActive()) {
                continue;
            }
            if (!checkColonyPosition($colony, $shipobj)) {
                continue;
            }
            if ($shipobj->getIsDestroyed()) {
                continue;
            }
            if ($colony->getEps() > 0 && $shipobj->getEBatt() < $shipobj->getMaxEbatt() && array_key_exists($ship,
                    $batt)) {
                if ($batt[$ship] == 'm') {
                    $load = $shipobj->getMaxEbatt() - $shipobj->getEBatt();
                } else {
                    $load = intval($batt[$ship]);
                    if ($shipobj->getEBatt() + $load > $shipobj->getMaxEBatt()) {
                        $load = $shipobj->getMaxEBatt() - $shipobj->getEBatt();
                    }
                }
                if ($load > $colony->getEps()) {
                    $load = $colony->getEps();
                }
                if ($load > 0) {
                    $shipobj->upperEBatt($load);
                    $colony->lowerEps($load);
                    $msg[] = sprintf(
                        _('%s: Batterie um %d Einheiten aufgeladen'),
                        $shipobj->getName(), $load
                    );
                    if (!$shipobj->ownedByCurrentUser()) {
                        PM::sendPM(
                            $userId,
                            $shipobj->getUserId(),
                            sprintf(
                                _('Die Kolonie %s lädt in Sektor %s die Batterie der %s um %s Einheiten'),
                                $colony->getName(),
                                $colony->getSectorString(),
                                $shipobj->getName(),
                                $load
                            ),
                            PM_SPECIAL_TRADE);
                    }
                }
            }
            if (isset($man[$shipobj->getId()]) && $shipobj->currentUserCanMan()) {
                if ($shipobj->getBuildplan()->getCrew() > $shipobj->getUser()->getFreeCrewCount()) {
                    $msg[] = sprintf(
                        _('%s: Nicht genügend Crew vorhanden (%d benötigt)'),
                        $shipobj->getName(),
                        $shipobj->getBuildplan()->getCrew()
                    );
                } else {
                    $this->crewCreator->createShipCrew($shipobj);
                    $msg[] = sprintf(
                        _('%s: Die Crew wurde hochgebeamt'),
                        $shipobj->getName()
                    );
                    $shipobj->getUser()->setFreeCrewCount($shipobj->getUser()->getFreeCrewCount() - $shipobj->getBuildplan()->getCrew());
                }
            }
            if (isset($unman[$shipobj->getId()]) && $shipobj->currentUserCanUnMan()) {
                $this->shipCrewRepository->truncateByShip((int) $shipobj->getId());
                $msg[] = sprintf(
                    _('%s: Die Crew wurde runtergebeamt'), $shipobj->getName()
                );
                $shipobj->deactivateSystems();
            }
            if (isset($wk[$shipobj->getId()]) && $wk[$shipobj->getId()] > 0) {
                if (
                    array_key_exists(CommodityTypeEnum::GOOD_DEUTERIUM, $storage) &&
                    array_key_exists(CommodityTypeEnum::GOOD_ANTIMATTER, $storage)
                ) {
                    if ($shipobj->getWarpcoreLoad() < $shipobj->getWarpcoreCapacity()) {
                        if ($wk[$shipobj->getId()] == INDICATOR_MAX) {
                            $load = ceil(($shipobj->getWarpcoreCapacity() - $shipobj->getWarpcoreLoad()) / WARPCORE_LOAD);
                        } else {
                            $load = ceil(intval($wk[$shipobj->getId()]) / WARPCORE_LOAD);
                            if ($load * WARPCORE_LOAD > $shipobj->getWarpcoreCapacity() - $shipobj->getWarpcoreLoad()) {
                                $load = ceil(($shipobj->getWarpcoreCapacity() - $shipobj->getWarpcoreLoad()) / WARPCORE_LOAD);
                            }
                        }
                        if ($load >= 1) {
                            if ($storage[CommodityTypeEnum::GOOD_DEUTERIUM]->getAmount() < $load) {
                                $load = $storage[CommodityTypeEnum::GOOD_DEUTERIUM]->getAmount();
                            }
                            if ($storage[CommodityTypeEnum::GOOD_ANTIMATTER]->getAmount() < $load) {
                                $load = $storage[CommodityTypeEnum::GOOD_ANTIMATTER]->getAmount();
                            }
                            $colony->lowerStorage(CommodityTypeEnum::GOOD_DEUTERIUM, (int) $load);
                            $colony->lowerStorage(CommodityTypeEnum::GOOD_ANTIMATTER, (int) $load);
                            if ($shipobj->getWarpcoreLoad() + $load * WARPCORE_LOAD > $shipobj->getWarpcoreCapacity()) {
                                $load = $shipobj->getWarpcoreCapacity() - $shipobj->getWarpcoreLoad();
                            } else {
                                $load = $load * WARPCORE_LOAD;
                            }
                            $shipobj->upperWarpcoreLoad($load);
                            $msg[] = sprintf(
                                _('Der Warpkern der %s wurde um %d Einheiten aufgeladen'),
                                $shipobj->getName(),
                                $load
                            );
                            if (!$shipobj->ownedByCurrentUser()) {
                                PM::sendPM(
                                    $userId,
                                    $shipobj->getUserId(),
                                    sprintf(
                                        _('Die Kolonie %s hat in Sektor %s den Warpkern der %s um %d Einheiten aufgeladen'),
                                        $colony->getName(),
                                        $colony->getSectorString(),
                                        $shipobj->getName(),
                                        $load
                                    ),
                                    PM_SPECIAL_TRADE);
                            }
                        }
                    }
                } else {
                    $msg[] = sprintf(
                        _('%s: Es wird Deuterium und Antimaterie zum Aufladen des Warpkerns benötigt'),
                        $shipobj->getName()
                    );
                }
            }
            if (isset($torp[$shipobj->getId()]) && $shipobj->canLoadTorpedos()) {
                if ($torp[$shipobj->getId()] == INDICATOR_MAX) {
                    $count = $shipobj->getMaxTorpedos();
                } else {
                    $count = intval($torp[$shipobj->getId()]);
                }
                try {
                    if ($count < 0) {
                        throw new Exception;
                    }
                    if ($count == $shipobj->getTorpedoCount()) {
                        throw new Exception;
                    }
                    if (!$shipobj->ownedByCurrentUser() && $count <= $shipobj->getTorpedoCount()) {
                        throw new Exception;
                    }
                    $possibleTorpedoTypes = $this->torpedoTypeRepository->getByLevel((int) $shipobj->getRump()->getTorpedoLevel());
                    if (
                        $shipobj->getTorpedoCount() == 0 && (!isset($torp_type[$shipobj->getId()]) ||
                        !array_key_exists($torp_type[$shipobj->getId()], $possibleTorpedoTypes))
                    ) {
                        throw new Exception;
                    }
                    if ($count > $shipobj->getMaxTorpedos()) {
                        $count = $shipobj->getMaxTorpedos();
                    }
                    if ($shipobj->getTorpedoCount() > 0) {
                        $torp_obj = $possibleTorpedoTypes[(int) $shipobj->getTorpedoType()];
                        $load = $count - $shipobj->getTorpedoCount();
                        if ($load > 0) {
                            if (!array_key_exists($torp_obj->getGoodId(), $storage)) {
                                $msg[] = sprintf(
                                    _('%s: Es sind keine Torpedos des Typs %s auf der Kolonie vorhanden'),
                                    $shipobj->getName(),
                                    $torp_obj->getName()
                                );
                                throw new Exception();
                            }
                            if ($load > $storage[$torp_obj->getGoodId()]->getAmount()) {
                                $load = $storage[$torp_obj->getGoodId()]->getAmount();
                            }
                        }
                        $shipobj->setTorpedoCount($shipobj->getTorpedoCount() + $load);
                        if ($load < 0) {
                            $colony->upperStorage($torp_obj->getGoodId(), abs($load));
                            if ($shipobj->getTorpedoCount() == 0) {
                                $shipobj->setTorpedoType(0);
                                $shipobj->setTorpedos(0);
                            }
                            $msg[] = sprintf(
                                _('%s: Es wurden %d Torpedos des Typs %s vom Schiff transferiert'),
                                $shipobj->getName(),
                                abs($load),
                                $torp_obj->getName()
                            );
                        } elseif ($load > 0) {
                            $colony->lowerStorage($torp_obj->getGoodId(), $load);
                            $msg[] = sprintf(
                                _('%s: Es wurden %d Torpedos des Typs %s zum Schiff transferiert'),
                                $shipobj->getName(),
                                $load,
                                $torp_obj->getName()
                            );
                        }
                    } else {
                        $type = (int) $torp_type[$shipobj->getId()];
                        $torp_obj = $this->torpedoTypeRepository->find($type);
                        if (!array_key_exists($torp_obj->getGoodId(), $storage)) {
                            throw new Exception;
                        }
                        if ($count > $storage[$torp_obj->getGoodId()]->getAmount()) {
                            $count = $storage[$torp_obj->getGoodId()]->getAmount();
                        }
                        if ($count > $shipobj->getMaxTorpedos()) {
                            $count = $shipobj->getMaxTorpedos();
                        }
                        $shipobj->setTorpedoType($type);
                        $shipobj->setTorpedoCount($count);
                        $colony->lowerStorage($torp_obj->getGoodId(), $count);
                        $msg[] = sprintf(
                            _('%s: Es wurden %d Torpedos des Typs %s zum Schiff transferiert'),
                            $shipobj->getName(),
                            $count,
                            $torp_obj->getName()
                        );
                    }
                } catch (Exception $e) {
                    // nothing
                }
            }
            $shipobj->save();
        }
        $colony->save();
        $game->addInformationMerge($msg);
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
