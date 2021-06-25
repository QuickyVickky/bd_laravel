<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/plugins/select2/select2.min.css')}}">
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
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>Add {{ $control }} </h4>
                  @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x close" data-dismiss="alert">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></button>
                    {{ Session::get("msg") }} </div>
                  @endif </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <form method="post" action="{{ route('subscription-purchase-add-manually') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hid" id="hid" value="0">
                <input type="hidden" name="created_at" id="created_at" value="0">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label>Select Customer (required) </label>
                      <select id="selUser" name="user_idx" class="form-control" required>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label>Select Subscription (required)</label>
                      <select id="subscription_id" name="subscription_id" class="form-control" required>
                      @foreach($dataSubscriptionList as $row)
                        <option value="{{ $row->id }}"> {{ $row->subscription_shortname }} | Rs {{ $row->subscription_value }} | {{ $row->subscription_validity_months }} Month(s)</option>
                       		@endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label>Notes (optional)</label>
                      <input type="text" name="notes" class="form-control" id="notes" placeholder="Any notes" value="" maxlength="250">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="confirmCheckit" id="confirmCheckit" value="1" required>
                        <label class="custom-control-label" for="confirmCheckit">Check This to Confirm Add Subscription Purchase Manually (required)</label>
                      </div>
                    </div>
                  </div>
                </div>
                <hr style="width:100%">
                <button type="submit" style="display:none;" class="btn btn-info float-right admin-button-add-vnew " id="submitbtnid">Submit & Add</button>
              </form>
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
<!----Add Custom Js ----start-----> 
<script src="{{ asset('admin_assets/plugins/select2/select2.min.js')}}"></script> 
<script type="text/javascript">


$(document).ready(function(){

  $("#selUser").select2({
    placeholder: "Search Customer by Name, Mobile or Email !!",
    width:"100%",
                ajax: {
					url: "{{ route('search-transporter-and-select') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
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
        });



 $("#selUser").change(function() {
    var id = $("#selUser").val();
	$('#created_at,#hid').val(0); 
	$('#submitbtnid').hide();
	
   $.ajax({
    type:"POST",
	url: "{{ route('getCustomerLastSubscriptionPurchaseValid') }}",
    data:{
		id:id,
		_token:'{{ csrf_token() }}',
		},
    dataType:"json",
    success: function(e){
		if(e.success==0){
			showSweetAlert('Wrong',e.msg, 0);
			return false;
		}
		if(e.dataCustomer.length==0){
			showSweetAlert('404','Customer Not Found', 0);
			return false;
		}
		$('#submitbtnid').show();
		$('#created_at').val(e.dataCustomer[0].created_at);
    }, 
	error:function() {  showSweetAlert('Something Went Wrong!','please refresh page and try again', 0); }
   });
});

</script> 
<!----Add Custom Js --end------->
</body>
</html>