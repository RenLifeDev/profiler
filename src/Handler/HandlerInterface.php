<?php

declare(strict_types=1);

namespace Renlife\Profiler\Handler;

use Renlife\Profiler\Formatter\FormatterInterface;
use Renlife\Profiler\Processor\ProcessorInterface;
use Renlife\Profiler\Profile;

/**
 * HandlerInterface accepts Profile object and process its data.
 */
interface HandlerInterface
{
    /**
     * @param Profile $result
     */
    public function handle(Profile $result): void;

    /**
     * @param callable|ProcessorInterface $callable
     *
     * @return HandlerInterface
     */
    public function pushProcessor(callable $callable): self;

    /**
     * @return callable|ProcessorInterface
     */
    public function popProcessor(): callable;

    /**
     * @param callable|FormatterInterface|null $formatter
     *
     * @return HandlerInterface
     */
    public function setFormatter(?callable $formatter): self;

    /**
     * @return callable|FormatterInterface|null
     */
    public function getFormatter(): ?callable;
}
