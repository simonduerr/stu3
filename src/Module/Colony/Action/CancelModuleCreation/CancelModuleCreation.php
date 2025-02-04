<?php

declare(strict_types=1);

namespace Stu\Module\Colony\Action\CancelModuleCreation;

use Colfields;
use request;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Orm\Entity\ModuleInterface;
use Stu\Orm\Repository\ModuleQueueRepositoryInterface;
use Stu\Orm\Repository\ModuleRepositoryInterface;

final class CancelModuleCreation implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_CANCEL_MODULECREATION';

    private $colonyLoader;

    private $moduleQueueRepository;

    private $moduleRepository;

    public function __construct(
        ColonyLoaderInterface $colonyLoader,
        ModuleQueueRepositoryInterface $moduleQueueRepository,
        ModuleRepositoryInterface $moduleRepository
    ) {
        $this->colonyLoader = $colonyLoader;
        $this->moduleQueueRepository = $moduleQueueRepository;
        $this->moduleRepository = $moduleRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $colony = $this->colonyLoader->byIdAndUser(
            request::indInt('id'),
            $game->getUser()->getId()
        );

        $module_id = request::postIntFatal('module');
        $function = request::postIntFatal('func');
        $count = request::postInt('count');

        /** @var ModuleInterface $module */
        $module = $this->moduleRepository->find((int) $module_id);

        if ($module === null) {
            return;
        }

        $game->setView('SHOW_MODULE_CANCEL', ['MODULE' => $module]);

        if (count(Colfields::getFieldsByBuildingFunction($colony->getId(), $function)) == 0) {
            return;
        }
        if ($count == 0) {
            return;
        }
        $queue = $this->moduleQueueRepository->getByColonyAndModuleAndBuilding((int) $colony->getId(), (int) $module_id, (int) $function);
        if ($queue === null) {
            return;
        }
        if ($queue->getAmount() < $count) {
            $count = $queue->getAmount();
        }
        if ($count >= $queue->getAmount()) {
            $this->moduleQueueRepository->delete($queue);
        } else {
            $queue->setAmount($queue->getAmount() - $count);

            $this->moduleQueueRepository->save($queue);
        }
        if ($module->getEcost() * $count > $colony->getMaxEps() - $colony->getEps()) {
            $colony->setEps($colony->getMaxEps());
        } else {
            $colony->upperEps($count * $module->getEcost());
        }
        foreach ($module->getCost() as $cost) {
            if ($colony->getStorageSum() >= $colony->getMaxStorage()) {
                break;
            }
            if ($cost->getAmount() * $count > $colony->getMaxStorage() - $colony->getStorageSum()) {
                $gc = $colony->getMaxStorage() - $colony->getStorageSum();
            } else {
                $gc = $count * $cost->getAmount();
            }
            $colony->upperStorage($cost->getCommodity()->getId(), $gc);
            $colony->setStorageSum($colony->getStorageSum() + $gc);
        }
        $colony->save();
    }

    public function performSessionCheck(): bool
    {
        return false;
    }
}
