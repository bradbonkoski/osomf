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
    const IS_NUM = "_isNumeric";
    const NUM_RANGE = "_numRange";
    const IS_PHONE = "_isPhone";

    private $_validFuncs = array(
        self::IS_STRING,
        self::STRLEN,
        self::IS_NUM,
        self::NUM_RANGE,
        self::IS_PHONE,
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
        if (!is_string($this->_var) && $this->_var != null) {
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

    private function _isNumeric($params)
    {
        if (!is_numeric($this->_var)) {
            throw new \Exception("Validation Error: {$this->_var} is not a number");
        }
    }

    private function _numRange($params)
    {
        if (isset($params['min'])) {
            $min = $params['min'];
        } else {
            $min = 0; //default value for min
        }

        if (!isset($params['max'])) {
            throw new \Exception("Validation Error: Cannot have undefined Max for NumRange Validator");
        }
        $max = $params['max'];
        if ($this->_var > $max) {
            throw new \Exception("Validation Error: {$this->_var} is Greater than Max");
        }
        if ($this->_var < $min) {
            throw new \Exception("Validation Error: {$this->_var} is Less than Min");
        }
    }

    private function _isPhone($params)
    {
        //echo "Validating phone number: {$this->_var}\n";
        /**
         * Need to Check to see if it is filled in
         * as it could be blank, and allowed based on
         * other validators
         */
        if(strlen($this->_var) <= 0 ) {
            return;
        }
        $pattern = "/(\d)?(\s|-)?(\()?(\d){3}(\))?(\s|-){1}(\d){3}(\s|-){1}(\d){4}/";
        preg_match_all($pattern, $this->_var, $phone);
        if(count($phone[0]) != 1) {
            throw new \Exception("Validation Error: {$this->_var} is not a valid phone number");
        }
    }
}
