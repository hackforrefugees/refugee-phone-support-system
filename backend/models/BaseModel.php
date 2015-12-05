<?php
require '../vendor/autoload.php';

class BaseModel extends \Illuminate\Database\Eloquent\Model
{
	public $errors;

	public function validate($data) {
    	$v = new \Valitron\Validator($data);
    	$v->rules($this->rules);

    	if ($v->validate()) {
    		return true;
    	} else {
    		$this->errors = $v->errors();
    		return false;
    	}
    }
}