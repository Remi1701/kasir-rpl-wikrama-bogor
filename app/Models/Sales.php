<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sales extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'items_data' => 'array',
    ];

    protected $fillable = [
        'id',
        'invoice_number',
        'user_id',
        'customers_id',
        'customer_name',
        'items_data',
        'total_amount',
        'payment_amount',
        'change_amount',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}

