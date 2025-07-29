<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\LocalEventType;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`event`",
 *    indexes={
 *        @ORM\Index(name="IDX_EVENT_TYPE", columns={"type"}),
 *        @ORM\Index(name="IDX_EVENT_CREATED_AT", columns={"create_at"}),
 *        @ORM\Index(name="IDX_EVENT_ACTOR", columns={"actor_id"}),
 *        @ORM\Index(name="IDX_EVENT_REPO", columns={"repo_id"}),
 *        @ORM\Index(name="IDX_EVENT_TYPE_CREATED", columns={"type", "create_at"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="UNQ_EVENT_ID", columns={"id"})
 *    }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint", options={"unsigned": true})
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private string $type;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true, "default": 1})
     */
    private int $count = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Actor", cascade={"persist", "merge"}, fetch="EAGER")
     * @ORM\JoinColumn(name="actor_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Actor $actor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Repo", cascade={"persist", "merge"}, fetch="EAGER")
     * @ORM\JoinColumn(name="repo_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Repo $repo;

    /**
     * @ORM\Column(type="json", nullable=false, options={"jsonb": true})
     * @var array<string, mixed>
     */
    private array $payload;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private \DateTimeImmutable $createAt;

    /**
     * @ORM\Column(type="text", nullable=true, length=65535)
     */
    private ?string $comment;

    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(int $id, string $type, Actor $actor, Repo $repo, array $payload, \DateTimeImmutable $createAt, ?string $comment)
    {
        $this->id = $id;
        LocalEventType::isValid($type);
        $this->type = $type;
        $this->actor = $actor;
        $this->repo = $repo;
        $this->payload = $payload;
        $this->createAt = $createAt;
        $this->comment = $comment;

        if ($type === LocalEventType::COMMIT->value) {
            $this->count = $payload['size'] ?? 1;
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function actor(): Actor
    {
        return $this->actor;
    }

    public function repo(): Repo
    {
        return $this->repo;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    public function createAt(): \DateTimeImmutable
    {
        return $this->createAt;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
