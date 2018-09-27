<?php

declare(strict_types=1);

namespace Renlife\Profiler\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Renlife\Profiler\Profile;

class ProfileTest extends TestCase
{
    public function testConstruct(): void
    {
        $startedAt    = new \DateTime();
        $stoppedAt    = new \DateTime();
        $results      = [1, 2, 3];
        $adapterClass = 'SomeClass';

        $profile = new Profile($adapterClass, $results, $startedAt, $stoppedAt);

        $this->assertSame($startedAt, $profile->getStartedAt());
        $this->assertSame($stoppedAt, $profile->getStoppedAt());
        $this->assertSame($results, $profile['profile']);
        $this->assertSame($adapterClass, $profile->getAdapterClass());
    }

    public function testArrayAccess(): void
    {
        $profile = new Profile('', [], new \DateTime(), new \DateTime());

        $profile['somekey'] = ['somevalue'];

        $this->assertSame(['somevalue'], $profile['somekey']);
    }
}
