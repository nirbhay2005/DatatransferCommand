<?php

/**
 * Created by Reliese Model.
 */

namespace Laravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OauthPersonalAccessClient
 * 
 * @property int $id
 * @property int $client_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 */
class OauthPersonalAccessClient extends Model
{
	protected $table = 'oauth_personal_access_clients';

	protected $casts = [
		'client_id' => 'int'
	];

	protected $fillable = [
		'client_id'
	];
}
