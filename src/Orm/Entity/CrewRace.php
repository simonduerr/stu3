<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\CrewRaceRepository")
 * @Table(
 *     name="stu_crew_race",
 *     indexes={
 * })
 **/
class CrewRace implements CrewRaceInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $faction_id = 0;

    /** @Column(type="string") */
    private $description = '';

    /** @Column(type="smallint") */
    private $chance = 0;

    /** @Column(type="smallint") */
    private $maleratio = 0;

    /** @Column(type="string") */
    private $define = '';

    /**
     * @ManyToOne(targetEntity="Stu\Orm\Entity\Faction")
     * @JoinColumn(name="faction_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $faction;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFactionId(): int
    {
        return $this->faction_id;
    }

    public function setFactionId(int $factionId): CrewRaceInterface
    {
        $this->faction_id = $factionId;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): CrewRaceInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getChance(): int
    {
        return $this->chance;
    }

    public function setChance(int $chance): CrewRaceInterface
    {
        $this->chance = $chance;

        return $this;
    }

    public function getMaleRatio(): int
    {
        return $this->maleratio;
    }

    public function setMaleRatio(int $maleRatio): CrewRaceInterface
    {
        $this->maleratio = $maleRatio;

        return $this;
    }

    public function getGfxPath(): string
    {
        return $this->define;
    }

    public function setGfxPath(string $gfxPath): CrewRaceInterface
    {
        $this->define = $gfxPath;

        return $this;
    }

    public function getFaction(): FactionInterface
    {
        return $this->faction;
    }

    public function setFaction(FactionInterface $faction): CrewRaceInterface
    {
        $this->faction = $faction;

        return $this;
    }
}
