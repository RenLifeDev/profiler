<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Profiler;

/**
 * Container for profile data.
 */
class Profile
{
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
    private $profile;

    /**
     * @var array
     */
    private $metadata;

    /**
     * ProfilingResult constructor.
     *
     * @param array $profile records containing profiling data
     */
    public function __construct(array $profile)
    {
        $this->profile  = $profile;
        $this->metadata = [];
    }

    /**
     * @return array
     */
    public function getProfile(): array
    {
        return $this->profile;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     *
     * @return Profile
     */
    public function setStartedAt(\DateTime $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStoppedAt(): \DateTime
    {
        return $this->stoppedAt;
    }

    /**
     * @param \DateTime $stoppedAt
     *
     * @return Profile
     */
    public function setStoppedAt(\DateTime $stoppedAt): self
    {
        $this->stoppedAt = $stoppedAt;

        return $this;
    }

    /**
     * Appends provided array to the profile metadata.
     *
     * @param array $metadata
     *
     * @return Profile
     */
    public function appendMetadata(array $metadata): self
    {
        $this->metadata = array_merge_recursive($this->metadata, $metadata);

        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
