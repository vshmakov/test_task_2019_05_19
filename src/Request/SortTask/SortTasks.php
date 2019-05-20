<?php

declare(strict_types=1);

namespace App\Request\SortTask;

use Symfony\Component\Validator\Constraints as Assert;

final class SortTasks
{
    /**
     * @var string|null
     * @Assert\NotNull
     */
    private $property;

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(?string $property): void
    {
        $this->property = $property;
    }
}
