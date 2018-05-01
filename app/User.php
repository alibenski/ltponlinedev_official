<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'indexno', 'name', 'indexno_new', 'nameFirst', 'nameLast', 'email', 'temp_email', 'password', 'must_change_password', 'approved_account', 'approved_update', 'account_token', 'update_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function preenrolment()
    {
    return $this->hasMany('App\Preenrolment', 'indexno' ,'INDEXID');
    }

    public function courses() {
    return $this->belongsTo('App\Course', 'course_id'); 
    }

    public function languages() {
    return $this->belongsTo('App\Language', 'language_id'); 
    }
    
    public function repos() {
    return $this->hasMany('App\Repo'); 
    }

    public function sddextr() {
    return $this->hasOne('App\SDDEXTR', 'INDEXNO', 'indexno'); 
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
            ->from('clm_language@un.org', 'CLM Language')
            ->subject( 'CLM Online Registration Password Reset' )
            ->priority(1)
            ->line('We are sending this email because we recieved a forgot password request.')
            ->action('Reset Password', url(config('app.url') . route('password.reset', $this->token, false)))
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');
    }
}
