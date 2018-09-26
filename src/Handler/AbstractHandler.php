<?php

declare(strict_types=1);

namespace Renlife\Profiler\Handler;

use Renlife\Profiler\Formatter\FormatterInterface;
use Renlife\Profiler\Processor\ProcessorInterface;
use Renlife\Profiler\Profile;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];

    /**
     * @var FormatterInterface|callable|null
     */
    private $formatter;

    /**
     * {@inheritdoc}
     */
    public function handle(Profile $result): void
    {
        foreach ($this->processors as $processor) {
            $processor($result);
        }

        if (null !== $this->formatter) {
            $result = \call_user_func($this->formatter, $result);
        }

        $this->write($result);
    }

    /**
     * Writes data to the handler's source.
     *
     * @param Profile|mixed $data depends on the formatter used
     */
    protected function write($data): void
    {
        throw new \RuntimeException('Write method should be implemented yourself.');
    }

    /**
     * {@inheritdoc}
     */
    public function pushProcessor(callable $callback): HandlerInterface
    {
        if (!\is_callable($callback)) {
            throw new \InvalidArgumentException('Processors must be an instance of ProcessorInterface or a callable.');
        }
        array_unshift($this->processors, $callback);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function popProcessor(): callable
    {
        if (!$this->processors) {
            throw new \LogicException('Cannot pop from empty processors stack.');
        }

        return array_shift($this->processors);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter(?callable $formatter): HandlerInterface
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatter(): ?callable
    {
        return $this->formatter;
    }
}
