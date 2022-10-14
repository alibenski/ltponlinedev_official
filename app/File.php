<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'tblLTP_Files';
    protected $fillable = [
        'user_id', 'actor_id', 'filename', 'size', 'path'
    ];
}
