<?php
declare(strict_types=1);

namespace SixShop\Core;

use phpDocumentor\Reflection\Types\Self_;
use SixShop\Core\Exception\LogicException;
use SixShop\Core\Response\Xml;
use SixShop\Core\Service\CoreService;
use think\Container;
use think\Paginator;
use think\Response;

final class Helper
{

    /**
     * 返回成功数据
     */
    public static function success_response(mixed $data = [], string $status = 'ok', int $code = 200, string $msg = 'success', string $type = 'json', string $xslt = ''): Response
    {
        if ($xslt) {
            $type = 'xml';
        }
        $responseData = [
            'code' => $code,
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        if ($type == 'xml') {
            /* @var Xml $response */
            $response = Container::getInstance()->invokeClass(Xml::class, [$responseData, 200]);
            $response = $response->options(['root_node' => 'root', 'xslt' => $xslt]);
        } else {
            $response = Response::create($responseData, $type);
        }
        return $response;
    }

    /**
     * 返回分页数据
     */
    public static function page_response(Paginator $page, mixed $data = [], string $status = 'ok', int $code = 200, string $msg = 'success'): Response
    {
        return json([
            'code' => $code,
            'status' => $status,
            'msg' => $msg,
            'page' => $page,
            'data' => $data
        ]);
    }

    /**
     * 返回失败数据
     */
    public static function error_response(string $msg = 'error', string $status = 'error', int $code = 1, mixed $data = [], int $httpCode = 400, $header = [], $options = []): Response
    {
        return json([
            'code' => $code,
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ], $httpCode, $header, $options);
    }

    /**
     * 抛出逻辑异常
     * @throws LogicException
     */
    public static function throw_logic_exception(string $msg = 'error', int $code = 1, string $status = 'error', mixed $data = [], int $httpCode = 200, $header = [], $options = []): void
    {
        throw new LogicException(self::error_response($msg, $status, $code, $data, $httpCode, $header, $options));
    }

    /**
     * 构建树形结构选项
     * @param array $data 数据源
     * @param string $valueField 值字段
     * @param string $labelField 标签字段
     * @param string $parentField 父字段
     * @param int $parentId 父ID
     * @param string $childrenKey 子节点键
     * @param bool $preserveOriginal 是否保留原始数据
     */
    public static function build_tree_options(
        array  $data,
        string $valueField = 'id',
        string $labelField = 'name',
        string $parentField = 'parent_id',
        int    $parentId = 0,
        string $childrenKey = 'children',
        bool   $preserveOriginal = true
    ): array
    {
        $tree = [];
        foreach ($data as $item) {
            if ($item[$parentField] == $parentId) {
                $node = [
                    'value' => $item[$valueField],
                    'label' => $item[$labelField]
                ];

                // 根据参数决定是否保留原始数据
                if ($preserveOriginal) {
                    $node = array_merge($item, $node);
                }

                $children = self::build_tree_options(
                    $data,
                    $valueField,
                    $labelField,
                    $parentField,
                    $item[$valueField],
                    $childrenKey,
                    $preserveOriginal // 传递参数到递归调用
                );

                if ($children) {
                    $node[$childrenKey] = $children;
                }
                $tree[] = $node;
            }
        }
        return $tree;
    }

    /**
     * 生成随机密码
     * @param int $length 密码长度
     * @return string 生成的密码
     */
    public static function secret_password(int $length = 16): string
    {
        // 确保密码包含各种字符类型
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        // 至少包含每种类型的一个字符
        $password = $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];

        // 剩余字符随机生成
        $allChars = $lowercase . $uppercase . $numbers . $specialChars;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // 打乱字符顺序
        return str_shuffle($password);
    }


    public static function extension_path(string $module = ''): string
    {
        return CoreService::$extensionPath . $module . '/';
    }

    public static function extension_name_list()
    {
        return CoreService::$extensionNameList;
    }
}
