<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\ShipRumpSpecialRepository")
 * @Table(
 *     name="stu_rumps_specials",
 *     indexes={
 *         @Index(name="ship_rump_idx", columns={"rumps_id"})
 *     }
 * )
 **/
class ShipRumpSpecial implements ShipRumpSpecialInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $rumps_id = 0;

    /** @Column(type="integer") * */
    private $special = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function getShipRumpId(): int
    {
        return $this->rumps_id;
    }

    public function setShipRumpId(int $shipRumpId): ShipRumpSpecialInterface
    {
        $this->rumps_id = $shipRumpId;

        return $this;
    }

    public function getSpecialId(): int
    {
        return $this->special;
    }

    public function setSpecialId(int $specialId): ShipRumpSpecialInterface
    {
        $this->special = $specialId;

        return $this;
    }
}
