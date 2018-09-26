<?php

declare(strict_types=1);

namespace Renlife\Profiler\Processor;

use Renlife\Profiler\Profile;

interface ProcessorInterface
{
    /**
     * Updates profile data.
     *
     * @param Profile $profile
     */
    public function __invoke(Profile $profile): void;
}
