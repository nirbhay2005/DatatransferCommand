<?php

/**
 * Created by Reliese Model.
 */

namespace Laravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * 
 * @property int $id
 * @property int $last_post_id
 * @property int $last_comment_id
 * @property int $last_user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 */
class Log extends Model
{
	protected $table = 'log';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'last_post_id' => 'int',
		'last_comment_id' => 'int',
		'last_user_id' => 'int'
	];

	protected $fillable = [
		'id',
		'last_post_id',
		'last_comment_id',
		'last_user_id'
	];
}
