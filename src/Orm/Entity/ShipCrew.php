<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\ShipCrewRepository")
 * @Table(
 *     name="stu_ships_crew",
 *     indexes={
 *         @Index(name="ship_idx", columns={"ships_id"}),
 *         @Index(name="user_idx", columns={"user_id"})
 *     }
 * )
 **/
class ShipCrew implements ShipCrewInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $ships_id = 0;

    /** @Column(type="integer") * */
    private $crew_id = 0;

    /** @Column(type="smallint") * */
    private $slot = 0;

    /** @Column(type="integer") * */
    private $user_id = 0;

    /**
     * @ManyToOne(targetEntity="Crew")
     * @JoinColumn(name="crew_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $crew;

    public function getId(): int
    {
        return $this->id;
    }

    public function getShidId(): int
    {
        return $this->ships_id;
    }

    public function setShipId(int $shipId): ShipCrewInterface
    {
        $this->ships_id = $shipId;

        return $this;
    }

    public function getCrewId(): int
    {
        return $this->crew_id;
    }

    public function setCrewId(int $crewId): ShipCrewInterface
    {
        $this->crew_id = $crewId;

        return $this;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function setSlot(int $slot): ShipCrewInterface
    {
        $this->slot = $slot;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): ShipCrewInterface
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getCrew(): CrewInterface
    {
        return $this->crew;
    }

    public function setCrew(CrewInterface $crew): ShipCrewInterface
    {
        $this->crew = $crew;

        return $this;
    }
}
