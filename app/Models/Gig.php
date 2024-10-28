<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'number_of_positions',
        'pay_per_hour',
        'status',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
