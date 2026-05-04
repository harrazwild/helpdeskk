<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;
    protected $table = 'audit_trail';

    protected $fillable = [
        'object_id', 'application_no', 'description', 'user_id', 'name', 'staff', 'officer', 'vendor', 'category', 'subcategory', 'details', 'status', 'remark', 'ip_address', 'agent'
    ];
}



