<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />


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
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>{{ @$control }}</h4>
                                  </div>
                <div class="col-xl-6 col-md-6 col-sm-6 col-6"> 
                  <a class="btn btn-primary  m-2 btn-rounded float-right" href="{{ route('account-add') }}" ><i class="fas fa-money"></i> Add Expense</a> 
                </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
              <table id="t1" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                            <th >Amount</th>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Bill No</th>
                                            <th width="20%">Details</th>
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
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Bill No</th>
                                            <th width="20%">Details</th>
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

<!----Add Custom Js ----start----->
    <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
   <script type="text/javascript" >

	 var tbl = '{{ $tbl }}';
	 var filepath = "{{ sendPath().constants('dir_name.bill').'/' }}";
	 var isdelete = 'N';
     var dataTable;
	 $.fn.dataTable.ext.errMode = 'none';
	$('#t1').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});


                $(document).ready(function () {
					/*$('#t1 tfoot th').each( function () {
						if ($(this).data('search')==1){
            					var title = $(this).text();
        					$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
						}
    				});*/
	
                    dataTable = $('#t1').DataTable({
						//initComplete: function(){this.api().columns().every(function(){var that = this;$('input', this.footer() ).on( 'keyup change clear', function () {if ( that.search() !== this.value ) {that.search( this.value).draw();}});}); },
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
                        stateSave: true,
                        order: [[1, 'desc']],
                        serverSide: true,
                        ajax: "{{ route('get-account-list') }}?datefrom=12",
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1,0],
                        }]
                    });
					
                });
				
				
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
		var id = $(this).data('id');
		var anybillno_document = $(this).data('anybillno_document');

  swal({
      title: 'Are You Sure ?',
      text: "Do You Want to Delete This ?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '-2em'
    }).then(function(result) {
      if (result.value) {
		  
		  $.ajax({
            url: "{{ route('delete-account-expenses') }}",
            data: {
              _token : '{{ csrf_token() }}',
              id: id, 
			  existing_img : anybillno_document,
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             dataTable.draw(); 
						 swal(
					  'Deleted!',
					  'Deleted Successfully',
					  'success'
					);
            },  error: function(){  showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });

      }
    });
});

  
				


     </script>
<!----Add Custom Js --end------->



@include('admin.layout.crudhelper') 
</body>
</html>