<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lang',
        'name',
        'voice_id',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sample()
    {
        return $this->hasMany(Samples::class);
    }
}
