<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\ActivityId;
use App\Domain\ValueObject\ActivityType;
use App\Domain\ValueObject\UserId;
use DateTimeImmutable;

final class Activity
{
    private DateTimeImmutable $createdAt;

    private function __construct(
        private ActivityId $id,
        private UserId $userId,
        private ActivityType $type,
        private ?string $page,
        private ?string $button,
        private string $ipAddress,
        private ?string $userAgent
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(
        ActivityId $id,
        UserId $userId,
        ActivityType $type,
        ?string $page,
        ?string $button,
        string $ipAddress,
        ?string $userAgent
    ): self {
        return new self(
            $id,
            $userId,
            $type,
            $page,
            $button,
            $ipAddress,
            $userAgent
        );
    }

    public static function fromDatabase(
        ActivityId $id,
        UserId $userId,
        ActivityType $type,
        ?string $page,
        ?string $button,
        string $ipAddress,
        ?string $userAgent,
        DateTimeImmutable $createdAt
    ): self {
        $activity = new self($id, $userId, $type, $page, $button, $ipAddress, $userAgent);
        $activity->createdAt = $createdAt;
        return $activity;
    }

    public function getId(): ActivityId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getType(): ActivityType
    {
        return $this->type;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function getButton(): ?string
    {
        return $this->button;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
