Zend Framework 2 Resources
==========================

Introduction
------------
This is a simple repo, containing shared classes to be used in zend framework 2 projects. 


Form
====

Installation
------------

Add validation helpers to form elements, that are not included in the framework. 
The idea behind is create the form element and add the validator.

1. $ zf2-resources/Form/Form.php path-to-project/module/Application/src/Application/Form/
1. Change the namespace (your current namespace)
2. Extend from Form


Example
-------

```php
class Register extends Form 
{
	function __construct()
	{
		parent::__construct();
		
		$this->setInputFilter($this->createInputFilter());
	}

	/**
	 * Function to create the validators and filters of the form
	 * 
	 * @return \Zend\InputFilter\InputFilter
	 */
	function createInputFilter()
	{
		$inputFilter = $this->_inputFilter;
		$factory = $this->_factory;
		
		parent::addTextBoxEmail('email', true);
		
		return $inputFilter;
	}
}
```

