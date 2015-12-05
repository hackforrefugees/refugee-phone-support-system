<?php
require '../vendor/autoload.php';

class Order extends BaseModel
{
	protected $fillable = array('purpose', 'city', 'postal_code', 'address', 'comment');

	protected $rules = array(
		'required' => [['city'], ['postal_code'], ['address'], ['purpose']]
	);

	public function products()
    {
        return $this->belongsToMany('\Product');
    }

    public function user()
    {
    	return $this->belongsTo('\User');
    }
}