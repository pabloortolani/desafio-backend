<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_origin_id',
        'wallet_destiny_id',
        'value'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function walletOrigin()
    {
        return $this->belongsTo(Wallet::class, 'wallet_origin_id', 'id');
    }

    public function walletDestiny()
    {
        return $this->belongsTo(Wallet::class, 'wallet_destiny_id', 'id');
    }
}
