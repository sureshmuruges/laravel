@php
// Helper function to convert number to Indian Rupees words
if (!function_exists('numberToWords')) {
    function numberToWords($num) {
        $number = (float)$num;
        $no = floor($number);
        $decimal = round(($number - $no) * 100);
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
            30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred : $words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else {
                $str[] = null;
            }
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? " and " . ($words[floor($decimal / 10) * 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : 'Zero Rupees ') . $paise . ' Only';
    }
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->billno }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9.5px;
            line-height: 1.3;
            color: #000000;
            margin: 0;
            padding: 0;
        }
        .container {
            border: 1.5px solid #000000;
            padding: 10px;
            background-color: #ffffff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        
        /* Header section styling */
        .header-table {
            border-bottom: 1.5px solid #000000;
            margin-bottom: 6px;
        }
        .header-logo-cell {
            width: 25%;
            vertical-align: middle;
        }
        .header-logo {
            height: 60px;
        }
        .header-text-cell {
            width: 75%;
            text-align: center;
            padding-right: 10%; /* Center relative to page width */
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0 0 2px 0;
        }
        .company-details {
            font-size: 8.5px;
            color: #000000;
            margin: 0;
        }

        /* Invoice Type Header */
        .invoice-type-header {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            margin: 2px 0 6px 0;
            text-transform: uppercase;
        }

        /* Info Grid section */
        .info-grid-table {
            border-bottom: 1px solid #000000;
            margin-bottom: 6px;
        }
        .info-grid-left {
            width: 55%;
            border-right: 1px solid #000000;
            padding-right: 10px;
        }
        .info-grid-right {
            width: 45%;
            padding-left: 10px;
        }
        .info-row {
            margin-bottom: 2px;
        }
        
        /* Job / Transport details table */
        .job-details-table {
            border: 1px solid #000000;
            margin-bottom: 8px;
        }
        .job-details-table td {
            border: 1px solid #000000;
            font-size: 9px;
            width: 50%;
            padding: 3px 6px;
        }

        /* Particulars Table */
        .particulars-table {
            border: 1px solid #000000;
            margin-bottom: 8px;
        }
        .particulars-table th {
            border: 1px solid #000000;
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 8.5px;
            text-align: center;
            padding: 4px 2px;
        }
        .particulars-table td {
            border-left: 1px solid #000000;
            border-right: 1px solid #000000;
            padding: 4px 6px;
            font-size: 9px;
        }
        .particulars-table tr.item-row td {
            border-bottom: 1px dashed #cccccc;
        }
        .particulars-table tr.total-summary-row td {
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        /* Totals / Summary Grid */
        .totals-grid-table {
            margin-bottom: 8px;
        }
        .totals-left-cell {
            width: 55%;
            padding-right: 10px;
        }
        .totals-right-cell {
            width: 45%;
            padding-left: 0;
        }
        
        .totals-table-right {
            border: 1px solid #000000;
            width: 100%;
        }
        .totals-table-right td {
            border: 1px solid #000000;
            padding: 4px 8px;
            font-size: 9px;
        }
        .totals-table-right tr.grand-total-row td {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10.5px;
        }

        /* Terms & Bank Info */
        .terms-section {
            font-size: 7.5px;
            margin-top: 4px;
            line-height: 1.4;
        }
        .terms-title {
            font-weight: bold;
            font-size: 8.5px;
            margin-bottom: 2px;
            text-decoration: underline;
        }
        .bank-details-box {
            border: 1px solid #000000;
            margin-top: 6px;
            padding: 6px;
            background-color: #ffffff;
            width: 100%;
        }
        .bank-details-table {
            width: 100%;
            margin: 0;
        }
        .bank-details-table td {
            padding: 1px 2px;
            font-size: 8.5px;
        }

        /* UPI & QR Code section */
        .payment-qr-table {
            width: 100%;
            margin-top: 6px;
        }
        .payment-qr-cell {
            width: 50%;
            vertical-align: middle;
        }
        .payment-qr-img {
            height: 70px;
            width: 70px;
        }
        
        /* Signatory Section */
        .signatory-box {
            text-align: right;
            margin-top: 10px;
            padding-right: 10px;
            position: relative;
        }
        .stamp-signature-img {
            height: 65px;
            position: absolute;
            right: 20px;
            top: -15px;
            z-index: 10;
        }
        .signatory-text {
            font-size: 8.5px;
            color: #555555;
            margin-top: 40px;
            border-top: 1px solid #000000;
            display: inline-block;
            width: 150px;
            text-align: center;
        }

        /* Footer */
        .footer-table {
            border-top: 1px solid #000000;
            margin-top: 10px;
            padding-top: 4px;
            font-size: 8px;
            color: #555555;
        }
    </style>
</head>
<body>

@php
    $companyDetail = \App\Models\CompanyDetail::getActive();
    $pdfLayout = $companyDetail->pdf_logo_address_layout ?? 'logo_left_address_right';
    $lineItemRows = $companyDetail->pdf_line_items_rows ?? 10;
    $showIrnQr = $companyDetail ? (bool) $companyDetail->irn_qr_enabled : true;

    $logoCell = '';
    ob_start();
    @endphp
    @if($companyDetail && $companyDetail->logo_path && file_exists(public_path($companyDetail->logo_path)))
        <img class="header-logo" src="{{ public_path($companyDetail->logo_path) }}" alt="{{ $companyDetail->company_name }} Logo" />
    @else
        <img class="header-logo" src="{{ public_path('images/ao_logo.jpg') }}" alt="AO Logistics Logo" />
    @endif
    @php
    $logoCell = ob_get_clean();

    ob_start();
    @endphp
    <div class="company-name">{{ $companyDetail ? $companyDetail->company_name : 'AO LOGISTICS' }}</div>
    <div class="company-details">
        @if($companyDetail)
            {!! nl2br(e($companyDetail->address)) !!}<br>
            Email : {{ $companyDetail->email }}, Tele : {{ $companyDetail->telephone }}<br>
            STATE CODE : {{ $companyDetail->state_code }}, GST NO : {{ $companyDetail->gst_number }}<br>
            PAN : {{ $companyDetail->pan }}, TAN : {{ $companyDetail->tan }}
        @else
            No. 7, 14th A Main Road, Behind More Mega Mart,<br>
            Sahakara Nagar, Bengaluru - 560 092. India<br>
            Email : nandan@aologistics.in, Tele : +91 70222 84895<br>
            STATE CODE : 29, GST NO : 29AHWPT9984H1ZV<br>
            PAN : AHWPT9984H, TAN : BLRB24521A
        @endif
    </div>
    @php
    $addressCell = ob_get_clean();
@endphp
<div class="container">
    <!-- COMPANY HEADER -->
    <table class="header-table">
        <tr>
            @if($pdfLayout === 'address_left_logo_right')
                <td class="header-text-cell" style="text-align: left; padding-right: 0; padding-left: 0;">{!! $addressCell !!}</td>
                <td class="header-logo-cell" style="text-align: right;">{!! $logoCell !!}</td>
            @elseif($pdfLayout === 'logo_left_address_center')
                <td class="header-logo-cell">{!! $logoCell !!}</td>
                <td class="header-text-cell" style="text-align: center;">{!! $addressCell !!}</td>
            @else
                {{-- logo_left_address_right (default) --}}
                <td class="header-logo-cell">{!! $logoCell !!}</td>
                <td class="header-text-cell" style="text-align: right; padding-right: 0;">{!! $addressCell !!}</td>
            @endif
        </tr>
    </table>

    <!-- INVOICE TYPE TITLE -->
    <div class="invoice-type-header">
        @if($invoice->invoice_type == 'Proforma')
            PROFORMA INVOICE
        @elseif($invoice->invoice_type == 'CreditNote')
            CREDIT NOTE
        @else
            TAX INVOICE
        @endif
    </div>

    <!-- CLIENT & INVOICE DETAILS GRID -->
    <table class="info-grid-table">
        <tr>
            <!-- Billing Details -->
            <td class="info-grid-left">
                <div class="info-row"><span class="fw-bold">To:</span></div>
                <div class="info-row fw-bold" style="font-size: 10.5px; margin-bottom: 2px;">{{ $invoice->company_name }}</div>
                <div class="info-row" style="color: #333333; font-size: 9px;">
                    {{ $invoice->aline1 }}<br>
                    @if($invoice->aline2){{ $invoice->aline2 }}<br>@endif
                    {{ $invoice->location }} - {{ $invoice->pincode }}<br>
                    @if($invoice->phone)Phone: {{ $invoice->phone }} | @endif
                    @if($invoice->email)Email: {{ $invoice->email }}<br>@endif
                    <span class="fw-bold">GST No. :</span> {{ $invoice->gst_no ?? 'URP' }}<br>
                    @if($invoice->pan)<span class="fw-bold">PAN :</span> {{ $invoice->pan }}<br>@endif
                    <span class="fw-bold">Place of Supply :</span> {{ $invoice->po_supply ?? 'KARNATAKA' }}
                </div>
            </td>
            
            <!-- Invoice Metadata -->
            <td class="info-grid-right" style="position: relative;">
                <table style="width: 100%; margin: 0;">
                    <tr>
                        <td style="padding: 0; width: 65%;">
                            <div class="info-row"><span class="fw-bold">Invoice No. :</span> <span class="fw-bold" style="font-size: 10.5px;">{{ $invoice->billno }}</span></div>
                            <div class="info-row"><span class="fw-bold">Invoice Date :</span> {{ \Carbon\Carbon::parse($invoice->billdate)->format('d/m/Y') }}</div>
                            @if($showIrnQr)
                                <div class="info-row" style="margin-top: 6px;"><span class="fw-bold">Ack No. :</span> {{ $invoice->credit_note_no ?? '112631097577649' }}</div>
                                <div class="info-row"><span class="fw-bold">Ack Date :</span> {{ $invoice->credit_note_date ? \Carbon\Carbon::parse($invoice->credit_note_date)->format('Y-m-d H:i:s') : \Carbon\Carbon::parse($invoice->billdate)->format('Y-m-d') . ' 17:26:00' }}</div>
                            @endif
                            <div class="info-row" style="margin-top: 6px;"><span class="fw-bold">Account Code :</span> {{ $invoice->acode ?? 'A0023' }}</div>
                            <div class="info-row"><span class="fw-bold">Invoice Due on :</span> {{ \Carbon\Carbon::parse($invoice->due_date ?? $invoice->billdate)->format('d/m/Y') }}</div>
                        </td>
                        @if($showIrnQr)
                            <td style="padding: 0; width: 35%; text-align: right; vertical-align: top;">
                                @if(isset($qrCodeBase64) && $qrCodeBase64)
                                    <img src="{{ $qrCodeBase64 }}" style="height: 70px; width: 70px; border: 1px solid #cccccc;" alt="QR Code" />
                                @else
                                    @php
                                        $qrData = urlencode("Invoice No: " . $invoice->billno . "\nGSTIN: 29AHWPT9984H1ZV\nAmount: " . $invoice->grand_total . "\nIRN: " . ($invoice->irn ?? ''));
                                    @endphp
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ $qrData }}" style="height: 70px; width: 70px; border: 1px solid #cccccc;" alt="QR Code" />
                                @endif
                            </td>
                        @endif
                    </tr>
                </table>
                @if($showIrnQr && $invoice->irn)
                    <div style="font-size: 7px; word-break: break-all; margin-top: 8px; border-top: 1px dashed #dddddd; padding-top: 2px;">
                        <span class="fw-bold">IRN :</span> {{ $invoice->irn }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- TRANSPORT / JOB DETAILS GRID -->
    <table class="job-details-table">
        <tr>
            <td>
                <span class="fw-bold">BE/SB No.& Date :</span> {{ $invoice->guarantee_l3 ?? '-' }}<br>
                <span class="fw-bold">MAWB No. :</span> {{ $invoice->guarantee_l1 ?? '-' }}<br>
                <span class="fw-bold">HAWB No. :</span> {{ $invoice->guarantee_l2 ?? '-' }}<br>
                <span class="fw-bold">LINE/IGM No. :</span> {{ $invoice->guarantee_l4 ?? '-' }}
            </td>
            <td>
                <span class="fw-bold">Shipper / Consignee :</span> {!! nl2br(e($invoice->shipper_consignee ?? '-')) !!}<br>
                <span class="fw-bold">No of Pkgs & G Weight :</span> {{ $invoice->taxsch ?? '-' }}<br>
                <span class="fw-bold">No of Pkgs & C Weight :</span> {{ $invoice->remarks ?? '-' }}<br>
                <span class="fw-bold">Origin & Destination :</span> {{ $invoice->category ?? '-' }} / {{ $invoice->stype ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- PARTICULARS TABLE -->
    @php
        $hasIgst = $invoice->igst_value > 0;
    @endphp
    <table class="particulars-table">
        <thead>
            <tr>
                <th style="width: 4%;">S.N</th>
                <th style="width: 44%; text-align: left;">Particulars</th>
                <th style="width: 10%;">SAC/HSN</th>
                <th style="width: 10%; text-align: right;">Non Tax</th>
                <th style="width: 10%; text-align: right;">Tax</th>
                @if($hasIgst)
                    <th style="width: 11%; text-align: right;">IGST %</th>
                    <th style="width: 11%; text-align: right;">IGST</th>
                @else
                    <th style="width: 6%; text-align: right;">CGST %</th>
                    <th style="width: 6%; text-align: right;">CGST</th>
                    <th style="width: 6%; text-align: right;">SGST %</th>
                    <th style="width: 6%; text-align: right;">SGST</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($particulars as $index => $item)
                <tr class="item-row">
                    <td class="text-center" style="border-right: 1px solid #000000;">{{ $index + 1 }}</td>
                    <td style="border-right: 1px solid #000000;">
                        <span class="fw-bold">{{ $item->Particulars }}</span>
                        @if($item->Additional)
                            <div style="font-size: 8px; color: #555555; margin-top: 1px;">{{ $item->Additional }}</div>
                        @endif
                    </td>
                    <td class="text-center" style="border-right: 1px solid #000000;">{{ $item->HSN ?? '-' }}</td>
                    <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->NonTaxAmount > 0 ? number_format($item->NonTaxAmount, 2) : '' }}</td>
                    <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->TaxAmount > 0 ? number_format($item->TaxAmount, 2) : '' }}</td>
                    @if($hasIgst)
                        <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->IGST > 0 ? number_format($item->IGST, 1) . '%' : '' }}</td>
                        <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->IGSTValue > 0 ? number_format($item->IGSTValue, 2) : '' }}</td>
                    @else
                        <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->CGST > 0 ? number_format($item->CGST, 1) . '%' : '' }}</td>
                        <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->CGSTValue > 0 ? number_format($item->CGSTValue, 2) : '' }}</td>
                        <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->SGST > 0 ? number_format($item->SGST, 1) . '%' : '' }}</td>
                        <td class="text-end" style="border-right: 1px solid #000000;">{{ $item->SGSTValue > 0 ? number_format($item->SGSTValue, 2) : '' }}</td>
                    @endif
                </tr>
            @endforeach
            
            <!-- Spacing row to push the totals down if few items -->
            @for ($k = count($particulars); $k < $lineItemRows; $k++)
                <tr class="item-row" style="height: 18px;">
                    <td style="border-right: 1px solid #000000;"></td>
                    <td style="border-right: 1px solid #000000;"></td>
                    <td style="border-right: 1px solid #000000;"></td>
                    <td style="border-right: 1px solid #000000;"></td>
                    <td style="border-right: 1px solid #000000;"></td>
                    @if($hasIgst)
                        <td style="border-right: 1px solid #000000;"></td>
                        <td style="border-right: 1px solid #000000;"></td>
                    @else
                        <td style="border-right: 1px solid #000000;"></td>
                        <td style="border-right: 1px solid #000000;"></td>
                        <td style="border-right: 1px solid #000000;"></td>
                        <td style="border-right: 1px solid #000000;"></td>
                    @endif
                </tr>
            @endfor

            <!-- Total Summary Row -->
            <tr class="total-summary-row">
                <td colspan="3" class="text-end">Total:</td>
                <td class="text-end">{{ number_format($invoice->total_non_tax, 2) }}</td>
                <td class="text-end">{{ number_format($invoice->total_tax, 2) }}</td>
                @if($hasIgst)
                    <td></td>
                    <td class="text-end">{{ number_format($invoice->igst_value, 2) }}</td>
                @else
                    <td></td>
                    <td class="text-end">{{ number_format($invoice->cgst_value, 2) }}</td>
                    <td></td>
                    <td class="text-end">{{ number_format($invoice->sgst_value, 2) }}</td>
                @endif
            </tr>
        </tbody>
    </table>

    <!-- TOTALS & TERMS SECTION -->
    <table class="totals-grid-table">
        <tr>
            <!-- Left Column: Words, Terms, Bank & UPI -->
            <td class="totals-left-cell">
                <div style="margin-bottom: 4px;">
                    <span class="fw-bold" style="font-size: 8px; color: #555555; text-transform: uppercase;">Total Tax & Non-Tax =</span>
                    <span class="fw-bold">{{ number_format($invoice->total_tax + $invoice->total_non_tax, 2) }}</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="fw-bold" style="font-size: 8px; color: #555555; text-transform: uppercase;">Total GST =</span>
                    <span class="fw-bold">{{ number_format($invoice->cgst_value + $invoice->sgst_value + $invoice->igst_value, 2) }}</span>
                </div>
                
                <div style="margin-bottom: 6px; padding: 4px; border: 1px dashed #999999; background-color: #fafafa;">
                    <span class="fw-bold" style="font-size: 8px; color: #555555;">INR IN WORDS :</span>
                    <span class="fw-bold" style="font-size: 9px; color: #0f172a; font-style: italic;">{{ numberToWords($invoice->grand_total) }}</span>
                </div>

                <!-- Terms & Conditions -->
                <div class="terms-section">
                    <div class="terms-title">Terms & Conditions:</div>
                    @if($companyDetail && $companyDetail->terms_conditions)
                        {!! nl2br(e($companyDetail->terms_conditions)) !!}
                    @else
                        1. Our Billing system start generating E-Invoices from 1st April 2026.<br>
                        2. Interest @ 2% per month or part thereof or at the rate stipulated in the contract will be imposed on overdue amounts.<br>
                        3. Payment to be made by cross cheque / Draft in favour of "{{ $companyDetail ? $companyDetail->company_name : 'AO LOGISTICS' }}"<br>
                        4. Contents of the Invoice will be considered correct if no error is reported within 7 days<br>
                        5. Payment should be settled within 30 days from the date of Invoice<br>
                        6. All Objections/Claims are subject to Bengaluru Jurisdiction<br>
                        7. MSME Udyam Reg No : UDYAM-KR-02-0008928
                    @endif
                </div>

                <!-- Bank & UPI Grid -->
                <table class="payment-qr-table">
                    <tr>
                        <td style="width: 65%; padding: 0;">
                            <div class="bank-details-box">
                                <div class="fw-bold" style="font-size: 9px; border-bottom: 1px solid #000000; padding-bottom: 2px; margin-bottom: 4px;">
                                    Our Bank Details:
                                </div>
                                <table class="bank-details-table">
                                    <tr>
                                        <td class="fw-bold" style="width: 45px;">A/c No:</td>
                                        <td class="fw-bold">6450907494</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Bank Name:</td>
                                        <td>Kotak Mahindra Bank</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">IFSC:</td>
                                        <td class="fw-bold">KKBK0008045</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Swift:</td>
                                        <td>KKBKINBBCPC</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Branch:</td>
                                        <td>Sahakara Nagar, Bengaluru - 560092</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">UPI:</td>
                                        <td class="fw-bold">9611570671@kotak</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td style="width: 35%; padding: 0 0 0 10px; text-align: center; vertical-align: middle;">
                            <div class="fw-bold" style="font-size: 8px; margin-bottom: 2px;">Scan to Pay</div>
                            <img class="payment-qr-img" src="{{ public_path('images/upi_qr.png') }}" alt="UPI QR Code" />
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Right Column: Computations & Signatory -->
            <td class="totals-right-cell" style="vertical-align: top;">
                <table class="totals-table-right">
                    <tr>
                        <td class="fw-bold">SUB TOTAL</td>
                        <td class="text-end fw-bold">{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">ROUND OFF</td>
                        <td class="text-end fw-bold">{{ number_format($invoice->round_off, 2) }}</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td>TOTAL AMOUNT (INR)</td>
                        <td class="text-end">{{ number_format($invoice->grand_total, 2) }}</td>
                    </tr>
                    @if($invoice->advance > 0)
                        <tr>
                            <td class="fw-bold" style="color: green;">ADVANCE PAID</td>
                            <td class="text-end fw-bold" style="color: green;">{{ number_format($invoice->advance, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold" style="color: red;">BALANCE DUE</td>
                            <td class="text-end fw-bold" style="color: red;">{{ number_format($invoice->balance, 2) }}</td>
                        </tr>
                    @endif
                </table>
                
                <div style="text-align: right; font-size: 8.5px; margin-top: 10px; padding-right: 5px; font-style: italic;">
                    E & O E
                </div>

                <!-- Signatory Section -->
                <div class="signatory-box">
                    <div class="fw-bold" style="font-size: 9px; margin-bottom: 20px;">For {{ $companyDetail ? $companyDetail->company_name : 'AO LOGISTICS' }}</div>
                    
                    <img class="stamp-signature-img" src="{{ public_path('images/stamp_signature.png') }}" alt="Rubber Stamp & Signature" />
                    
                    <div class="signatory-text fw-bold">Authorised Signatory</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <table class="footer-table">
        <tr>
            <td style="width: 33%; text-align: left; font-weight: bold;">Original Copy</td>
            <td style="width: 34%; text-align: center; font-style: italic;">Thanks for your Business</td>
            <td style="width: 33%; text-align: right;">Powered by: www.tracksen.com</td>
        </tr>
    </table>
</div>

</body>
</html>
