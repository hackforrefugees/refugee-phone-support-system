<?php
require '../vendor/autoload.php';

class Module extends BaseModel
{
	protected $fillable = array('name');

	protected $rules = array(
		'required' => [['name']]
	);
}
