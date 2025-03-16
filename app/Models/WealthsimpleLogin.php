<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WealthsimpleLogin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'email',
        'display_name',        
        'session_data',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(WealthsimpleAccount::class, 'login_id');
    }

    public function audit()
    {
        return $this->hasMany(WealthsimpleConnectionAudit::class, 'login_id');
    }
}