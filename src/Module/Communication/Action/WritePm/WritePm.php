<?php

declare(strict_types=1);

namespace Stu\Module\Communication\Action\WritePm;

use PM;
use PMCategory;
use PMData;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Communication\View\ShowPmCategory\ShowPmCategory;
use Stu\Orm\Repository\IgnoreListRepositoryInterface;
use User;

final class WritePm implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_WRITE_PM';

    private $writePmRequest;

    private $ignoreListRepository;

    public function __construct(
        WritePmRequestInterface $writePmRequest,
        IgnoreListRepositoryInterface $ignoreListRepository
    ) {
        $this->writePmRequest = $writePmRequest;
        $this->ignoreListRepository = $ignoreListRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $text = $this->writePmRequest->getText();
        $recipientId = $this->writePmRequest->getRecipientId();
        $userId = $game->getUser()->getId();

        $recipient = User::getUserById($recipientId);
        if (!$recipient) {
            $game->addInformation("Dieser Siedler existiert nicht");
            return;
        }
        if ($recipient->getId() == $userId) {
            $game->addInformation("Du kannst keine Nachricht an Dich selbst schreiben");
            return;
        }
        if ($this->ignoreListRepository->exists((int) $recipient->getId(), $userId)) {
            $game->addInformation("Der Siedler ignoriert Dich");
            return;
        }

        if (strlen($text) < 5) {
            $game->addInformation("Der Text ist zu kurz");
            return;
        }
        $cat = PMCategory::getOrGenSpecialCategory(PM_SPECIAL_MAIN, $recipient->getId());

        $pm = new PMData();
        $pm->setText($text);
        $pm->setRecipientId($recipient->getId());
        $pm->setSenderId($userId);
        $pm->setDate(time());
        $pm->setCategoryId($cat->getId());
        $pm->copyPM();
        $pm->save();

        $repid = $this->writePmRequest->getReplyPmId();
        $replyPm = PM::getPMById($repid);
        if ($replyPm && $replyPm->getRecipientId() == $userId) {
            $replyPm->setReplied(1);
            $replyPm->save();
        }

        $game->addInformation(_('Die Nachricht wurde abgeschickt'));

        $game->setView(ShowPmCategory::VIEW_IDENTIFIER);
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
