<?php

declare(strict_types=1);

namespace Renlife\Profiler\Processor;

use Renlife\Profiler\Profile;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * HttpRequestProcessor adds current request information to the profile.
 */
class HttpRequestProcessor implements ProcessorInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Profile $profile): void
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return;
        }

        $profile['http'] = [
            'route'       => $request->get('_route'),
            'uri'         => $request->getUri(),
            'request_uri' => $request->getRequestUri(),
            'path_info'   => $request->getPathInfo(),
            'base_url'    => $request->getBaseUrl(),
            'base_path'   => $request->getBasePath(),
            'method'      => $request->getMethod(),
            'host'        => $request->getHost(),
            'scheme'      => $request->getScheme(),
        ];
    }
}
