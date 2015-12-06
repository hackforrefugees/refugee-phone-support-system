<?php
require '../vendor/autoload.php';

class Area extends BaseModel
{
	protected $fillable = array('name');

	protected $rules = array(
		'required' => [['name']]
	);

	public function orders()
    {
        return $this->hasMany('\Order');
    }
}