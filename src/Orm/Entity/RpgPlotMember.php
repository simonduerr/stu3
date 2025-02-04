<?php

declare(strict_types=1);

namespace Stu\Orm\Entity;

use User;

/**
 * @Entity(repositoryClass="Stu\Orm\Repository\RpgPlotMemberRepository")
 * @Table(
 *     name="stu_plots_members",
 *     uniqueConstraints={@UniqueConstraint(name="plot_user_idx", columns={"plot_id", "user_id"})}
 * )
 **/
class RpgPlotMember implements RpgPlotMemberInterface
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="integer") * */
    private $plot_id = 0;

    /** @Column(type="integer") * */
    private $user_id = 0;

    /**
     * @ManyToOne(targetEntity="RpgPlot", inversedBy="members")
     * @JoinColumn(name="plot_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $rpgPlot;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlotId(): int
    {
        return $this->plot_id;
    }

    public function setPlotId(int $plotId): RpgPlotMemberInterface
    {
        $this->plot_id = $plotId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): RpgPlotMemberInterface
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getRpgPlot(): RpgPlotInterface
    {
        return $this->rpgPlot;
    }

    public function setRpgPlot(RpgPlotInterface $rpgPlot): RpgPlotMemberInterface
    {
        $this->rpgPlot = $rpgPlot;

        return $this;
    }

    public function getUser(): User
    {
        // @todo refactor
        return new User($this->getUserId());
    }
}
