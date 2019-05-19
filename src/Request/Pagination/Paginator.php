<?php

declare(strict_types=1);

namespace App\Request\Pagination;

use Webmozart\Assert\Assert;

final class Paginator implements \Iterator
{
    /** @var int */
    private $totalPagesCount;

    /** @var int */
    private $currentPage = 1;

    public function __construct(int $elementsCount, int $pageLength)
    {
        Assert::greaterThanEq($elementsCount, 0);
        Assert::greaterThanEq($pageLength, 1);

        if (0 === $elementsCount) {
            $this->totalPagesCount = 1;

            return;
        }

        $this->totalPagesCount = ceil($elementsCount / $pageLength);
    }

    public function current(): int
    {
        return $this->currentPage;
    }

    public function next(): void
    {
        ++$this->currentPage;
    }

    public function key(): int
    {
        return $this->current();
    }

    public function valid(): bool
    {
        return $this->currentPage <= $this->totalPagesCount;
    }

    public function rewind()
    {
        $this->currentPage = 1;
    }
}
