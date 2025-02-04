<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

use User;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\AllianceBoardPostRepository")
 * @Table(
 *     name="stu_alliance_posts",
 *     indexes={
 *         @Index(name="topic_date_idx", columns={"topic_id","date"}),
 *         @Index(name="board_date_idx", columns={"board_id","date"})
 *     }
 * )
 **/
class AllianceBoardPost implements AllianceBoardPostInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $topic_id = 0;

    /** @Column(type="integer") * */
    private $board_id = 0;

    /** @Column(type="integer") * */
    private $alliance_id = 0;

    /** @Column(type="string") */
    private $name = '';

    /** @Column(type="integer") */
    private $date = 0;

    /** @Column(type="text") */
    private $text = '';

    /** @Column(type="integer") */
    private $user_id = 0;

    /**
     * @ManyToOne(targetEntity="AllianceBoardTopic", inversedBy="posts")
     * @JoinColumn(name="topic_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $topic;

    /**
     * @ManyToOne(targetEntity="AllianceBoard", inversedBy="posts")
     * @JoinColumn(name="board_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $board;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTopicId(): int
    {
        return $this->topic_id;
    }

    public function setTopicId(int $topicId): AllianceBoardPostInterface
    {
        $this->topic_id = $topicId;

        return $this;
    }

    public function getBoardId(): int
    {
        return $this->board_id;
    }

    public function setBoardId(int $boardId): AllianceBoardPostInterface
    {
        $this->board_id = $boardId;

        return $this;
    }

    public function getAllianceId(): int
    {
        return $this->alliance_id;
    }

    public function setAllianceId(int $allianceId): AllianceBoardPostInterface
    {
        $this->alliance_id = $allianceId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AllianceBoardPostInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function setDate(int $date): AllianceBoardPostInterface
    {
        $this->date = $date;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): AllianceBoardPostInterface
    {
        $this->text = $text;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): AllianceBoardPostInterface
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getUser(): User
    {
        return new User($this->getUserId());
    }

    public function getTopic(): AllianceBoardTopicInterface
    {
        return $this->topic;
    }

    public function setTopic(AllianceBoardTopicInterface $topic): AllianceBoardPostInterface
    {
        $this->topic = $topic;

        return $this;
    }

    public function getBoard(): AllianceBoardInterface
    {
        return $this->board;
    }

    public function setBoard(AllianceBoardInterface $board): AllianceBoardPostInterface
    {
        $this->board = $board;

        return $this;
    }
}
