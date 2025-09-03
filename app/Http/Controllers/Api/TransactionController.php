<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    // Listar todas las transacciones
    public function index()
    {
        return response()->json(Transaction::all());
    }
}
