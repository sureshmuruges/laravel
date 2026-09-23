<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;

    protected $table = 'company_details';

    protected $fillable = [
        'company_name',
        'address',
        'email',
        'telephone',
        'state_code',
        'gst_number',
        'pan',
        'tan',
        'logo_path',
        'is_active',
        'company_code_prefix',
        'company_code_enabled',
        'pdf_logo_address_layout',
        'pdf_line_items_rows',
        'terms_conditions',
        'irn_qr_enabled',
        'booking_code_prefix',
        'tax_invoice_prefix',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'company_code_enabled' => 'boolean',
        'irn_qr_enabled' => 'boolean',
        'pdf_line_items_rows' => 'integer',
    ];

    /**
     * Get the currently active company details record.
     * Fallbacks to the latest record if no active record is set, or null if none exist.
     *
     * @return self|null
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first() ?? self::latest()->first();
    }
}
