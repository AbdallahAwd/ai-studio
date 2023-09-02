<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Samples extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_voices_id',
        'sample_id',

    ];
    public function voice()
    {
        return $this->belongsTo(UserVoices::class);
    }
}
