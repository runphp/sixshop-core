<?php
declare(strict_types=1);

namespace SixShop\core\Job;

use think\facade\Log;
use think\queue\Job;
use function Opis\Closure\{serialize, unserialize};
use Closure;

/**
 * @template T
 */
abstract class BaseJob
{
    // 最大重试次数
    protected int $maxAttempts = 3;

    // 重试延迟时间（秒）
    protected int $retryDelay = 60;

    // 是否启用失败回调
    protected bool $enableFailedCallback = true;

    // 是否闭包
    protected bool $isClosure = false;

    /**
     * 任务失败处理方法 - 子类可重写
     *
     * @param T $data 任务数据
     */
    protected function onFailed(mixed $data): void
    {
        // 默认失败处理逻辑
        Log::error('队列任务执行失败: ' . static::class, (array)$data);
    }

    /**
     * 任务前置处理 - 子类可重写
     *
     * @param T $data 任务数据
     * @return bool 是否继续执行
     */
    protected function beforeExecute(mixed $data): bool
    {
        return true;
    }

    /**
     * 任务后置处理 - 子类可重写
     *
     * @param T $data 任务数据
     * @param mixed $result 执行结果
     */
    protected function afterExecute(mixed $data, mixed $result): void
    {
        // 可以在这里添加通用的后置处理逻辑
    }

    /**
     * 主要的处理方法 - 不需要子类重写
     *
     * @param Job $job 队列任务对象
     * @param T $data 任务数据
     */
    public function fire(Job $job, mixed $data): void
    {
        try {
            if ($this->isClosure) {
                $data = unserialize($data);
            }
            // 前置处理
            if (!$this->beforeExecute($data)) {
                $job->delete();
                return;
            }

            if (method_exists($this, 'execute')) {
                // 执行任务
                $result = $this->execute($data);
            }

            // 后置处理
            $this->afterExecute($data, $result);

            // 标记任务完成
            $job->delete();

        } catch (\Exception|\Throwable $e) {
            $this->handleException($job, $data, $e);
        }
    }

    /**
     * 分发任务
     *
     * @param T $data 任务数据
     * @param int $delay 延迟时间
     * @param string|null $queue 队列名称
     */
    public static function dispatch(mixed $data = '', int $delay = 0, ?string $queue = null): JobDispatcher
    {
        if ($data instanceof Closure) {
            $data = serialize($data);
        }
        return new JobDispatcher(static ::class, $data, $delay, $queue);
    }

    /**
     * 异常处理
     *
     * @param Job $job 队列任务对象
     * @param T $data 任务数据
     * @param \Throwable|\Exception $exception 异常对象
     */
    protected function handleException(Job $job, mixed $data, \Throwable|\Exception $exception): void
    {
        Log::error('队列任务执行异常: ' . static::class . ' - ' . $exception->getMessage(), [
            'data' => is_array($data) ? $data : ['data' => $data],
            'trace' => $exception->getTraceAsString()
        ]);

        // 判断是否需要重试
        if ($job->attempts() < $this->maxAttempts) {
            // 重新发布任务
            $job->release($this->retryDelay);
        } else {
            // 标记任务失败
            $job->failed($exception);

            // 执行失败回调
            if ($this->enableFailedCallback) {
                try {
                    $this->onFailed($data);
                } catch (\Exception $e) {
                    Log::error('任务失败回调执行异常: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * 设置最大重试次数
     *
     * @param int $attempts
     * @return $this
     */
    protected function setMaxAttempts(int $attempts): static
    {
        $this->maxAttempts = $attempts;
        return $this;
    }

    /**
     * 设置重试延迟时间
     *
     * @param int $delay
     * @return $this
     */
    protected function setRetryDelay(int $delay): static
    {
        $this->retryDelay = $delay;
        return $this;
    }

    /**
     * 禁用失败回调
     *
     * @return $this
     */
    protected function disableFailedCallback(): static
    {
        $this->enableFailedCallback = false;
        return $this;
    }
}
