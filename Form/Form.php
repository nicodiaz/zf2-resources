<?php

/**
 * General FORM Class
 *
 * @copyright Copyright (c) 2015 Nico Diaz (http://www.nicodp.com.ar)
 * @author Nicolás Díaz País <nico@diazpais.com.ar>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Form;

use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;

/**
 * Main Class for every form constructed for the application
 */
class Form extends \Zend\Form\Form
{

    /**
     * Factories to be used in the creation of the form
     */
    protected $_inputFilter;

    protected $_factory;

    function __construct()
    {
        if (empty($this->_inputFilter))
        {
            $this->_inputFilter = new InputFilter();
        }
        
        if (empty($this->_factory))
        {
            $this->_factory = new Factory();
        }
        
        parent::__construct();
    }

    /**
     * Function to create an array of options, that can be used with multiple checkboxes or radios
     *
     * @param string $name            
     * @param bool $required            
     * @param bool $acceptEmptyValues            
     * @return boolean
     */
    public function addArrayOfOptions($name, $required = false, $acceptEmptyValues = false)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Callback',
                    'options' => array(
                        'callback' => function ($value, $acceptEmptyValues) use($acceptEmptyValues)
                        {
                            return $this->validateArrayOnlyInt($value, $acceptEmptyValues);
                        }
                    )
                )
            )
        )));
    }

    /**
     * Function to create a field that contain the validator of a digit.
     *
     *
     * This can be used as any field, on
     *
     * @param string $name            
     * @param bool $required            
     */
    public function addDigitsField($name, $required)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Digits'
                )
            )
        )));
    }

    /**
     * Function to create a field that contain the validator and filter of integer (greater than cero).
     *
     *
     * This can be used as any field, on
     *
     * @param string $name            
     * @param bool $required            
     */
    public function addDigitsGreaterThanCeroField($name, $required)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Digits'
                ),
                array(
                    'name' => 'GreaterThan',
                    'min' => 0,
                    'inclusive' => false
                )
            )
        )));
    }

    /**
     * Function to create a field that contain the validator and filter of integer (greater than cero) and the
     * value is between the min and max (inclusive depending the param)
     *
     * This can be used as any field, on
     *
     * @param string $name            
     * @param bool $required            
     */
    public function addNumberBetweenValues($name, $required, $min, $max, $inclusive = false)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => $min,
                        'max' => $max,
                        'inclusive' => $inclusive
                    )
                )
            )
        )));
    }

    /**
     * Function to create a field that contain the validator and filter of float (greater than cero) for use
     * in curreny fields
     *
     * This can be used as any field, on
     *
     * @param string $name            
     * @param bool $required            
     */
    public function addCurrencyGreaterThanCeroField($name, $required)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Callback',
                    'options' => array(
                        'callback' => function ($value)
                        {
                            return $this->validateFloatGreaterThanCero($value, true, 100000);
                        }
                    )
                )
            )
        )));
    }

    /**
     * Function to create a field that contain the validator of CUIT
     *
     * @param string $name            
     * @param bool $required            
     */
    public function addCUITField($name, $required)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Callback',
                    'options' => array(
                        'callback' => function ($value)
                        {
                            return $this->validateCUIT($value);
                        }
                    )
                )
            )
        )));
    }

    /**
     * Function to create a text box field.
     *
     * Also, apply the strip tags and trim filters.
     *
     * Use UTF8 charset
     *
     * @param string $name            
     * @param bool $required            
     * @param int $min            
     * @param int $max            
     */
    public function addTextBoxStripAndTrim($name, $required = false, $max = 255, $min = 1)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => $min,
                        'max' => $max
                    )
                )
            ),
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));
    }

    /**
     * Function to create a text box field that validate as a email address
     *
     * @param string $name            
     * @param bool $required            
     */
    public function addTextBoxEmail($name, $required = false)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                )
            )
        )));
    }

    /**
     * Function to create a simple text box, that contains validator over lenght
     *
     * Doesn't apply any filter
     *
     * @param string $name            
     * @param bool $required            
     * @param int $min            
     * @param int $max            
     */
    public function addTextBox($name, $required = false, $max = 255, $min = 1)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => $min,
                        'max' => $max
                    )
                )
            )
        )));
    }

    /**
     * Function to create a simple text box, that contains validator as date (dd-MM-yyyy)
     *
     * Doesn't apply any filter
     *
     * @param string $name            
     * @param bool $required            
     * @param int $min            
     * @param int $max            
     */
    public function addTextBoxDate($name, $required = false)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'd/m/Y' // Use the format of DateTime::createFromFormat
                                        )
                )
            )
        )));
    }

    /**
     * Function to create a simple text box, that contains validator as hours (H:i)
     *
     * Doesn't apply any filter
     *
     * @param string $name            
     * @param bool $required            
     * @param int $min            
     * @param int $max            
     */
    public function addTextBoxHour($name, $required = false)
    {
        $this->_inputFilter->add($this->_factory->createInput(array(
            'name' => $name,
            'required' => $required,
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'H:i'
                    )
                )
            )
        )));
    }

    /**
     * Function to retrieve in array the id of the params that fails the validation
     *
     * @return array
     */
    public function getErrorParamNames()
    {
        $errors = array();
        
        foreach ($this->getInputFilter()->getMessages() as $name => $value)
        {
            $errors[] = $name;
        }
        
        return $errors;
    }

    /**
     * Validate that every options of a select consist of int convertible valuables (greater than 0)
     */
    protected function validateArrayOnlyInt($array, $acceptEmptyValues = false)
    {
        // Preconditions
        if (! is_array($array))
        {
            return false;
        }
        
        foreach ($array as $value)
        {
            if ($acceptEmptyValues && empty($value))
            {
                continue;
            }
            if (! is_numeric($value))
            {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Validate that the value is a valid CUIT
     */
    protected function validateCUIT($value)
    {
        $mult = '5432765432'; // Established array
        $mult = str_split($mult);
        
        if (strlen($value) == 11)
        {
            $cuit = str_split($value);
            $result = 0;
            
            for ($i = 0; $i <= 9; $i ++)
            {
                $result += $cuit[$i] * $mult[$i];
            }
            
            $result = ($result % 11);
            $result = 11 - $result;
            
            if ($result == 11)
            {
                $result = 0;
            }
            
            if ($result == 10)
            {
                $result = 9;
            }
            
            if ($result == $cuit[10])
            {
                return true;
            }
        }
        
        return false;
        
        /*
         * var aMult = '5432765432'; var aMult = aMult.split(''); if (sCUIT && sCUIT.length == 11) { aCUIT = sCUIT.split(''); var iResult = 0; for(i = 0; i <= 9; i++) { iResult += aCUIT[i] * aMult[i]; } iResult = (iResult % 11); iResult = 11 - iResult; if (iResult == 11) iResult = 0; if (iResult == 10) iResult = 9; if (iResult == aCUIT[10]) { return true; } } return false;
         */
    }

    /**
     * Validate a number as float.
     * Optionally can accepter greater than cero or not
     *
     * @param string $value            
     * @param boolean $greaterThanCero            
     */
    protected function validateFloatGreaterThanCero($value, $greaterThanCero = false, $max = 100000.00)
    {
        $result = preg_match('/^\$?[0-9]+(,[0-9]{3})*(.[0-9]{0,2})?$/', $value);
        
        if ($result == 0)
        {
            return false; // isn't a valid float string
        }
        
        // If reach here, then is a valid float
        $val = floatval($value);
        
        return $greaterThanCero ? $val > 0.0 && $val < $max : true;
    }
}

