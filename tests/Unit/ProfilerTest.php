<?php

declare(strict_types=1);

namespace Renlife\Profiler\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Renlife\Profiler\Profiler;
use Renlife\Profiler\Tests\SingletonHelper;

class ProfilerTest extends TestCase
{
    protected function setUp()
    {
        SingletonHelper::clearState(Profiler::class);
    }

    public function testSingleton(): void
    {
        $profiler = Profiler::getInstance();

        $this->assertSame($profiler, Profiler::getInstance());
    }

    public function testAga(): void
    {
        $profiler = Profiler::getInstance();

        $this->assertSame($profiler, Profiler::getInstance());
    }
}
