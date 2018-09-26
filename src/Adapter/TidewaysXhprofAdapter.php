<?php

declare(strict_types=1);

namespace Renlife\Profiler\Adapter;

/**
 * TidewaysXhprofAdapter works with https://github.com/tideways/php-xhprof-extension.
 */
class TidewaysXhprofAdapter implements AdapterInterface
{
    /**
     * @var int
     */
    private $flags;

    /**
     * TidewaysXhprofAdapter constructor.
     *
     * @param int $flags
     */
    public function __construct(int $flags = 0)
    {
        $this->flags = $flags;
    }

    /**
     * {@inheritdoc}
     */
    public function start(): void
    {
        tideways_xhprof_enable($this->flags);
    }

    /**
     * {@inheritdoc}
     */
    public function stop(): array
    {
        return tideways_xhprof_disable();
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return \extension_loaded('tideways_xhprof');
    }
}
