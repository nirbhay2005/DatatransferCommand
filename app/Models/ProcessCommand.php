<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class ProcessCommand
 *
 * @property int $id
 * @property string $command_name
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property int $exec_time
 * @property bool $status
 * @property int $last_processed_id
 * @property string $exception
 *
 * @package Laravel\Models
 * @method static insert(array $array)
 * @method static select()
 */
class ProcessCommand extends Model
{
    //use Notifiable;
	protected $table = 'process_commands';
	public $timestamps = false;

	protected $casts = [
		'exec_time' => 'int',
		'status' => 'bool',
		'last_processed_id' => 'int'
	];

	protected $dates = [
		'start_time',
		'end_time'
	];

	protected $fillable = [
		'command_name',
		'start_time',
		'end_time',
		'exec_time',
		'status',
		'last_processed_id',
		'exception'
	];
}
