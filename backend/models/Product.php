<?php
require '../vendor/autoload.php';

class Product extends \Illuminate\Database\Eloquent\Model
{
	public function orders()
    {
        return $this->belongsToMany('\Order');
    }
}