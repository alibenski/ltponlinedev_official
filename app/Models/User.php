<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use FullTextSearch;
    use SoftDeletes;

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function history()
    {
        return $this->morphMany(History::class, 'historical', 'reference_table', 'reference_id');
    }

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'name',
        'email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'indexno', 'name', 'indexno_old', 'nameFirst', 'nameLast', 'profile', 'email', 'temp_email', 'password', 'must_change_password', 'approved_account', 'approved_update', 'mailing_list', 'contract_date', 'account_token', 'update_token', 'last_login_at', 'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Part of Laravel 5.7 User Verification service
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function newUserInt()
    {
        return $this->hasOne('App\NewUser', 'indexno_new', 'indexno');
    }

    public function preenrolment()
    {
        return $this->hasMany('App\Preenrolment', 'INDEXID', 'indexno');
    }
    public function placement()
    {
        return $this->hasMany('App\PlacementForm', 'INDEXID', 'indexno');
    }
    public function adminCommentPlacement()
    {
        return $this->hasMany('App\AdminCommentPlacement', 'user_id', 'id');
    }

    public function adminComment()
    {
        return $this->hasMany('App\AdminComment', 'user_id', 'id');
    }

    public function courses()
    {
        return $this->belongsTo('App\Course', 'created_by');
    }

    public function languages()
    {
        return $this->belongsTo('App\Language', 'language_id');
    }

    public function repos()
    {
        return $this->hasMany('App\Repo', 'INDEXID', 'indexno');
    }

    public function preview()
    {
        return $this->hasMany('App\Preview');
    }

    public function sddextr()
    {
        return $this->hasOne('App\SDDEXTR', 'INDEXNO', 'indexno');
    }

    public function teachers()
    {
        return $this->hasOne('App\Teachers', 'IndexNo', 'indexno');
    }

    public function teachersById()
    {
        return $this->hasOne('App\Teachers', 'id', 'teacher_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPassword($token));
    }
}
// class to modify email template of password reset 
class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('clm_language@unog.ch', 'CLM Language')
            ->subject('CLM Online Registration Password Reset')
            ->priority(1)
            ->line('We are sending this email because we recieved a forgot password request. Reset password request will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            ->action('Reset Password', url(config('app.url') . route('password.reset', $this->token, false)))
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');
    }
}
