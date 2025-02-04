<?php

namespace Stu\Orm\Entity;

use User;

interface TradeShoutboxInterface
{
    public function getId(): int;

    public function getUserId(): int;

    public function setUserId(int $userId): TradeShoutboxInterface;

    public function getTradeNetworkId(): int;

    public function setTradeNetworkId(int $tradeNetworkId): TradeShoutboxInterface;

    public function getDate(): int;

    public function setDate(int $date): TradeShoutboxInterface;

    public function getMessage(): string;

    public function setMessage(string $message): TradeShoutboxInterface;

    public function getUser(): User;
}