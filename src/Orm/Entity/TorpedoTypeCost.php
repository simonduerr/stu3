<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity
 * @Table(
 *     name="stu_torpedo_cost"
 * )
 **/
class TorpedoTypeCost implements TorpedoTypeCostInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $torpedo_type_id = 0;

    /** @Column(type="integer") * */
    private $good_id = 0;

    /** @Column(type="integer") * */
    private $count = 0;

    /**
     * @ManyToOne(targetEntity="Stu\Orm\Entity\TorpedoType")
     * @JoinColumn(name="torpedo_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $torpedoType;

    /**
     * @ManyToOne(targetEntity="Stu\Orm\Entity\Commodity")
     * @JoinColumn(name="good_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $good;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTorpedoType(): TorpedoTypeInterface
    {
        return $this->torpedoType;
    }

    public function getTorpedoTypeId(): int
    {
        return $this->torpedo_type_id;
    }

    public function setTorpedoTypeId(int $torpedoTypeId): TorpedoTypeCostInterface
    {
        $this->torpedo_type_id = $torpedoTypeId;

        return $this;
    }

    public function getGoodId(): int
    {
        return $this->good_id;
    }

    public function setGoodId(int $goodId): TorpedoTypeCostInterface
    {
        $this->good_id = $goodId;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->count;
    }

    public function setAmount(int $amount): TorpedoTypeCostInterface
    {
        $this->count = $amount;

        return $this;
    }

    public function getGood(): CommodityInterface
    {
        return $this->good;
    }
}
