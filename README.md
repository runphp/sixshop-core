

# Core Extension

这个扩展为后端应用提供了核心功能。

## 特性

- 提供 \SixShop\Core\Entity\BaseEntity 类，作为所有实体的基类。

## 安装

请使用 Composer 来安装此扩展：

```bash
composer require six-shop/core
```

## 使用

使用此扩展，你需要创建一个继承自 `SixShop\\core\\Entity\\BaseEntity` 的实体类，并根据需要实现 `SixShop\\core\\Contracts\\ExtensionInterface` 接口。

你还可以使用 `SixShop\\core\\Attribute\\Cron` 和 `SixShop\\core\\Attribute\\Hook` 属性来定义定时任务和钩子。

对于异常处理，可以使用 `SixShop\\core\\Exception\\ExceptionHandle` 类来渲染异常并返回适当的响应。

## 服务

该扩展包括多个服务类，用于初始化和注册功能，如自动加载服务、命令服务、钩子属性服务和路由注册服务。

## 调度任务

使用 `SixShop\\core\\Job\\BaseJob` 抽象类来创建作业任务，并利用 `SixShop\\core\\Job\\JobDispatcher` 来调度这些任务。

## 中间件

该扩展还提供了一些中间件，如 `ExtensionStatusMiddleware` 和 `MacroPageMiddleware`，用于处理请求和响应。

## 贡献

欢迎为这个扩展贡献代码和测试。请确保遵循 PSR-12 编码标准，并为你的拉取请求提供清晰的描述。

## 许可证

此项目遵循 MIT 许可证。详情请查看 LICENSE 文件。