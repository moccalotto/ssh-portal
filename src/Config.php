<?php

namespace Moccalotto\SshPortal;

class Config extends Singletonian
{
    /**
     * @var array
     */
    protected $config;

    protected $file;

    protected $defaultFile;

    protected function __construct()
    {
        $this->defaultFile = realpath('resources/config.default.php');
        $this->file = getenv('HOME').'/.ssh-portal.config.php';

        $defaults = require $this->defaultFile;

        $config = is_file($this->file) ? require $this->file : [];

        $this->config = array_replace($defaults, $config);
    }

    public function doFile()
    {
        return $this->file;
    }

    public function doDefaultFile()
    {
        return $this->defaultFile;
    }

    /**
     * Get by key via dot notation.
     */
    public function doGet(string $key, $default = null)
    {
        $key_parts = explode('.', $key);
        $rest_of_key = $key;
        $current = $this->config;
        foreach ($key_parts as $sub_key) {
            if (isset($current[$rest_of_key])) {
                return $current[$rest_of_key];
            }
            $rest_of_key = substr($rest_of_key, strlen($sub_key) + 1);
            if (isset($current[$sub_key])) {
                $current = $current[$sub_key];
                continue;
            }
            if (isset($current->$sub_key)) {
                $current = $current->$sub_key;
                continue;
            }

            return $default;
        }

        return $current;
    }

    /**
     * Set by key, using dot notation.
     */
    public function doSet(string $key, $value)
    {
        $key_parts = explode('.', $key);
        $current = &$this->config;
        $last = array_pop($key_parts);
        foreach ($key_parts as $sub_key) {
            if (isset($current[$sub_key])) {
                $current = &$current[$sub_key];
                continue;
            }
            if (isset($current->$sub_key)) {
                $current = &$current->$sub_key;
                continue;
            }
            if (is_array($current)) {
                $current[$sub_key] = [];
                $current = &$current[$sub_key];
                continue;
            }
            if (is_object($current)) {
                $current->$sub_key = [];
                $current = &$current->$sub_key;
                continue;
            }
            throw new RuntimeException(sprintf(
                'Cannot set config for key "%s". Cannot add %s because parent element is neither object nor array',
                $key,
                $sub_key
            ));
        }
        if (is_array($current)) {
            $current[$last] = $value;

            return $this;
        }
        if (is_object($current)) {
            $current->$last = $value;

            return $this;
        }
        throw new RuntimeException(sprintf(
            'Cannot set config for key "%s". Cannot add %s because parent element is neither object nor array',
            $key,
            $last
        ));
    }
}
