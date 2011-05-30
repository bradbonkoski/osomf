<?php

namespace osomf;

/**
 * User: bradb
 * Date: 5/26/11
 * Time: 8:20 PM
 */
 
class Validator
{

    const IS_STRING = "_isString";
    const STRLEN = "_stringLength";

    private $_validFuncs = array(
        self::IS_STRING,
        self::STRLEN,
    );

    private $_var;
    private $_validators;
    private $_errors;
    public $errNo;

    public function __construct($validators = array())
    {
        $this->_errors = array();
        $this->errNo = 0;
        foreach($validators as $v => $p) {
            if (in_array($v, $this->_validFuncs)) {
                $this->_validators[$v] = $p;
            }
        }
    }

    public function validate($value)
    {
        $this->_var = $value;
        //print_r($this->_validators);
        foreach( $this->_validators as $func => $params) {
            try {
                //echo "Calling User Function: $func\n";
                call_user_func(array($this, $func), $params);
            } catch (\Exception $e) {
                $this->_errors[] = $e->getMessage();
                $this->errNo++;
            }
        }
    }

    public function getErrors()
    {
        if(count($this->_errors) > 0 ) {
            return $this->_errors;
        } else {
            return -1;
        }
    }

    private function _isString($params)
    {
        if (!ctype_alpha($this->_var)) {
            throw new \Exception("Validation Error: {$this->_var} is not a string");
        }
    }

    private function _stringLength($params)
    {
        if (isset($params['min'])) {
            $min = $params['min'];
        } else {
            $min = 1; //default value for min
        }

        if (!isset($params['max'])) {
            throw new \Exception("Validation Error: Cannot have undefined Max for Strlen Validator");
        }
        $max = $params['max'];
        if (strlen($this->_var) > $max) {
            throw new \Exception("Validation Error: {$this->_var} is Longer than Max Length");
        }
        if (strlen($this->_var) < $min) {
            throw new \Exception("Validation Error: {$this->_var} is Less than Min Length");
        }
    }
}
