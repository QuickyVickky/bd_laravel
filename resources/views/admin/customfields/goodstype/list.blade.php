<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('admin_assets/plugins/font-icons/fontawesome/css/regular.css')}}">
<link rel="stylesheet" href="{{ asset('admin_assets/plugins/font-icons/fontawesome/css/fontawesome.css')}}">
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
          <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#goodstype" role="tab" aria-controls="home" aria-selected="true">{{ $control }}</a> </li>
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade show active" id="goodstype" role="tabpanel" aria-labelledby="home-tab">
              <button class="btn btn-info mb-4 mr-2 btn-sm" type="button" id="addidbtn">Add {{ $control }}</button>
              <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="8%">#</th>
                    <th width="20%">Name</th>
                    <th width="20%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th width="8%">#</th>
                    <th width="20%">Name</th>
                    <th width="20%">Action</th>
                  </tr>
                </tfoot>
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

<!----category modal start---->
<div class="modal fade fadeInUp" id="addModal" tabindex="-1" role="dialog" aria-labelledby="x" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="x">Add</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <form id="main_categoryform">
        @csrf
        <div class="modal-body">
          <div class="row">
            <input type="hidden" name="hid" id="hid" value="0" >
            <div class="col-sm-12">
              <div class="form-group required">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Goods Type Name" value="" required>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group required">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="status0" name="status" class="custom-control-input " value="0" checked>
                  <label class="custom-control-label" for="status0">Active</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="status1" name="status" class="custom-control-input " value="1" >
                  <label class="custom-control-label" for="status1">Deactive</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="button" class="btn btn-primary admin-button-add-vnew" id="btn_submit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!----category modal end----> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/jquery-ui.js')}}"></script>
<link rel="stylesheet" href="{{ asset('admin_assets/assets/css/jquery-ui.css')}}">

<!----Add Custom Js ----start-----> 
<script type="text/javascript" >

var isdelete = 'N';
var dataTable;  
$.fn.dataTable.ext.errMode = 'none';
	$('#t1').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});


	/*-------goods type---------*/
                    dataTable = $('#t1').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
						lengthMenu : [[10, 50, 100, 500], [10, 50, 100, 500]],
						pageLength: 10,
                        order: [[0, 'desc']],
                        serverSide: true,
                        ajax: {
						"url": "{{ route('get_goods_type_data') }}",
						"type": "get",
						"data":function(data) {
							data.onlytest =1;
						},
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1],
                        }]
						},
                    });
/*-------goods type---------*/
	
	

 </script> 
<script type="text/javascript" >	
$('body').on('click', '#addidbtn', function () {
	 	$('#name').val('');
		$('#x').html('Add');
		$('#hid').val(0);
		$('#status0').prop("checked", true);
        $("#addModal").modal('show');
});

  $('body').on('click', '#btn_submit', function () {
        var id = $('#hid').val();
		var name = $('#name').val();
		var status = $('input[name=status]:checked').val();

        if(id=='' || name=='' || status==''){
		showSweetAlert('Error','Please Check All Required Fields.', 0); 
          return false;
        }
		$("#addModal").modal('hide');
		
     $.ajax({
            url: "{{ route('add-update-goodstype') }}",
            data: {
              _token:'{{ csrf_token() }}',
              hid: id, 
              name: name,
			  status: status,
            },
            type: 'post',
            dataType: 'json',
            success: function (e) {
				showSweetAlert("Completed",e.msg, 1); 
              	dataTable.draw(false);
            },
			error: function(jqXHR, textStatus, errorThrown) {
                showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
  });
  
  
  $('body').on('click', '.edititmain', function () {
     	var id = $(this).data('id');
		$('#x').html('Edit');
		$('#hid').val(id);

     $.ajax({
            url: "{{ route('edit-goodstype-data') }}",
            data: {
              _token:'{{ csrf_token() }}',
              id: id
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
				if(data[0].is_active==1){ $('#status1').prop("checked", true); }
				else { $('#status0').prop("checked", true); }
              $('#name').val(data[0].name);
			  $('#hid').val(data[0].id);
              $("#addModal").modal('show');
            },
			error: function(jqXHR, textStatus, errorThrown) {
               showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
  });
 

</script> 
<!----Add Custom Js --end------->

</body>
</html>