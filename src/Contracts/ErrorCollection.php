<?php

namespace Viloveul\Transport\Contracts;

use Countable;
use Exception;

interface ErrorCollection extends Countable
{
    /**
     * @param Exception $e
     */
    public function add(Exception $e): void;

    public function all(): array;

    public function clear(): void;

    public function top(): ?Exception;
}
