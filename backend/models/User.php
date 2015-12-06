<?php
require '../vendor/autoload.php';

class User extends BaseModel
{
	protected $hidden     = ['password_hash'];
	protected $casts      = [
    							'active'   => 'boolean',
    							'verified' => 'boolean'
								];

	public function modules()
	{
		return $this->hasMany('\Module');
	}

}
