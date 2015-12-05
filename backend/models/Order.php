<?php
require '../vendor/autoload.php';

class Order extends \Illuminate\Database\Eloquent\Model
{
	protected $fillable = array('user_id', 'comment');

	public function products()
    {
        return $this->belongsToMany('\Product');
    }
}