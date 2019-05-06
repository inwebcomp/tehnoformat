<?php

/**
 * Работа с кэшем
 *
 * Class Cache
 * @package Base
 */
class Cache extends Singleton
{
    protected static $instance;

	/**
     * Объект кэша с использованием оперативной памяти
     */
    public static $memory;

	protected function __construct()
    {
		if (self::isCacheEnabled()) {
			try {
				self::$memory = $this->memcacheConnect();
			}catch(Exception $ex){
				self::$memory = $this->memcachedConnect();
			}
		}
    }

	/**
     * Проверка работы кэша
     *
     * @return bool
     */
	public static function isCacheEnabled()
	{	
		return (bool)Application::$params['use_cache'];
	}

	/**
     * Подключение серверу кэширования
     *
     * @return Memcache
     */
	protected function memcacheConnect()
	{
		$cache = new Memcache();

		if ($cache->connect('127.0.0.1', 11211) == false) {
			throw new Excaption('Could not connect to Memcache host');
		}
		
		$cacheDriver = new \Doctrine\Common\Cache\MemcacheCache();
		$cacheDriver->setMemcache($cache);

		return $cacheDriver;
	}

	/**
     * Подключение серверу кэширования
     *
     * @return Memcached
     */
	protected function memcachedConnect()
	{	
		$cache = new Memcached();
	
		if ($cache->addServer('localhost', 11211) == false) {
			throw new Excaption('Could not connect to Memcached host');
		}

		$cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
		$cacheDriver->setMemcached($cache);

		return $cacheDriver;
	}

	public function clearAll()
	{
		Cache::$memory->flush(3);
	}

	/**
     * Очистка кэша путём прохода по последовательному интервалю целых чисел
     */
	public function deleteByRange($group, $range = 5000)
	{	
		$result = 0;

		for ($i = 1; $i < $range; $i++) {
			if(Cache::$memory->delete($group.'::'.$i))
				$result++;
		}

		return $result;
	}
}