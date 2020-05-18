<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * @property int $id
 * @property string $comment
 * @property int $post_id
 * @property int $comment_user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 *
 * @package Laravel\Models
 * @method static insert(array $array)
 */
class Comment extends Model
{
	public $table = 'comments';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'post_id' => 'int',
		'comment_user' => 'int'
	];

	protected $fillable = [
	    'id',
		'comment',
		'post_id',
		'comment_user'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'comment_user');
	}
}
