<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['key', 'value', 'type'];

    // public function getValueAttribute($value)
    // {
    //     if (is_null($value)) {
    //         return null;
    //     }

    //     if (is_numeric($value)) {
    //         return $value;
    //     }

    //     if (in_array($value, ['1', '0'])) {
    //         return $value === 'true';
    //     }

    //     return json_decode($value, true) ?? $value;
    // }
}
