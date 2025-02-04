<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\KnCommentRepository")
 * @Table(
 *     name="stu_kn_comments",
 *     indexes={
 *         @Index(name="post_idx", columns={"post_id"}),
 *         @Index(name="user_idx", columns={"user_id"})
 *     }
 * )
 **/
class KnComment implements KnCommentInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $post_id = 0;

    /** @Column(type="integer") * */
    private $user_id = 0;

    /** @Column(type="string") */
    private $username = '';

    /** @Column(type="string") */
    private $text = '';

    /** @Column(type="integer") */
    private $date = 0;

    /**
     * @ManyToOne(targetEntity="KnPost")
     * @JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function setPostId(int $postId): KnCommentInterface
    {
        $this->post_id = $postId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): KnCommentInterface
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): KnCommentInterface
    {
        $this->username = $username;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): KnCommentInterface
    {
        $this->text = $text;

        return $this;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function setDate(int $date): KnCommentInterface
    {
        $this->date = $date;

        return $this;
    }

    public function getPosting(): KnPostInterface
    {
        return $this->post;
    }

    public function setPosting(KnPostInterface $post): KnCommentInterface
    {
        $this->post = $post;

        return $this;
    }
}
