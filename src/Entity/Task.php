<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Task
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column
     */
    private $username;

    /**
     * @var string|null
     * @ORM\Column
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column
     */
    private $title;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isDone = false;

    public function getId()
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): void
    {
        $this->isDone = $isDone;
    }
}
