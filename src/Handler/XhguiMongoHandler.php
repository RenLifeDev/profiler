<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Handler;

use Renlife\ProfilerBundle\Profiler\Profile;

/**
 * Writes profiling data to Mongo in Xhgui format.
 */
class XhguiMongoHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     */
    public function write(Profile $result): void
    {
        // TODO: Implement write() method.
    }
}
