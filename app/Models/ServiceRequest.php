<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'user_id',

        ## Translation / Interpretation ##
        'trans_type',
        'lang_from',
        'lang_to',
        'email',
        'trans_industry',
        'full_name',
        'phone_number',
        'attachment',
        'notes',
        'country',
        'postal_code',
        'date',
        'time',
        'adress',
        'town_city',
        'state_zone',

        
        'status',
    ];
    
    protected $casts = [
        'created_at' => 'datetime:d M Y H:i',
        'updated_at' => 'datetime:d M Y H:i',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function industry()
    {
        return $this->belongsTo(TransIndustry::class);
    }
}