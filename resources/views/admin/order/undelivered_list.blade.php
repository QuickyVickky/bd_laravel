<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />
</head>
<body data-spy="scroll" data-target="#navSection" data-offset="100">

<style>
  td span:first-child {
    margin-bottom: 5px;
  }
</style>


    
@include('admin.layout.header') 

<!--  BEGIN MAIN CONTAINER  -->
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
                  <h4>{{ $control }} </h4>
                 
                  @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></button>
                    {{ Session::get("msg") }}</div>
                  @endif </div>
                  <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <a class="btn btn-warning m-2 btn-rounded float-right" href="javascript:void(0)" onClick="assignMultiple()"> Assign</a>
                  </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
             <input type="hidden" name="ALL"  id="ALL"  value="">
              <table id="t1" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">BD LR Number <input type="checkbox" id="selectAllid" onClick="selectAll(this)"></th>
                                                <th width="5%">Transporter LR Number</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <thead class="searchnow">
                                            <tr>
                                                <th width="5%" class="searchcls">BD LR Number</th>
                                                <th width="5%" class="searchcls">Transporter LR Number</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%" class="searchcls">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="t1tbody">
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="5%">#</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
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

    <!----assign driver Modal start---->
    <div class="modal fade" id="assignModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Assign This Order to Driver</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form id="assign_form">
                        @csrf
                        <div class="col-sm-12">
                            <div class="form-group required">
                                <select id="driver_id" name="driver_id" class="form-control" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <label>Type</label>
                            <div class="form-group required">
                                <select id="driver_assign_type" name="driver_assign_type" class="form-control" required>
                                    <option value="PPT">Pay Per Trip</option>
                                    <option value="PPP" >Pay Per Parcel</option>
                                    <option value="PRL" selected="">Payroll</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-sm-12" id="driver_assign_type_amount_rowid">
                            <label>Amount &#x20B9; *</label>
                            <div class="form-group required">
                                <input type="text" id="driver_assign_type_amount" placeholder="Amount"
                                    name="driver_assign_type_amount" value="0" class="form-control allownumber">
                            </div>
                        </div>

                        <input type="hidden" name="ovalueid" id="ovalueid" value="0">
                        <input type="hidden" name="dvalueid" id="dvalueid" value="0">
                        <input type="hidden" name="bigdaddylrnumberhid" id="bigdaddylrnumberhid" value="0">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Bigdaddy LR Number </label>
                                    <p id="BigdaddyLRNumberid"></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Order Date</label>
                                    <p id="OrderDateid"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!----assign driver Modal end---->

<!----order payment confirm display Modal starts---->
<div class="modal fade fadeInUp" id="pModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="">Select</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <form id="">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group required">
              <label>is This Order payment is Paid or Pending ?</label>
              <br>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="payment_status1" name="payment_status" class="custom-control-input payment_status" value="1" checked>
                  <label class="custom-control-label" for="payment_status1">Paid</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="payment_status0" name="payment_status" class="custom-control-input payment_status" value="0" >
                  <label class="custom-control-label" for="payment_status0">Pending</label>
                </div>
              </div>
            </div>
            
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="button" class="btn btn-primary" id="btn_submit_payment_status">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!----order payment confirm display Modal ends---->


@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 

<!----Add Custom Js ----start-----> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script type="text/javascript" >
     var t1; 
	$.fn.dataTable.ext.errMode = 'none';
	errorCount = 1;
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 2) {
            showSweetAlert('something went wrong', 'please refresh page and try again', 0);
        } else {
            t1.draw(false);
        }
        errorCount++;
    });


     $(document).ready(function () {
		 
		  t1 = $('#t1').DataTable({
            processing: true,
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
                    testonly : 0,
                },
                url: "{{ route('get-order-list-undelivered') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                 'aTargets': [-1,-2,-3,0] 
            }],
        });
		$('#t1 .searchnow th').each(function(colIdx) {
            var abc = $("#t1").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 100px;" />');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t1.column(colIdx).search() !== this.value) {
                    t1.column(colIdx).search(this.value).draw();
                }
            });
        });
		
		
     });
				
				
			
				
    /*------assign driver----------*/
    $("#assign_form").submit(function(event) {
        event.preventDefault();
        amount = $("#driver_assign_type_amount").val();
        $('#assignModal').modal('hide');
        var formData = new FormData($("#assign_form")[0]);
        $.ajax({
            url: "{{ route('assign-order-to-driver') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#assignModal').modal('hide');
				/*----gotopage---------*/
				if(data.success==1){
					swal({
								title: 'Are You Sure ?',
								text: "Want to Go on Driver Assign Page ?",
								type: 'warning',
								showCancelButton: true,
								confirmButtonText: 'Yes',
								padding: '-2em'
								}).then(function(result) {
						window.location.href = "{{ route('view-driver') }}/"+data.data;
					});
					t1.draw();
				}
				else
				{
					showAlert(data);
				}
				/*----gotopage---------*/
            },
            error: function() {
                showSweetAlert('Something Went Wrong.', 'please refresh page and try again', 0);
            }
        });
        return false;
    });

   $(document).ready(function() {
        /*--------------*/
        $('body').on('click', '.assignit', function() {
            $('#driver_id').val(null).trigger('change');
            $('#assign_form')[0].reset();
            var dvalue = $(this).data('dvalue');
            var ovalue = $(this).data('ovalue');
            $("#ovalueid").val(ovalue);
            $("#dvalueid").val(dvalue);
            var OrderDateid = $(this).data('orderdateid');
            var BigdaddyLRNumberid = $(this).data('bigdaddylrnumberid');
            $("#BigdaddyLRNumberid").html(BigdaddyLRNumberid);
            $("#bigdaddylrnumberhid").val(BigdaddyLRNumberid);
            $("#OrderDateid").html(OrderDateid);


            $("#driver_assign_type_amount").val(0);
            var val = $("#driver_assign_type").val();
            if (val == "PRL") {
                $("#driver_assign_type_amount_rowid").hide();
            } else {
                $("#driver_assign_type_amount_rowid").show();
            }
        });

        /*--------------*/
    });



 $(document).ready(function(){
	 var dvalueid = $("#dvalueid").val();
  $("#driver_id").select2({
    placeholder: "Search Driver by Name, Mobile or Email !!",
    width:"100%",
                ajax: {
					url: "{{ route('search_driver_and_select') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'driver_id': dvalueid, 
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
        });


 $("#selUser").change(function() {
    var driver_id = $("#driver_id").val(); 
	$("#dvalueid").val(driver_id);
});
	
$('body').on('click', '.deliverit', function () {
		var bigdaddylrnumberid = $(this).data('bigdaddylrnumberid');
		var oid = $(this).data('oid');
		var orderdateid = $(this).data('orderdateid');
		$('#payment_status1').prop("checked", true);

  swal({
      title: 'Are You Sure ?',
      text: "Do You Want to Change This Order Status to Delivered",
	   imageUrl: "{{ asset('admin_assets/assets/etc/deliverorder.png') }}",
    imageWidth: 400,
    imageHeight: 200,
    imageAlt: 'cancel order  image',
      //type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '-2em'
    }).then(function(result) {
      if (result.value) {
		  
		  $("#pModal").modal("show");
		  
		  $('body').on('click', '#btn_submit_payment_status', function () {
		  $.ajax({
            url: "{{ route('change-order-status-delivered') }}",
            data: {
              _token : '{{ csrf_token() }}',
              oid: oid, 
			  payment_status: $('input[name=payment_status]:checked').val(),
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             t1.draw(); 
						 swal(
					  'Delivered!',
					  'This Order Status Changed to Delivered.',
					  'success'
					);
					$("#pModal").modal("hide");
            },  error: function(){ showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  $("#pModal").modal("hide"); }
        });
		
		 });

      }
    });
});
			
$('body').on('click', '.cancelit', function () {
		var bigdaddylrnumberid = $(this).data('bigdaddylrnumberid');
		var oid = $(this).data('oid');
		var orderdateid = $(this).data('orderdateid');

  swal({
      title: 'Are You Sure ?',
      text: "Do You Want to Cancel This Order ?",
	  imageUrl: "{{ asset('admin_assets/assets/etc/cancelorder.png') }}",
    imageWidth: 400,
    imageHeight: 200,
    imageAlt: 'cancel order  image',
     // type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '-2em'
    }).then(function(result) {
      if (result.value) {
		  
		  $.ajax({
            url: "{{ route('change-order-status-cancelled') }}",
            data: {
              _token : '{{ csrf_token() }}',
              oid: oid, 
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             t1.draw(); 
						 swal(
					  'Cancelled!',
					  'This Order Has Been Cancelled.',
					  'success'
					);
            },  error: function(){   showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });

      }
    });
});

/*------assign driver----------*/	



     </script>
     <script type="text/javascript" >
     function selectAll(source) {
            
            checkboxes = document.getElementsByName('selectlist[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked; }
                if($("#selectAllid").prop('checked') == true){
                var ck = [];
                        $.each($("input[name='selectlist[]']:checked"),function(){ ck.push($(this).data('id'));
                				$('#ALL').val(ck);    
                        });
                 }
                else {   $('#ALL').val('');  }
                                                
          } 
		  
		  function assignMultiple() {
        $('#driver_id').val(null).trigger('change');
        $("#BigdaddyLRNumberid").html('');
        $("#OrderDateid").html('');

        var ck = [];
        $.each($("input[name='selectlist[]']:checked"), function() {
            ck.push($(this).data('id'));
        });
        len = ck.length;

        if (len == 0) {
            alert("none selected");
            return false;
        }
        var v = ck.toString();
        $("#assignModal").modal('show');
        $("#ovalueid").val(v);
            $("#driver_assign_type_amount").val(0);
            var val = $("#driver_assign_type").val();
            if (val == "PRL") {
                $("#driver_assign_type_amount_rowid").hide();
            } else {
                $("#driver_assign_type_amount_rowid").show();
            }
    }
		
	   $('body').on('change', '#driver_assign_type', function() {
        var val = $(this).val();
        $("#driver_assign_type_amount").val(0);
        if (val == "PRL") {
            $("#driver_assign_type_amount_rowid").hide();
        } else {
            $("#driver_assign_type_amount_rowid").show();
        }
    });
	
	
             </script>
<!----Add Custom Js --end-------> 
    @include('admin.order.order_logs')
</body>
</html>