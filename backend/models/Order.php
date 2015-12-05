<?php
require '../vendor/autoload.php';

class Order extends \Illuminate\Database\Eloquent\Model
{
	protected $fillable = array('purpose', 'city', 'postal_code', 'address', 'comment');

	public function products()
    {
        return $this->belongsToMany('\Product');
    }

    public function user()
    {
    	return $this->belongsTo('\User');
    }
}