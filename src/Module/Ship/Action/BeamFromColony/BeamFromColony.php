<?php

declare(strict_types=1);

namespace Stu\Module\Ship\Action\BeamFromColony;

use Colony;
use request;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Ship\Lib\ShipLoaderInterface;
use Stu\Module\Ship\View\ShowShip\ShowShip;
use SystemActivationWrapper;

final class BeamFromColony implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_BEAMFROM_COLONY';

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
        $wrapper = new SystemActivationWrapper($ship);
        if ($wrapper->getError()) {
            $game->addInformation($wrapper->getError());
            return;
        }
        if ($ship->getEps() == 0) {
            $game->addInformation(_("Keine Energie vorhanden"));
            return;
        }
        if ($ship->getCloakState()) {
            $game->addInformation(_("Die Tarnung ist aktiviert"));
            return;
        }
        if ($ship->getShieldState()) {
            $game->addInformation(_('Die Schilde sind aktiviert'));
            return;
        }
        if ($ship->getWarpState()) {
            $game->addInformation(_("Der Warpantrieb ist aktiviert"));
            return;
        }
        $target = new Colony(request::postIntFatal('target'));
        if (!$ship->canInteractWith($target, true)) {
            return;
        }
        if (!$ship->storagePlaceLeft()) {
            $game->addInformation(sprintf(_('Der Lagerraum der %s ist voll'), $ship->getName()));
            return;
        }
        $goods = request::postArray('goods');
        $gcount = request::postArray('count');
        if ($target->getStorage() === []) {
            $game->addInformation(_("Keine Waren zum Beamen vorhanden"));
            return;
        }
        if (count($goods) == 0 || count($gcount) == 0) {
            $game->addInformation(_("Es wurde keine Waren zum Beamen ausgewählt"));
            return;
        }
        $game->addInformation(sprintf(_('Die %s hat folgende Waren von der Kolonie %s transferiert'),
            $ship->getName(), $target->getName()));
        foreach ($goods as $key => $value) {
            $value = (int) $value;
            if ($ship->getEps() < 1) {
                break;
            }
            if (!array_key_exists($key, $gcount) || $gcount[$key] < 1) {
                continue;
            }
            $good = $target->getStorage()[$value] ?? null;
            if ($good === null) {
                continue;
            }
            $count = $gcount[$key];
            if (!$good->getGood()->isBeamable()) {
                $game->addInformation(sprintf(_('%s ist nicht beambar'), $good->getGood()->getName()));
                continue;
            }
            if ($count == "m") {
                $count = (int) $good->getAmount();
            } else {
                $count = (int) $count;
            }
            if ($count < 1) {
                continue;
            }
            if ($ship->getStorageSum() >= $ship->getMaxStorage()) {
                break;
            }
            if ($count > $good->getAmount()) {
                $count = (int) $good->getAmount();
            }
            if (ceil($count / $good->getGood()->getTransferCount()) > $ship->getEps()) {
                $count = $ship->getEps() * $good->getGood()->getTransferCount();
            }
            if ($ship->getStorageSum() + $count > $ship->getMaxStorage()) {
                $count = $ship->getMaxStorage() - $ship->getStorageSum();
            }

            $count = (int) $count;

            $game->addInformationf(
                _('%d %s (Energieverbrauch: %d)'),
                $count,
                $good->getGood()->getName(),
                ceil($count / $good->getGood()->getTransferCount())
            );

            $target->lowerStorage($value, $count);
            $ship->upperStorage((int) $value, $count);
            $ship->lowerEps(ceil($count / $good->getGood()->getTransferCount()));
            $ship->setStorageSum($ship->getStorageSum() + $count);
        }
        if ($target->getUserId() != $ship->getUserId()) {
            $game->sendInformation($target->getUserId(), $ship->getUserId(), PM_SPECIAL_TRADE);
        }
        $ship->save();
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
