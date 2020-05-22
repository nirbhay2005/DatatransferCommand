<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPost
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $emails
 * @property string $post
 * @property string $comment
 * @property int $comment_user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 * @method static where(string $string, string $string1, int $getLastId)
 * @method static select(string $string, string $string1, string $string2, string $string3, \Illuminate\Database\Query\Expression $raw)
 *
 *
 *
 */
class UserPost extends Model
{
	public $table = 'user_post';
	public $connection = 'mysql2';

	protected $casts = [
		'user_id' => 'int',
		'comment_user' => 'int'
	];

	protected $fillable = [
	    'id',
		'user_id',
		'name',
		'emails',
		'post',
		'comment',
		'comment_user'
	];
}
