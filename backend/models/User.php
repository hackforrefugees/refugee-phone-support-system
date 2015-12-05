<?php
require '../vendor/autoload.php';

class User extends \Illuminate\Database\Eloquent\Model
{
	protected $connection = 'userDb';
	protected $table      = 'Users';
	protected $hidden     = ['password_hash'];
}