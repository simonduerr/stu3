<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\TorpedoTypeRepository")
 * @Table(
 *     name="stu_torpedo_types",
 *     indexes={
 *          @Index(name="research_idx", columns={"research_id"}),
 *          @Index(name="level_idx", columns={"level"})
 * })
 **/
class TorpedoType implements TorpedoTypeInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="string") * */
    private $name = '';

    /** @Column(type="integer") * */
    private $base_damage = 0;

    /** @Column(type="integer") * */
    private $critical_chance = 0;

    /** @Column(type="integer") * */
    private $hit_factor = 0;

    /** @Column(type="integer") * */
    private $hull_damage_factor = 0;

    /** @Column(type="integer") * */
    private $shield_damage_factor = 0;

    /** @Column(type="integer") * */
    private $variance = 0;

    /** @Column(type="integer") * */
    private $good_id = 0;

    /** @Column(type="integer") * */
    private $level = 0;

    /** @Column(type="integer") * */
    private $research_id = 0;

    /** @Column(type="integer") * */
    private $ecost = 0;

    /** @Column(type="integer") * */
    private $amount = 0;

    /**
     * @OneToMany(targetEntity="TorpedoTypeCost", mappedBy="torpedoType")
     */
    private $productionCosts;

    public function __construct() {
        $this->productionCosts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): TorpedoTypeInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getBaseDamage(): int
    {
        return $this->base_damage;
    }

    public function setBaseDamage(int $baseDamage): TorpedoTypeInterface
    {
        $this->base_damage = $baseDamage;

        return $this;
    }

    public function getCriticalChance(): int
    {
        return $this->critical_chance;
    }

    public function setCriticalChance(int $criticalChance): TorpedoTypeInterface
    {
        $this->critical_chance = $criticalChance;

        return $this;
    }

    public function getHitFactor(): int
    {
        return $this->hit_factor;
    }

    public function setHitFactor(int $hitFactor): TorpedoTypeInterface
    {
        $this->hit_factor = $hitFactor;

        return $this;
    }

    public function getHullDamageFactor(): int
    {
        return $this->hull_damage_factor;
    }

    public function setHullDamageFactor(int $hullDamageFactor): TorpedoTypeInterface
    {
        $this->hull_damage_factor = $hullDamageFactor;

        return $this;
    }

    public function getShieldDamageFactor(): int
    {
        return $this->shield_damage_factor;
    }

    public function setShieldDamageFactor(int $shieldDamageFactor): TorpedoTypeInterface
    {
        $this->shield_damage_factor = $shieldDamageFactor;

        return $this;
    }

    public function getVariance(): int
    {
        return $this->variance;
    }

    public function setVariance(int $variance): TorpedoTypeInterface
    {
        $this->variance = $variance;

        return $this;
    }

    public function getGoodId(): int
    {
        return $this->good_id;
    }

    public function setGoodId(int $goodId): TorpedoTypeInterface
    {
        $this->good_id = $goodId;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): TorpedoTypeInterface
    {
        $this->level = $level;

        return $this;
    }

    public function getResearchId(): int
    {
        return $this->research_id;
    }

    public function setResearchId(int $researchId): TorpedoTypeInterface
    {
        $this->research_id = $researchId;

        return $this;
    }

    public function getEnergyCost(): int
    {
        return $this->ecost;
    }

    public function setEnergyCost(int $energyCost): TorpedoTypeInterface
    {
        $this->ecost = $energyCost;

        return $this;
    }

    public function getProductionAmount(): int
    {
        return $this->amount;
    }

    public function setProductionAmount(int $productionAmount): TorpedoTypeInterface
    {
        $this->amount = $productionAmount;

        return $this;
    }

    /**
     * @return TorpedoTypeCostInterface[]
     */
    public function getProductionCosts(): Collection
    {
        return $this->productionCosts;
    }
}
