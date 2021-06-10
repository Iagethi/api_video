<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body'
    ];

    public function user() {
        $this->belongsTo(User::class, 'user_id');
    }

    public function comment() {
        $this->belongsTo(Video::class, 'video_id');
    }
}
