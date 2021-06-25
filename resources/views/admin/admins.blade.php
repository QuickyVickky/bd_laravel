<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
<link href="{{ asset('admin_assets/assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin_assets/assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
<!-- end plugin css -->
<link href="{{ asset('admin_assets/assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<!-- common css -->
<link href="{{ asset('admin_assets/css/app.css') }}" rel="stylesheet" />
<!-- end common css -->
</head><body>
<div class="main-wrapper" id="app"> @include('admin.layout.sidebar')
  <div class="page-wrapper"> @include('admin.layout.header')
    <div class="page-content">
      <nav class="page-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Admin {{ Session::get("msg") }}</a></li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Admin
                <button type="button" class="btn btn-primary btn-xs pull-right" id="addbtn">Add</button>
              </h6>
              <div class="table-responsive">
                <table id="t1" class="table dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                  <thead>
                    <tr role="row">
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
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
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <form method="post" action="{{ route('add-update-admins') }}" enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="modal-body">
          <input type="hidden" id="hid" name="hid" value="0">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">FullName:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value=""  minlength="2" required>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value=""   required>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Mobile:</label>
            <input type="text" class="form-control" id="mobile" name="mobile" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;" minlength="10" minlength="15"  required>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" value="" minlength="6"  minlength="20"  required>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <label class="form-check-label">
                <input type="radio" checked class="form-check-input" name="status" id="r0" value="0">
                Active <i class="input-frame"></i></label>
            </div>
            <div class="form-check form-check-inline">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="status" id="r1" value="1">
                DeActive <i class="input-frame"></i></label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn_submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- base js --> 
<script src="{{ asset('admin_assets/js/app.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/plugins/feather-icons/feather.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/plugins/datatables-net/jquery.dataTables.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/template.js') }}"></script> 
<!-- end common js --> 

<script type="text/javascript" >

var tbl = '{{ $tbl }}';
     var dataTable;

                $(document).ready(function () {
                    dataTable = $('#t1').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
                        stateSave: true,
                        order: [[1, 'desc']],
                        serverSide: true,
                        ajax: "{{ route('get_admins_data') }}",
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1,-2],
                        }]
                    });
                });

$(document).ready(function(){
  $('body').on('click', '.change_status', function () {
     var id = $(this).data('id');
     var val = $(this).data('val');

     $.ajax({
            url: "{{ route('change_status') }}",
            data: {
              "_token" : '{{ csrf_field() }}',
              'id': id, 
              'value': val,
              'tbl' : tbl
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
              dataTable.draw(false);
            }
        });

  });

   $('body').on('click', '#addbtn', function () {
	   $("#myForm").trigger("reset");
      $('#hid').val(0);
      $('#exampleModalLabel').html('Add');
      $("#exampleModal").modal('show');
    });

   $('body').on('click', '.editit', function () {
     var id = $(this).data('id');
     $('#hid').val(id);
	 
	 var x = confirm("you can update only admin fullname and You will have to change password also.");
	 if(x==false){
		 window.location.reload(); return false;
	 }

     $.ajax({
            url: "{{ route('edit-admins-data') }}",
            data: {
              _token:'{{ csrf_token() }}',
              id: id
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
				$('#hid').val(data[0].id);
              $('#fullname').val(data[0].fullname);
			  $('#email').val(data[0].email);
			  $('#mobile').val(data[0].mobile);
			  $('#password').val('');
			  $("input[name=status][value=" + data[0].status + "]").prop('checked', true);
              $('#exampleModalLabel').html('Edit');
              $("#exampleModal").modal('show');
            }
        });
  });


});

 </script> 
 
 </body>
 </html>
