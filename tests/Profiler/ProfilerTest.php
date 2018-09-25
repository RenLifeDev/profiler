<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Tests\Profiler;

use PHPUnit\Framework\TestCase;
use Renlife\ProfilerBundle\Profiler\Profile;
use Renlife\ProfilerBundle\Profiler\Profiler;

class ProfilerTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('RENLIFE_PROFILER_ENABLED');
        Profiler::getInstance()->tearDown();
    }

    public function testGetInstance(): void
    {
        $instance = Profiler::getInstance();

        $this->assertSame($instance, Profiler::getInstance());
    }

    public function testStart(): void
    {
        putenv('RENLIFE_PROFILER_ENABLED=1');
        $instance = Profiler::getInstance();

        $instance->start();

        $this->assertTrue($instance->isStarted());
    }

    public function testStartWithoutEnv(): void
    {
        $instance = Profiler::getInstance();

        $instance->start();

        $this->assertFalse($instance->isStarted());
    }

    public function testStartWithWorngEnvValue(): void
    {
        putenv('RENLIFE_PROFILER_ENABLED=true');
        $instance = Profiler::getInstance();

        $instance->start();

        $this->assertFalse($instance->isStarted());
    }

    public function testStop(): void
    {
        putenv('RENLIFE_PROFILER_ENABLED=1');
        $instance = Profiler::getInstance();
        $instance->start();

        $profile = $instance->stop();

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertFalse($instance->isStarted());

        $this->assertNotEmpty($profile->getProfile());
        $this->assertNotNull($profile->getStartedAt());
        $this->assertNotNull($profile->getStoppedAt());
        $this->assertEmpty($profile->getMetadata());
    }

    public function testStartStopMultipleTimes(): void
    {
        putenv('RENLIFE_PROFILER_ENABLED=1');
        $instance = Profiler::getInstance();

        $instance->start();
        $profile1 = $instance->stop();

        $instance->start();
        $profile2 = $instance->stop();

        $this->assertNotSame($profile1, $profile2);
        $this->assertNotEmpty($profile1->getProfile());
        $this->assertNotEmpty($profile2->getProfile());
    }
}
