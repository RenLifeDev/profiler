<?php

declare(strict_types=1);

namespace Renlife\Profiler;

use Renlife\Profiler\Adapter\AdapterInterface;

final class Profiler
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var bool
     */
    private $isStarted = false;

    /**
     * @var \DateTime|null
     */
    private $startedAt;

    /**
     * @var AdapterInterface|null
     */
    private $adapter;

    /**
     * @return Profiler
     */
    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Singleton patterns needs constructor to be disabled.
     */
    private function __construct()
    {
    }

    /**
     * Starts profiling.
     *
     * @return self
     */
    public function start(): self
    {
        if ($this->isStarted() || null === $this->adapter || !$this->adapter->isEnabled()) {
            return $this;
        }

        $this->adapter->start();

        $this->isStarted = true;
        $this->startedAt = $this->createDateTimeWithMicroseconds();

        return $this;
    }

    /**
     * Stops profiling and returns its result.
     *
     * @return Profile
     */
    public function stop(): Profile
    {
        if (!$this->isStarted()) {
            throw new \RuntimeException('Cannot stop profiling as it was not started.');
        }
        if (null === $this->adapter) {
            throw new \RuntimeException('Cannot stop profiling with empty adapter.');
        }

        $profile = new Profile(
            \get_class($this->adapter),
            tideways_xhprof_disable(),
            $this->startedAt,
            $this->createDateTimeWithMicroseconds()
        );

        $this->isStarted = false;
        $this->startedAt = false;

        return $profile;
    }

    /**
     * @param AdapterInterface $adapter
     *
     * @return Profiler
     */
    public function setAdapter(AdapterInterface $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->isStarted;
    }

    /**
     * Creates \DateTime object with microseconds.
     *
     * @return \DateTime
     */
    private function createDateTimeWithMicroseconds(): \DateTime
    {
        $date = \DateTime::createFromFormat('0.u00 U', microtime());
        $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $date;
    }
}
