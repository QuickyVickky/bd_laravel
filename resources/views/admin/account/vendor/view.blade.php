<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />
      <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/bootstrap-multiselect.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/daterangepicker.min.css') }}">
</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">

    <style>
    td span:first-child {
        margin-bottom: 5px;
    }
	.custom-select.multiselect
	 {
		padding: 10px 37px !important;
		border: 1px solid #061625 !important;
	 }
    </style>

    </head>

    <body data-spy="scroll" data-target="#navSection" data-offset="100">
        @include('admin.layout.header')

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">
            <div class="overlay"></div>
            <div class="search-overlay"></div>
            @include('admin.layout.sidebar')

            <!--  BEGIN CONTENT AREA  -->
           @if(!isset($one->id))
            <h4>Not Found</h4>
            @else
            <div id="content" class="main-content">
                <div class="layout-px-spacing">
                    <div class="row layout-top-spacing">
                        <div class="col-lg-12 col-12 layout-spacing">
                        @if(Session::get("msg")!='')
                                        <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x close" data-dismiss="alert">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg></button>
                                            {{ Session::get("msg") }}
                                        </div>
                                        @endif
                            <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
                                @if(is_allowedHtml('anything')==true)
                                <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab"
                                        href="#viewid" role="tab" aria-controls="home" aria-selected="true">View</a>
                                </li>
                                @endif
                                @if(is_allowedHtml('anything')==true)
                                <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab"
                                        href="#historyid" role="tab" aria-controls="contact"
                                        aria-selected="false">Transactions</a> </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="simpletabContent">
                                <div class="tab-pane fade show active" id="viewid" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    @if(is_allowedHtml('anything')==true)
                                    <div class="widget-content widget-content-area">
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">FullName / Company</label>
                                                        <span class="badge badge-warning float-right admid-select-color" onClick="show_add_newvendorModal({{ $one->id }})">
                                                            want to edit ? </span>
                                                        <input type="text" name="fullname"
                                                            class="form-control showcls24mec" id="fullname"
                                                            value="{{ $one->fullname }}" 
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Email</label>
                                                        <input type="email" name="email"
                                                            class="form-control showcls24mec" id="email"
                                                            value="{{ $one->email }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Mobile Number</label>
                                                        <input type="text" name="mobile"
                                                            class="form-control showcls24mec allownumber" id="mobile"
                                                            value="{{ $one->mobile }}"
                                                            maxlength="13" minlength="10" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                             <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Vendor Type  
                                                        @if($one->vendor_type==constants('vendor_type.Driver.key'))
                                                        <a target="_blank" href="{{ route('view-driver').'/'.@$one->driver->id }}" ><span class="badge badge-info float-right admid-select-color">View Driver</span></a>
                                                        @endif
                                                         </label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->vendor_type }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">First Name</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->firstname }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Last Name</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->lastname }}" disabled>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group required">
                                                        <label for="name">About</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->vendor_about }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group required">
                                                        <label for="name">Address</label>
                                                        <textarea type="text" rows="2" class="form-control showcls24mec" disabled>{{ $one->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group required">
                                                        <label for="name">Landmark</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->landmark }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group required">
                                                        <label for="name">Country</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->country }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group required">
                                                        <label for="name">State</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->state }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group required">
                                                        <label for="name">City</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->city }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group required">
                                                        <label for="name">Pincode</label>
                                                        <input type="text" class="form-control showcls24mec" value="{{ $one->pincode }}" disabled>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                @if(is_allowedHtml('anything')==true)
                                <div class="tab-pane fade" id="historyid" role="tabpanel" aria-labelledby="contact-tab">
                                <form method="post" action="{{ route('transactions_export_excel') }}" enctype="multipart/form-data" id="myExcelForm">
              @csrf
              <div class="card-header">
               <div class="row">
               <div class="col-md-3">
                <label>Banks</label>
                </div>
                <div class="col-md-5">
                <label>Category</label>
                </div>
                <div class="col-md-4">
                <label>Type</label>
                </div>
                </div>
                <div class="row">
                <div class="form-group col-md-3">
                    <select id="filter_global_accountid_from" name="filter_global_accountid_from[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
@foreach($accountBanksInDropDown as $ab)
<option value="{{ $ab->id }}" selected="selected"> {{ $ab->name }}</option>
@endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-5">
                    <select id="filter_global_transaction_subcategory_id" name="filter_global_transaction_subcategory_id[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
@foreach($transactionSubCategoryInDropDown as $tsb)
<option value="{{ $tsb->id }}" selected="selected"> {{ $tsb->name }}</option>
@endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-4">
                    <select id="filter_global_transaction_type" name="filter_global_transaction_type[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
						@foreach(constants('transaction_type_list') as $row)
                        <option value="{{ $row['key'] }}" selected> {{ $row['name2'] }} / {{ $row['name3'] }}</option>
                       		@endforeach
                            </select>
                  </div>
                  <input type="hidden" name="select_vendor_from_select2_dropdown_id[]" value="{{ $one->id }}" >
                  <div class="form-group col-md-3">
                    <input type="text" id="filter_global_transaction_date" class="form-control-sm" placeholder="Select Date" name="filter_global_transaction_date" required value="" autocomplete="off">
                  </div>
                  
                  </div>
                  <div class="row">
                  <div class="form-group col-md-3"> <a class="btn btn-outline-success" id="Apply_Filter_Btn_Id" onclick="getAllTransactionsData()"> Apply Filter</a> <a class="btn btn-outline-info" id="Export_Excel_Filter_Btn_Id" onclick="$('#myExcelForm').submit()"> Export Excel</a> </div>
                </div>
              </div>
            </form>
            <br>
             <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th >Amount</th>
                    <th>Date</th>
                    <th>Category & Details</th>
                    <th>Type</th>
                    <th>Bill No</th>
                    <th width="20%">Description</th>
                    <th width="20%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th data-search="1">Amount</th>
                    <th>Date</th>
                    <th>Category & Details</th>
                    <th>Type</th>
                    <th>Bill No</th>
                    <th width="20%">Description</th>
                    <th width="20%">Action</th>
                  </tr>
                </tfoot>
              </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.layout.footer')
            </div>
            @endif
            <!--  END CONTENT AREA  -->
        </div>
        <!-- END MAIN CONTAINER -->
        @include('admin.layout.js')
        <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/bootstrap-multiselect.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/daterangepicker.min.js') }}"></script> 
        <!----Add Custom Js ----start----->
@if(isset($one->id))
        <script type="text/javascript">
    $(document).ready(function() {
        $('.transaction_filter_multi_selectid').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
			maxHeight: 300,
        });
    });
</script> 
<script type="text/javascript">

var t1;
    $.fn.dataTable.ext.errMode = 'none';
    errorCount = 1;
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 5) {
            showSweetAlert('something went wrong', 'please refresh page and try again', 0);
        } else {
            t1.draw();
        }
        errorCount++;
    });
$(document).ready(function () {
	 getAllTransactionsData();
});


	function getAllTransactionsData() {
 			filter_global_accountid_from = $("#filter_global_accountid_from").val();
			filter_global_transaction_subcategory_id = $("#filter_global_transaction_subcategory_id").val();
			filter_global_transaction_date =  $("#filter_global_transaction_date").val();
			filter_global_transaction_type = $("#filter_global_transaction_type").val();
			filter_global_vendor_id = "{{ $one->id }}";

     		t1 = $('#t1').DataTable({
            processing: true,
			destroy: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            paging: true,
            searching: true,
            lengthMenu: [
                [10, 50, 100, 500],
                [10, 50, 100, 500]
            ],
            pageLength: 50,
            order: [
                [0, "desc"]
            ],
            ajax: {
                data: {
                    accountid_from:filter_global_accountid_from,
					transaction_subcategory_id:filter_global_transaction_subcategory_id,
					transaction_date:filter_global_transaction_date,
					transaction_type:filter_global_transaction_type,
					select_vendor_id:filter_global_vendor_id,
                },
                url: "{{ route('get-transaction-list-vendorwise') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
        });
	}















</script>
<script type="text/javascript">
$(function () {
            var start =  moment().clone().startOf('month').format('YYYY-MM-DD');
            var end = moment().clone().endOf('month').format('YYYY-MM-DD');

            function cb(start, end) {
                /*$('#filter_global_transaction_date').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));*/
            }

            $('#filter_global_transaction_date').daterangepicker({
                autoUpdateInput: true,
                //maxDate: moment().endOf("day"),
                startDate: start,
                endDate: end,
                ranges: {
					'Last 365 Days': [moment().subtract(365, 'days'), moment()],
					'Last 30 Days': [moment().subtract(30, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Today': [moment(), moment()],
                    'Last 7 Days': [moment().subtract(7, 'days'), moment()],
                }, locale: {
                    format: 'YYYY-MM-DD'
                }
            }, cb);

        });
		
$(document).ready(function () {
   $('#filter_global_transaction_date').val('');
});
</script>
@endif
<!----Add Custom Js --end------->
@include('admin.layout.snippets.add_newvendor')
</body>

</html>