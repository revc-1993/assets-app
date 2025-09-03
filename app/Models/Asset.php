<?php

namespace App\Models;

use App\Models\Department;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory, SoftDeletes;

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
