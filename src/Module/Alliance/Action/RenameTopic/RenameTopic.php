<?php

declare(strict_types=1);

namespace Stu\Module\Alliance\Action\RenameTopic;

use AccessViolation;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Alliance\View\Board\Board;
use Stu\Orm\Repository\AllianceBoardTopicRepositoryInterface;

final class RenameTopic implements ActionControllerInterface
{

    public const ACTION_IDENTIFIER = 'B_RENAME_TOPIC';

    private $renameTopicRequest;

    private $allianceBoardTopicRepository;

    public function __construct(
        RenameTopicRequestInterface $renameTopicRequest,
        AllianceBoardTopicRepositoryInterface $allianceBoardTopicRepository
    ) {
        $this->renameTopicRequest = $renameTopicRequest;
        $this->allianceBoardTopicRepository = $allianceBoardTopicRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $alliance = $game->getUser()->getAlliance();

        $name = $this->renameTopicRequest->getTitle();

        $topic = $this->allianceBoardTopicRepository->find($this->renameTopicRequest->getTopicId());
        if ($topic === null || $topic->getAllianceId() != $alliance->getId()) {
            throw new AccessViolation();
        }

        $game->setView(Board::VIEW_IDENTIFIER);

        if (mb_strlen($name) < 1) {
            $game->addInformation(_('Es wurde kein Themenname eingegeben'));
            return;
        }

        $topic->setName($name);
        $topic->save();

        $game->addInformation(_('Das Thema wurde umbenannt'));
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
