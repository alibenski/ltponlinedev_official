<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileNewUser extends Model
{
    protected $table = 'tblLTP_NewUserFiles';
    protected $fillable = [
        'filename', 'size', 'path'
    ];
}
