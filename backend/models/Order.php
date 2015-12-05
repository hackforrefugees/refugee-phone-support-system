<?php
require '../vendor/autoload.php';

class Order extends \Illuminate\Database\Eloquent\Model
{
	public function products()
    {
        return $this->belongsToMany('\Product');
    }
}