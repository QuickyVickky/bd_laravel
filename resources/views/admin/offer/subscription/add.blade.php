<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
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
        <div class="col-lg-12 col-12 layout-spacing">
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>Add {{ $control }} </h4>
                  @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <form method="post" action="{{ route('submit-subscription-add') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hid" id="hid" value="0">   
                
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Subscription Name *</label>
                      <input type="text" name="subscription_shortname" class="form-control" id="subscription_shortname" placeholder="Like GoldPlus" value="" maxlength="100" required>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Subscription Amount &#x20B9; *</label>
                      <input type="text" name="subscription_value" class="form-control allowdecimal" id="subscription_value" placeholder="Subscription Amount Value" value="" maxlength="9" minlength="1" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label>Subscription Title</label>
                      <input type="text" name="subscription_title" class="form-control" id="subscription_title" placeholder="Subscription Title" value="" maxlength="250">
                    </div>
                  </div>
                </div>
                             
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label>Subscription Description</label>
                      <input type="text" name="subscription_description" class="form-control" id="subscription_description" placeholder="Subscription Description" value="" maxlength="1000">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Subscription Terms & Conditions to Display Customer.">Subscription Terms</label>
                      <textarea type="text" rows="5" name="subscription_terms" class="form-control" id="subscription_terms" placeholder="Subscription Terms" maxlength="10000"></textarea>
                    </div>
                  </div>
                </div>
                
                
                
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Discount Type *</label>
                      <select name="discount_type" class="form-control" id="discount_type" required>
                      @foreach(constants('discount_type') as $key => $value)
                      <option value="{{ $key }}">{{ $value['name']}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Minimum Order Amount to Use This Subscription.">Minimum Order Value &#x20B9; *</label>
                      <input type="text" name="min_order_value" class="form-control allownumber" id="min_order_value" placeholder="Minimum Order Value" value="120" maxlength="8" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Amount Per Order Can Be Used With This Subscription.">Maximum Discount PerOrder &#x20B9; *</label>
                      <input type="text" name="maximum_discount_perorder" class="form-control allownumber" id="maximum_discount_perorder" placeholder="Maximum Discount Value" value="50" maxlength="10" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                    <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Amount Can Be Used With This Subscription OverAll.">Maximum Discount OverAll *</label>
                      <input type="text" name="maximum_discount_amount" class="form-control allownumber" id="maximum_discount_amount" placeholder="Maximum Discount OverAll" value="1000" maxlength="7" autocomplete="off" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Number Of Months For This Subscription Validity.">Subscription Month(s) *</label>
                      <input type="text" name="subscription_validity_months" class="form-control allownumber" id="subscription_validity_months" placeholder="Subscription Month(s)" value="3" maxlength="3" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Minimum Discount Percent or Flat Amount For This Subscription.">Minimum Discount Percent/Amount*</label>
                      <input type="text" name="discount_value_min" class="form-control allowdecimal" id="discount_value_min" placeholder="Minimum Discount Percent/Amount" value="" maxlength="8" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Percent or Flat Amount For This Subscription.">Maximum Discount Percent/Amount*</label>
                      <input type="text" name="discount_value_max" class="form-control allowdecimal" id="discount_value_max" placeholder="Maximum Discount Percent/Amount" value="" maxlength="8" minlength="1" required>
                    </div>
                  </div>
                  
                </div>
                
                
                <div class="row">
                  <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="is_default_bestvalue" id="is_default_bestvalue" value="1" >
                          <label class="custom-control-label" for="is_default_bestvalue">Check This to Make This Subscription Best Value Among All Subscription List</label>
                        </div>
                      </div>
                </div>
                </div>
                 

                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status0" name="status" class="custom-control-input" checked>
                        <label class="custom-control-label" for="status0">Active</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status1" name="status" class="custom-control-input">
                        <label class="custom-control-label" for="status1">Deactive</label>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-sm-12">
                 <label>Select Features *</label>
                @foreach($dataSubscriptionFeature as $row)
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="subscription_feature_ids[]" id="subscription_feature_ids{{ $row->id }}" value="{{ $row->id }}" >
                          <label class="custom-control-label" for="subscription_feature_ids{{ $row->id }}">{{ $row->subscription_feature }}</label>
                        </div>
                      </div>
                 @endforeach
                </div>
                </div>
                
                <br>
 
                <hr style="width:100%">
                <button type="submit" class="btn btn-info float-right admin-button-add-vnew">Submit</button>
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
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<!----Add Custom Js ----start-----> 
<script type="text/javascript">
$('body').on('keyup', '#discount_value_max', function() {
	 discount_type = $('#discount_type').val();
	 discount_value_min = $('#discount_value_min').val();
	 discount_value_max = $('#discount_value_max').val();
	 min_order_value = $('#min_order_value').val();
	 if(discount_type=='P'){
		 if(parseFloat(discount_value_max)>80){
			 showToastAlert('Discount Percent Must Be Less Than 80% .');
			 $('#discount_value_max').val(Math.round(15));
			 $('#discount_value_min').val(Math.round(8));
		 }
		 else if(parseFloat(discount_value_min) >= parseFloat(discount_value_max)){
			 showToastAlert('Minimum Discount Percent Must Be Less Than Maximum Discount Percent .');
			 $('#discount_value_max').val(Math.round(15));
			 $('#discount_value_min').val(Math.round(8));
		 }
	 }
	 else if(discount_type=='F')
	 {
		 if(parseFloat(discount_value_max) >= parseFloat(min_order_value)){
			 showToastAlert('Maximum Discount Amount Must Be Less Than Minimum Order Value.');
			 $('#discount_value_max').val(Math.round(min_order_value*0.3));
			 $('#discount_value_min').val(Math.round(min_order_value*0.1));
		 }
		 else if(parseFloat(discount_value_min) >= parseFloat(discount_value_max)){
			 showToastAlert('Minimum Discount Amount Must Be Less Than Maximum Discount Amount.');
			 $('#discount_value_max').val(Math.round(min_order_value*0.3));
			 $('#discount_value_min').val(Math.round(min_order_value*0.1));
		 }
	 }
});	 
$('body').on('change', '#discount_type', function() {
	$('#discount_value_max,#discount_value_min').val('');
});



</script> 
<!----Add Custom Js --end-------> 
</body>
</html>