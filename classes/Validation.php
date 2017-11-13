<?php
class Validation {
		private $_passed = false, 
				$_error = array(),
				$_db = null;

	public function __construct(){
		$this->_db = DB::getInstance(); 
	}


	public function check($source, $items = array()) {
		foreach($items as $item => $rules){
			$screenName = $rules[Config::getScreen()];
			if(empty($screenName)){
				$screenName = $item;
			}
			foreach($rules as $rule => $rule_value){
				$value = $source[$item];

				if($rule === "require" && empty($value)){
					$this->addError("{$screenName} is required");

				} else if(!empty($value)){

					switch ($rule) {
						case 'min':
							if($rule_value > strlen($value)){
								$this->addError("{$screenName} must have atleast {$rule_value} character/s. ");
							}
							break;
						case 'max':
							if($rule_value < strlen($value)){
								$this->addError("{$screenName} must a maximum of {$rule_value} character/s. ");
							}
							break;
						case 'matches':
							if($source[$rule_value] != $value){
								$this->addError($items[$rule_value][Config::getScreen()] . " didn't match"); //to {$screenName}. 
							}
							break;
						case 'unique':
							$check = $this->_db->select($rule_value, array( $item, '=', $value )); 

							if($check->count()){
								$this->addError("{$screenName} {$value} is already exist"); 


							}
							break;
						default:
							# code...
							break;
					}
				}
			}



		}

		if(empty($this->errors())){
			$this->_passed = true;
		}

			return $this;
	
	}

	

	private function addError($error){
		$this->_error[] = $error; 

	}

	public function errors(){
		return $this->_error; 
	}

	public function passed() {
		return $this->_passed;  
	}
}
?>