<?php

namespace Viloveul\Transport\Contracts;

use Interop\Queue\Context;

interface Passenger
{
    /**
     * @return mixed
     */
    public function connection(): string;

    public function handle(): void;

    public function data(): string;

    public function route(): string;

    public function point(): string;

    /**
     * @param $name
     * @param $default
     */
    public function getAttribute($name, $default = null);

    public function initialize(): void;

    public function run(): void;

    /**
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value): void;

    /**
     * @param Context $context
     */
    public function with(Context $context): void;
}
