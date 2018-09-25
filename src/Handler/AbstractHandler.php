<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Handler;

use Renlife\ProfilerBundle\Profiler\Profile;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(Profile $result): void
    {
    }
}
