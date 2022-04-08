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
}
