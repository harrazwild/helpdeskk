<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';

    public function complaint() {
        return $this->belongsTo('App\Model\Status');
    }

    public function complaintlist() {
        return $this->belongsTo('App\Model\Complaintlist');
    }
}
