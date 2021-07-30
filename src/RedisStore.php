<?php

declare(strict_types=1);
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Colaphp\Redis;

class RedisStore implements StoreInterface
{
	/**
	 * db连接.
	 */
	protected $redis;

	/**
	 * 前缀
	 */
	protected $prefix;

	/**
	 * 连接名.
	 */
	protected $connection;

	/**
	 * 初始化.
	 *
	 * @param object $redis
	 * @param string $prefix
	 * @param string $connection
	 */
	public function __construct(RedisDb $redis, $prefix = '', $connection = 'default')
	{
		$this->redis = $redis;
		$this->connection = $connection;
		$this->prefix = strlen($prefix) > 0 ? $prefix . ':' : '';
	}

	/**
	 * 动态命令.
	 *
	 * @param string $method
	 * @param array $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return $this->connection()->{$method}(...$parameters);
	}

	/**
	 * 获取.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		if (! is_null($value = $this->connection()->get($this->prefix . $key))) {
			return is_numeric($value) ? $value : unserialize($value);
		}
	}

	/**
	 * 设置（生命周期）|setex.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $minutes
	 */
	public function put($key, $value, $minutes)
	{
		$value = is_numeric($value) ? $value : serialize($value);

		$minutes = max(1, $minutes);

		$this->connection()->setex($this->prefix . $key, $minutes * 60, $value);
	}

	/**
	 * 将 key 中储存的数字加上指定的增量值
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return int
	 */
	public function increment($key, $value = 1)
	{
		return $this->connection()->incrby($this->prefix . $key, $value);
	}

	/**
	 * 将 key 所储存的值减去指定的减量值。
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return int
	 */
	public function decrement($key, $value = 1)
	{
		return $this->connection()->decrby($this->prefix . $key, $value);
	}

	/**
	 * 设置（永久）.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function forever($key, $value)
	{
		$value = is_numeric($value) ? $value : serialize($value);

		$this->connection()->set($this->prefix . $key, $value);
	}

	/**
	 * 删除.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function forget($key)
	{
		return (bool) $this->connection()->del($this->prefix . $key);
	}

	/**
	 * 清空数据库.
	 */
	public function flush()
	{
		$this->connection()->flushdb();
	}

	/**
	 * 获取前缀
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * 获取Redis对象
	 *
	 * @return \Predis\ClientInterface
	 */
	public function connection()
	{
		return $this->redis->connection($this->connection);
	}

	/**
	 * 设置连接名.
	 *
	 * @param string $connection
	 */
	public function setConnection($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * 获取redis实例.
	 *
	 * @return object
	 */
	public function getRedis()
	{
		return $this->redis;
	}
}
