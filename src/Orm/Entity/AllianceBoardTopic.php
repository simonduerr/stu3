<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Stu\Module\Alliance\View\Topic\Topic;
use Stu\Orm\Repository\AllianceBoardPostRepositoryInterface;
use User;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\AllianceBoardTopicRepository")
 * @Table(
 *     name="stu_alliance_topics",
 *     indexes={
 *         @Index(name="recent_topics_idx", columns={"alliance_id","last_post_date"}),
 *         @Index(name="ordered_topics_idx", columns={"board_id","last_post_date"})
 *     }
 * )
 **/
class AllianceBoardTopic implements AllianceBoardTopicInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $board_id = 0;

    /** @Column(type="integer") * */
    private $alliance_id = 0;

    /** @Column(type="string") */
    private $name = '';

    /** @Column(type="integer") */
    private $last_post_date = 0;

    /** @Column(type="integer") */
    private $user_id = 0;

    /** @Column(type="boolean") */
    private $sticky = false;

    /**
     * @ManyToOne(targetEntity="AllianceBoard", inversedBy="topics")
     * @JoinColumn(name="board_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $board;

    /**
     * @OneToMany(targetEntity="AllianceBoardPost", mappedBy="topic")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBoardId(): int
    {
        return $this->board_id;
    }

    public function setBoardId(int $boardId): AllianceBoardTopicInterface
    {
        $this->board_id = $boardId;

        return $this;
    }

    public function getAllianceId(): int
    {
        return $this->alliance_id;
    }

    public function setAllianceId(int $allianceId): AllianceBoardTopicInterface
    {
        $this->alliance_id = $allianceId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AllianceBoardTopicInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getLastPostDate(): int
    {
        return $this->last_post_date;
    }

    public function setLastPostDate(int $lastPostDate): AllianceBoardTopicInterface
    {
        $this->last_post_date;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): AllianceBoardTopicInterface
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getSticky(): bool
    {
        return $this->sticky;
    }

    public function setSticky(bool $sticky): AllianceBoardTopicInterface
    {
        $this->sticky = $sticky;

        return $this;
    }

    public function getUser(): User
    {
        return new User($this->getUserId());
    }

    public function getPages(): ?array
    {
        $postCount = count($this->posts);

        if ($postCount <= Topic::ALLIANCEBOARDLIMITER) {
            return null;
        }

        $pages = [];
        for ($i = 1; $i <= ceil($postCount / Topic::ALLIANCEBOARDLIMITER); $i++) {
            $pages[$i] = ($i - 1) * Topic::ALLIANCEBOARDLIMITER;
        }
        return $pages;
    }

    public function getPostCount(): int
    {
        return count($this->posts);
    }

    public function getLatestPost(): ?AllianceBoardPostInterface
    {
        global $container;

        return $container->get(AllianceBoardPostRepositoryInterface::class)->getRecentByTopic((int)$this->getId());
    }

    public function getBoard(): AllianceBoardInterface
    {
        return $this->board;
    }

    public function setBoard(AllianceBoardInterface $board): AllianceBoardTopicInterface
    {
        $this->board = $board;

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
