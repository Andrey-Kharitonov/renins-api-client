<?php

namespace ReninsApi\Soap;

class ClientResponseException extends \RuntimeException
{
    protected $errors = [];

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }
}