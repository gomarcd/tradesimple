<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WealthsimplePosition extends Model
{
    protected $table = 'wealthsimple_positions';

    protected $fillable = [
        'user_id',
        'account_id',
        'symbol',
        'asset_type',
        'qty',
        'avg_price',
        'current_price',
        'book_value',
        'market_value',
        'pnl',
        'currency',
        'strike_price',
        'expiration_date',
        'call_put',
        'assigned',
        'expired',
    ];

    protected $casts = [
        'asset_type' => 'string',
        'qty' => 'decimal:15,4',
        'avg_price' => 'decimal:15,2',
        'current_price' => 'decimal:15,2',
        'book_value' => 'decimal:15,2',
        'market_value' => 'decimal:15,2',
        'pnl' => 'decimal:15,2',
        'strike_price' => 'decimal:15,2',
        'expiration_date' => 'date',
        'call_put' => 'string',
        'assigned' => 'boolean',
        'expired' => 'boolean',
    ];

    /**
     * Get the user that owns this position.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the Wealthsimple account that this position belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(WealthsimpleAccount::class, 'account_id');
    }
}