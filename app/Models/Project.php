<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{

    protected $fillable = ['name', 'logo', 'concern', 'status'];

    const CONCERNS = [
        'bhaiya_housing'      => 'Bhaiya Housing',
        'bhaiya_hotel_resort' => 'Bhaiya Hotel & Resort',
        'right_aid_hospital'  => 'Right Aid Hospital',
    ];

public function getConcernNameAttribute()
{
    return self::CONCERNS[$this->concern] ?? $this->concern;
}

    // Helper for Logo URL
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
