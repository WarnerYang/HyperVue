<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Amqp\Message\ProducerMessageInterface;
use Hyperf\Amqp\Producer;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;
use App\Kernel\Http\Response;

if (!function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param null|mixed $id
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }

        return $container;
    }
}

if (!function_exists('format_throwable')) {
    /**
     * Format a throwable to string.
     * @param Throwable $throwable
     * @return string
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (!function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}

if (!function_exists('amqp_produce')) {
    /**
     * Produce a amqp message.
     */
    function amqp_produce(ProducerMessageInterface $message): bool
    {
        return di()->get(Producer::class)->produce($message, true);
    }
}

if (!function_exists('success')) {
    /**
     * 响应成功数据
     *
     * @param mixed $data
     * @return string
     */
    function success($data = [])
    {
        $response = di(Response::class);
        return $response->success($data);
    }
}

if (!function_exists('cache')) {
    /**
     * 缓存快捷操作
     *
     * @param string $key 键
     * @param array|string $value 值
     * @param integer $ttl 过期时间
     * @return mixed
     * @example
     *   cache(null); 清空所有缓存
     *   cache('foo', null); 清空 foo 缓存
     *   cache('foo', 'bar'); 设置 foo 缓存
     *   cache('foo', 'bar', 60); 设置 foo 缓存 60 秒
     */
    function cache($key, $value = '', int $ttl = null)
    {
        $cache = di(CacheInterface::class);
        if ($key === null) {
            return $cache->clear();
        }
        if ($value === null) {
            return $cache->delete($key);
        }
        if ($value === '') {
            return $cache->get($key);
        }
        return $cache->set($key, $value, $ttl);
    }
}

if (!function_exists('user_md5')) {
    /**
     * 密码加密
     *
     * @param string $str 字符串
     * @return string
     */
    function user_md5($str): string
    {
        $solt = config('app_secret');
        return '' === $str ? $str : md5(sha1((string) $str) . $solt);
    }
}

if (!function_exists('memu_level_clear')) {
    /**
     * 给树状菜单添加 level 并去掉没有子菜单的菜单项
     *
     * @param array $data 要转换的数据集
     * @param integer $root 返回的根节点ID
     * @param string $child 子节点的键
     * @param string $level 级别键
     * @return void
     */
    function memu_level_clear(array $data, int $root = 1, string $child = 'child', string $level = 'level')
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key]['selected'] = false;
                $data[$key]['level'] = $root;
                if (!empty($val[$child]) && is_array($val[$child])) {
                    $data[$key][$child] = memu_level_clear($val[$child], $root + 1);
                } else if ($root < 3 && $data[$key]['menu_type'] == 1) {
                    unset($data[$key]);
                }
                if (empty($data[$key][$child]) && ($data[$key]['level'] == 1) && ($data[$key]['menu_type'] == 1)) {
                    unset($data[$key]);
                }
            }
            return array_values($data);
        }
        return array();
    }
}

if (!function_exists('rules_deal')) {
    /**
     * 给树状规则表处理成 module-controller-action
     *
     * @param array $data
     * @return array
     */
    function rules_deal(array $data): array
    {
        if (is_array($data)) {
            $ret = [];
            foreach ($data as $k1 => $v1) {
                $str1 = $v1['name'];
                if (is_array($v1['child'])) {
                    foreach ($v1['child'] as $k2 => $v2) {
                        $str2 = $str1 . '-' . $v2['name'];
                        if (is_array($v2['child'])) {
                            foreach ($v2['child'] as $k3 => $v3) {
                                $str3 = $str2 . '-' . $v3['name'];
                                $ret[] = $str3;
                            }
                        } else {
                            $ret[] = $str2;
                        }
                    }
                } else {
                    $ret[] = $str1;
                }
            }
            return $ret;
        }
        return [];
    }
}

if (!function_exists('encrypt')) {
    /**
     * DES encrypt
     *
     * @param array $arr
     * @return string
     */
    function encrypt($arr): string
    {
        $data = json_encode($arr);
        $method = 'AES-128-CBC';
        $key = config('app_secret');
        $padding = OPENSSL_RAW_DATA;
        $iv = $key;
        $encrypted = base64_encode(openssl_encrypt($data, $method, $key, $padding, $iv));
        return $encrypted;
    }
}

if (!function_exists('decrypt')) {
    /**
     * DES decrypt
     *
     * @param string $encrypted
     * @return array
     */
    function decrypt($encrypted)
    {
        $method = 'AES-128-CBC';
        $key = config('app_secret');
        $padding = OPENSSL_RAW_DATA;
        $iv = $key;
        $decrypted = openssl_decrypt(base64_decode($encrypted), $method, $key, $padding, $iv);
        $decrypted = json_decode($decrypted, true);
        return $decrypted;
    }
}

if (!function_exists('object_array')) {
    /**
     * 对象转成数组
     *
     * @param object $object 对象
     * @return array
     */
    function object_array($object): array
    {
        $array = [];
        if (is_object($object)) {
            $array = (array) $object;
        }
        if (is_array($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }
}

if (!function_exists('rand_string')) {
    /**
     * 随机字符串
     *
     * @param integer $len 长度
     * @return string
     */
    function rand_string($len = 16): string
    {
        $chars = [
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        ];
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $string = '';
        for ($i = 0; $i < $len; $i++) {
            $string .= $chars[mt_rand(0, $charsLen)];
        }
        return $string;
    }
}

if (!function_exists('unique_id')) {
    /**
     * 唯一id
     *
     * @return string
     */
    function unique_id(): string
    {
        return md5(uniqid(rand_string()));
    }
}