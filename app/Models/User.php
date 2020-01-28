<?php namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Notifiable;

    protected $fillable = [
        "name",
        "email",
        //"email_verified_at",
        "password",
        "user_verified",
        'lang'
    ];

    protected $hidden = [
        "password",
        "remember_token",

    ];

    protected $dates = [
        "email_verified_at",
        "created_at",
        "updated_at",

    ];

    protected $casts = [
        'user_verified' => 'boolean'
    ];


    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute() {
        return url('/admin/users/'.$this->getKey());
    }


}
