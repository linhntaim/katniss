<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 10:09
 */

namespace Katniss\Models\Helpers\Cache;

use Memcache;
use Illuminate\Cache\MemcachedConnector;
use RuntimeException;

class MemcacheConnector extends MemcachedConnector
{
    /**
     * Create a new Memcached connection.
     *
     * @param  array $servers
     * @return \Memcached
     *
     * @throws \RuntimeException
     */
    public function connect(array $servers)
    {
        $memcached = $this->getMemcached();

        // For each server in the array, we'll just extract the configuration and add
        // the server to the Memcached connection. Once we have added all of these
        // servers we'll verify the connection is successful and return it back.
        foreach ($servers as $server) {
            $memcached->addserver(
                $server['host'], $server['port'], $server['weight']
            );
        }

        $memcachedStatus = $memcached->getversion();

        if ($memcachedStatus === false) {
            throw new RuntimeException('No Memcached servers added.');
        }

        if (in_array('255.255.255', (array)$memcachedStatus) && count(array_unique((array)$memcachedStatus)) === 1) {
            throw new RuntimeException('Could not establish Memcached connection.');
        }

        return $memcached;
    }

    /**
     * Get a new Memcached instance.
     *
     * @return Memcache
     */
    public function getMemcached()
    {
        return new Memcache();
    }
}