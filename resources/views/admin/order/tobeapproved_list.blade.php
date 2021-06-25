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
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></button>
                    {{ Session::get("msg") }}</div>
                  @endif </div>
                  
              </div>
            </div>
            
            
            <div class="col-lg-12 col-12 layout-spacing">
          <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-id1" role="tab" aria-controls="home" aria-selected="true">{{ $control1 }}</a> </li>
            <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#tab-id2" role="tab" aria-controls="contact" aria-selected="false">{{ $control2 }}</a> </li>
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade show active" id="tab-id1" role="tabpanel" aria-labelledby="home-tab">
              <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="8%">Status</th>
                    <th width="15%"> Customer Details</th>
                    <th width="20%">PickUp Location</th>
                    <th width="20%">Drop Location</th>
                    <th width="10%">Date</th>
                    <th width="20%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th width="8%">Status</th>
                    <th width="15%"> Customer Details</th>
                    <th width="20%">PickUp Location</th>
                    <th width="20%">Drop Location</th>
                    <th width="10%">Date</th>
                    <th width="20%">Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="tab-pane fade" id="tab-id2" role="tabpanel" aria-labelledby="contact-tab">
              <table id="t2" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="8%">Status</th>
                    <th width="15%"> Customer Details</th>
                    <th width="20%">PickUp Location</th>
                    <th width="20%">Drop Location</th>
                    <th>Charges</th>
                    <th width="10%">Date</th>
                    <th width="25%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th width="8%">Status</th>
                    <th width="15%"> Customer Details</th>
                    <th width="20%">PickUp Location</th>
                    <th width="20%">Drop Location</th>
                    <th>Charges</th>
                    <th width="10%">Date</th>
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
    </div>
    @include('admin.layout.footer') </div>
  <!--  END CONTENT AREA  --> 
</div>
<!-- END MAIN CONTAINER --> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 

<!----Add Custom Js ----start-----> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script type="text/javascript" >

     var t1;
	 var t2;

	$.fn.dataTable.ext.errMode = 'none';errorCount = 1;
	$('#t1,#t2').on('error.dt', function(e, settings, techNote, message) {
   		if(errorCount>2){
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
		}
		else
		{
			t1.draw(false);
			t2.draw(false);
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
						lengthMenu : [[10, 50, 100, 500], [10, 50, 100, 500]],
						pageLength: 50,
						order: [[0, 'desc']],
						ajax: "{{ route('get-order-list-requested_orders') }}",
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1,0],
                        }],
            });
			
			t2 = $('#t2').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
						serverSide: true,
						lengthMenu : [[10, 50, 100, 500], [10, 50, 100, 500]],
						pageLength: 50,
						order: [[0, 'desc']],
						ajax: "{{ route('get-order-list-approved_orders') }}",
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1,0],
                        }],
            });
			
     });
				
				



$('body').on('click', '.confirmit', function () {
		var id = $(this).data('id');
		
  	swal({
      title: 'Are You Sure ?',
      text: "Confirm This Order & Move to New Orders",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '-2em'
    }).then(function(result) {
      if (result.value) {
		  
		  $.ajax({
            url: "{{ route('change-order-status-toneworder') }}",
            data: {
              _token : '{{ csrf_token() }}',
              oid: id, 
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             t1.draw(); 
						 swal(
					  'Done!',
					  'This Order Confirmed Successfully',
					  'success'
					);
            },  error: function(){ showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });

      }
    });
});


 </script>
     
<!----Add Custom Js --end-------> 
@include('admin.order.order_logs') 
</body>
</html>