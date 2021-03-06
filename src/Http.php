<?php

namespace Moccalotto\SshPortal;

use RuntimeException;

class Http extends Singletonian
{
    /**
     * @var array
     */
    protected $defaultContextOptions;

    /**
     * Constructor.
     */
    protected function __construct()
    {
        $this->defaultContextOptions = [
            'http' => Config::get('http'),
            'ssl' => Config::get('https'),
        ];
    }

    /**
     * Make an HTTP request.
     *
     * @param string       $url
     * @param string|array $content
     * @param array        $headers
     * @param string       $method
     *
     * @return string
     */
    public function doRequest(string $url, $content = null, array $headers = [], string $method = 'GET')
    {
        $context_options = array_replace_recursive($this->defaultContextOptions, [
            'http' => [
                'method' => $method,
                'header' => $headers,
                'content' => $content,
            ],
        ]);

        $response = file_get_contents($url, false, stream_context_create($context_options));

        return $response;
    }
}
