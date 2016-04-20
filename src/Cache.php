<?php

namespace Moccalotto\SshPortal;

class Cache extends Singletonian
{
    /**
     * @var array
     */
    protected $cache = [];

    protected function __construct()
    {
        $this->loadCache();
    }

    protected function cacheFilename()
    {
        return getenv('HOME').'/.ssh-portal.cache';
    }

    protected function loadCache()
    {
        $file = $this->cacheFilename();

        $cacheTimeout = Config::get('cache.timeout');

        if (!is_file($file)) {
            return false;
        }

        if (!is_readable($file)) {
            return false;
        }

        if (filemtime($file) < time() - $cacheTimeout) {
            return false;
        }

        $this->cache = json_decode(file_get_contents($file), true);
    }

    protected function dumpCache()
    {
        $file = $this->cacheFilename();

        return file_put_contents($file, json_encode($this->cache));
    }

    public function doGet(string $key, $default = null)
    {
        if (!array_key_exists($key, $this->cache)) {
            return $default;
        }

        return $this->cache[$key];
    }

    public function doSet(string $key, $value, $dumpCache = true)
    {
        $this->cache[$key] = $value;

        if ($dumpCache) {
            $this->dumpCache();
        }
    }

    public function doClear()
    {
        $this->cache = [];

        $file = $this->cacheFilename();

        if (is_file($file) && is_writeable($file)) {
            unlink($file);
        }
    }
}
