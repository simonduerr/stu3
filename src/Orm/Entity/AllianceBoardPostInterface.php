<?php

namespace Stu\Orm\Entity;

use User;

interface AllianceBoardPostInterface
{
    public function getId(): int;

    public function getTopicId(): int;

    public function setTopicId(int $topicId): AllianceBoardPostInterface;

    public function getBoardId(): int;

    public function setBoardId(int $boardId): AllianceBoardPostInterface;

    public function getAllianceId(): int;

    public function setAllianceId(int $allianceId): AllianceBoardPostInterface;

    public function getName(): string;

    public function setName(string $name): AllianceBoardPostInterface;

    public function getDate(): int;

    public function setDate(int $date): AllianceBoardPostInterface;

    public function getText(): string;

    public function setText(string $text): AllianceBoardPostInterface;

    public function getUserId(): int;

    public function setUserId(int $userId): AllianceBoardPostInterface;

    public function getUser(): User;

    public function getTopic(): AllianceBoardTopicInterface;

    public function setTopic(AllianceBoardTopicInterface $topic): AllianceBoardPostInterface;

    public function getBoard(): AllianceBoardInterface;

    public function setBoard(AllianceBoardInterface $board): AllianceBoardPostInterface;
}