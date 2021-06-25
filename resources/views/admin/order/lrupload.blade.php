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
          <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-id1" role="tab" aria-controls="home" aria-selected="true">{{ $control }}</a> </li>
            <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#tab-id2" role="tab" aria-controls="contact" aria-selected="false">{{ $control }} Completed</a> </li>
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade show active" id="tab-id1" role="tabpanel" aria-labelledby="home-tab">
              <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Details</th>
                    <th>File</th>
                    <th> Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="tab-id2" role="tabpanel" aria-labelledby="contact-tab">
              <table id="t2" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Details</th>
                    <th>File</th>
                    <th> Action</th>
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
    @include('admin.layout.footer') </div>
  
  
  <!--  END CONTENT AREA  --> 
</div>
<!-- END MAIN CONTAINER --> 



@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<!----Add Custom Js ----start-----> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script type="text/javascript" >
     var t1; var t2; 
	$.fn.dataTable.ext.errMode = 'none';
	$('#t1').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
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
	 searchDelay: 999,
     lengthMenu: [[10, 50, 100,500], [10, 50, 100,500]],
	pageLength: 50, 
	order: [[ 0, "desc" ]],
    ajax:  { 
    data: {"status": 0 },
    url: "{{ route('get-lr-upload-list') }}" ,
	type: "get" 
	},
    aoColumnDefs: 
	[{ 
		'bSortable': false,
		 'aTargets': [-1,-2] 
	}],
   }); 
  /*--------------*/
  t2 = $('#t2').DataTable({
    processing: true,
	language: {
		processing: processingHTML_Datatable,
  	},
	serverSide: true,
 	paging: true,
	 searching: true,
     lengthMenu: [[10, 50, 100,500], [10, 50, 100,500]],
	pageLength: 50, 
	order: [[ 0, "desc" ]],
    ajax:  { 
    data: {"status": 1 },
    url: "{{ route('get-lr-upload-list') }}" ,
	type: "get" 
	},
    aoColumnDefs: 
	[{ 
		'bSortable': false,
		 'aTargets': [-1] 
	}],
   }); 
   /*--------------*/
     });


$('body').on('click', '.reviewit', function () {
		var id = $(this).data('id');
		var val = $(this).data('val');

		 $.ajax({
            url: "{{ route('change-lruploads-completed') }}",
            data: {
              _token : '{{ csrf_token() }}',
              id: id,
			  status: val, 
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             t1.draw(); 
			  toast({
				type: 'success',
				title: data.msg,
				padding: '2em',
			  })
            },  error: function(){ showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });

 
});

     </script> 
<!----Add Custom Js --end-------> 

@include('admin.layout.crudhelper')
</body></html>