<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 *
 * @property int $id
 * @property string $post
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 * @method static insert(array $array)
 */
class Post extends Model
{
	public $table = 'posts';
	public $incrementing = false;
	public $connection = 'mysql';
	protected $casts = [
		'id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
	    'id',
		'post',
		'user_id'
	];
}
