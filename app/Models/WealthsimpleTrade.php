<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WealthsimpleTrade extends Model
{
    protected $table = 'wealthsimple_trades';

    protected $fillable = [
        'user_id',
        'account_id',
        'symbol',
        'asset_type',
        'entry_at',
        'entry_price',
        'exit_at',
        'exit_price',
        'pnl',
        'transaction_fee',
        'currency',
        'strike_price',
        'expiration_date',
        'call_put',
        'assigned',
        'expired',
    ];

    protected $casts = [
        'asset_type' => 'string',
        'entry_at' => 'datetime',
        'exit_at' => 'datetime',
        'entry_price' => 'decimal:15,2',
        'exit_price' => 'decimal:15,2',
        'pnl' => 'decimal:15,2',
        'transaction_fee' => 'decimal:15,2',
        'strike_price' => 'decimal:15,2',
        'expiration_date' => 'date',
        'call_put' => 'string',
        'assigned' => 'boolean',
        'expired' => 'boolean',
    ];

    /**
     * Get the user that owns this trade.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the Wealthsimple account that this trade belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(WealthsimpleAccount::class, 'account_id');
    }
}