<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Colaphp\Redis;

interface StoreInterface
{
	/**
	 * 获取.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 * 设置（生命周期）|setex.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $minutes
	 */
	public function put($key, $value, $minutes);

	/**
	 * 将 key 中储存的数字加上指定的增量值
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return bool|int
	 */
	public function increment($key, $value = 1);

	/**
	 * 将 key 所储存的值减去指定的减量值。
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return bool|int
	 */
	public function decrement($key, $value = 1);

	/**
	 * 设置（永久）.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function forever($key, $value);

	/**
	 * 删除.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function forget($key);

	/**
	 * 清空数据库.
	 */
	public function flush();

	/**
	 * 获取前缀
	 *
	 * @return string
	 */
	public function getPrefix();
}
