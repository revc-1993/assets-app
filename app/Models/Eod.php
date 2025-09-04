<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Eod extends Model
{
    /** @use HasFactory<\Database\Factories\EodFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Atributos que se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'eod_code',
        'eod_name',
    ];
}
