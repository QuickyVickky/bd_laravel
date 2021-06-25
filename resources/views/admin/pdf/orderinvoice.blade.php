<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Document</title>

<style>
@font-face {
  font-family: 'Eras Bold ITC';
    src: url("{{ asset('font/eras-bold-itc/ErasITC-Bold.woff2')}}") format('woff2'),url("{{ asset('font/eras-bold-itc/ErasITC-Bold.woff')}}") format('woff');
    font-weight: bold;
    font-style: normal;
    font-display: swap;
  }
  @font-face {
  font-family: 'Berlin Sans FB';
    src: url("{{ asset('font/berlin-sans-fb-regular/BerlinSansFB-Reg.woff2')}}") format('woff2'),url("{{ asset('font/berlin-sans-fb-regular/BerlinSansFB-Reg.woff')}}") format('woff');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
  }
  
body {
  font-family: Eras Bold ITC;
  }
.font {
  font-family: Berlin Sans FB;
}

@page {  
  margin: 30mm auto 16mm 10px;
}


body {
             
   }

    header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
    }

   footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
        }

</style>
</head>
<body>


<?php

  //dde($orderData);
  
  $business_name =  "";
  if(isset($orderData[0]['customer']['business_name']) && ($orderData[0]['customer']['business_name']!='')){
    $business_name = $orderData[0]['customer']['business_name'];
  }
  else if(isset($orderData[0]['customer']['fullname'])){
    $business_name = $orderData[0]['customer']['fullname'];
  }
  
  
  $bill_to =  "";
  if(isset($orderData[0]['customer']['customer_address_first']['pincode']) && ($orderData[0]['customer']['customer_address_first']['pincode']!='')){
    $bill_to = $orderData[0]['customer']['customer_address_first']['address']. " " .$orderData[0]['customer']['customer_address_first']['landmark']. "- " .$orderData[0]['customer']['customer_address_first']['pincode'];
  }
  
  $transporter_id = '24AEHFS0479Q1ZD'; 
  //isset($orderData[0]['customer']['GST_number']) ? $orderData[0]['customer']['GST_number'] : '';
  
?>

@if(count($orderData)>0)
<section style=" margin: 0 auto;">
  <header></header>
  <div class="parcel-experts">
    @php
    $invoice_date = isset($invoice_date) ? $invoice_date : date('Y-m-d');
    @endphp
    <div style="position: relative;">
      <div class="bill_invoice" style="padding: 0 15px;">
        <div class="party_bill_invoice" style="display:table;width: 100%; margin-top: 5px;margin-bottom: 5px; font-size: 11px;">
          <div class="party-bill-name" style="display: table-cell; vertical-align: top;width: 50%;">
            <p style="margin: 0; padding-left: 35px"><b>Bill To: </b> 
            <span style="text-transform: capitalize;"><b>{{ $business_name }} </b> <br>
            <p class="party_bill-address" style="text-transform: capitalize; padding-left: 36px;max-width: 250px;margin: 0;">{{ $bill_to }} </p>
            </span>
            </p>
          </div>
          <div class="party-bill-name" style="display: table-cell; vertical-align: top;width: 50%; text-align: left;">
            <div style="max-width: 270px; margin: 0 0 0 auto;">
              <div>
                <div class="bill_tran_id">
                  <p style="text-transform: capitalize; margin: 0px;"><b>transporter id</b><span>:</span> <span>{{ $transporter_id }}</span></p>
                </div>
              </div>
              <div>
                <div class="bill_tran_id">
                  <p style="text-transform: capitalize; margin: 0px;"><b>invoice no</b> <span style="padding-left: 16px;">:</span> <span> {{ @$invoice_number }}</span></p>
                </div>
              </div>
              <div>
                <div class="bill_tran_id">
                  <p style="text-transform: capitalize; margin: 0px;"><b>invoice date</b><span style="padding-left: 10px;">:</span> <span>{{ date("d/m/Y", strtotime($invoice_date)) }}</span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style="position: relative;">
          <table style="text-align: center;border-top:1px solid black;border-bottom:1px solid black; font-size: 11px; width: 100%;">
            <tr>
              <th style="border-bottom:1px solid black;">S.R No.</th>
              <th style="border-bottom:1px solid black;">Particulars</th>
              <th style="border-bottom:1px solid black;">Total Parcels</th>
              <th style="border-bottom:1px solid black;">L.R. No.</th>
              <th style="border-bottom:1px solid black;">Tempo No.</th>
              <th style="border-bottom:1px solid black;">Tempo Charge</th>
              <th style="border-bottom:1px solid black;">Service Charge</th>
              <th style="border-bottom:1px solid black;">Total Delivery Charges</th>
            </tr>
            <?php
            $i =1;  $tranporter_name_all = []; 
            ?>
            @foreach($orderData as $o)
            @foreach($o['order_parcel'] as $op)
            <tr>
              <th>{{ $i++ }}</td>
              <td>{{ @$op['goods_type']['name'] }}</td>
              <td>{{ @$op['no_of_parcel'] }}</td>
              <td>{{ @$o['transporter_lr_number'] }}</td>
              <td style="text-transform: uppercase;"> {{ @$o['vehicle']['vehicle_no'] }}</td>
              <td>{{ @number_format($op['tempo_charge'],2) }}</td>
              <td>{{ @number_format($op['service_charge'],2) }}</td>
              <td>{{ @number_format($op['delivery_charge'],2) }}</td>
            </tr>
            @endforeach
            @php 
              if(trim($o['transporter_name'])!=''){ $tranporter_name_all[] = trim($o['transporter_name']);  }
              if(trim($o['transporter_name_drop'])!=''){ $tranporter_name_all[] = trim($o['transporter_name_drop']);  }
            @endphp
            @endforeach
            <tfoot>
              <tr>
                <th colspan="7" style="border-top:1px solid black;text-align: right;">Delivery Charges</th>
                <td style="border-top:1px solid black;"><span style="font-weight: bold;">{{  number_format(array_sum(array_column($orderData, 'final_cost')),2) }}</span></td>
              </tr>
             @if(array_sum(array_column($orderData, 'redeliver_charge'))>0)
              <tr>
                <th colspan="7" style="text-align: right;">Redeliver Charges</th>
                <td><span style="font-weight: bold;">{{  number_format(array_sum(array_column($orderData, 'redeliver_charge')),2) }}</span></td>
              </tr>
              @endif
              @if(array_sum(array_column($orderData, 'min_order_value_charge'))>0)
              <tr>
                <th colspan="7" style="text-align: right;">Minimum Order Charges</th>
                <td><span style="font-weight: bold;">{{  number_format(array_sum(array_column($orderData, 'min_order_value_charge')),2) }}</span></td>
              </tr>
              @endif
              @if(array_sum(array_column($orderData, 'discount'))>0)
              <tr>
                <th colspan="7" style="text-align: right;">Discount Amount</th>
                <td><span style="font-weight: bold;">{{  number_format(array_sum(array_column($orderData, 'discount')),2) }}</span></td>
              </tr>
              @endif
              <?php
              $total_charges =  floatval(array_sum(array_column($orderData, 'redeliver_charge'))  + array_sum(array_column($orderData, 'final_cost')) + array_sum(array_column($orderData, 'min_order_value_charge')) - array_sum(array_column($orderData, 'discount')));
			  ?>
              <tr>
                <th colspan="7" style="text-align: right; border-top:1px solid">Total Charges</th>
                <td style="border-top:1px solid black;"><span style="font-weight: bold; ">{{  number_format($total_charges,2)  }}</span></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div style="position: relative; margin-top: 0px;">
          <div style="position: absolute;">
            <div class="terms_conditions" style="display: table;width: 100%;">
              <div style="display: table-cell;width: 65%;position: relative;">
                <p style="margin: 3px 0;font-size: 11px;"><i><b>(To be reimbursed to transporter)</b></i> <span style="border-bottom: 1px solid black;">{{ @implode(', ',array_unique($tranporter_name_all)) }}</span><span style="border-bottom: 1px solid;">Charges Rs. {{  @number_format(array_sum(array_column($orderData, 'transport_cost')),2) }}/-</span></p>
                <div>
                  <p style="font-weight: bold;margin: 0;font-size: 10px;">Terms & Conditions</p>
                  <ul style="list-style: none;padding-left: 0;margin-top: 3px; font-size: 11px;">
                    <li style="line-height: 15px;font-size: 11px;"><span style="padding-right: 5px;">1)</span>Subject to Surat jurisdiction.</li>
                    <li style="line-height: 15px;font-size: 11px;"><span style="padding-right: 5px;">2)</span>Payment to be made by payee’s account cheque, cash, draft or RTGS only.</li>
                    <li style="line-height: 15px;font-size: 11px;"><span style="padding-right: 5px;">3)</span>Bills to be cleared on delivery of goods. Interest will be charged @ 24% p.a. after due date.</li>
                    <li style="line-height: 15px;font-size: 11px;"><span style="padding-right: 5px;">4)</span><b style="border-bottom: 1px solid black;">Please Note</b>: Shakti Business Lab is <b>Udyam/ <span style="border-bottom: 1px solid black;">MSME/Udhyog Aadhar</span> </b> registered entity.</li>
                    <li style="line-height: 15px;font-size: 11px;"><span style="padding-right: 5px;">5)</span>The tax liability for services of a GTA is required to be borne by the recipient of these services under the Reverse Charge provisions of GST Act 2017.</li>
                  </ul>
                </div>
              </div>
              <div style="display: table-cell;width: 35%;vertical-align: top; font-size: 11px;position: relative; padding-top: 10px;">
                <div style="text-align: center;font-size: 11px;"> <b>For SHAKTI BUSINESS LAB</b> </div>
                <div style="margin-top: 70px;text-align: center;font-size: 11px;"> <b>Authorized Signatory</b> </div>
              </div>
            </div>
            <div style="padding-bottom: 40px;">
              <div style="">
                <p style="border: 1px solid black;padding: 4px 8px;font-size: 11px;"><b>AXIS BANK</b> - A/C Name: <b>SHAKTI BUSINESS LAB</b> - A/C No.: <b>920020073205444</b> - IFSC: <b>UTIB0000047</b></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer></footer>
</section>
@endif
</body>
</html>