<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'Id';

    const CREATED_AT = 'CreateDate';
    const UPDATED_AT = 'ModifyDate';

    protected $fillable = [
        'Category',
        'BookingNo',
        'booking_date',
        'companyname',
        'shipper',
        'origin',
        'Destination',
        'MAWB_MBL',
        'HAWB_HBL',
        'Consignee',
        'Pieces',
        'ETD',
        'ETA',
        'accode_companyname',
        'acode_Shipper',
        'accode_consignee',
        'IATA',
        'SBNo',
        'SBDate',
        'ShipperInvoice',
        'Line',
        'IGM_EGM',
        'CBM',
        'GrWeight',
        'ChWeight',
        'Vessel',
        'Volume',
        'FCL',
        'TOS',
        'IEC',
        'OOC',
        'Asses',
        'LUT',
        'CFS',
        'SalesRep',
        'Reference',
        'Month',
        'Year',
        'Active',
        'CreateBy',
        'ModifyBy'
    ];

    /**
     * Logic for BookingNo: Start current year 2026+1 + auto-increment.
     * So 2027XXXX format.
     */

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'JobNo', 'BookingNo');
    }
}
