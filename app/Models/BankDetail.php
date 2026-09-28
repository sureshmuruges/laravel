<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $table = 'bank_details';

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'ifsc',
        'swift',
        'branch',
        'upi_id',
        'upi_qr_path',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the default bank account shown on invoices.
     * Falls back to the latest active record, or null if none exist.
     *
     * @return self|null
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first()
            ?? self::where('is_active', true)->latest()->first();
    }

    /**
     * Label used in bank selection dropdowns, e.g. "Kotak Mahindra Bank - A/c ...7494".
     */
    public function getDropdownLabelAttribute()
    {
        return $this->bank_name . ' - A/c ' . $this->account_number;
    }
}
