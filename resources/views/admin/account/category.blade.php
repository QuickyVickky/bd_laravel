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
          <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#MainCategoryid" role="tab" aria-controls="home" aria-selected="true">Category</a> </li>
            <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#SubCategoryid" role="tab" aria-controls="contact" aria-selected="false">Sub-Category</a> </li>
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade show active" id="MainCategoryid" role="tabpanel" aria-labelledby="home-tab">
              <button class="btn btn-info mb-4 mr-2 btn-sm" type="button" id="addcategoryidbtn">Add Category</button>
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
            <div class="tab-pane fade" id="SubCategoryid" role="tabpanel" aria-labelledby="contact-tab">
              <button class="btn btn-info mb-4 mr-2 btn-sm" type="button" id="addsubcategoryidbtn">Add Sub-Category</button>
              <table id="t2" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="8%">#</th>
                    <th width="20%">Name</th>
                    <th width="20%">Main-Category</th>
                    <th width="20%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th width="8%">#</th>
                    <th width="20%">Name</th>
                   <th width="20%">Main-Category</th>
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
<div class="modal fade fadeInUp" id="addcategoryModal" tabindex="-1" role="dialog" aria-labelledby="x" aria-hidden="true">
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
            <input type="hidden" name="hidmaincategoryid" id="hidmaincategoryid" value="0" >
            <div class="col-sm-12">
              <div class="form-group required">
                <label for="name">Main-Category</label>
                <input type="text" name="main_category_name" class="form-control" id="main_category_name" placeholder="Enter Main-Category Name" value="" required>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group required">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="main_category_status0" name="main_category_status" class="custom-control-input main_category_status" value="0" checked>
                  <label class="custom-control-label" for="main_category_status0">Active</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="main_category_status1" name="main_category_status" class="custom-control-input main_category_status" value="1" >
                  <label class="custom-control-label" for="main_category_status1">Deactive</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="button" class="btn btn-primary admin-button-add-vnew" id="btn_submit_main_category">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!----category modal end----> 

<!----sub--category modal start---->
<div class="modal fade fadeInUp" id="addsubcategoryModal" role="dialog" aria-labelledby="x1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="x1">Add</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <form id="submain_categoryform">
        @csrf
        <div class="modal-body">
          <div class="row">
            <input type="hidden" name="hidsubcategoryid" id="hidsubcategoryid" value="0" >
            <input type="hidden" name="sub_category_main_category_name_hid" id="sub_category_main_category_name_hid" value="0" >
            
            
            <div class="col-sm-12">
              <div class="form-group required">
                <label for="name">Select Main Category</label>
                <select class="form-control" name="select_main_category" id="select_main_category" required="">
                </select>
              </div>
            </div>
            
            <div class="col-sm-12">
              <div class="form-group required">
                <label for="name">Main-Category-Name</label>
                <input type="text" name="sub_category_main_category_name" class="form-control" id="sub_category_main_category_name"  value="" readonly>
              </div>
            </div>
            
            <div class="col-sm-12">
              <div class="form-group required">
                <label for="name">Sub-Category</label>
                <input type="text" name="sub_category_name" class="form-control" id="sub_category_name" placeholder="Enter Sub-Category Name" value="" required>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group required">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="sub_category_status0" name="sub_category_status" class="custom-control-input sub_category_status" value="0" checked>
                  <label class="custom-control-label" for="sub_category_status0">Active</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="sub_category_status1" name="sub_category_status" class="custom-control-input sub_category_status" value="1" >
                  <label class="custom-control-label" for="sub_category_status1">Deactive</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="button" class="btn btn-primary admin-button-add-vnew" id="btn_submit_sub_category">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!----sub--category modal start----> 

<!----order logs Modal start---->
<div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="logsexampleModalLabel">Order Logs</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <div class="modal-body">
        <table id="t2" class="table table-hover" style="width:100%">
          <thead>
            <tr>
              <th width="8%">#</th>
              <th>Logs</th>
              <th >DateTime</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!----order logs  Modal end----> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<!----Add Custom Js ----start-----> 
<script type="text/javascript" >
var dataTable;  var t2; 
$.fn.dataTable.ext.errMode = 'none';
	$('#t1,#t2').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});



	/*-------main-category---------*/
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
						"url": "{{ route('get-accountmaincategory-list') }}",
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
	/*-------main-category---------*/
	/*-------sub-category---------*/
                    t2 = $('#t2').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
						lengthMenu : [[10, 50, 100, 500], [10, 50, 100, 500]],
						pageLength: 50,
                        order: [[0, 'desc']],
                        serverSide: true,
                        ajax: {
						"url": "{{ route('get-accountsubcategory-list') }}",
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
	/*-------sub-category---------*/
	

 </script> 
<script type="text/javascript" >

$("#select_main_category").select2({
    		placeholder: "Search...",
    		width:"100%",
        ajax: {
				url: "{{ route('get-allmaincategory-data') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
        return {
            searchTerm: params.term ,
						_token:'{{ csrf_token() }}',
						'includeid': $('#sub_category_main_category_name_hid').val(), 
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



			
$('body').on('click', '#addcategoryidbtn', function () {
	 	$('#main_category_name').val('');
		$('#x').html('Add');
		$('#hidmaincategoryid').val(0);
		$('#main_category_status0').prop("checked", true);
    $("#addcategoryModal").modal('show');
});

$('body').on('click', '#addsubcategoryidbtn', function () {
	 	$('#sub_category_name').val('');
		$('#sub_category_main_category_name').val('');
		$('#x1').html('Add');
		$('#hidsubcategoryid').val(0);
		$('#sub_category_main_category_name_hid').val(0);
		$('#sub_category_status0').prop("checked", true);
    $("#addsubcategoryModal").modal('show');

});

$("#select_main_category").change(function() {
  var sub_category_main_category_name_hid = $("#select_main_category").val(); 
	$('#sub_category_main_category_name_hid').val(sub_category_main_category_name_hid);
	var selectedtext = $("#select_main_category option:selected").text();
	$('#sub_category_main_category_name').val(selectedtext);
});



$('body').on('click', '#btn_submit_sub_category', function () {
    var id = $('#hidsubcategoryid').val();
		var sub_category_name = $('#sub_category_name').val();
		var sub_category_status = $('input[name=sub_category_status]:checked').val();
		var sub_category_main_category_name_hid = $('#sub_category_main_category_name_hid').val();

        if(id=='' || sub_category_name=='' || sub_category_status=='' || sub_category_main_category_name_hid<1){
          showSweetAlert('Error','Please Check All Required Fields.', 0); 
          return false;
        }

        $("#addsubcategoryModal").modal('hide');
		
     $.ajax({
            url: "{{ route('add-update-subcategory') }}",
            data: {
              _token:'{{ csrf_token() }}',
              hid: id, 
              name: sub_category_name,
			  status: sub_category_status,
			  main_category_hid : sub_category_main_category_name_hid,
            },
            type: 'post',
            dataType: 'json',
            success: function (e) {
				showSweetAlert("Completed",e.msg, 1); 
              t2.draw(false);
              
            },
			error: function(jqXHR, textStatus, errorThrown) {
				showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
  });
  
  $('body').on('click', '#btn_submit_main_category', function () {
        var id = $('#hidmaincategoryid').val();
		var main_category_name = $('#main_category_name').val();
		var main_category_status = $('input[name=main_category_status]:checked').val();

        if(id=='' || main_category_name=='' || main_category_status==''){
		showSweetAlert('Error','Please Check All Required Fields.', 0); 
          return false;
        }

        $("#addcategoryModal").modal('hide');
		
     $.ajax({
            url: "{{ route('add-update-maincategory') }}",
            data: {
              _token:'{{ csrf_token() }}',
              hid: id, 
              name: main_category_name,
			  status: main_category_status,
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
		$('#hidmaincategoryid').val(id);

     $.ajax({
            url: "{{ route('edit-accountmaincategory-data') }}",
            data: {
              _token:'{{ csrf_token() }}',
              id: id
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
				if(data[0].is_active==1){ $('#main_category_status1').prop("checked", true); }
				else { $('#main_category_status0').prop("checked", true); }
              $('#main_category_name').val(data[0].name);
			  $('#hidmaincategoryid').val(data[0].id);
              $("#addcategoryModal").modal('show');
            },
			error: function(jqXHR, textStatus, errorThrown) {
               showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
  });
  
  $('body').on('click', '.edititsub', function () {
     	var id = $(this).data('id');
		$('#x1').html('Edit');
		$('#hidsubcategoryid').val(id);

     $.ajax({
            url: "{{ route('edit-accountsubcategory-data') }}",
            data: {
              _token:'{{ csrf_token() }}',
              id: id
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
				if(data[0].is_active==1){ $('#sub_category_status1').prop("checked", true); }
				else { $('#sub_category_status0').prop("checked", true); }
				$('#hidsubcategoryid').val(data[0].id);
				$('#sub_category_main_category_name').val(data[0].main_category_name);
				$('#sub_category_main_category_name_hid').val(data[0].path_to);
              	$('#sub_category_name').val(data[0].name);
              	$("#addsubcategoryModal").modal('show');
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