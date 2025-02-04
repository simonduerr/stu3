<?php

declare(strict_types=1);

namespace Stu\Lib\ModuleScreen;

use ColonyData;
use Stu\Module\ShipModule\ModuleTypeDescriptionMapper;
use Stu\Module\Tal\TalPageInterface;
use Stu\Orm\Entity\ShipBuildplanInterface;
use Stu\Orm\Entity\ShipRumpInterface;
use Stu\Orm\Repository\ModuleRepositoryInterface;
use Stu\Orm\Repository\ShipRumpModuleLevelRepositoryInterface;

class ModuleSelector implements ModuleSelectorInterface
{

    private $moduleType;
    private $rump;
    private $userId;
    private $macro = 'html/modulescreen.xhtml/moduleselector';
    private $templateFile = 'html/ajaxempty.xhtml';
    private $template;
    private $colony;
    private $buildplan;

    public function __construct(
        $moduleType,
        ColonyData $colony,
        ShipRumpInterface $rump,
        int $userId,
        ?ShipBuildplanInterface $buildplan = null
    ) {
        $this->moduleType = $moduleType;
        $this->rump = $rump;
        $this->userId = $userId;
        $this->colony = $colony;
        $this->buildplan = $buildplan;
    }

    public function allowMultiple(): bool
    {
        return false;
    }

    private function getTemplate(): TalPageInterface
    {
        if ($this->template === null) {
            // @todo refactor
            global $container;

            $this->template = $container->get(TalPageInterface::class);
            $this->template->setTemplate($this->templateFile);
            $this->template->setVar('THIS', $this);
        }
        return $this->template;
    }

    public function getMacro(): string
    {
        return $this->macro;
    }

    public function render(): string
    {
        return $this->getTemplate()->parse(true);
    }

    public function getModuleType(): int
    {
        return $this->moduleType;
    }

    public function allowEmptySlot(): bool
    {
        return $this->getRump()->getModuleLevels()->{'getModuleMandatory' . $this->getModuleType()}() == 0;
    }

    public function getModuleDescription(): string
    {
        return ModuleTypeDescriptionMapper::getDescription($this->getModuleType());
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRump(): ShipRumpInterface
    {
        return $this->rump;
    }

    private $modules;

    /**
     * @return ModuleSelectorWrapper[]
     */
    public function getAvailableModules(): array
    {
        // @todo refactor
        global $container;
        if ($this->modules === null) {
            $this->modules = [];
            if ($this->getModuleType() == MODULE_TYPE_SPECIAL) {
                $modules = $container->get(ModuleRepositoryInterface::class)->getBySpecialTypeAndRump(
                    (int)$this->getColony()->getId(),
                    (int)$this->getModuleType(),
                    $this->getRump()->getId(),
                    $this->getRump()->getShipRumpRole()->getId()
                );
            } else {
                $mod_level = $container->get(ShipRumpModuleLevelRepositoryInterface::class)->getByShipRump(
                    $this->getRump()->getId()
                );

                $min_level = $mod_level->{'getModuleLevel' . $this->getModuleType() . 'Min'}();
                $max_level = $mod_level->{'getModuleLevel' . $this->getModuleType() . 'Max'}();

                $modules = $container->get(ModuleRepositoryInterface::class)->getByTypeAndLevel(
                    (int)$this->getColony()->getId(),
                    (int)$this->getModuleType(),
                    $this->getRump()->getShipRumpRole()->getId(),
                    range($min_level, $max_level)
                );
            }
            foreach ($modules as $obj) {
                $this->modules[$obj->getId()] = new ModuleSelectorWrapper($obj, $this->getBuildplan());
            }
        }
        return $this->modules;
    }

    public function hasModuleSelected(): ModuleSelectWrapper
    {
        return new ModuleSelectWrapper($this->buildplan);
    }

    public function getColony(): ColonyData
    {
        return $this->colony;
    }

    public function getBuildplan(): ?ShipBuildplanInterface
    {
        return $this->buildplan;
    }

}