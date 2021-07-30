<?php

declare(strict_types=1);
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
// 助手函数

if (! function_exists('getRedis')) {
	/**
	 * 获取Redis实例.
	 *
	 * @param string $connection 连接名
	 * @param string $prefix 前缀
	 * @param array $servers 配置
	 * @return object
	 */
	function getRedis($connection = '', $prefix = '', $servers = [])
	{
		static $redis = [];
		if (! isset($redis[$connection])) {
			$RedisDb = new \Colaphp\Redis\RedisDb($servers);
			$redis[$connection] = new \Colaphp\Redis\RedisStore($RedisDb, $prefix, $connection);
		}
		return $redis[$connection];
	}
}
