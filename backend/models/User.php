<?php
require '../vendor/autoload.php';

class User extends BaseModel
{
	protected $connection = 'userDb';
	protected $table      = 'Users';
	protected $hidden     = ['password_hash'];
	protected $casts      = [
    							'active'   => 'boolean',
    							'verified' => 'boolean'
							];
	public $timestamps    = false;
}