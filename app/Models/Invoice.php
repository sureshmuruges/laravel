<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'id';

    protected $fillable = [
        'version',
        'taxsch',
        'stype',
        'category',
        'invoice_category',
        'invoice_type',
        'irn',
        'booking_no',
        'proforma_invoice_no',
        'proforma_invoice_date',
        'billno',
        'billdate',
        'credit_note_no',
        'credit_note_date',
        'shipper_invoice',
        'shipper_consignee',
        'acode',
        'company_name',
        'aline1',
        'aline2',
        'location',
        'pincode',
        'phone',
        'email',
        'gst_no',
        'pan',
        'state',
        'state_code',
        'po_supply',
        'guarantee_l1',
        'guarantee_l2',
        'guarantee_l3',
        'guarantee_l4',
        'total_non_tax',
        'total_tax',
        'sub_total',
        'igst_value',
        'sgst_value',
        'cgst_value',
        'total',
        'total_non_inr',
        'round_off',
        'grand_total',
        'advance',
        'balance',
        'status',
        'currency',
        'ex_rate',
        'remarks',
        'month',
        'year',
        'exten_date',
        'due_date',
        'credit_days',
        'bank',
        'hcode',
        'total_expense',
        'created_by',
        'updated_by'
    ];

    /**
     * Relationship to InvoiceParticular
     */
    public function particulars()
    {
        return $this->hasMany(InvoiceParticular::class, 'BillNo', 'billno');
    }

    /**
     * Get Indian Financial Year string (e.g., '2526' for April 1, 2025 - March 31, 2026)
     */
    public static function getFinancialYear($dateString = null)
    {
        try {
            $date = $dateString ? new \DateTime($dateString) : new \DateTime();
        } catch (\Exception $e) {
            $date = new \DateTime();
        }

        $year = (int)$date->format('Y');
        $month = (int)$date->format('n'); // 1 to 12

        if ($month >= 4) {
            $startYear = $year;
            $endYear = $year + 1;
        } else {
            $startYear = $year - 1;
            $endYear = $year;
        }

        $startYearShort = substr((string)$startYear, -2);
        $endYearShort = substr((string)$endYear, -2);

        return $startYearShort . $endYearShort;
    }

    /**
     * Auto-generate the next Invoice Number (billno)
     */
    public static function generateInvoiceNumber($dateString = null)
    {
        $fy = self::getFinancialYear($dateString);
        $companyDetail = \App\Models\CompanyDetail::getActive();
        $prefixBase = ($companyDetail && !empty($companyDetail->tax_invoice_prefix)) ? $companyDetail->tax_invoice_prefix : 'CSHL';
        $prefix = $prefixBase . $fy; // e.g., CSHL2627

        // Get all invoice numbers matching this prefix and find the highest serial
        $lastInvoice = self::where('billno', 'LIKE', $prefix . '%')
            ->orderBy('billno', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract the serial number part (last 4 characters)
            $lastSerial = substr($lastInvoice->billno, -4);
            $nextSerial = (int)$lastSerial + 1;
        } else {
            $nextSerial = 1;
        }

        // Format as 4-digit zero-padded number
        return $prefix . str_pad((string)$nextSerial, 4, '0', STR_PAD_LEFT);
    }
}
