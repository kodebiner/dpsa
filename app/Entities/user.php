<?php namespace App\Entities;

use Myth\Auth\Entities\User as MythUser;

class User extends MythUser
{
    /**
     * Default attributes.
     * @var array
     */
    protected $attributes = [
    	'firstname' => 'Anonimous',
    	'lastname'  => 'User',
    ];

	/**
	 * Returns a full name: "first last"
	 *
	 * @return string
	 */
	public function getName()
	{
        return ($this->attributes['username']);
		// return trim(trim($this->attributes['firstname']) . ' ' . trim($this->attributes['lastname']));
	}
}