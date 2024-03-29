<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia, GeneratesUuid, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'referrer_id',
        'username',
        'name',
        'email',
        'password',
        'referral_token',
        'is_email_verified',
        'skills'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'uuid' => EfficientUuid::class,
    ];

    /**
     * A user has a referrer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    /**
     * A user has many referrals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['referral_link'];

    /**
     * Get the user's referral link.
     *
     * @return string
     */
    public function getReferralLinkAttribute()
    {
        return $this->referral_link = route('dev_ref', ['ref' => $this->referral_token]);
    }

    public function uuid()
    {
        return hex2bin(str_replace('-', '', $this->uuid));
    }

    // Clients' projects
    public function client_projects()
    {
        return $this->hasMany(ClientProject::class);    
    }

    /**
     * Get all of the questionnaires that are assigned this User.
     */
    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class)->using(UserQuestionnaire::class);
    }
}
