<?php

/**
 * Created by Reliese Model.
 */

namespace Laravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OauthRefreshToken
 * 
 * @property string $id
 * @property string $access_token_id
 * @property bool $revoked
 * @property Carbon $expires_at
 *
 * @package Laravel\Models
 */
class OauthRefreshToken extends Model
{
	protected $table = 'oauth_refresh_tokens';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'revoked' => 'bool'
	];

	protected $dates = [
		'expires_at'
	];

	protected $fillable = [
		'access_token_id',
		'revoked',
		'expires_at'
	];
}
