<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\UserApproved;
use App\Notifications\SetPassword;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'verification_code',
        'created_by',
        'approved'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'verification_code',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($user) {
            if ($user->getOriginal('approved') == 0 && $user->approved == 1) {
                // If approved was 0 and is now set to 1
                Mail::to($user->email)->send(new UserApproved($user));
            }
        });
    }

    public function savePushToken($token)
    {
        $this->push_token = $token;
        $this->save();
    }

    public function routeNotificationForExpo($notification)
    {
        if ($this->push_token) {
            return $this->push_token;
        } else {
            return null;
        }
    }

    public static function createOrUpdate($user)
    {
        dd($user);
        // dd($user);
        // if (!$user->wasChanged('approved')) {
        //     if (is_null($user->password)) {
        //         $user->notify(new SetPassword());
        //     } else {
        //         dd($user->password);
        //         // User's password is already set.
        //     }
        // } else {
        //     dd('not approved');
        // }

    }

    // public function sendPasswordSetNotification($token)
    // {
    //     $this->notify(new ResetPasswordNotification($token));
    // }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'Admin')->exists() || $this->roles()->where('name', 'Super Admin')->exists();
    }

    public function isSuperAdmin()
    {
        return $this->roles()->where('name', 'Super Admin')->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SetPassword($token));
    }

    public function ownedCompanies()
    {
        return $this->hasMany(Company::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function likes()
    {
        return $this->belongsToMany(Company::class, 'company_likes', 'user_id', 'company_id');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
