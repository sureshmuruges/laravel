<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particular extends Model
{
    protected $fillable = [
        'particulars',
        'hsn',
        'gst',
        'igst',
        'cgst',
        'sgst',
        'except_particulars',
        'is_service',
        'active'
    ];
}
