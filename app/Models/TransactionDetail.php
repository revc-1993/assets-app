<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionDetailFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Atributos que se pueden asignar de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'asset_id',
        'comments',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
