<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawatan extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 't_ref_jawatan';
    protected $primaryKey = 'jaw_code';
    public $incrementing = false;

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
