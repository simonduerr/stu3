<?php

declare(strict_types=1);

namespace Stu\Module\History\Lib;

use Stu\Orm\Repository\HistoryRepositoryInterface;

final class EntryCreator implements EntryCreatorInterface
{
    private $historyRepository;

    public function __construct(
        HistoryRepositoryInterface $historyRepository
    ) {
        $this->historyRepository = $historyRepository;
    }

    public function addShipEntry(
        string $text,
        int $userId = USER_NOONE
    ): void {
        $this->addEntry(HISTORY_SHIP, $text, $userId);
    }

    public function addAllianceEntry(
        string $text,
        int $userId = USER_NOONE
    ): void {
        $this->addEntry(HISTORY_ALLIANCE, $text, $userId);
    }

    private function addEntry(
        int $typeId,
        string $text,
        int $userId
    ): void {
        $entry = $this->historyRepository->prototype();
        $entry->setText($text);
        $entry->setUserId($userId);
        $entry->setDate(time());
        $entry->setType($typeId);

        $this->historyRepository->save($entry);
    }
}