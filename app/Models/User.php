<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_no',
        'site_id',
        'country_id',
        'state_id',
        'city_id',
        'nationality',
        'designation',
        'cnic',
        'contact',
        'countryDetails',
        'optional_contact',
        'OptionalCountryDetails',
        'address',
        'mailing_address'
    ];

    public $rules = [
        'name' => 'required|string|min:1|max:50',
        'email' => 'required|email|unique:users',
        'contact' => 'required|string|min:11|max:11',
        'password' => 'required | confirmed',
        // 'attachment' => 'sometimes|min:2',
        'role_id' => 'required',

        'designation' => 'nullable|string|max:50',
        'cnic' => 'required|unique:users,cnic',
        'contact' => 'required|string|min:1|max:20',
        'address' => 'required|string',
        'city_id' => 'nullable|numeric',
        'state_id' => 'nullable|numeric',
        'country_id' => 'nullable|numeric',
        'nationality' => 'sometimes',
    ];

    // public $ruleMessages = [
    //     'attachment.min' => 'Minimum 2 attachments are required.',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(get_class($this))->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users')->withPivot('site_id');
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users';
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function userBatches()
    {
        return $this->hasMany(UserBatch::class);
    }

    public function salesPlans()
    {
        return $this->hasMany(SalesPlan::class);
    }

    public function CustomFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'modelable');
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function journalVouchers()
    {
        return $this->hasMany(JournalVoucher::class);
    }
}
