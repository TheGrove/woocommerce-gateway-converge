<?php

namespace Elavon\Converge2\Message;

trait MessageTrait {
    /** @var array */
    protected $headers = array();

    /** @var array  */
    protected $headerNames = array();

    /** @var string */
    protected $protocol = '1.1';

    /** @var string */
    protected $body = '';

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($header)
    {
        return isset($this->headerNames[strtolower($header)]);
    }

    public function getHeader($header)
    {
        $header = strtolower($header);

        if (!isset($this->headerNames[$header])) {
            return [];
        }

        $header = $this->headerNames[$header];

        return $this->headers[$header];
    }

    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    protected function setHeaders(array $headers)
    {
        $this->headerNames = $this->headers = array();
        foreach ($headers as $header => $value) {
            if (is_int($header)) {
                $header = (string)$header;
            }

            $value = $this->normalizeHeaderValue($value);
            $normalized = strtolower($header);
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }

    protected function normalizeHeaderValue($value)
    {
        if (!is_array($value)) {
            return $this->trimHeaderValues([$value]);
        }

        return $this->trimHeaderValues($value);
    }

    protected function trimHeaderValues(array $values)
    {
        return array_map(function ($value) {
            return trim((string)$value, " \t");
        }, $values);
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getProtocolVersion()
    {
        return $this->protocol;
    }
}