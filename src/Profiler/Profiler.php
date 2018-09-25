<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Profiler;

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
     * Приватный констуктор для реализации синглтона.
     */
    private function __construct()
    {
    }

    /**
     * Запускает процесс сбора данных в зависимости от параметров конфигурации.
     */
    public function start(): void
    {
        if ('1' !== getenv('RENLIFE_PROFILER_ENABLED') || $this->isStarted()) {
            return;
        }

        if (!\extension_loaded('tideways_xhprof')) {
            trigger_error('Не удалось запустить профилирование: не установлено расширение tideways_xhprof.');

            return;
        }

        tideways_xhprof_enable();

        $this->startedAt = $this->createDateTimeWithMicroseconds();
        $this->isStarted = true;
    }

    /**
     * Stops profiling and returns its result.
     *
     * @return Profile
     */
    public function stop(): Profile
    {
        $profile = (new Profile(tideways_xhprof_disable()))
            ->setStartedAt($this->startedAt)
            ->setStoppedAt($this->createDateTimeWithMicroseconds());

        $this->isStarted = false;
        $this->startedAt = false;

        return $profile;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->isStarted;
    }

    /**
     * @return \DateTime|null with microseconds
     */
    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    /**
     * Сбрасывает состояние синглтона для тестов.
     */
    public function tearDown(): void
    {
        self::$instance = null;
    }

    /**
     * Создает объект даты и времени с микросекундами.
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
