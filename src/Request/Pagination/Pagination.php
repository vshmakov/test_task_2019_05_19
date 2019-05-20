<?php

declare(strict_types=1);

namespace App\Request\Pagination;

use Symfony\Component\Validator\Constraints as Assert;

final class Pagination
{
    /**
     * @var int|null
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\NotNull
     */
    private $page = 1;

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    public function getLength(): int
    {
        return 3;
    }

    public function getStart(): int
    {
        return ($this->getPage() - 1) * $this->getLength();
    }
}
