<?php

declare(strict_types=1);

namespace Renlife\Profiler;

/**
 * Container for profile data.
 */
class Profile implements \ArrayAccess
{
    /**
     * Adapter FQCN.
     *
     * @var string
     */
    private $adapterClass;

    /**
     * @var \DateTime
     */
    private $startedAt;

    /**
     * @var \DateTime
     */
    private $stoppedAt;

    /**
     * @var array
     */
    private $container;

    /**
     * ProfilingResult constructor.
     *
     * @param string    $adapterClass
     * @param array     $profile      records containing profiling data
     * @param \DateTime $startedAt
     * @param \DateTime $stoppedAt
     */
    public function __construct(string $adapterClass, array $profile, \DateTime $startedAt, \DateTime $stoppedAt)
    {
        $this->adapterClass         = $adapterClass;
        $this->container['profile'] = $profile;
        $this->startedAt            = $startedAt;
        $this->stoppedAt            = $stoppedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->container[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->container[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * @return string
     */
    public function getAdapterClass(): string
    {
        return $this->adapterClass;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getStoppedAt(): \DateTime
    {
        return $this->stoppedAt;
    }
}
