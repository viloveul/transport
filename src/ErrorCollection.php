<?php

namespace Viloveul\Transport;

use Exception;
use Viloveul\Transport\Contracts\ErrorCollection as IErrorCollection;

class ErrorCollection implements IErrorCollection
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param Exception $e
     */
    public function add(Exception $e): void
    {
        $this->errors[] = $e;
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->errors;
    }

    public function clear(): void
    {
        $this->errors = [];
    }

    public function count(): int
    {
        return count($this->errors);
    }

    public function top(): ?Exception
    {
        if (!empty($this->errors)) {
            return current($this->errors);
        }
        return null;
    }
}
