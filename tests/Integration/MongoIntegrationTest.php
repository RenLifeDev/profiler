<?php

declare(strict_types=1);

namespace Renlife\Profiler\Tests\Integration;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Model\BSONArray;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;
use Renlife\Profiler\Adapter\TidewaysXhprofAdapter;
use Renlife\Profiler\Formatter\XhguiMongoFormatter;
use Renlife\Profiler\Handler\MongoHandler;
use Renlife\Profiler\Processor\HttpRequestProcessor;
use Renlife\Profiler\Profiler;
use Renlife\Profiler\Tests\SingletonHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @group Integration
 */
class MongoIntegrationTest extends TestCase
{
    private const MONGO_URI = 'mongodb://root:root@mongo:27017';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Collection
     */
    private $collection;

    protected function setUp()
    {
        SingletonHelper::clearState(Profiler::class);

        $this->client     = new Client(self::MONGO_URI);
        $this->collection = $this->client->profiling->results;

        $this->collection->deleteMany([]);
    }

    public function testProfile(): void
    {
        $profiler = Profiler::getInstance()
            ->setAdapter(new TidewaysXhprofAdapter(TIDEWAYS_XHPROF_FLAGS_MEMORY | TIDEWAYS_XHPROF_FLAGS_CPU))
            ->start();

        $profile = $profiler->stop();

        $request = Request::create('https://example.org/some-resource?key=value', 'POST', ['param' => 'value']);
        $request->attributes->set('_route', 'app_some_route_name');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $handler = (new MongoHandler([
                'uri'        => self::MONGO_URI,
                'collection' => 'results',
                'db'         => 'profiling',
            ]))
            ->setFormatter(new XhguiMongoFormatter())
            ->pushProcessor(new HttpRequestProcessor($requestStack));

        $handler->handle($profile);

        $document = $this->collection->findOne();

        // Exactle 1 documents has to be in the collection.
        $this->assertSame(1, $this->collection->count());
        $this->assertNotNull($document);

        // Info has to be filled in.
        /** @var BSONDocument $info */
        $info = $document->info;
        $this->assertNotNull($info);
        $this->assertSame('/some-resource?key=value', $info->url);
        $this->assertEquals(new BSONArray(), $info->SERVER);
        $this->assertEquals(new BSONArray(), $info->get);
        $this->assertEquals(new BSONArray(), $info->env);
        $this->assertEquals('app_some_route_name', $info->simple_url);
        $this->assertInternalType('int', $info->request_ts);
        $this->assertInternalType('string', $info->request_ts_micro);
        $this->assertRegExp('/^\d{4}-\d{2}-\d{2}$/', $info->request_date);
    }
}
