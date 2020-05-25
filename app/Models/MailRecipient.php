<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class MailRecipient
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $locale
 * @property int $phone_number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 */
class MailRecipient extends Model implements HasLocalePreference
{
    use Notifiable;
	protected $table = 'mail_recipients';

	protected $casts = [
		'phone_number' => 'int'
	];

	protected $fillable = [
		'name',
		'email',
		'locale',
		'phone_number'
	];

    public function preferredLocale()
    {
        return $this->locale;
    }

    public function routeNotificationForNexmo()
    {
        return $this->phone_number;
    }
}
