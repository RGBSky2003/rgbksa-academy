<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'lang_from',
        'lang_to',
        'full_name',
        'type_id',
        'status',
        'email',
        'industry_id',
        'phone_number',
        'notes',
    ];

    public function getLangName($id)
    {
        return TransLang::find($id)->name;
    }

    public function industry()
    {
        return $this->belongsTo(TransIndustry::class);
    }
}