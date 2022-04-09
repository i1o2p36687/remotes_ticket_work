<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMap extends Model
{
    use HasFactory;

    protected $table = 'role_map';
    protected $primaryKey = 'user_id';

    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['role_id'];

    public function getRole(){
        return $this->belongsTo('App\Models\Role', 'role_id', 'id')
                ->select(array('id', 'name'));
    }

    public function getUser(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id')
                ->select(array('id', 'name', 'account'));
    }
}
