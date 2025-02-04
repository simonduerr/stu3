<?php

declare(strict_types=1);

namespace Stu\Module\Communication\Action\DeleteKnPost;

use AccessViolation;
use Stu\Module\Communication\Action\EditKnPost\EditKnPost;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Orm\Entity\KnPostInterface;
use Stu\Orm\Repository\KnPostRepositoryInterface;

final class DeleteKnPost implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_DEL_KN';

    private $deleteKnPostRequest;

    private $knPostRepository;

    public function __construct(
        DeleteKnPostRequestInterface $deleteKnPostRequest,
        KnPostRepositoryInterface $knPostRepository
    ) {
        $this->deleteKnPostRequest = $deleteKnPostRequest;
        $this->knPostRepository = $knPostRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $userId = $game->getUser()->getId();

        /** @var KnPostInterface $post */
        $post = $this->knPostRepository->find($this->deleteKnPostRequest->getPostId());
        if ($post === null || $post->getUserId() != $userId) {
            throw new AccessViolation();
        }
        if ($post->getDate() < time() - EditKnPost::EDIT_TIME) {
            $game->addInformation(_('Dieser Beitrag kann nicht editiert werden'));
            return;
        }

        // @todo foreign key
        //KnComment::truncate('WHERE post_id=' . $post->getId());

        $this->knPostRepository->delete($post);

        $game->addInformation(_('Der Beitrag wurde gelöscht'));
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
