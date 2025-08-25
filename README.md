

# SixShop扩展

## 特性

次扩展旨在为ThinkPHP开发者们开发出更易维护的项目

每个项目都是不同的扩展组合而成，对ThinkPHP应用的扩展只需要通过`composer require` 进行安装（包括数据的迁移脚本）

最终你只需要寻找现成合适的扩展进行安装，然后自己自行开发部分扩展

支持私有仓库进行扩展模块开发，保护用户定制开发的代码

支持应用市场扩展开发模式

## 项目依赖
```
"php": ">=8.3",
"topthink/framework": "^8.1",
"topthink/think-orm": "^4.0"
```

## 扩展列表

<https://packagist.org/?type=sixshop-extension>

<https://packagist.jd29.com>


## SixShop扩展开发指南

扩展模块开发流程：
1. 先创建git项目
2. 项目clone到本地`runtime/extension`目录下,也可以其他没有版本控制的目录下
    ```shell
    cd backend/runtime/extension
    git clone git@github.com:runphp/sixshop-hello.git
    ```
3. 在~/.composer/auth.json中添加path仓库(下面以hello为例)
    ```json
    {
        "repositories": [
            {
                "type": "path",
                "url": "runtime/extension/sixshop-hello",
                "options": {
                    "symlink": true,
                    "versions": {
                        "six-shop/hello": "v0.2.9"
                    }
                }
            }
        ]
    }
    ```
4. 安装你的扩展模块
    ```shell
    ddev composer require  "six-shop/hello:^v0.2.0"
    ```
   上面的版本是假如最新版本的`six-shop/hello`是`v0.2.0`,那么我们的开发版本比它大的版本就好了，我们可以设置成`v0.2.9`

   成功处理的话可以看到
   ```shell
   Package operations: 1 install, 0 updates, 0 removals
     - Installing six-shop/hello (v0.2.9): Symlinking from runtime/extension/sixshop-hello
   ```

   现在你就可以在`runtime/extension`或`vendor`目录下修改代码了

5. 私有扩展模块认证说明

   在 ~/.composer/auth.json 中添加认证信息
   ```json
   {
      "repositories": [
         {
            "type": "composer",
            "url": "https://packagist.jd29.com",
            "options": {
               "http": {
                  "header": [
                     "X-API-KEY: 699d5f8e02a255e657bb2bfad35570bb468e970e72918b2e38797f6a00beb4e4"
                  ]
               }
            }
         }
      ]
   }
   ```
   说明: ddev环境对应的auth.json文件为`~/.ddev/homeadditions/.composer/auth.json`

   当然你可以做软连接过去
   ```shell
   mkdir -p ~/.ddev/homeadditions/.composer && ln -s ~/.composer/auth.json ~/.ddev/homeadditions/.composer/auth.json
   ```

   最后你可以参考`extension/auth.json`这份示例文件,进行修改，这些设置也可以直接在`composer.json`文件设置，最终使用方式请参考composer官方文档。

6. 扩展模块的composer.json
   参考其他扩展，不同地方是添加了`"type": "sixshop-extension"`，然后就是
   ```json
   {
     "extra": {
       "sixshop": {
         "id": "hello",
         "class": "SixShop\\Hello\\Extension"
       }
     }
   }
   ```
   id为扩展模块的标识符,需要唯一，class为扩展模块的类名，实现了`SixShop\Extension\ExtensionInterface`接口

7. 扩展模块的sql安装脚本使用cakephp的migration，在模块的`database/migrations`目录添加

8. 扩展路由在`route`目录下添加路由文件
   默认会加载`admin.php`和`api.php`两个文件, 对应的是admin和api应用，你也可以实现`SixShop\Extension\ExtensionInterface`的`getRoutes`接口

9. 扩展模块的事件监听，可以说使用`SixShop\Core\Attribute\Hook`实现，具体请查看`SixShop\Core\Attribute\Hook`类，在模块的`src/Hooks`目录下添加，然后实现`SixShop\Extension\ExtensionInterface`的`getHooks`接口

10. 扩展模块的异步任务实现你可以继承`SixShop\Core\Job\BaseJob`即可，具体请查看`SixShop\Core\Job\BaseJob`类
    使用示例
   ```php
   \app\api\job\IndexJob::dispatch(); // 异步任务
   \app\api\job\IndexJob::dispatch()->delay(10); // 延迟10s执行
   ```
11. 扩展定时任务,使用`SixShop\Core\Attribute\Cron`注解, 并且需要实现`SixShop\Extension\ExtensionInterface`的`getCronJobs`接口
    参考`\SixShop\System\Cron\SystemCron`类

12. 扩展命令行
    注册命令行请实现`SixShop\Extension\ExtensionInterface`的`getCommands`接口 ，默认自动加载扩展目录下`command.php`文件
    可以参考`backend/vendor/six-shop/system/command.php`

13. 扩展的配置，默认自动加载扩展目录下`config.php`文件，可以参考`backend/vendor/six-shop/hello/config.php`
       统一配置的实现目前需要安装`six-shop/system`，后续会单独的扩展包，目前配置的实现是使用 [form-create](https://form-create.com/v3/designer/) 生成配置表单即可，支持子表单组件

## 欢迎加入我们