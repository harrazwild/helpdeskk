<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_JAN extends Model
{
  use HasFactory;

  protected $connection = 'mysql2';
  protected $table = 't_staff';
}
