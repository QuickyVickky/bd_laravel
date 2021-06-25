<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/bootstrap-multiselect.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/daterangepicker.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/select2.min.css')}}">
</head><body data-spy="scroll" data-target="#navSection" data-offset="100">
@include('admin.layout.header') 

<!--  BEGIN MAIN CONTAINER  -->
<style>
  .opacitydowncls {
	  opacity:0.7;
	  color:black;
	  }
	 .custom-select.multiselect
	 {
		padding: 10px 37px !important;
		border: 1px solid #061625 !important;
	 }
  </style>
<div class="main-container" id="container">
  <div class="overlay"></div>
  <div class="search-overlay"></div>
  @include('admin.layout.sidebar') 
  
  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="layout-px-spacing">
      <div class="row layout-top-spacing">
        <div class="col-lg-12 col-12 layout-spacing">
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>{{ @$control }}</h4>
                   @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif 
                 </div>
                <div class="col-xl-6 col-md-6 col-sm-6 col-6"> <a class="btn btn-primary  m-2 btn-rounded float-right" href="{{ route('add-transaction') }}?type=Expense" ><i class="fas fa-money"></i> Add Expense</a> </div>
              </div>
            </div>
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
                  <div class="form-group col-md-4">
                    <select id="select_vendor_from_select2_dropdown_id" name="select_vendor_from_select2_dropdown_id[]" class="form-control-sm" multiple="multiple">
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <input type="text" id="filter_global_transaction_date" class="form-control-sm" placeholder="Select Date" name="filter_global_transaction_date" required value="" autocomplete="off">
                  </div>
                  
                  </div>
                  <div class="row">
                  <div class="form-group col-md-3"> <a class="btn btn-outline-success" id="Apply_Filter_Btn_Id" onclick="getAllTransactionsData()"> Apply Filter</a> <button type="submit" class="btn btn-outline-info" id="Export_Excel_Filter_Btn_Id" > Export Excel</button> </div>
                </div>
              </div>
            </form>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
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
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('admin.layout.footer') </div>
  <!--  END CONTENT AREA  --> 
</div>
<!-- END MAIN CONTAINER --> 

<!----image display Modal start---->
<div class="modal fade fadeInUp" id="iModal" tabindex="-1" role="dialog" aria-labelledby="iModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="iModalLabel">View</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <div class="modal-body">
        <h5 id="billnoh5id"></h5>
        <div id="billdocumentid"></div>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!----image display  Modal end----> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/bootstrap-multiselect.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/daterangepicker.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script type="text/javascript">
    $(document).ready(function() {
        $('.transaction_filter_multi_selectid').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
			maxHeight: 300,
        });
    });
</script> 

<script type="text/javascript" >
   	var filepath = "{{ sendPath().constants('dir_name.bill').'/' }}";
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
			select_vendor_from_select2_dropdown_id = $("#select_vendor_from_select2_dropdown_id").val();
			filter_global_transaction_date =  $("#filter_global_transaction_date").val();
			filter_global_transaction_type = $("#filter_global_transaction_type").val();

     		t1 = $('#t1').DataTable({
            processing: true,
			destroy: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            paging: true,
            searching: true,
			searchDelay: 999,
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
					select_vendor_id:select_vendor_from_select2_dropdown_id,
					transaction_date:filter_global_transaction_date,
					transaction_type:filter_global_transaction_type,
                },
                url: "{{ route('get-transaction-list') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
        });
	}

				
				
$('body').on('click', '.viewit', function () {
  $('#billdocumentid').empty();
     	var id = $(this).data('id');
		var anybillno_document = $(this).data('anybillno_document');
		var anybillno = $(this).data('billno');
		$('#iModalLabel').html('View');
		$('#billnoh5id').html('Bill No : '+anybillno);
		if(anybillno_document!=''){
			var fileextension = anybillno_document.substr( (anybillno_document.lastIndexOf('.') +1) );
			if(fileextension=='png' || fileextension=='jpg' || fileextension=='jpeg' || fileextension=='gif' || fileextension=='bmp'){
			$('#billdocumentid').html('<img src="'+filepath+anybillno_document+'" alt="no-file" id="img_src" class="img-responsive" style="max-width: 400px; max-height: 250px;" />');
			}
			else
			{
				$('#billdocumentid').html('<a href="'+filepath+anybillno_document+'" target="_blank">View File</a>');
			}
		}
		
		$("#iModal").modal('show');
  });
  
  
  
  $('body').on('click', '.deleteit', function () {
		var editid = $(this).data('editid');
		var anybillno_document = $(this).data('anybillno_document');
		var transactionid = $(this).data('transactionid');

  swal({
      title: 'Are You Sure ?',
      text: "Do You Want to Delete This ?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '-2em'
    }).then(function(result) {
      if (result.value) {
		  
		  var answers = prompt("Please Type 'delete' to Delete This", "type");
		 if (answers != "delete") {
					swal(
                            'Wrong!',
                            "Please Type Correctly to Delete This Order",
                            'error',
                        );
					return false;
		 }
		  
		  $.ajax({
            url: "{{ route('delete-this-transaction') }}",
            data: {
              _token : '{{ csrf_token() }}',
              hid: editid, 
			  hidtransactionid: transactionid, 
			  existing_img : anybillno_document,
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             t1.draw(); 
			showAlert(data);
            },  error: function(){  showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });

      }
    });
});

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


var select_vendor_from_select2_dropdown_id = $("#select_vendor_from_select2_dropdown_id").select2({
    		placeholder: "  Select A Vendor (optional)",
    		width:"100%",
                ajax: {
					url: "{{ route('vendorInDropDown') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'includeid': 0, 
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
			
 });

</script>
<!----Add Custom Js --end-------> 

@include('admin.layout.crudhelper')
</body>
</html>