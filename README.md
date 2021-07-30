# colaphp-redis

版本^1.0
# require predis/predis

```php

$servers = [
	'default' => [
		'host' => '192.168.0.1',
		'port' => 6379,
		'database' => 1,
	],
	'options' => [
        'cluster' => 'redis'
    ],
	'cluster' => false,//分布式配置
];

//实例
$RedisDb = new \Colaphp\Redis\RedisDb($servers);
$RedisStore =  new \Colaphp\Redis\RedisStore($RedisDb, 'prefix', 'default');
//也可直接用助手函数
$RedisStore = getRedis('prefix', 'default', $servers);

//调用
$RedisStore->get
$RedisStore->put
...

```