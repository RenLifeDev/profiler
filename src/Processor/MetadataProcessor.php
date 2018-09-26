<?php

declare(strict_types=1);

namespace Renlife\Profiler\Processor;

use MongoDB\BSON\UTCDateTime;
use Renlife\ProfilerBundle\Profiler\Profile;

/**
 * Add metadata to profile object.
 */
class MetadataProcessor
{
    public function __invoke(Profile $profile)
    {
        $uri = $_SERVER['REQUEST_URI'] ?? null;
        if ((null === $uri || '' === $uri) && isset($_SERVER['argv'])) {
            $cmd = basename($_SERVER['argv'][0]);
            $uri = $cmd.' '.implode(' ', \array_slice($_SERVER['argv'], 1));
        }

        $time = $_SERVER['REQUEST_TIME'] ?? time();

        $delimiter        = (false !== strpos($_SERVER['REQUEST_TIME_FLOAT'], ',')) ? ',' : '.';
        $requestTimeFloat = explode($delimiter, $_SERVER['REQUEST_TIME_FLOAT']);
        if (!isset($requestTimeFloat[1])) {
            $requestTimeFloat[1] = 0;
        }

        $requestTs      = new UTCDateTime($time);
        $requestTsMicro = new UTCDateTime();

        $profile['meta'] = [
            'url'              => $uri,
            'SERVER'           => $_SERVER,
            'get'              => $_GET,
            'env'              => $_ENV,
            'simple_url'       => $uri,
            'request_ts'       => $requestTs,
            'request_ts_micro' => $requestTsMicro,
            'request_date'     => date('Y-m-d', $time),
        ];
    }
}
