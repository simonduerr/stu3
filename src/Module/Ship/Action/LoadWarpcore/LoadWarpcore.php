<?php

declare(strict_types=1);

namespace Stu\Module\Ship\Action\LoadWarpcore;

use request;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Ship\Lib\ShipLoaderInterface;
use Stu\Module\Ship\View\ShowShip\ShowShip;

final class LoadWarpcore implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_LOAD_WARPCORE';

    private $shipLoader;

    public function __construct(
        ShipLoaderInterface $shipLoader
    ) {
        $this->shipLoader = $shipLoader;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setView(ShowShip::VIEW_IDENTIFIER);

        $userId = $game->getUser()->getId();

        $ship = $this->shipLoader->getByIdAndUser(
            request::indInt('id'),
            $userId
        );

        if (request::postString('fleet')) {
            $msg = array();
            $msg[] = _('Flottenbefehl ausgeführt: Aufladung des Warpkerns');
            DB()->beginTransaction();
            foreach ($ship->getFleet()->getShips() as $key => $ship) {
                if ($ship->getWarpcoreLoad() >= $ship->getWarpcoreCapacity()) {
                    continue;
                }
                $load = $ship->loadWarpCore(1);
                if (!$load) {
                    $game->addInformation(sprintf(_('%s: Zum Aufladen des Warpkerns werden mindestens 1 Deuterium sowie 1 Antimaterie benötigt'),
                        $ship->getName()));
                    continue;
                }
                $game->addInformation(sprintf(_('%s: Der Warpkern wurde um %d Einheiten aufgeladen'), $ship->getName(),
                    $load));
            }
            DB()->commitTransaction();
            $game->addInformationMerge($msg);
            return;
        }
        if ($ship->getWarpcoreLoad() >= $ship->getWarpcoreCapacity()) {
            $game->addInformation(_('Der Warpkern ist bereits vollständig geladen'));
            return;
        }
        $load = $ship->loadWarpCore(1);
        if (!$load) {
            $game->addInformation(_('Zum Aufladen des Warpkerns werden mindestens 1 Deuterium sowie 1 Antimaterie benötigt'));
            return;
        }
        $game->addInformation(sprintf(_('Der Warpkern wurde um %d Einheiten aufgeladen'), $load));
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
