<?php

declare(strict_types=1);

namespace Renlife\Profiler\Formatter;

use Renlife\Profiler\Profile;

interface FormatterInterface
{
    /**
     * Formats given profile and returns formatted data.
     *
     * @param Profile $profile
     *
     * @return mixed
     */
    public function __invoke(Profile $profile);
}
