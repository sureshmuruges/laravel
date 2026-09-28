<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceParticular extends Model
{
    protected $table = 'invoice_particulars';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'InvoiceType',
        'ProformaInvoiceNo',
        'BillNo',
        'CreditNoteNo',
        'HSN',
        'Particulars',
        'Additional',
        'NonTaxAmount',
        'NonTaxAmt_NonINR',
        'TaxAmount',
        'IGST',
        'IGSTValue',
        'SGST',
        'SGSTValue',
        'CGST',
        'CGSTValue',
        'Total',
        'IsService',
        'ExceptionalParticulars',
        'Month',
        'Year',
        'CreateDate',
        'CreateBy',
        'ModifyDate',
        'ModifyBy',
    ];
}
