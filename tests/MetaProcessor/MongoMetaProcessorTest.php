<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Tests\MetaProcessor;

use PHPUnit\Framework\TestCase;
use Renlife\ProfilerBundle\Processor\MongoProcessor;

class MongoMetaProcessorTest extends TestCase
{
    public function testInvoke(): void
    {
        $processor = new MongoProcessor();

        $meta = $processor([
            'other' => 'val',
        ]);

        $this->assertSame($meta, [
        ]);
    }
}
