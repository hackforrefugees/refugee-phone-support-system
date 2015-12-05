<?php
require '../vendor/autoload.php';

class Product extends BaseModel
{
	public function orders()
    {
        return $this->belongsToMany('\Order');
    }
}