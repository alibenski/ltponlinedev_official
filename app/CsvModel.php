<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvModel extends Model
{
    protected $table = 'tblltp_csv_extract';
    protected $fillable = [
        'course', 'day','time',  
    ];
}
