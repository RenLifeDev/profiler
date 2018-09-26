<?php

declare(strict_types=1);

namespace Renlife\Profiler\Adapter;

/**
 * AdapterInterface is an interface for classes that implements functions control of low level profilers such as
 * Tideways Xhprof.
 */
interface AdapterInterface
{
    /**
     * Start profiling.
     */
    public function start(): void;

    /**
     * Stops profiling and returns an array of records.
     *
     * @return array
     */
    public function stop(): array;

    /**
     * Checks whether the adapter is enabled in PHP.
     *
     * @return bool
     */
    public function isEnabled(): bool;
}
