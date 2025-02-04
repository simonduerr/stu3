<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity
 * @Table(
 *     name="stu_database_types",
 *     options={"engine":"InnoDB"}
 * )
 * @Entity(repositoryClass="Stu\Orm\Repository\DatabaseTypeRepository")
 **/
class DatabaseType implements DatabaseTypeInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="string") * */
    private $description;

    /** @Column(type="string") * */
    private $macro;

    public function getId(): int
    {
        return $this->id;
    }

    public function setDescription(string $description): DatabaseTypeInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setMacro(string $macro): DatabaseTypeInterface
    {
        $this->macro = $macro;

        return $this;
    }

    public function getMacro(): string
    {
        return $this->macro;
    }
}
