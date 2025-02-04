<?php

declare(strict_types=1);

namespace Stu\Module\Research;

use Stu\Orm\Entity\CommodityInterface;
use Stu\Orm\Entity\ResearchInterface;
use Stu\Orm\Repository\ResearchDependencyRepositoryInterface;
use Stu\Orm\Repository\ResearchedRepositoryInterface;
use UserData;

final class TalSelectedTech implements TalSelectedTechInterface
{

    private $research;

    private $currentUser;

    private $researchedRepository;

    private $researchDependencyRepository;

    private $state;

    private $excludes;

    private $dependencies;

    public function __construct(
        ResearchedRepositoryInterface $researchedRepository,
        ResearchDependencyRepositoryInterface $researchDependencyRepository,
        ResearchInterface $research,
        UserData $currentUser
    ) {
        $this->research = $research;
        $this->currentUser = $currentUser;
        $this->researchedRepository = $researchedRepository;
        $this->researchDependencyRepository = $researchDependencyRepository;
    }

    public function getId(): int
    {
        return $this->research->getId();
    }

    public function getName(): string
    {
        return $this->research->getName();
    }

    public function getDescription(): string
    {
        return $this->research->getDescription();
    }

    public function getPoints(): int
    {
        return $this->research->getPoints();
    }

    public function getGoodId(): int
    {
        return $this->research->getGoodId();
    }

    public function getUpperPlanetLimit(): int
    {
        return $this->research->getUpperPlanetLimit();
    }

    public function getUpperMoonLimit(): int
    {
        return $this->research->getUpperMoonLimit();
    }

    public function getGood(): CommodityInterface
    {
        return $this->research->getGood();
    }

    public function getResearchState()
    {
        if ($this->state === null) {
            $this->state = $this->researchedRepository->getFor(
                $this->research->getId(),
                (int) $this->currentUser->getId()
            );
        }
        return $this->state;
    }

    public function getExcludes(): array
    {
        if ($this->excludes === null) {
            $this->excludes = $this->researchDependencyRepository->getExcludesByResearch($this->research->getId());
        }
        return $this->excludes;
    }

    public function hasExcludes(): bool
    {
        return count($this->getExcludes()) > 0;
    }

    public function getPositiveDependencies(): array
    {
        if ($this->dependencies === null) {
            $this->dependencies = $this->researchDependencyRepository->getRequirementsByResearch(
                $this->research->getId()
            );
        }
        return $this->dependencies;
    }

    public function hasPositiveDependencies(): bool
    {
        return count($this->getPositiveDependencies()) > 0;
    }

    public function getDonePoints(): int
    {
        return $this->getPoints() - $this->getResearchState()->getActive();
    }

    public function isResearchFinished(): bool
    {
        return $this->getResearchState()->getFinished() > 0;
    }
}