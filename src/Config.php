<?php

namespace Moccalotto\SshPortal;

class Config extends Singletonian
{
    /**
     * @var array
     */
    protected $config;

    /**
     * The fully qualified filename of the users config file
     *
     * @var string
     */
    protected $file;

    /**
     * The fully qualified filename of the defaults config file.
     *
     * @var string
     */
    protected $defaultsFile;

    protected function __construct()
    {
        $this->defaultsFile = realpath('resources/config.default.php');
        $this->file = getenv('HOME').'/.ssh-portal.config.php';

        $defaults = require $this->defaultsFile;

        $config = is_file($this->file) ? require $this->file : [];

        $this->config = array_replace($defaults, $config);
    }

    /**
     * Get the filename of the user's config file
     */
    public function doFile() : string
    {
        return $this->file;
    }

    /**
     * Get the fully qualified filename of the defaults config file.
     */
    public function doDefaultFile() : string
    {
        return $this->defaultsFile;
    }

    /**
     * Overwrite the user's config file with the default one.
     *
     * @return void
     */
    public function doResetConfig()
    {
        if (!copy($this->defaultsFile, $this->file)) {
            throw new RuntimeException(sprintf(
                'Could not reset config. Could not write to "%s"',
                $this->file
            ));
        }
        chmod($this->file, 0600);
    }

    /**
     * Get config entry by dot notation
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
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
     * Set config entry by key, using dot notation.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
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
