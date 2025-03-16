<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WealthsimpleAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'login_id',
        'owner_name',
        'owner_email',
        'has_multiple_owners',
        'display_name',
        'account_id',
        'account_type',
        'description',
        'currency',
        'balance',
        'is_active',
        'last_synced_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_multiple_owners' => 'boolean',
        'balance' => 'decimal:2',
        'last_synced_at' => 'datetime',
    ];

    public function login()
    {
        return $this->belongsTo(WealthsimpleLogin::class, 'login_id');
    }
}