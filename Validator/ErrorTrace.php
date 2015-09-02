<?php

namespace TPN\EmailTemplatesComponent\Validator;

/**
 * Class ErrorTrace.
 */
class ErrorTrace
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param $path
     * @param $message
     */
    public function addMessage($path, $message = '')
    {
        $this->errors[$path][] = $message;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * @return string
     */
    public function dump()
    {
        $result = array_map(function ($v, $k) {
            return sprintf('%s %s', $k, implode("\n", $v));
        }, $this->errors, array_keys($this->errors));

        return implode("\n", $result);
    }
}
