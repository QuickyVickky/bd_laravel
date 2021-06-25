<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />
</head><body data-spy="scroll" data-target="#navSection" data-offset="100">
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
            
              <div class="row" style="align-items: center;">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>{{ $control }}</h4>
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
                
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <a class="btn btn-info m-2 btn-rounded float-right" href="{{ route('add-vehicle') }}"> Add {{ $control }}</a>
                  </div>
                
              </div>
            </div>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
              <table id="t1" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                            <th>Vehicle Number</th>
                                            <th>Driver </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                            <th>Vehicle Number</th>
                                            <th>Driver </th>
                                            <th>Action</th>
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
        <h6 class="modal-title" id="exampleModalLabel">Assign This Vehicle to Driver</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <div class="modal-body">
        <form id="assign_form">
          @csrf
          <div class="col-sm-12">
            <div class="form-group required">
              <select id="select_driver_id" name="select_driver_id" class="form-control">
              <option value="0" selected>Select</option>
              </select>
            </div>
          </div>
          <input type="hidden" name="vehicle_hidid" id="vehicle_hidid" value="0">
          <input type="hidden" name="dvalueid" id="dvalueid" value="0">
          <div class="row" >
            <div class="col-sm-6">
              <div class="form-group required">
                <label>Vehicle Number </label>
                <p id="VehicleNumberid"></p>
              </div>
            </div>
            
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="submit"  class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!----assign driver Modal end----> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script type="text/javascript" >

     var t1;
	 
	$.fn.dataTable.ext.errMode = 'none';
	$('#t1').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});
	
     $(document).ready(function () {
		 vehicleDatatable();
     });

		function vehicleDatatable(){
			
		testonly = 1;
						
	t1 = $('#t1').DataTable({
    processing: true,
	language: {
		processing: processingHTML_Datatable,
  	},
	serverSide: true,
	destroy: true,
 	paging: true,
	stateSave: true,
	 searching: true,
    lengthMenu: [[10, 50, 100,500], [10, 50, 100,500]],
	pageLength: 50, 
	order: [[ 1, "desc" ]],
    ajax:  { 
    data: { 
	testonly:testonly,
	},
    url: "{{ route('get-vehicle-list') }}" ,
	type: "get" 
	},
    aoColumnDefs: 
	[{ 
		'bSortable': false,
		 'aTargets': [-1,0] 
	}],
	createdRow: function( row, c, dataIndex ){ 
							if (c[4]==1 || c[5]==1){ 
									$(row).attr({ style:"background-color:#dd91a2" }); 
										} 
						},
   }); 
}


     </script>
     
<script type="text/javascript">
	 
	 
	/*--------------*/  
 	$('body').on('click', '.assignit', function () {
     var vehicle = $(this).data('vehicle');
	 var id = $(this).data('id');
	 $("#VehicleNumberid").html(vehicle);
	 $("#vehicle_hidid").val(id);
	 $("#assignModal").modal("show");
  });
		 
/*--------------*/  
    $(document).ready(function () {
  $("#select_driver_id").select2({
    placeholder: "Search Driver by Name, Mobile or Email !!",
    width:"100%",
                ajax: {
					url: "{{ route('get_driver_with_vehicle_and_select') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'driver_id': $("#dvalueid").val(), 
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


$("#select_driver_id").change(function() {
    var driver_id = $("#select_driver_id").val(); 
});
$("#assign_form").submit(function (event) { event.preventDefault();
        var formData = new FormData($("#assign_form")[0]); 
      $.ajax({
			url: "{{ route('assign-vehicle-to-driver') }}",
        	type: 'POST', 
			data: formData,
            contentType: false,
			processData: false,
         	success: function (data) { 
         			$('#assignModal').modal('hide');
         			t1.draw();
         	},  error: function(){   showSweetAlert('could not be assigned','please refresh page and try again', 0); }
            }); 
			return false; 
		});
		
	
	
	/*----------remove driver from vehicle----------------*/  
	$('body').on('click', '.removeassignit', function () {
	 var id = $(this).data('id');

	 $.ajax({
            url: "{{ route('remove-assigned-driver-from-vehicle') }}",
            data: {
              id : id,
			 _token:'{{ csrf_token() }}',
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
				t1.draw();
            },
			error: function(jqXHR, textStatus, errorThrown) {
               showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
	

  });	
/*----------remove driver from vehicle----------------*/  
	 </script>
<!----Add Custom Js --end------->



</body>
</html>