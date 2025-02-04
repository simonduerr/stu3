<?php

declare(strict_types=1);

namespace Stu\Module\Alliance\Action\AcceptOffer;

use AccessViolation;
use Stu\Module\Control\ActionControllerInterface;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\History\Lib\EntryCreatorInterface;
use Stu\Orm\Repository\AllianceRelationRepositoryInterface;

final class AcceptOffer implements ActionControllerInterface
{
    public const ACTION_IDENTIFIER = 'B_ACCEPT_OFFER';

    private $acceptOfferRequest;

    private $entryCreator;

    private $allianceRelationRepository;

    public function __construct(
        AcceptOfferRequestInterface $acceptOfferRequest,
        EntryCreatorInterface $entryCreator,
        AllianceRelationRepositoryInterface $allianceRelationRepository
    ) {
        $this->acceptOfferRequest = $acceptOfferRequest;
        $this->entryCreator = $entryCreator;
        $this->allianceRelationRepository = $allianceRelationRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        $user = $game->getUser();
        $alliance = $user->getAlliance();
        $userId = $user->getId();
        $allianceId = $alliance->getId();

        $relation = $this->allianceRelationRepository->find($this->acceptOfferRequest->getRelationId());

        if (!$alliance->currentUserIsDiplomatic()) {
            throw new AccessViolation();
        }

        if ($relation === null || $relation->getRecipientId() != $allianceId) {
            return;
        }
        if (!$relation->isPending()) {
            return;
        }
        $rel = $this->allianceRelationRepository->getActiveByAlliancePair($relation->getAllianceId(), $relation->getRecipientId());
        if ($rel) {
            $this->allianceRelationRepository->delete($rel);
        }
        $relation->setDate(time());

        $this->allianceRelationRepository->save($relation);

        $text = sprintf(
            _("%s abgeschlossen!\nDie Allianz %s hat hat das Angebot angenommen"),
            $relation->getTypeDescription(),
            $alliance->getNameWithoutMarkup()
        );

        $this->entryCreator->addAllianceEntry(
            sprintf(
                _('Die Allianzen %s und %s sind ein %s eingegangen'),
                $relation->getAlliance()->getName(),
                $relation->getOpponent()->getName(),
                $relation->getTypeDescription()
            ),
            $userId
        );

        if ($relation->getAllianceId() == $allianceId) {
            $relation->getOpponent()->sendMessage($text);
        } else {
            $relation->getAlliance()->sendMessage($text);
        }

        $game->addInformation(_('Das Angebot wurden angemommen'));
    }

    public function performSessionCheck(): bool
    {
        return true;
    }
}
