<!DOCTYPE html>
<html lang="en">
<head>
<style>
td span:first-child {
	margin-bottom: 5px;
}
</style>

@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
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
                  <h4>{{ $control }}</h4>
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
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
              <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Details</th>
                    <th width="50%">Message</th>
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
    </div>
    @include('admin.layout.footer') </div>
  <!--  END CONTENT AREA  --> 
</div>
<!-- END MAIN CONTAINER --> 

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
<script type="text/javascript" >
	 var tbl = '{{ $tbl }}';
	 var isdelete = 'N';
     var t1; var t2; 
	$.fn.dataTable.ext.errMode = 'none';
	$('#t1').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});

	 $(document).ready(function () {
                    t1 = $('#t1').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
                        stateSave: true,
						lengthMenu : [[10, 50, 100, 500], [10, 50, 100, 500]],
						pageLength: 50,
                        order: [[0, 'desc']],
                        serverSide: true,
                        ajax: "{{ route('get-inquiry-data') }}",
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1],
                        }]
                    });
     });


$('body').on('click', '.reviewit', function () {
		var id = $(this).data('id');
		var val = $(this).data('val');

		 $.ajax({
            url: "{{ route('change-inquiry-completed') }}",
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