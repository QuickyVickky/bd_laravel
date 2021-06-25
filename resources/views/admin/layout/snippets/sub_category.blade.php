<!-- Button trigger modal -->
<!--button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sub_categoryModal"> Launch Category modal</button-->

<!-- Category Modal Starts -->

<div class="modal fade" id="sub_categoryModal" tabindex="-1" role="dialog" aria-labelledby="sub_categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sub_categoryModalLabel">Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form id="sub_categoryformid">
          @csrf
          <input type="hidden" name="hidsubcategoryid" id="hidsubcategoryid" value="0" required="required">
          <input type="hidden" name="transaction_typeid" id="transaction_typeid" value="" required="required">
          <input type="hidden" name="addnew_sub_category_is_active_id" id="addnew_sub_category_is_active_id" value="0" required="required">
          <input type="hidden" id="addnew_sub_category_typehid" name="addnew_sub_category_typehid" value="">
          <div class="form-group">
            <label>Name * </label>
            <input type="text" name="addnew_sub_category_name_id" class="form-control" id="addnew_sub_category_name_id" placeholder="Enter Category Name" value="" required="required">
          </div>
          <div class="form-group">
            <label>Additional Name (optional)</label>
            <input type="text" name="addnew_sub_category_name2_id" class="form-control" id="addnew_sub_category_name2_id" placeholder="Additional Name" value="">
          </div>
          <div class="form-group">
            <label>Description (optional)</label>
            <textarea type="text" rows="2" name="addnew_sub_category_description" class="form-control" id="addnew_sub_category_description" placeholder="Description"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="sub_categoryformsaveid">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Category Modal Ends --> 
<!----Customer Add Snippet------starts------here------> 
<script type="text/javascript" >
$('body').on('click', '#sub_categoryformsaveid', function () {
        var id = $('#hidsubcategoryid').val();
		var transaction_typeid = $('#transaction_typeid').val();
		var addnew_sub_category_name_id = $('#addnew_sub_category_name_id').val();
		var addnew_sub_category_is_active_id = $('#addnew_sub_category_is_active_id').val();
		var addnew_sub_category_name2_id = $('#addnew_sub_category_name2_id').val();
		var addnew_sub_category_description = $('textarea#addnew_sub_category_description').val();
		var addnew_sub_category_typehid =  $("#addnew_sub_category_typehid").val();
		var is_editable = "{{ constants('is_editable_yes') }}";

        if(id=='' || addnew_sub_category_name_id=='' || transaction_typeid=='' || addnew_sub_category_is_active_id=='' || addnew_sub_category_typehid==''){
			showSweetAlert('Error','Please Check All Required Fields.', 0); 
          	return false;
        }

        $("#sub_categoryModal").modal('hide');
		
     $.ajax({
            url: "{{ route('add-update-transaction-subcategory') }}",
            data: {
              _token:'{{ csrf_token() }}',
              hid: id, 
              name: addnew_sub_category_name_id,
			  transaction_type: transaction_typeid,
			  name2: addnew_sub_category_name2_id,
			  is_active: addnew_sub_category_is_active_id,
			  details:addnew_sub_category_description,
			  is_editable: is_editable,
			  typehid: addnew_sub_category_typehid
            },
            type: 'post',
            dataType: 'json',
            success: function (e) {
				showAlert(e);
				if(typeof t1 !== "undefined"){ t1.draw(); } 
            },
			error: function(jqXHR, textStatus, errorThrown) {
                showSweetAlert('Something Went Wrong!','An error occurred, Please Refresh Page and Try again.', 0); 
            },
        });
  });



  function show_add_newsubcategoryModal(idx=0){
	  var addnew_sub_category_typehid =  $("#addnew_sub_category_typehid").val();
		
	  if(idx==0){
		  $("#sub_categoryModalLabel").html('Add '+ addnew_sub_category_typehid+ ' Category');
		  $('#hidsubcategoryid').val(0);
		  $("#sub_categoryModal").modal('show');
		}
		else if(idx>0){
		$('#sub_categoryModalLabel').html('Edit '+ addnew_sub_category_typehid+ ' Category');
		$('#hidsubcategoryid').val(idx);

     $.ajax({
            url: "{{ route('edit-transaction-subcategory-data') }}",
            data: {
              _token:'{{ csrf_token() }}',
              id: idx
            },
            type: 'get',
            dataType: 'json',
            success: function (data) {
				if (typeof data !== 'undefined' && data != null) {
				$("#sub_categoryModal").modal('show');
				$('#hidsubcategoryid').val(data.id);
				$('#transaction_typeid').val(data.transaction_type);
				$('#addnew_sub_category_name_id').val(data.name);
				$('#addnew_sub_category_is_active_id').val(data.is_active);
				$('#addnew_sub_category_name2_id').val(data.name2);
				$('#addnew_sub_category_description').val(data.details);
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