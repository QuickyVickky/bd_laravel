<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
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
        <div class="col-lg-12 col-12 layout-spacing"> @if(!$errors->isEmpty())
          @foreach ($errors->all(':message') as $input_error)
          <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button>
          </div>
          @endforeach 
          @endif
          @if(Session::get("msg")!='')
          <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
            {{ Session::get("msg") }} </div>
          @endif
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>{{ @$control }}</h4>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6 col-6"> <a class="btn btn-primary  m-2 btn-rounded float-right"   onclick="show_add_newaccountModal()"> Add A New Account</a> </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <div class="table-responsive mb-4 mt-4">
                <table id="t1" class="table table-hover" style="width:100%">
                  <thead>
                    <tr>
                      <th>Names</th>
                      <th>Description</th>
                      <th>Category</th>
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
	/*--------------*/
   t1 = $('#t1').DataTable({
    processing: true,
	language: {
		processing: processingHTML_Datatable,
  	},
	serverSide: true,
 	paging: true,
	 searching: true,
     lengthMenu: [[10, 50, 100,500,1000], [10, 50, 100,500,1000]],
	pageLength: 10, 
	order: [[ 0, "desc" ]],
    ajax:  { 
    data: {"testonly": 1 },
    url: "{{ route('get-accountsnbanks-list') }}" ,
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
	

				



</script> 
<!----Add Custom Js --end-------> 

@include('admin.layout.snippets.add_newaccount')
</body>
</html>