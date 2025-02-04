<?php

namespace Stu\Orm\Entity;

use Doctrine\Common\Collections\Collection;

interface KnPostInterface
{
    public function getId(): int;

    public function getTitle(): string;

    public function setTitle(string $title): KnPostInterface;

    public function getText(): string;

    public function setText(string $text): KnPostInterface;

    public function getDate(): int;

    public function setDate(int $date): KnPostInterface;

    public function getUsername(): string;

    public function setUsername(string $username): KnPostInterface;

    public function getUserId(): int;

    public function setUserId(int $userId): KnPostInterface;

    public function getEditDate(): int;

    public function setEditDate(int $editDate): KnPostInterface;

    public function getPlotId(): ?int;

    public function setPlotId(int $plotId): KnPostInterface;

    public function getRpgPlot(): ?RpgPlotInterface;

    public function setRpgPlot(?RpgPlotInterface $rpgPlot): KnPostInterface;

    /**
     * @return KnCommentInterface[]
     */
    public function getComments(): Collection;
}