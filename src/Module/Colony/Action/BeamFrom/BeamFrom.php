<?php

declare(strict_types=1);

namespace Stu\Module\Colony\Action\BeamFrom;

use request;
use Ship;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Module\Colony\View\ShowColony\ShowColony;

final class BeamFrom implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_BEAMFROM';

    private $colonyLoader;

    public function __construct(
        ColonyLoaderInterface $colonyLoader
    ) {
        $this->colonyLoader = $colonyLoader;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setView(ShowColony::VIEW_IDENTIFIER);

        $userId = $game->getUser()->getId();

        $colony = $this->colonyLoader->byIdAndUser(
            request::indInt('id'),
            $userId
        );

        if ($colony->getEps() == 0) {
            $game->addInformation(_('Keine Energie vorhanden'));
            return;
        }
        $target = new Ship(request::postIntFatal('target'));
        if ($target->shieldIsActive() && $target->getUserId() != $userId) {
            $game->addInformationf(_('Die %s hat die Schilde aktiviert'), $target->getName());
            return;
        }
        if (!$colony->storagePlaceLeft()) {
            $game->addInformationf(_('Der Lagerraum der %s ist voll'), $colony->getName());
            return;
        }
        $goods = request::postArray('goods');
        $gcount = request::postArray('count');

        $targetStorage = $target->getStorage();

        if ($targetStorage === []) {
            $game->addInformation(_('Keine Waren zum Beamen vorhanden'));
            return;
        }
        if (count($goods) == 0 || count($gcount) == 0) {
            $game->addInformation(_('Es wurde keine Waren zum Beamen ausgewählt'));
            return;
        }
        $game->addInformationf(
            _('Die Kolonie %s hat folgende Waren von der %s transferiert'),
            $colony->getName(),
            $target->getName()
        );
        foreach ($goods as $key => $value) {
            $commodityId = (int) $value;
            if ($colony->getEps() < 1) {
                break;
            }
            if ($colony->getStorageSum() >= $colony->getMaxStorage()) {
                break;
            }
            if (!array_key_exists($key, $gcount) || $gcount[$key] < 1) {
                continue;
            }
            $count = $gcount[$key];

            $storage = $targetStorage[$commodityId] ?? null;
            if ($storage === null) {
                continue;
            }

            $commodity = $storage->getCommodity();

            if (!$commodity->isBeamable()) {
                $game->addInformationf(_('%s ist nicht beambar'), $commodity->getName());
                continue;
            }
            if ($count == "m") {
                $count = $storage->getAmount();
            } else {
                $count = (int) $count;
            }
            if ($count < 1) {
                continue;
            }
            $count = min($count, $storage->getAmount());

            $transferAmount = $storage->getCommodity()->getTransferCount();

            if (ceil($count / $transferAmount) > $colony->getEps()) {
                $count = $colony->getEps() * $transferAmount;
            }
            if ($colony->getStorageSum() + $count > $colony->getMaxStorage()) {
                $count = $colony->getMaxStorage() - $colony->getStorageSum();
            }

            $eps_usage = ceil($count / $transferAmount);
            $game->addInformationf(_('%d %s (Energieverbrauch: %d)'),
                $count,
                $commodity->getName(),
                $eps_usage
            );

            $target->lowerStorage($commodityId, $count);
            $colony->upperStorage($commodityId, $count);
            $colony->lowerEps(ceil($count / $transferAmount));
            $colony->setStorageSum($colony->getStorageSum() + $count);
        }
        if ($target->getUser() != $userId) {
            $game->sendInformation($target->getUserId(), $userId, PM_SPECIAL_TRADE);
        }
        $colony->save();
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
