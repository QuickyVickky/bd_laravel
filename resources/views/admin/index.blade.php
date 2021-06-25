<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<!--link href="{{ asset('admin_assets/assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin_assets/assets/js/loader.js')}}"></script-->
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="{{ asset('admin_assets/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin_assets/assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" />

<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head><body>
<!-- BEGIN LOADER --> 
<!--div id="load_screen">
  <div class="loader">
    <div class="loader-content">
      <div class="spinner-grow align-self-center"></div>
    </div>
  </div>
</div--> 
<!--  END LOADER --> 

@include('admin.layout.header') 

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
  <div class="overlay"></div>
  <div class="search-overlay"></div>
  @include('admin.layout.sidebar') 
  
  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="layout-px-spacing">
      <div class="layout-px-spacing">
        <div class="row layout-top-spacing" >
        @if(is_allowedHtml('roleclass_view_todayeportsection_dashboard')==true)
          <div class="col-xl-4 col-lg-12 col-sm-12 col-12 layout-spacing">
            <div class="widget-one">
              <div class="widget-content"> <a href="{{ route('order-list') }}">
                <div class="w-numeric-value">
                  <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="20.001" height="20" viewBox="0 0 20.001 20">
                    <g id="delivery-box_1_" data-name="delivery-box (1)" transform="translate(0.001 0)">
                      <path id="Path_11113" data-name="Path 11113" d="M9.994,20.959a.414.414,0,0,1-.193-.047l-9.577-5A.417.417,0,0,1,0,15.542V6.167a.416.416,0,0,1,.6-.373l9.577,4.792a.417.417,0,0,1,.23.373v9.584a.417.417,0,0,1-.2.357A.411.411,0,0,1,9.994,20.959ZM.833,15.289l8.744,4.565V11.216L.833,6.841Z" transform="translate(0 -0.958)" fill="#1b55e2"/>
                      <path id="Path_11114" data-name="Path 11114" d="M11.925,20.959a.416.416,0,0,1-.417-.417V10.958a.417.417,0,0,1,.23-.373l9.577-4.792a.417.417,0,0,1,.6.373v9.375a.417.417,0,0,1-.224.369l-9.577,5a.414.414,0,0,1-.193.048Zm.417-9.743v8.639l8.744-4.565V6.841Zm9.16,4.326h.008Z" transform="translate(-1.918 -0.958)" fill="#1b55e2"/>
                      <path id="Path_11115" data-name="Path 11115" d="M.417,5.625A.416.416,0,0,1,.23,4.836L9.82.044a.418.418,0,0,1,.373,0l9.577,4.792a.417.417,0,0,1-.373.746l-9.39-4.7L.6,5.581a.415.415,0,0,1-.187.044Z" transform="translate(0 0)" fill="#1b55e2"/>
                      <path id="Path_11116" data-name="Path 11116" d="M16,12.959a.417.417,0,0,1-.417-.417V8.216L6.238,3.539a.417.417,0,0,1,.373-.746l9.577,4.792a.42.42,0,0,1,.23.373v4.583a.417.417,0,0,1-.417.417Z" transform="translate(-1.001 -0.458)" fill="#1b55e2"/>
                    </g>
                    </svg> </div>
                  <div class="w-content"> <span class="w-value">{{ @$today['total'][0]->total_count}}</span> <span class="w-numeric-title">Today's Total Orders</span> </div>
                </div>
                </a> <a href="{{ route('tobeassigned-orders') }}">
                <div class="w-numeric-value">
                  <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="21.422" height="20.5" viewBox="0 0 21.422 20.5">
                    <path id="favourite" d="M20.85,8.055a1.478,1.478,0,0,0-1.294-.919L14.146,6.35l-2.42-4.9A1.478,1.478,0,0,0,10.453.5a1.479,1.479,0,0,0-1.273.947L6.76,6.35l-5.411.786a1.478,1.478,0,0,0-1.294.919,1.478,1.478,0,0,0,.507,1.5l3.915,3.816-.924,5.389a1.557,1.557,0,0,0,.273,1.327,1.171,1.171,0,0,0,.914.41,1.886,1.886,0,0,0,.873-.24l4.839-2.544,4.84,2.544a1.888,1.888,0,0,0,.873.24h0a1.171,1.171,0,0,0,.914-.41,1.556,1.556,0,0,0,.273-1.327l-.924-5.389,3.915-3.816a1.478,1.478,0,0,0,.507-1.5Zm-1.362.626-4.146,4.041a.612.612,0,0,0-.176.542l.979,5.706a.749.749,0,0,1,0,.3.755.755,0,0,1-.288-.1l-5.125-2.694a.613.613,0,0,0-.57,0L5.043,19.176a.757.757,0,0,1-.288.1.752.752,0,0,1,0-.3l.979-5.706a.613.613,0,0,0-.176-.542L1.417,8.681a.755.755,0,0,1-.183-.243.754.754,0,0,1,.291-.089l5.73-.833a.613.613,0,0,0,.461-.335l2.562-5.192a.754.754,0,0,1,.175-.249.755.755,0,0,1,.175.249L13.19,7.181a.613.613,0,0,0,.461.335l5.729.833a.753.753,0,0,1,.291.089.755.755,0,0,1-.183.243Zm0,0" transform="translate(0.259 -0.25)" fill="#1b55e2" stroke="#1b55e2" stroke-width="0.5"/>
                    </svg> </div>
                  <div class="w-content"> <span class="w-value">{{ @$today['neworder'][0]->neworder}}</span> <span class="w-numeric-title">Today's Total New Orders</span> </div>
                </div>
                </a> <a href="{{ route('assigned-orders') }}">
                <div class="w-numeric-value">
                  <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="3" height="19.692" viewBox="0 0 3 19.692">
                    <g id="Group_3495" data-name="Group 3495" transform="translate(-3426.697 -10102.909)">
                      <g id="Group_3500" data-name="Group 3500" transform="translate(3426.697 10102.909)">
                        <g id="Group_3499" data-name="Group 3499" transform="translate(0 0)">
                          <path id="Path_11133" data-name="Path 11133" d="M236.261,125.909a1.581,1.581,0,0,0-1.5,1.653v9.918a1.507,1.507,0,1,0,3,0v-9.918A1.581,1.581,0,0,0,236.261,125.909Z" transform="translate(-234.761 -125.909)" fill="#1b55e2"/>
                        </g>
                      </g>
                      <g id="Group_3502" data-name="Group 3502" transform="translate(3426.697 10119.602)">
                        <g id="Group_3501" data-name="Group 3501" transform="translate(0 0)">
                          <path id="Path_11134" data-name="Path 11134" d="M237.639,342.214a1.4,1.4,0,0,0-.315-.495,1.43,1.43,0,0,0-.24-.18,1.624,1.624,0,0,0-.54-.225,1.5,1.5,0,0,0-1.348.4,1.4,1.4,0,0,0-.315.495,1.414,1.414,0,0,0,0,1.14,1.352,1.352,0,0,0,.81.81,1.41,1.41,0,0,0,1.138,0,1.75,1.75,0,0,0,.495-.315,1.732,1.732,0,0,0,.315-.495,1.414,1.414,0,0,0,0-1.14Z" transform="translate(-234.762 -341.284)" fill="#1b55e2"/>
                        </g>
                      </g>
                    </g>
                    </svg> </div>
                  <div class="w-content"> <span class="w-value">{{ @$today['assigned'][0]->assigned}}</span> <span class="w-numeric-title">Today's Total Assigned Orders</span> </div>
                </div>
                </a> <a href="{{ route('cancelled-orders') }}">
                <div class="w-numeric-value">
                  <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="20.022" height="20" viewBox="0 0 20.022 20">
                    <g id="_001-cancel" data-name="001-cancel" transform="translate(0 -0.135)">
                      <path id="Close" d="M11.468,10.149,19.7,1.965a.991.991,0,0,0,0-1.412,1.015,1.015,0,0,0-1.426,0L10.049,8.73l-8.3-8.3A1.01,1.01,0,0,0,.325,1.861l8.292,8.293L.3,18.43a.992.992,0,0,0,0,1.412,1.015,1.015,0,0,0,1.426,0l8.315-8.269L18.3,19.839a1.01,1.01,0,1,0,1.426-1.43Z" fill="#1b55e2"/>
                    </g>
                    </svg> </div>
                  <div class="w-content"> <span class="w-value">{{ @$today['cancelorder'][0]->total_cancelled}}</span> <span class="w-numeric-title">Today's Total Cancelled Orders</span> </div>
                </div>
                </a> <a href="{{ route('delivered-orders') }}">
                <div class="w-numeric-value">
                  <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="26.299" height="20" viewBox="0 0 26.299 20">
                    <g id="_003-check" data-name="003-check" transform="translate(0 -51.096)">
                      <g id="Group_3496" data-name="Group 3496" transform="translate(0 51.096)">
                        <path id="Path_11131" data-name="Path 11131" d="M26,51.418a1.052,1.052,0,0,0-1.487-.026l-.026.026L7.351,68.557,1.783,62.989A1.052,1.052,0,0,0,.3,64.477l6.312,6.312a1.052,1.052,0,0,0,1.487,0L25.978,52.905A1.052,1.052,0,0,0,26,51.418Z" transform="translate(0 -51.096)" fill="#1b55e2"/>
                      </g>
                    </g>
                    </svg> </div>
                  <div class="w-content"> <span class="w-value">{{ @$today['deliveredorder'][0]->total_delivered}}</span> <span class="w-numeric-title">Today's Total Delivered Orders</span> </div>
                </div>
                </a> 
                
                <!--
                <div class="w-chart" style="position: relative;">
                  <div id="total-orders" style="min-height: 280px;"></div>
                  <div class="resize-triggers">
                    <div class="expand-trigger">
                      <div style="width: 399px; height: 281px;"></div>
                    </div>
                    <div class="contract-trigger"></div>
                  </div>
                </div>
                --> 
                
              </div>
            </div>
          </div>
          @endif
          @if(is_allowedHtml('roleclass_view_customereportsection_dashboard')==true)
          <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-two">
              <div class="widget-heading">
                <h5 class=""><a href="{{ route('customer-list') }}">Customers</a></h5>
              </div>
              <div class="widget-content" style="position: relative;">
                <div id="chart_customer_id" class="" style="min-height: 380px;"></div>
                <div class="resize-triggers">
                  <div class="expand-trigger">
                    <div style="width: 399px; height: 401px;"></div>
                  </div>
                  <div class="contract-trigger"></div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @if(is_allowedHtml('roleclass_view_summaryreportsection_dashboard')==true)
          <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-three">
              <div class="widget-heading">
                <h5 class="">Summary</h5>
              </div>
              <div class="widget-content">
                <div class="order-summary">
                  <div class="summary-list">
                    <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag">
                      <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                      <line x1="3" y1="6" x2="21" y2="6"></line>
                      <path d="M16 10a4 4 0 0 1-8 0"></path>
                      </svg> </div>
                    <div class="w-summary-details">
                      <div class="w-summary-info">
                        <h6>Income</h6>
                        <p class="summary-count">&#x20B9; {{ @$chartdata['total_revenue'][0]->total_balance }}</p>
                      </div>
                      <div class="w-summary-stats">
                        <div class="progress">
                          <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="summary-list">
                    <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag">
                      <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                      <line x1="7" y1="7" x2="7" y2="7"></line>
                      </svg> </div>
                      <?php
					  $totalprofitorloss = @$chartdata['total_revenue'][0]->total_balance - @$chartdata['total_expenses'][0]->total_balance;
					  
					  if($totalprofitorloss>0 && $totalprofitorloss!='' ){
						  $textforProfirorLoss = 'Profit';
						  $colorgradientsforProfirorLoss = 'success';
					  }
					  else
					  {
						  $textforProfirorLoss = 'Loss';
						  $colorgradientsforProfirorLoss = 'danger';
					  }
					  
					  ?>
                    <div class="w-summary-details">
                      <div class="w-summary-info">
                        <h6>{{ $textforProfirorLoss }}</h6>
                        <p class="summary-count">&#x20B9; {{ @$chartdata['total_revenue'][0]->total_balance - @$chartdata['total_expenses'][0]->total_balance }} </p>
                      </div>
                      <div class="w-summary-stats">
                        <div class="progress">
                          <div class="progress-bar bg-gradient-{{ $colorgradientsforProfirorLoss }}" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="summary-list">
                    <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                      <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                      <line x1="1" y1="10" x2="23" y2="10"></line>
                      </svg> </div>
                    <div class="w-summary-details">
                      <div class="w-summary-info">
                        <h6>Expenses</h6>
                        <p class="summary-count">&#x20B9; {{ @$chartdata['total_expenses'][0]->total_balance}} </p>
                      </div>
                      <div class="w-summary-stats">
                        <div class="progress">
                          <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @if(is_allowedHtml('roleclass_view_last7dayscountreportsection_dashboard')==true)
          <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
            <div class="widget-two" style="min-height: 420px;">
              <div class="widget-content">
                <div class="w-numeric-value">
                  <div class="w-content"> <span class="w-value">Daily Sales</span> <span class="w-numeric-title">Go to columns for details.</span> </div>
                  <div class="w-icon"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg> </div>
                </div>
                <div class="w-chart">
                  <div id="daily-sales" style="min-height: 175px;"></div>
                  <div class="resize-triggers">
                    <div class="expand-trigger">
                      <div style="width: 399px; height: 176px;"></div>
                    </div>
                    <div class="contract-trigger"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @if(is_allowedHtml('roleclass_view_thisyearmonthlyreportsection_dashboard')==true)
          <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-chart-one">
              <div class="widget-heading">
                <h5 class="">Revenue</h5>
                <ul class="tabs tab-pills">
                  <li><a href="javascript:void(0);" id="tb_1" class="tabmenu">Monthly</a></li>
                </ul>
              </div>
              <div class="widget-content">
                <div class="tabs tab-content">
                  <div id="content_1" class="tabcontent" style="position: relative;">
                    <div id="revenueMonthly" style="min-height: 380px;"></div>
                    <div class="resize-triggers">
                      <div class="expand-trigger">
                        <div style="width: 787px; height: 381px;"></div>
                      </div>
                      <div class="contract-trigger"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

        </div>
      </div>
      
      <!--
      <div class="row layout-top-spacing">
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
          <div class="widget widget-account-invoice-one">
            <div class="widget-heading">
              <h5 class="">Today</h5>
            </div>
            <div class="widget-content">
              <div class="invoice-box">
                <div class="acc-total-info">
                  <h5>Total Revenue</h5>
                  @php
                  
                  //dde($today)
                  
                  @endphp
                  <p class="acc-amount">{{ @$today['total'][0]->total_balance}} Rs.</p>
                </div>
                <div class="inv-detail">
                  <div class="info-detail-2">
                    <p>Total Order</p>
                    <p>{{ @$today['total'][0]->total_count}}</p>
                  </div>
                  <div class="info-detail-2">
                    <p>Total Cancelled Order</p>
                    <p>{{ @$today['cancelorder'][0]->total_cancelled}}</p>
                  </div>
                  <div class="info-detail-2">
                    <p>Total Delivered Order</p>
                    <p>{{ @$today['deliveredorder'][0]->total_delivered }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      --> 
      
    </div>
    @include('admin.layout.footer') </div>
  <!--  END CONTENT AREA  --> 
  
</div>
<!-- END MAIN CONTAINER -->

<?php 
		$thisyearallmonth = []; $thisyearallincomeinjson = [];  $thisyearallexpensesinjson = []; 
		$last7daysdeliveredcount = []; $last7dayscancelledcount = [];
			for($mj=0; $mj<=11; $mj++) {
                $thisyearallincomeinjson[] = 0; 
				$thisyearallexpensesinjson[] = 0; 
				$thisyearallmonth[] = date('M', mktime(0,0,0,$mj+1, 1, date('Y')));
			}
			
			for($mj=0; $mj<=6; $mj++) {
                $last7daysdeliveredcount[date('Y-m-d', strtotime('-'.$mj.' days'))] = 0; 
				$last7dayscancelledcount[date('Y-m-d', strtotime('-'.$mj.' days'))] = 0; 
			}
			
			foreach($chartdata['thisyear_revenue_graph'] as $row){
				  $thisyearallincomeinjson[$row->month_number-1] =  $row->total_revenue;
			}
			
			foreach($chartdata['thisyear_expenses_graph'] as $row){
				  $thisyearallexpensesinjson[$row->month_number-1] =  $row->total;
			}
			
			foreach($chartdata['last7days_revenue_graph_delivered'] as $row){
				  $last7daysdeliveredcount[$row->date_number] =  $row->total_count;
			}
			
			foreach($chartdata['last7days_revenue_graph_cancelled'] as $row){
				  $last7dayscancelledcount[$row->date_number] =  $row->total_count;
			}

?>
@include('admin.layout.js') 
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS --> 
<script src="{{ asset('admin_assets/plugins/apex/apexcharts.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dashboard/dash_1.js')}}"></script> 
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS --> 
<script type="text/javascript">
var customerchartjsonseries = <?php echo isset($chartdata['customer']) ? json_encode(array_column($chartdata['customer'],'series')) : 0;  ?>;
var customerchartjsonlabels =  <?php echo isset($chartdata['customer']) ? json_encode(array_column($chartdata['customer'],'labels')) : 0;  ?>;
var thisyearallmonth =  <?php echo json_encode($thisyearallmonth); ?>;
var thisyearallincomeinjson =  <?php echo json_encode($thisyearallincomeinjson); ?>;
var thisyearallexpensesinjson =  <?php echo json_encode($thisyearallexpensesinjson); ?>;
var thisyearallincomeinrupeejson =  <?php echo array_sum($thisyearallincomeinjson); ?>;
var last7daysdateinjson =  <?php echo json_encode(getLastNDays(7, 'd-M-Y')) ?>;
var last7dayscancelledcount =  <?php echo json_encode(array_values(array_reverse($last7dayscancelledcount))) ?>;
var last7daysdeliveredcount =  <?php echo json_encode(array_values(array_reverse($last7daysdeliveredcount))) ?>;


//console.log(customerchartjsonseries);

      
/*
    ==================================
      Customer  | Options
    ==================================
*/
var chart_customer_variable = {
    chart: {
        type: 'donut',
        width: 380
    },
    colors: ['#5c1ac3', '#e2a03f', '#e7515a', '#e2a03f'],
    dataLabels: {
      enabled: false
    },
    legend: {
        position: 'bottom',
        horizontalAlign: 'center',
        fontSize: '14px',
        markers: {
          width: 10,
          height: 10,
        },
        itemMargin: {
          horizontal: 0,
          vertical: 8
        }
    },
    plotOptions: {
      pie: {
        donut: {
          size: '65%',
          background: 'transparent',
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: '20px',
              fontFamily: 'Nunito, sans-serif',
              color: undefined,
              offsetY: -10
            },
            value: {
              show: true,
              fontSize: '26px',
              fontFamily: 'Nunito, sans-serif',
              color: '20',
              offsetY: 16,
              formatter: function (val) {
                return val
              }
            },
            total: {
              show: true,
              showAlways: true,
              label: 'Total',
              color: '#888ea8',
              formatter: function (w) {
                return w.globals.seriesTotals.reduce( function(a, b) {
                  return a + b
                }, 0)
              }
            }
          }
        }
      }
    },
    stroke: {
      show: true,
      width: 25,
    },
    series: customerchartjsonseries,
    labels: customerchartjsonlabels,
    responsive: [{
        breakpoint: 1599,
        options: {
            chart: {
                width: '350px',
                height: '400px'
            },
            legend: {
                position: 'bottom'
            }
        },

        breakpoint: 1439,
        options: {
            chart: {
                width: '250px',
                height: '390px'
            },
            legend: {
                position: 'bottom'
            },
            plotOptions: {
              pie: {
                donut: {
                  size: '65%',
                }
              }
            }
        },
    }]
}




/*
    =============================
        Total Orders | Options
    =============================
*/
var chart_TodayTotalOrder_variable = {
  chart: {
    id: 'sparkline1',
    group: 'sparklines',
    type: 'area',
    height: 280,
    sparkline: {
      enabled: true
    },
  },
  stroke: {
      curve: 'smooth',
      width: 2
  },
  fill: {
    opacity: 1,
  },
  series: [{
    name: '',
    data: []
  }],
  labels: [],
  yaxis: {
    min: 0
  },
  grid: {
    padding: {
      top: 125,
      right: 0,
      bottom: 36,
      left: 0
    }, 
  },
  fill: {
      type:"gradient",
      gradient: {
          type: "vertical",
          shadeIntensity: 1,
          inverseColors: !1,
          opacityFrom: .40,
          opacityTo: .05,
          stops: [45, 100]
      }
  },
  tooltip: {
    x: {
      show: false,
    },
    theme: 'dark'
  },
  colors: ['#fff']
}




/*
    =================================
        Revenue Monthly | Options
    =================================
*/
var chart_Revenue_Monthly_variable = {
  chart: {
    fontFamily: 'Nunito, sans-serif',
    height: 365,
    type: 'area',
    zoom: {
        enabled: false
    },
    dropShadow: {
      enabled: true,
      opacity: 0.3,
      blur: 5,
      left: -7,
      top: 22
    },
    toolbar: {
      show: false
    },
    events: {
      mounted: function(ctx, config) {
        const highest1 = ctx.getHighestValueInSeries(0);
        const highest2 = ctx.getHighestValueInSeries(1);

        ctx.addPointAnnotation({
          x: new Date(ctx.w.globals.seriesX[0][ctx.w.globals.series[0].indexOf(highest1)]).getTime(),
          y: highest1,
          label: {
            style: {
              cssClass: 'd-none'
            }
          },
          customSVG: {
              SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#1b55e2" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
              cssClass: undefined,
              offsetX: -8,
              offsetY: 5
          }
        })

        ctx.addPointAnnotation({
          x: new Date(ctx.w.globals.seriesX[1][ctx.w.globals.series[1].indexOf(highest2)]).getTime(),
          y: highest2,
          label: {
            style: {
              cssClass: 'd-none'
            }
          },
          customSVG: {
              SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#e7515a" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
              cssClass: undefined,
              offsetX: -8,
              offsetY: 5
          }
        })
      },
    }
  },
  colors: ['#1b55e2', '#e7515a'],
  dataLabels: {
      enabled: false
  },
  markers: {
    discrete: [{
    seriesIndex: 0,
    dataPointIndex: 7,
    fillColor: '#000',
    strokeColor: '#000',
    size: 5
  }, {
    seriesIndex: 2,
    dataPointIndex: 11,
    fillColor: '#000',
    strokeColor: '#000',
    size: 4
  }]
  },
  subtitle: {
    text: 'Total Profit',
    align: 'left',
    margin: 0,
    offsetX: -10,
    offsetY: 35,
    floating: false,
    style: {
      fontSize: '14px',
      color:  '#888ea8'
    }
  },
  title: {
    text: '₹ '+thisyearallincomeinrupeejson,
    align: 'left',
    margin: 0,
    offsetX: -10,
    offsetY: 0,
    floating: false,
    style: {
      fontSize: '25px',
      color:  '#0e1726'
    },
  },
  stroke: {
      show: true,
      curve: 'smooth',
      width: 2,
      lineCap: 'square'
  },
  series: [{
      name: 'Income',
      data: thisyearallincomeinjson
  }, {
      name: 'Expenses',
      data: thisyearallexpensesinjson
  }],
  labels: thisyearallmonth,
  xaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      show: true
    },
    labels: {
      offsetX: 0,
      offsetY: 5,
      style: {
          fontSize: '12px',
          fontFamily: 'Nunito, sans-serif',
          cssClass: 'apexcharts-xaxis-title',
      },
    }
  },
  yaxis: {
    labels: {
      formatter: function(value, index) {
        return (value / 1000) + 'K'
      },
      offsetX: -22,
      offsetY: 0,
      style: {
          fontSize: '12px',
          fontFamily: 'Nunito, sans-serif',
          cssClass: 'apexcharts-yaxis-title',
      },
    }
  },
  grid: {
    borderColor: '#e0e6ed',
    strokeDashArray: 5,
    xaxis: {
        lines: {
            show: true
        }
    },   
    yaxis: {
        lines: {
            show: false,
        }
    },
    padding: {
      top: 0,
      right: 0,
      bottom: 0,
      left: -10
    }, 
  }, 
  legend: {
    position: 'top',
    horizontalAlign: 'right',
    offsetY: -50,
    fontSize: '16px',
    fontFamily: 'Nunito, sans-serif',
    markers: {
      width: 10,
      height: 10,
      strokeWidth: 0,
      strokeColor: '#fff',
      fillColors: undefined,
      radius: 12,
      onClick: undefined,
      offsetX: 0,
      offsetY: 0
    },    
    itemMargin: {
      horizontal: 0,
      vertical: 20
    }
  },
  tooltip: {
    theme: 'dark',
    marker: {
      show: true,
    },
    x: {
      show: false,
    }
  },
  fill: {
      type:"gradient",
      gradient: {
          type: "vertical",
          shadeIntensity: 1,
          inverseColors: !1,
          opacityFrom: .28,
          opacityTo: .05,
          stops: [45, 100]
      }
  },
  responsive: [{
    breakpoint: 575,
    options: {
      legend: {
          offsetY: -30,
      },
    },
  }]
}


/*
    =============================
        Daily Sales | Options
    =============================
*/
var chart_last7days_variable = {
  chart: {
        height: 160,
        type: 'bar',
        stacked: true,
        stackType: '100%',
        toolbar: {
          show: false,
        }
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        width: 1,
    },
    colors: ['#e2a03f', '#e0e6ed'],
    responsive: [{
        breakpoint: 480,
        options: {
            legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
            }
        }
    }],
    series: [{
        name: 'Delivered',
        data: last7daysdeliveredcount
    },{
        name: 'Cancelled',
        data: last7dayscancelledcount
    }],
    xaxis: {
        labels: {
            show: false,
        },
        categories: last7daysdateinjson,
    },
    yaxis: {
        show: false
    },
    fill: {
        opacity: 1
    },
    plotOptions: {
        bar: {
            horizontal: false,
            endingShape: 'rounded',
            columnWidth: '25%',
        }
    },
    legend: {
        show: false,
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        padding: {
          top: 10,
          right: 0,
          bottom: -40,
          left: 0
        }, 
    },
}





/*
    =================================
        Customer | Render
    =================================
*/
var chart_customer_v = new ApexCharts(document.querySelector("#chart_customer_id"), chart_customer_variable);
chart_customer_v.render();


/*
    ============================
        Total Orders | Render
    ============================
*/
var chart_TodayTotalOrder_v = new ApexCharts(document.querySelector("#total-orders"), chart_TodayTotalOrder_variable);
chart_TodayTotalOrder_v.render();


/*
    ================================
        Revenue Monthly | Render
    ================================
*/
var chart_Revenue_Monthly_v = new ApexCharts(document.querySelector("#revenueMonthly"),chart_Revenue_Monthly_variable);

chart_Revenue_Monthly_v.render();



/*
    ============================
        Daily Sales | Render
    ============================
*/
var chart_last7days_v = new ApexCharts(document.querySelector("#daily-sales"), chart_last7days_variable);
chart_last7days_v.render();






</script>
</body>
</html>