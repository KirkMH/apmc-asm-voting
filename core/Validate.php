<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct(){
		$this->_db = new MyDb();
	}

	public function check($source, $items = array()){
		foreach ($items as $item => $rules) {
			foreach($rules as $rule => $rule_value){
				// print_r($rule_value. '</br>');
				$tag_name;
				$value = trim($source[$item]);
				$filter = isset($source['filter']) ? $source['filter'] : '';
				if ($filter !== '')
					$filter_val = $source[$filter]; # this also means update

				if($rule === 'tag_name'){
					$tag_name = $rule_value;
				}

				if($rule === 'required' && empty($value)){
					// $replace_item = str_replace('_', ' ', $item);
					$this->addError("{$tag_name} is required");
				} else if(!empty($value)){
					switch ($rule) {
						case 'min':
							if(strlen($value) < $rule_value){
								// $this->addError(1);
								$this->addError("{$tag_name} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value){
								$this->addError("{$tag_name} must be a maximum of {$rule_value} characters.");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]){
								// $this->addError("{$rule_value} must be match {$item}");
								$this->addError("{$tag_name} don't match");
							}
						break;
						case 'unique':
							$add = "";
							$addfilter = "";
							if ($filter !== "" && $filter_val !== "") {
								$add = "{$filter}";
								$addfilter = "{$filter_val}";

								$check = MyDb::run("SELECT * FROM $rule_value WHERE $item = ? AND $add != ?", [$value, $addfilter]);
							}
							else{
								$check = MyDb::run("SELECT * FROM $rule_value WHERE $item = ?", [$value]);
							}
							//echo "{$item}='{$value}' {$add}";
							if($check->rowCount()){
								$this->addError("{$tag_name} already exists.");
							}
						break;
						case 'alphanumeric':
							if (!preg_match("/^[a-z0-9]+([\\s]{1}[a-z0-9]|[a-z0-9])+$/i", $value)) {
							   $this->addError("{$tag_name} must be combination of letters and numbers.");
							}
						break;
						case 'numeric':
							// if (!preg_match('/^\d+$/', $value)) {
							if (!is_numeric($value)) {
							   $this->addError("{$tag_name} must be numeric.");
							}
						break;
						case 'email';
							if(filter_var($value, FILTER_VALIDATE_EMAIL) === false){
								$this->addError("Email is not valid");
							}
						break;
					}
				}

			}
			
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}

	public function addError($error){
		$this->_errors[] = $error;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		return $this->_passed;
	}
}

?>