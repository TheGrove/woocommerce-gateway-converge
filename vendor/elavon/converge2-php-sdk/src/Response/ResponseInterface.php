<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\Client\Response\RawResponseInterface;
use Elavon\Converge2\DataObject\Resource\FailureInterface;

interface ResponseInterface extends FailureInterface
{
    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @param bool $success
     */
    public function setSuccess($success);

    /**
     * @return string|null
     */
    public function getShortErrorMessage();

    /**
     * @return string|null
     */
    public function getRawErrorMessage();

    /**
     * @return bool
     */
    public function hasRawResponse();

    /**
     * @return RawResponseInterface|null
     */
    public function getRawResponse();

    /**
     * @return string|null
     */
    public function getRawResponseBody();

    /**
     * @return int
     */
    public function getRawResponseStatusCode();

    public function setShortErrorMessage($message);

    public function setRawErrorMessage($message);

    public function setRawResponse(RawResponseInterface $response = null);

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return \Exception|null
     */
    public function getException();

    public function setException(\Exception $e = null);
}
