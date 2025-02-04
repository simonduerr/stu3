<?php

declare(strict_types=1);

namespace Stu\Module\PlayerProfile\View\Overview;

use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Orm\Repository\RpgPlotMemberRepositoryInterface;
use Stu\Orm\Repository\UserProfileVisitorRepositoryInterface;
use User;

final class Overview implements ViewControllerInterface
{
    private $overviewRequest;

    private $userProfileVisitorRepository;

    private $rpgPlotMemberRepository;

    public function __construct(
        OverviewRequestInterface $overviewRequest,
        UserProfileVisitorRepositoryInterface $userProfileVisitorRepository,
        RpgPlotMemberRepositoryInterface $rpgPlotMemberRepository
    ) {
        $this->overviewRequest = $overviewRequest;
        $this->userProfileVisitorRepository = $userProfileVisitorRepository;
        $this->rpgPlotMemberRepository = $rpgPlotMemberRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        $profile = new User($this->overviewRequest->getProfileId());

        $profileId = (int) $profile->getId();

        if ($profileId !== $userId && $this->userProfileVisitorRepository->isVisitRegistered($profileId, $userId) === false) {
            $obj = $this->userProfileVisitorRepository->prototype()
                ->setProfileUserId($profileId)
                ->setUserId($userId)
                ->setDate(time());

            $this->userProfileVisitorRepository->save($obj);
        }

        $game->appendNavigationPart(
            sprintf(
                'userprofile.php?uid=%d',
                $profile->getId()
            ),
            _('Spielerprofil')
        );
        $game->setPageTitle(_('/ Spielerprofile'));
        $game->setTemplateFile('html/userprofile.xhtml');
        $game->setTemplateVar('PROFILE', $profile);
        $game->setTemplateVar(
            'RPG_PLOTS',
            $this->rpgPlotMemberRepository->getByUser($profileId)
        );
    }
}