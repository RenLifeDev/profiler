<?php

declare(strict_types=1);

namespace Renlife\Profiler\Tests;

use PHPUnit\Framework\TestCase;
use Renlife\Profiler\Adapter\TidewaysXhprofAdapter;
use Renlife\Profiler\Formatter\XhguiMongoFormatter;
use Renlife\Profiler\Handler\MongoHandler;
use Renlife\Profiler\Processor\HttpRequestProcessor;
use Renlife\Profiler\Profiler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class MongoIntegrationTest extends TestCase
{
    public function testProfile(): void
    {
        $profiler = Profiler::getInstance()
            ->setAdapter(new TidewaysXhprofAdapter(TIDEWAYS_XHPROF_FLAGS_MEMORY | TIDEWAYS_XHPROF_FLAGS_CPU))
            ->start();

        $profile = $profiler->stop();

        $requestStack = new RequestStack();
        $requestStack->push(Request::create('https://example.org/some-resource?key=value', 'POST', ['param' => 'value']));

        $handler = (new MongoHandler([
                'uri'        => 'mongodb://root:root@mongo:27017',
                'collection' => 'results',
                'db'         => 'xhprof',
            ]))
            ->setFormatter(new XhguiMongoFormatter())
            ->pushProcessor(new HttpRequestProcessor($requestStack));

        $handler->handle($profile);
    }
}
