<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\Client\Response\RawResponseInterface;
use Elavon\Converge2\DataObject\CastToDataObjectTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\FailuresGetterTrait;
use Elavon\Converge2\Exception\InvalidBodyException;

class Response implements ResponseInterface
{
    use CastToDataObjectTrait;
    use FailuresGetterTrait;

    /** @var \Exception */
    protected $exception;

    /** @var RawResponseInterface */
    protected $rawResponse;

    /** @var bool */
    protected $success;

    /** @var string */
    protected $shortErrorMessage;

    /** @var string */
    protected $rawErrorMessage;

    /** @var object */
    protected $data;

    public function __construct(ResponseInterface $response = null)
    {
        if ($response) {
            $this->setSuccess($response->isSuccess());
            $this->setRawResponse($response->getRawResponse());
            $this->setShortErrorMessage($response->getShortErrorMessage());
            $this->setRawErrorMessage($response->getRawErrorMessage());
            $this->setException($response->getException());
        }
    }

    public function isSuccess()
    {
        return !empty($this->success);
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getShortErrorMessage()
    {
        return $this->shortErrorMessage;
    }

    public function setShortErrorMessage($message)
    {
        $this->shortErrorMessage = $message;
    }

    public function getRawErrorMessage()
    {
        return $this->rawErrorMessage;
    }

    public function setRawErrorMessage($message)
    {
        $this->rawErrorMessage = $message;
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function hasRawResponse()
    {
        return isset($this->rawResponse);
    }

    public function getRawResponseBody()
    {
        return $this->rawResponse ? (string)$this->rawResponse->getBody() : null;
    }

    public function setRawResponse(RawResponseInterface $response = null)
    {
        $this->rawResponse = $response;
        $data = $this->getData();
        if ($data) {
            $this->setData($data);
        }
    }

    public function getRawResponseStatusCode()
    {
        if ($this->hasRawResponse()) {
            return $this->rawResponse->getStatusCode();
        }

        return 0;
    }

    /**
     * @return mixed|object|null
     * @throws InvalidBodyException
     */
    public function getData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        $body = $this->getRawResponseBody();
        if ($body) {
            try {
                $data = json_decode($body);
                if (JSON_ERROR_NONE !== json_last_error()) {
                    throw new \Exception(
                        'json_decode error: ' . json_last_error_msg()
                    );
                }

                return $data;
            } catch (\Exception $e) {
                if ($this->isSuccess()) {
                    throw new InvalidBodyException($body, 0, $e);
                }
            }
        }

        return null;
    }

    protected function setData($data)
    {
        $this->data = $data;
        $this->castObjectFields();

        // We need to make sure failures are being cast explicitly,
        // since not all Resources cast it in castObjectFields() call.
        $this->castFailures();
    }

    protected function getDataField($field)
    {
        $dt = $this->getData();
        return isset($dt->$field) ? $dt->$field : null;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException(\Exception $e = null)
    {
        $this->exception = $e;
    }
}

