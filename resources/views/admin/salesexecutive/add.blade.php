<!-- Button trigger modal -->
<!--button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_newsalesexecutiveModal"> Launch Add- modal</button-->

<!-- Add-Account Modal Starts -->

<div class="modal fade" id="add_newsalesexecutiveModal" tabindex="-1" role="dialog" aria-labelledby="add_newsalesexecutiveModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add_newsalesexecutiveModalLabel">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form id="add_newsalesexecutiveformid">
          @csrf
          <input type="hidden" name="hidaddnewasalesexecutiveid" id="hidaddnewasalesexecutiveid" value="0" >
          <input type="hidden" id="add_newsalesexecutiveis_active" name="add_newsalesexecutiveis_active" value="0">
          <input type="hidden" id="add_newsalesexecutivetypehid" name="add_newsalesexecutivetypehid" value="Sales Executive" >
          <div class="form-group row">
            <label class="col-form-label col-md-4">Full Name *</label>
            <div class="col-md-8">
              <input type="text" class="form-control" maxlength="150" name="add_newsalesexecutivefullname" id="add_newsalesexecutivefullname" placeholder="Full Name" value="" required>
            </div>
          </div>
          <!--div class="form-group row">
            <label class="col-form-label col-md-4">Email</label>
            <div class="col-md-8">
              <input type="email" class="form-control" maxlength="150" name="add_newsalesexecutiveemail" id="add_newsalesexecutiveemail" placeholder="Email (optional)" value="">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-4">Mobile</label>
            <div class="col-md-8">
              <input type="text" class="form-control allownumber" placeholder="Mobile (optional)" name="add_newsalesexecutivemobile" id="add_newsalesexecutivemobile" value="" maxlength="10" minlength="10">
            </div>
          </div>-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="add_newsalesexecutiveformsaveid">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Add-Account Modal Ends --> 
<!----Customer Add Snippet------starts------here------> 
<script type="text/javascript" >
$('body').on('click', '#add_newsalesexecutiveformsaveid', function () {
        var id = $('#hidaddnewasalesexecutiveid').val();
		var add_newsalesexecutivefullname = $('#add_newsalesexecutivefullname').val();
		var add_newsalesexecutiveis_active = $('#add_newsalesexecutiveis_active').val();
		var add_newsalesexecutivetypehid = $('#add_newsalesexecutivetypehid').val();
		
        if(id=='' || add_newsalesexecutivefullname=='' || add_newsalesexecutiveis_active=='' || add_newsalesexecutivetypehid=='' ){
		showSweetAlert('Error','Please Check All Required Fields.', 0); 
          return false;
        }

        $("#add_newsalesexecutiveModal").modal('hide');
		
     $.ajax({
            url: "{{ route('add-update-salesexecutive') }}",
            data: {
              _token:'{{ csrf_token() }}',
              hid: id, 
              fullname: add_newsalesexecutivefullname,
			  is_active : add_newsalesexecutiveis_active,
			  typehid : add_newsalesexecutivetypehid,
            },
            type: 'post',
            dataType: 'json',
            success: function (e) {
				if(e.success==1){
					showSweetAlert("Completed",e.msg, 1); 
              		if(typeof t1 !== "undefined"){ t1.draw(false); } 
				}
				else
				{
					showSweetAlert("Error",e.msg, 0); 
              		if(typeof t1 !== "undefined"){ t1.draw(false); } 
				}
            },
			error: function(jqXHR, textStatus, errorThrown) {
                showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
  });
  
  
  function show_add_newsalesexecutiveModal(idx=0){
	  $('#add_newsalesexecutiveformid')[0].reset();
	  $('#add_newsalesexecutivetypehid').val('Sales Executive');
	  
	  if(idx==0){
		  $("#add_newsalesexecutiveModal").modal('show');
		  $('#hidaddnewasalesexecutiveid').val(0);
		}
		else if(idx>0){
		$('#add_newsalesexecutiveModalLabel').html('Edit');
		$('#hidaddnewasalesexecutiveid').val(idx);

     $.ajax({
            url: "{{ route('salesexecutive-getedit') }}",
            data: {
              _token:'{{ csrf_token() }}',
              id: idx
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
				if (typeof data !== 'undefined' && data != null) {
				$("#add_newsalesexecutiveModal").modal('show');
				$('#hidaddnewasalesexecutiveid').val(data.id);
				$('#add_newsalesexecutivefullname').val(data.fullname);
				$('#add_newsalesexecutiveis_active').val(data.is_active);
				}
            },
			error: function(jqXHR, textStatus, errorThrown) {
               showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
		
		}
  }







</script> 
<!----Customer Add Snippet------ends------here------>