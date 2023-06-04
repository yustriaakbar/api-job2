<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'job_name',
        'job_type',
        'salary',
        'location',
        'deadline',
        'requirement',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
