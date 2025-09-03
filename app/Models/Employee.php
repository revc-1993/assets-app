<?php

namespace App\Models;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory, SoftDeletes;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function verificationTransactions()
    {
        return $this->hasMany(Transaction::class, 'verification_id');
    }

    public function custodianTransactions()
    {
        return $this->hasMany(Transaction::class, 'custodian_id');
    }

    public function responsibleGIZATransactions()
    {
        return $this->hasMany(Transaction::class, 'responsible_giza_id');
    }

    public function responsibleGAFYBTransactions()
    {
        return $this->hasMany(Transaction::class, 'responsible_gafyb_id');
    }

    public function deliveryTransactions()
    {
        return $this->hasMany(Transaction::class, 'delivery_id');
    }

    public function receiveTransactions()
    {
        return $this->hasMany(Transaction::class, 'receive_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
}
