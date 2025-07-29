<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="repo",
 *    indexes={
 *        @ORM\Index(name="IDX_REPO_NAME", columns={"name"}),
 *        @ORM\Index(name="IDX_REPO_URL", columns={"url"}),
 *        @ORM\Index(name="IDX_REPO_NAME_URL", columns={"name", "url"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="UNQ_REPO_ID", columns={"id"}),
 *        @ORM\UniqueConstraint(name="UNQ_REPO_URL", columns={"url"})
 *    }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Repo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint", options={"unsigned": true})
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public string $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    public string $url;

    public function __construct(int $id, string $name, string $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['name'],
            $data['url']
        );
    }
}
