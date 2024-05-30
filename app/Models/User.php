<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Attribute;
use App\Models\StudentClass;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'user_email',
        'number',
        'password',
        'nis',
        'class_id',
        'category_id',
        'gender'
    ];

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
        'password' => 'hashed',
    ];


    public function classes()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function paymentCredit()
    {
        return $this->belongsToMany(Credit::class, 'payments');
    }

    public function paymentAttribute()
    {
        return $this->belongsToMany(Attribute::class, 'payments');
    }

    public function billing()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function enrollmentAll()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'class_id');
    }
}
