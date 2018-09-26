<?php

declare(strict_types=1);

namespace Renlife\Profiler\Formatter;

use Renlife\Profiler\Profile;

/**
 * XhguiFormatter formats profile data to Xhgui compatible structure for MongoDb.
 *
 * @See https://github.com/perftools/xhgui
 */
class XhguiMongoFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(Profile $profile): array
    {
        $startedAt = $profile->getStartedAt();

        return [
            'profile' => $profile['profile'],
            'info'    => [
                'url'              => $profile['http']['request_uri'] ?? null,
                'SERVER'           => [],
                'get'              => [],
                'env'              => [],
                'simple_url'       => $profile['http']['route'] ?? $profile['http']['path_info'] ?? null,
                'request_ts'       => $startedAt->getTimestamp(),
                'request_ts_micro' => $startedAt->format('U.u'),
                'request_date'     => $startedAt->format('Y-m-d'),
            ],
        ];
    }
}
