<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    protected $fillable = [
        "path",
        "user_id",
        "results_filled_date",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'results_filled_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute() {
        return url('/admin/results/'.$this->getKey());
    }
}
