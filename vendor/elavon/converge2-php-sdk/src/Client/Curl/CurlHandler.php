<?php

namespace Elavon\Converge2\Client\Curl;

use Elavon\Converge2\Client\Response\RawResponseInterface;
use Elavon\Converge2\Request\RequestInterface;

/**
 * Represents a cURL easy handle and the data it populates.
 *
 * @internal
 */
final class CurlHandler
{
    /** @var resource cURL resource */
    protected $curlHandle;

    /** @var String Where data is being written */
    protected $body;

    /** @var array Received HTTP headers so far */
    protected $headers = array();

    /** @var RawResponseInterface Received response (if any) */
    protected $response;

    /** @var RequestInterface Request being sent */
    protected $request;

    /** @var array Request options */
    protected $options = array();

    /** @var array Curl config */
    protected $curlConf = array();

    public function __construct(RequestInterface $request, array $options = array())
    {
        $this->request = $request;
        $this->options = $options;
    }

    public function init()
    {
        $this->curlConf = $this->getDefaultConf();
        $this->addBodyToConf();
        $this->addOptionsToConf();
        $this->addHeadersToConf();

        $this->curlConf[CURLOPT_HEADERFUNCTION] = $this->getResponseHeaderCallback();
        $this->curlHandle = curl_init();
        curl_setopt_array($this->curlHandle, $this->curlConf);
    }

    public function release()
    {
        curl_close($this->curlHandle);
        unset($this->curlHandle);
    }

    public function exec()
    {
        $ret = curl_exec($this->curlHandle);
        if ($ret !== false) {
            $this->body = $ret;
        }
        $this->createResponse();
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function getDefaultConf()
    {
        $base = '';
        if (isset($this->options['base_uri'])) {
            $base = trim($this->options['base_uri'], '/') . '/';
        }

        $conf = [
            '_headers' => $this->request->getHeaders(),
            CURLOPT_CUSTOMREQUEST => $this->request->getMethod(),
            CURLOPT_URL => $base . $this->request->getUri(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_CONNECTTIMEOUT => 150,
        ];

        if (defined('CURLOPT_PROTOCOLS')) {
            $conf[CURLOPT_PROTOCOLS] = CURLPROTO_HTTP | CURLPROTO_HTTPS;
        }

        $conf[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;

        if (!empty($this->options['auth']) && is_array($this->options['auth'])) {
            $value = $this->options['auth'];
            $type = isset($value[2]) ? strtolower($value[2]) : 'basic';
            if ($type == 'basic') {
                $conf['_headers']['Authorization'] = array('Basic ' . base64_encode("$value[0]:$value[1]"));
            }
        }


        return $conf;
    }

    protected function addBodyToConf()
    {
        $size = strlen($this->request->getBody());

        if ($size === null || $size > 0) {
            $this->curlConf[CURLOPT_POSTFIELDS] = $this->request->getBody();
            $this->removeHeader('Content-Length', $this->curlConf);
            $this->removeHeader('Transfer-Encoding', $this->curlConf);

            if (!$this->request->hasHeader('Expect')) {
                $this->curlConf[CURLOPT_HTTPHEADER][] = 'Expect:';
            }

            if (!$this->request->hasHeader('Content-Type')) {
                $this->curlConf[CURLOPT_HTTPHEADER][] = 'Content-Type:';
            }
        } else {
            $method = $this->request->getMethod();
            if (($method === 'PUT' || $method === 'POST') && !$this->request->hasHeader('Content-Length')) {
                $this->curlConf[CURLOPT_HTTPHEADER][] = 'Content-Length: 0';
            }
        }
    }

    protected function addHeadersToConf()
    {
        foreach ($this->curlConf['_headers'] as $name => $values) {
            foreach ($values as $value) {
                $value = (string)$value;
                if ($value === '') {
                    $this->curlConf[CURLOPT_HTTPHEADER][] = "$name;";
                } else {
                    $this->curlConf[CURLOPT_HTTPHEADER][] = "$name: $value";
                }
            }
        }

        unset($this->curlConf['_headers']);

        if (!$this->request->hasHeader('Accept')) {
            $this->curlConf[CURLOPT_HTTPHEADER][] = 'Accept:';
        }
    }

    protected function removeHeader($name, array &$options)
    {
        foreach (array_keys($options['_headers']) as $key) {
            if (!strcasecmp($key, $name)) {
                unset($options['_headers'][$key]);
                return;
            }
        }
    }

    protected function addOptionsToConf()
    {
        if (isset($this->options['verify'])) {
            if ($this->options['verify'] === false) {
                unset($this->curlConf[CURLOPT_CAINFO]);
                $this->curlConf[CURLOPT_SSL_VERIFYHOST] = 0;
                $this->curlConf[CURLOPT_SSL_VERIFYPEER] = false;
            } else {
                $this->curlConf[CURLOPT_SSL_VERIFYHOST] = 2;
                $this->curlConf[CURLOPT_SSL_VERIFYPEER] = true;
            }
        }

        if (!empty($this->options['decode_content'])) {
            $accept = $this->request->getHeaderLine('Accept-Encoding');
            if ($accept) {
                $this->curlConf[CURLOPT_ENCODING] = $accept;
            } else {
                $this->curlConf[CURLOPT_ENCODING] = '';
                $this->curlConf[CURLOPT_HTTPHEADER][] = 'Accept-Encoding:';
            }
        }

        $timeoutRequiresNoSignal = false;
        if (isset($this->options['timeout'])) {
            $timeoutRequiresNoSignal |= $this->options['timeout'] < 1;
            $this->curlConf[CURLOPT_TIMEOUT_MS] = $this->options['timeout'] * 1000;
        }

        if (isset($this->options['connect_timeout'])) {
            $timeoutRequiresNoSignal |= $this->options['connect_timeout'] < 1;
            $this->curlConf[CURLOPT_CONNECTTIMEOUT_MS] = $this->options['connect_timeout'] * 1000;
        }

        if ($timeoutRequiresNoSignal && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $this->curlConf[CURLOPT_NOSIGNAL] = true;
        }

        if (isset($this->options['proxy']) && is_string($this->options['proxy'])) {
            $this->curlConf[CURLOPT_PROXY] = $this->options['proxy'];
        }
    }

    protected function getResponseHeaderCallback()
    {
        return function ($ch, $h) use (
            &$startingBody
        ) {
            $value = trim($h);
            if ($value === '') {
                $startingBody = true;
            } elseif ($startingBody) {
                $startingBody = false;
                $this->headers = [$value];
            } else {
                $this->headers[] = $value;
            }
            return strlen($h);
        };
    }

    protected function createResponse()
    {
        if (empty($this->headers) || curl_errno($this->curlHandle)) {
            $msg = curl_error($this->curlHandle);
            if (!$msg) {
                $msg = 'No headers have been received';
            }
            throw new \RuntimeException($msg);
        }

        $startLine = explode(' ', array_shift($this->headers), 3);
        $_headers = $this->headersFromLines($this->headers);
        $normalizedKeys = $this->normalizeHeaderKeys($_headers);

        if (!empty($this->options['decode_content'])
            && isset($normalizedKeys['content-encoding'])
        ) {
            $_headers['x-encoded-content-encoding']
                = $_headers[$normalizedKeys['content-encoding']];
            unset($_headers[$normalizedKeys['content-encoding']]);
            if (isset($normalizedKeys['content-length'])) {
                $_headers['x-encoded-content-length']
                    = $_headers[$normalizedKeys['content-length']];

                $bodyLength = strlen($this->body);
                if ($bodyLength) {
                    $_headers[$normalizedKeys['content-length']] = $bodyLength;
                } else {
                    unset($_headers[$normalizedKeys['content-length']]);
                }
            }
        }

        // Attach a response to the easy handle with the parsed headers.
        $this->response = new CurlResponse(
            $startLine[1],
            $_headers,
            $this->body,
            substr($startLine[0], 5),
            isset($startLine[2]) ? (string)$startLine[2] : null
        );
    }

    protected function headersFromLines($lines)
    {
        $_headers = array();

        foreach ($lines as $item) {
            $parts = explode(':', $item, 2);
            $_headers[trim($parts[0])][] = isset($parts[1])
                ? trim($parts[1])
                : null;
        }

        return $_headers;
    }

    function normalizeHeaderKeys(array $headers)
    {
        $result = array();
        foreach (array_keys($headers) as $key) {
            $result[strtolower($key)] = $key;
        }

        return $result;
    }
}
