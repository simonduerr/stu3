<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\PlanetFieldTypeBuildingRepository")
 * @Table(
 *     name="stu_field_build",
 *     indexes={@Index(name="type_building_idx", columns={"type", "buildings_id"})}
 * )
 **/
class PlanetFieldTypeBuilding implements PlanetFieldTypeBuildingInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $type = 0;

    /** @Column(type="integer") * */
    private $buildings_id = 0;

    /**
     * @ManyToOne(targetEntity="Stu\Orm\Entity\PlanetFieldType")
     * @JoinColumn(name="type_id", referencedColumnName="field_id", onDelete="CASCADE")
     */
    private $fieldType;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFieldTypeId(): int
    {
        return $this->type;
    }

    public function setFieldTypeId(int $fieldTypeId): PlanetFieldTypeBuildingInterface
    {
        $this->type = $fieldTypeId;

        return $this;
    }

    public function getBuildingId(): int
    {
        return $this->buildings_id;
    }

    public function setBuildingId(int $buildingId): PlanetFieldTypeBuildingInterface
    {
        $this->buildings_id = $buildingId;

        return $this;
    }

    public function getFieldType(): PlanetFieldTypeInterface
    {
        return $this->fieldType;
    }
}
