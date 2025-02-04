<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="stu_database_categories")
 * @Entity(repositoryClass="Stu\Orm\Repository\DatabaseCategoryRepository")
 **/
class DatabaseCategory implements DatabaseCategoryInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="string") * */
    private $description;

    /** @Column(type="integer") * */
    private $points;

    /** @Column(type="integer") * */
    private $type;

    /** @Column(type="integer") * */
    private $sort;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Stu\Orm\Entity\DatabaseEntry", mappedBy="category")
     */
    private $entries;

    public function getId(): int
    {
        return $this->id;
    }

    public function setDescription(string $description): DatabaseCategoryInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setPoints(int $points): DatabaseCategoryInterface
    {
        $this->points = $points;

        return $this;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setType(int $type): DatabaseCategoryInterface
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setSort(int $sort): DatabaseCategoryInterface
    {
        $this->sort = $sort;

        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function getEntries(): array
    {
        return $this->entries->toArray();
    }
}
