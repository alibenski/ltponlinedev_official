<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'LTP_RoomList';
    protected $primaryKey = 'Rl_Room';
    //so Eloquent does not expect primary key to be auto-incrementing 
    public $incrementing = false;

}