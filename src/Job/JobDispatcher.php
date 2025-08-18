<?php
declare(strict_types=1);

namespace SixShop\core\Job;

class JobDispatcher
{
    public function __construct(
        private readonly string $jobClass,
        private readonly mixed $data = null,
        private int $delay = 0,
        private ?string $queue = null
    ) {
    }

    /**
     * 设置延迟时间
     *
     * @param int $delay 延迟秒数
     * @return $this
     */
    public function delay(int $delay): self
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * 设置队列名称
     *
     * @param string $queue 队列名称
     * @return $this
     */
    public function onQueue(string $queue): self
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * 设置在特定时间执行
     *
     * @param int $timestamp 时间戳
     * @return $this
     */
    public function at(int $timestamp): self
    {
        $this->delay = $timestamp - time();
        return $this;
    }

    /**
     * 析构时自动分发任务
     */
    public function __destruct()
    {
        queue($this->jobClass, $this->data, $this->delay, $this->queue);
    }
}
