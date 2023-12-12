<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable =[
        'id',
        'name',
        'date',
    ];

    /**
     * @var string[]
     */
//    protected $casts = [
//      'id' => 'int',
//      'date' => 'date:d/m/yyyy',
//    ];

}
