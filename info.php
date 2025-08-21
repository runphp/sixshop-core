<?php
declare(strict_types=1);

return [
    'id' => 'core', # 扩展的唯一标识符
    'name' => '核心', # 扩展的名称
    'is_core' => true, # 是否核心扩展'
    'category' => 'core', # 扩展的分类 core:核心扩展，other:其他扩展
    'description' => '这是系统的核心扩展。', # 扩展的描述
    'version' => '1.0.0',  # 扩展的版本
    'core_version' => '^1.0',  # 支持的核心版本
    'author' => 'runphp', # 作者
    'email' => 'runphp@qq.com', # 作者的邮箱
    'website' => '', # 扩展的地址，可以是扩展的仓库地址，帮助用户寻找扩展，安装扩展等网络地址
    'image' => '', # 扩展的图片，用于展示扩展的图标，或者是扩展的截图等图片地址
    'license' => 'MIT', # 扩展的开源协议
];
