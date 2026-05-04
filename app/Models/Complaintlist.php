<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaintlist extends Model
{
    use HasFactory;
    protected $table = 'complaints';

    public function sector(){
        return $this->hasOne(Sector::class);
    }

    public function department(){
        return $this->hasOne(Department::class);
    }

    public function status(){
        return $this->hasOne(Status::class);
    }

}
