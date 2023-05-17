<?php

declare(strict_types=1);

namespace PeibinLaravel\Engine;

use PeibinLaravel\Engine\Contracts\CoroutineInterface;
use PeibinLaravel\Engine\Exceptions\CoroutineDestroyedException;
use PeibinLaravel\Engine\Exceptions\RunningInNonCoroutineException;
use PeibinLaravel\Engine\Exceptions\RuntimeException;
use Swoole\Coroutine as SwooleCo;

class Coroutine implements CoroutineInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var int
     */
    private $id;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public static function create(callable $callable, ...$data)
    {
        $coroutine = new static($callable);
        $coroutine->execute(...$data);
        return $coroutine;
    }

    public function execute(...$data)
    {
        $this->id = SwooleCo::create($this->callable, ...$data);
        return $this;
    }

    public function getId()
    {
        if (is_null($this->id)) {
            throw new RuntimeException('Coroutine was not be executed.');
        }
        return $this->id;
    }

    public static function id()
    {
        return SwooleCo::getCid();
    }

    public static function pid(?int $id = null)
    {
        if ($id) {
            $cid = SwooleCo::getPcid($id);
            if ($cid === false) {
                throw new CoroutineDestroyedException(sprintf('Coroutine #%d has been destroyed.', $id));
            }
        } else {
            $cid = SwooleCo::getPcid();
        }
        if ($cid === false) {
            throw new RunningInNonCoroutineException('Non-Coroutine environment don\'t has parent coroutine id.');
        }
        return max(0, $cid);
    }

    public static function set(array $config)
    {
        SwooleCo::set($config);
    }

    /**
     * @return null|ArrayObject
     */
    public static function getContextFor(?int $id = null)
    {
        if ($id === null) {
            return SwooleCo::getContext();
        }

        return SwooleCo::getContext($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function defer(callable $callable)
    {
        SwooleCo::defer($callable);
    }

    /**
     * {@inheritdoc}
     */
    public static function stats(): array
    {
        return SwooleCo::stats();
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(int $id): bool
    {
        return SwooleCo::exists($id);
    }
}
