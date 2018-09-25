<?php

declare(strict_types=1);

namespace Renlife\ProfilerBundle\Handler;

use Renlife\ProfilerBundle\Profiler\Profile;

/**
 * Хендлер принимает на вход записи логов профайлера и обрабатывает их, это может быть отправка в MongoDb, сохранение в
 * файл и др.
 */
interface HandlerInterface
{
    /**
     * Обрабаотывает переданные записи профилирования.
     *
     * @param Profile $result
     */
    public function handle(Profile $result): void;

    /**
     * Записывает переданные записи профилирования в хранилище.
     *
     * @param Profile $result
     */
    public function write(Profile $result): void;
}
