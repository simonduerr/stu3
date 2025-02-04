<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\ShipRumpBuildingFunctionRepository")
 * @Table(
 *     name="stu_rumps_buildingfunction",
 *     indexes={
 *         @Index(name="ship_rump_idx", columns={"rump_id"}),
 *         @Index(name="building_function_idx", columns={"building_function"})
 *     }
 * )
 **/
class ShipRumpBuildingFunction implements ShipRumpBuildingFunctionInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $rump_id = 0;

    /** @Column(type="integer") * */
    private $building_function = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function getShipRumpId(): int
    {
        return $this->rump_id;
    }

    public function setShipRumpId(int $shipRumpId): ShipRumpBuildingFunctionInterface
    {
        $this->rump_id = $shipRumpId;

        return $this;
    }

    public function getBuildingFunction(): int
    {
        return $this->building_function;
    }

    public function setBuildingFunction(int $buildingFunction): ShipRumpBuildingFunctionInterface
    {
        $this->building_function = $buildingFunction;

        return $this;
    }
}
