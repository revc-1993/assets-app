<?php

namespace App\Models;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\TransactionType;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory, SoftDeletes;

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function verification()
    {
        return $this->belongsTo(Employee::class, 'constatado_id');
    }

    public function custodian()
    {
        return $this->belongsTo(Employee::class, 'custodio_id');
    }

    public function responsibleGIZA()
    {
        return $this->belongsTo(Employee::class, 'jefe_giza_id');
    }

    public function responsibleGAFYB()
    {
        return $this->belongsTo(Employee::class, 'jefe_gafyb_id');
    }

    public function delivery()
    {
        return $this->belongsTo(Employee::class, 'entrega_id');
    }

    public function receive()
    {
        return $this->belongsTo(Employee::class, 'recibe_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
