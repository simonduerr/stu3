<?php

declare(strict_types=1);

namespace Stu\Module\Colony\Action\CreateModules;

use Colfields;
use Exception;
use request;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Colony\Lib\ColonyLoaderInterface;
use Stu\Module\Colony\View\ShowColony\ShowColony;
use Stu\Orm\Entity\ModuleBuildingFunctionInterface;
use Stu\Orm\Entity\ModuleCostInterface;
use Stu\Orm\Repository\ModuleBuildingFunctionRepositoryInterface;
use Stu\Orm\Repository\ModuleQueueRepositoryInterface;

final class CreateModules implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_CREATE_MODULES';

    private $colonyLoader;

    private $moduleBuildingFunctionRepository;

    private $moduleQueueRepository;

    public function __construct(
        ColonyLoaderInterface $colonyLoader,
        ModuleBuildingFunctionRepositoryInterface $moduleBuildingFunctionRepository,
        ModuleQueueRepositoryInterface $moduleQueueRepository
    ) {
        $this->colonyLoader = $colonyLoader;
        $this->moduleBuildingFunctionRepository = $moduleBuildingFunctionRepository;
        $this->moduleQueueRepository = $moduleQueueRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $game->setView(ShowColony::VIEW_IDENTIFIER);

        $userId = $game->getUser()->getId();

        $colony = $this->colonyLoader->byIdAndUser(
            request::indInt('id'),
            $userId
        );

        $colonyId = $colony->getId();

        $modules = request::postArrayFatal('module');
        $func = request::postIntFatal('func');
        if (count(Colfields::getFieldsByBuildingFunction($colonyId, $func)) == 0) {
            return;
        }
        $prod = array();

        /** @var ModuleBuildingFunctionInterface[] $modules_av */
        $modules_av = [];
        foreach ($this->moduleBuildingFunctionRepository->getByBuildingFunctionAndUser($func, $userId) as $module) {
            $modules_av[$module->getModuleId()] = $module;
        }

        $storage = $colony->getStorage();
        foreach ($modules as $module_id => $count) {
            if (!array_key_exists($module_id, $modules_av)) {
                continue;
            }
            $count = intval($count);
            $module = $modules_av[$module_id]->getModule();
            if ($module->getEcost() * $count > $colony->getEps()) {
                $count = (int) floor($colony->getEps() / $module->getEcost());
            }
            if ($count == 0) {
                continue;
            }
            /** @var ModuleCostInterface[] $costs */
            $costs = $module->getCost();

            try {
                foreach ($costs as $cost) {
                    $commodity = $cost->getCommodity();
                    $commodityId = $commodity->getId();

                    $stor = $storage[$commodityId] ?? null;
                    if ($stor === null) {
                        $prod[] = sprintf(
                            _('Zur Herstellung von %s wird %s benötigt'),
                            $module->getName(),
                            $cost->getCommodity()->getName()
                        );
                        throw new Exception();
                    }
                    if ($stor->getAmount() < $cost->getAmount()) {
                        $prod[] = sprintf(
                            _('Zur Herstellung von %s wird %d %s benötigt'),
                            $module->getName(),
                            $cost->getAmount(),
                            $commodity->getName()
                        );
                        throw new Exception();
                    }
                    if ($stor->getAmount() < $cost->getAmount() * $count) {
                        $count = (int) floor($stor->getAmount() / $cost->getAmount());
                    }
                }
            } catch (Exception $e) {
                continue;
            }
            foreach ($costs as $cost) {
                $colony->lowerStorage($cost->getCommodity()->getId(), $cost->getAmount() * $count);
            }
            $colony->lowerEps($count * $module->getEcost());
            $colony->save();

            if (($queue = $this->moduleQueueRepository->getByColonyAndModuleAndBuilding((int) $colonyId, (int) $module_id, (int) $func)) !== null) {
                $queue->setAmount($queue->getAmount()+$count);

                $this->moduleQueueRepository->save($queue);
            } else {
                $queue = $this->moduleQueueRepository->prototype();
                $queue->setColonyId((int) $colonyId);
                $queue->setBuildingFunction((int) $func);
                $queue->setModule($module);
                $queue->setAmount($count);

                $this->moduleQueueRepository->save($queue);
            }

            $prod[] = $count . ' ' . $module->getName();
        }
        if (count($prod) == 0) {
            $game->addInformation(_('Es wurden keine Module hergestellt'));
            return;
        }
        $game->addInformation(_('Es wurden folgende Module zur Warteschlange hinzugefügt'));
        foreach ($prod as $msg) {
            $game->addInformation($msg);
        }
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
