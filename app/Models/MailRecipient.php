<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MailRecipient
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $locale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Laravel\Models
 * @method static select()
 */
class MailRecipient extends Model implements HasLocalePreference
{
	protected $table = 'mail_recipients';

	protected $fillable = [
		'name',
		'email',
		'locale'
	];

    public function preferredLocale()
    {
        return $this->locale;
    }
}
