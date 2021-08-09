<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Colaphp\Redis;

use Predis\Client;

class RedisDb
{
	/**
	 * Redis连接.
	 *
	 * @var array
	 */
	protected $clients;

	/**
	 * 初始化.
	 */
	public function __construct(array $servers = [])
	{
		if (isset($servers['cluster']) && $servers['cluster']) {
			$this->clients = $this->createAggregateClient($servers);
		} else {
			$this->clients = $this->createSingleClients($servers);
		}
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
		return $this->command($method, $parameters);
	}

	/**
	 * 获取连接.
	 *
	 * @param string $name
	 * @return \Predis\ClientInterface
	 */
	public function connection($name = 'default')
	{
		return $this->clients[$name ?: 'default'];
	}

	/**
	 * 执行命令.
	 *
	 * @param string $method
	 * @return mixed
	 */
	public function command($method, array $parameters = [])
	{
		return call_user_func_array([$this->clients['default'], $method], $parameters);
	}

	/**
	 * 集群形式.
	 *
	 * @return array
	 */
	protected function createAggregateClient(array $servers)
	{
		$options = $this->getClientOptions($servers);
		$servers = array_diff_key($servers, array_flip(['cluster', 'options']));

		return ['default' => new Client(array_values($servers), $options)];
	}

	/**
	 * 单连接.
	 *
	 * @return array
	 */
	protected function createSingleClients(array $servers)
	{
		$clients = [];

		$options = $this->getClientOptions($servers);
		$servers = array_diff_key($servers, array_flip(['cluster', 'options']));

		foreach ($servers as $key => $server) {
			$clients[$key] = new Client($server, $options);
		}

		return $clients;
	}

	/**
	 * 获取配置.
	 *
	 * @return array
	 */
	protected function getClientOptions(array $servers)
	{
		return isset($servers['options']) ? (array) $servers['options'] : [];
	}
}
