<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserEvents extends Model {
    protected $table = 'user_events';
    public function newEvent($data){
        DB::table($this->table)->insert($data);
    }
}
