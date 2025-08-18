<?php
declare(strict_types=1);

namespace SixShop\core\Contracts;

interface ExtensionInterface
{
    /**
     * 安装扩展
     */
    public function install(): void;

    /**
     * 卸载扩展
     */
    public function uninstall(): void;

    /**
     * 获取扩展信息
     *
     * @return array
     */
    public function getInfo(): array;

    /**
     * 获取扩展配置
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * 获取扩展命令
     */
    public function getCommands(): array;

    /**
     * 获取扩展钩子
     */
    public function getHooks(): array;

    /**
     * 获取扩展路由
     */
    public function getRoutes(): array;

    /**
     * 获取扩展计划任务
     */
    public function getCronJobs(): array;
}