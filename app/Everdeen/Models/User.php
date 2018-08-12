<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Notifications\ResetPassword as ResetPasswordNotification;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait, Notifiable;

    const AVATAR_THUMB_WIDTH = 150; // pixels
    const AVATAR_THUMB_HEIGHT = 150; // pixels
    const AVATAR_FOLDER = 'profile_pictures';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name',
        'name',
        'email',
        'password',
        'url_avatar',
        'url_avatar_thumb',
        'activation_code',

        'active',
        'setting_id',
        'channel',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_code',
        'active',
        'setting_id',
        'settings',
        'channel',
        'created_at',
        'updated_at',
        'roles',
        'perms',
    ];

    public function getOwnDirectoryAttribute()
    {
        $dir = 'user_' . $this->id;
        makeUserPublicPath($dir);
        return $dir;
    }

    public function getProfilePictureDirectoryAttribute()
    {
        $dir = concatDirectories('user_' . $this->id, 'profile_pictures');
        makeUserPublicPath($dir);
        return $dir;
    }

    public function getMemberSinceAttribute()
    {
        return DateTimeHelper::getInstance()->shortDate($this->attributes['created_at']);
    }

    public function socialProviders()
    {
        return $this->hasMany(UserSocial::class, 'user_id', 'id');
    }

    public function scopeFromSocial($query, $provider, $provider_id, $email = null)
    {
        $query->whereExists(function ($query) use ($provider, $provider_id) {
            $query->select(DB::raw(1))
                ->from('user_socials')
                ->where('provider', $provider)->where('provider_id', $provider_id);
        });
        if (!empty($email)) {
            $query->orWhere('email', $email);
        }
        return $query;
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class, 'id', 'setting_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this));
    }
}