<?php

declare(strict_types=1);

namespace PeibinLaravel\Engine\Contracts;

use ArrayObject;
use PeibinLaravel\Engine\Exceptions\CoroutineDestroyedException;
use PeibinLaravel\Engine\Exceptions\RunningInNonCoroutineException;

interface CoroutineInterface
{
    /**
     * @param callable $callable [required]
     */
    public function __construct(callable $callable);

    /**
     * @param mixed ...$data
     * @return $this
     */
    public function execute(...$data);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param callable $callable [required]
     * @param mixed    ...$data
     * @return $this
     */
    public static function create(callable $callable, ...$data);

    /**
     * @return int returns coroutine id from current coroutine, -1 in non coroutine environment
     */
    public static function id();

    /**
     * Returns the parent coroutine ID.
     * Returns 0 when running in the top level coroutine.
     * @throws RunningInNonCoroutineException when running in non-coroutine context
     * @throws CoroutineDestroyedException when the coroutine has been destroyed
     */
    public static function pid(?int $id = null);

    /**
     * Set config to coroutine.
     */
    public static function set(array $config);

    /**
     * @param null|int $id coroutine id
     * @return null|ArrayObject
     */
    public static function getContextFor(?int $id = null);

    /**
     * Execute callback when coroutine destruct.
     */
    public static function defer(callable $callable);

    /**
     * Get the coroutine stats.
     */
    public static function stats(): array;

    /**
     * Check if a coroutine exists or not.
     */
    public static function exists(int $id): bool;
}
