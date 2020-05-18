<?php

/**
 * Created by Reliese Model.
 */

namespace Laravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OauthClient
 * 
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 */
class OauthClient extends Model
{
	protected $table = 'oauth_clients';

	protected $casts = [
		'user_id' => 'int',
		'personal_access_client' => 'bool',
		'password_client' => 'bool',
		'revoked' => 'bool'
	];

	protected $hidden = [
		'secret'
	];

	protected $fillable = [
		'user_id',
		'name',
		'secret',
		'redirect',
		'personal_access_client',
		'password_client',
		'revoked'
	];
}
