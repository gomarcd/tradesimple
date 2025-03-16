<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WealthsimpleDashboardSetting extends Model
{
    protected $table = 'wealthsimple_dashboard_settings';

    protected $fillable = [
        'user_id',
        'time_range_start',
        'time_range_end',
    ];

    protected $casts = [
        'time_range_start' => 'date',
        'time_range_end' => 'date',
    ];

    /**
     * Get the user that owns this dashboard setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}