<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/plugins/select2/select2.min.css')}}">
</head>
<body data-spy="scroll" data-target="#navSection" data-offset="100">
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
                  <h4>{{ @$control }}</h4>
                  @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></button>
                    {{ Session::get("msg") }}</div>
                  @endif </div>
                <div class="col-xl-6 col-md-6 col-sm-6 col-6"> <a href="{{ route('subscription-add') }}" class="btn btn-info m-2 btn-rounded float-right"> Add A New Subscription</a> </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
              <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Title</th>
                     <th>Details</th>
                     <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
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

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<!----Add Custom Js ----start-----> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/plugins/select2/select2.min.js')}}"></script>
<script type="text/javascript" >
    var t1;
    $.fn.dataTable.ext.errMode = 'none';
    errorCount = 1;
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 3) {
            showSweetAlert('something went wrong', 'please refresh page and try again', 0);
        } else {
            t1.draw(false);
        }
        errorCount++;
    });

	$(document).ready(function () {
		/*--------------*/
	   t1 = $('#t1').DataTable({
		processing: true,
		language: {
			processing: processingHTML_Datatable,
		},
		serverSide: true,
		paging: true,
		 searching: true,
		 lengthMenu: [[10, 25, 50, 100,500], [10, 25, 50, 100,500]],
		pageLength: 25, 
		order: [[ 0, "desc" ]],
		ajax:  { 
		data: {"testonly": 1 },
		url: "{{ route('get-subscription-list') }}" ,
		type: "get" 
		},
		aoColumnDefs: 
		[{ 
			'bSortable': false,
			 'aTargets': [-1,-2] 
		}],
	   }); 
	  /*--------------*/
	});
	

$('body').on('click', '.editit', function () {
	var id = $(this).data('id');
});				

    $('body').on('click', '.change_status_confirm', function() {
        var id = $(this).data('id');
        var status = $(this).data('val');

        if (status == 1) {
            texttext = "Do You Want to DeActivate This Subscription ?";
        } else if (status == 0) {
            texttext = "Do You Want to Activate This Subscription ?";
        } else {
            return false;
        }


        swal({
            title: 'Are You Sure ?',
            text: texttext,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            padding: '-2em'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('change-subscription-status') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        t1.draw();
                        showAlert(data);
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });
            }
        });
    });


</script> 
<!----Add Custom Js --end-------> 
</body>
</html>