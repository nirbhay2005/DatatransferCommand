<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $emails
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection|Comment[] $comments
 *
 * @package Laravel\Models
 * @method static insert(array $array)
 */
class User extends Model
{
	public $table = 'users';
	public $connection = 'mysql';
	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
	    'id',
		'name',
		'emails',
		'email_verified_at',
		'password',
		'remember_token'
	];
/*
	public function comments()
	{
		return $this->hasMany(Comment::class, 'comment_user');
	}*/
}
