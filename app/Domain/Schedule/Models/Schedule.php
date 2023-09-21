<?php

namespace App\Domain\Schedule\Models;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'starts_at',
        'ends_at',
        'finished_at',
    ];

    public function scopeDateBetween($query, $start, $end)
    {
        $query->where(function ($query) use ($start, $end) {
            $query->where('starts_at', '>=', $start)
                ->where('starts_at', '<=', $end)
                ->orWhere('ends_at', '>=', $start)
                ->where('ends_at', '<=', $end);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
