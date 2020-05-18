<?php

/**
 * Created by Reliese Model.
 */

namespace Laravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OauthAccessToken
 * 
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string $name
 * @property string $scopes
 * @property bool $revoked
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $expires_at
 *
 * @package Laravel\Models
 */
class OauthAccessToken extends Model
{
	protected $table = 'oauth_access_tokens';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'client_id' => 'int',
		'revoked' => 'bool'
	];

	protected $dates = [
		'expires_at'
	];

	protected $fillable = [
		'user_id',
		'client_id',
		'name',
		'scopes',
		'revoked',
		'expires_at'
	];
}
