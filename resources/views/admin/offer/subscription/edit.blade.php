<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/plugins/select2/select2.min.css')}}">
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
      @if(isset($one))
        <div class="col-lg-12 col-12 layout-spacing">
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>Edit {{ $control }} <span class="badge badge-warning admid-select-color wanttoeditidspan">Want To Edit ?</span> </h4>
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
              <form method="post" action="{{ route('submit-subscription-update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hid" id="hid" value="{{ $one->id }}"> 
                <input type="hidden" name="created_at" id="created_at" value="{{ $one->created_at }}">
                
                
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Subscription Name * </label>
                      <input type="text" name="subscription_shortname" class="form-control showcls24mec" disabled id="subscription_shortname" placeholder="Like GoldPlus" value="{{ $one->subscription_shortname }}" maxlength="100" required>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Subscription Amount &#x20B9; *</label>
                      <input type="text" name="subscription_value" class="form-control allowdecimal showcls24mec" disabled id="subscription_value" placeholder="Subscription Amount Value" value="{{ $one->subscription_value }}" maxlength="9" minlength="1" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label>Subscription Title</label>
                      <input type="text" name="subscription_title" class="form-control showcls24mec" disabled id="subscription_title" placeholder="Subscription Title" value="{{ $one->subscription_title }}" maxlength="250">
                    </div>
                  </div>
                </div>                
                
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label>Subscription Description</label>
                      <input type="text" name="subscription_description" class="form-control showcls24mec" disabled id="subscription_description" placeholder="Subscription Description" value="{{ $one->subscription_description }}" maxlength="1000">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Subscription Terms & Conditions to Display Customer.">Subscription Terms</label>
                      <textarea type="text" rows="5" name="subscription_terms" class="form-control showcls24mec" disabled id="subscription_terms" placeholder="Subscription Terms" maxlength="10000">{{ $one->subscription_terms }}</textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Discount Type *</label>
                      <select name="discount_type" class="form-control showcls24mec" id="discount_type" disabled required>
                      @foreach(constants('discount_type') as $key => $value)
                      <option value="{{ $key }}">{{ $value['name']}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                  
                     <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Minimum Order Amount to Use This Subscription.">Minimum Order Value &#x20B9; *</label>
                      <input type="text" name="min_order_value" class="form-control allownumber showcls24mec" disabled id="min_order_value" placeholder="Minimum Order Value" value="{{ $one->min_order_value }}" maxlength="8" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Amount Per Order Can Be Used With This Subscription.">Maximum Discount PerOrder &#x20B9; *</label>
                      <input type="text" name="maximum_discount_perorder" class="form-control allownumber showcls24mec" disabled id="maximum_discount_perorder" placeholder="Maximum Discount Value" value="{{ $one->maximum_discount_perorder }}" maxlength="10" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                    <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Amount Can Be Used With This Subscription OverAll.">Maximum Discount OverAll *</label>
                      <input type="text" name="maximum_discount_amount" class="form-control allownumber showcls24mec" disabled id="maximum_discount_amount" placeholder="Maximum Discount OverAll" value="{{ $one->maximum_discount_amount }}" maxlength="7" autocomplete="off" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Number Of Months For This Subscription Validity.">Subscription Month(s) *</label>
                      <input type="text" name="subscription_validity_months" class="form-control allownumber showcls24mec" disabled id="subscription_validity_months" placeholder="Subscription Month(s)" value="{{ $one->subscription_validity_months }}" maxlength="3" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Minimum Discount Percent or Flat Amount For This Subscription.">Minimum Discount Percent/Amount*</label>
                      <input type="text" name="discount_value_min" class="form-control allowdecimal showcls24mec" disabled id="discount_value_min" placeholder="Minimum Discount Percent/Amount" value="{{ $one->discount_value_min }}" maxlength="8" minlength="1" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Percent or Flat Amount For This Subscription.">Maximum Discount Percent/Amount*</label>
                      <input type="text" name="discount_value_max" class="form-control allowdecimal showcls24mec" disabled id="discount_value_max" placeholder="Maximum Discount Percent/Amount" value="{{ $one->discount_value_max }}" maxlength="8" minlength="1" required>
                    </div>
                  </div>
                </div>
                
                
                <div class="row">
                  <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="is_default_bestvalue" id="is_default_bestvalue" value="1" {{ (isset($one->is_default_bestvalue) && ($one->is_default_bestvalue==1)) ? 'checked' : '' }}>
                          <label class="custom-control-label" for="is_default_bestvalue">Check This to Make This Subscription Best Value Among All Subscription List</label>
                        </div>
                      </div>
                </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status0" name="status" class="custom-control-input showcls24mec" value="0" {{ (!isset($one->is_active)) ? 'checked' : '' }} {{ (isset($one->is_active) && ($one->is_active==0)) ? 'checked' : '' }} disabled>
                        <label class="custom-control-label" for="status0" >Active</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status1" name="status" class="custom-control-input showcls24mec" value="1"  {{ (isset($one->is_active) && ($one->is_active==1)) ? 'checked' : '' }} disabled>
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
                          <input type="checkbox" class="custom-control-input showcls24mec" name="subscription_feature_ids[]" id="subscription_feature_ids{{ $row->id }}" disabled value="{{ $row->id }}"  {{ in_array($row->id, explode(',',$one->subscription_feature_ids)) ? 'checked' : '' }}  >
                          <label class="custom-control-label" for="subscription_feature_ids{{ $row->id }}">{{ $row->subscription_feature }}</label>
                        </div>
                      </div>
                 @endforeach
                </div>
                </div>
 
                <hr style="width:100%">
                <button type="submit" class="btn btn-info float-right admin-button-add-vnew showhiddencls" style="display:none">Submit</button>
              </form>
            </div>
          </div>
        </div>
        @else
        <h4>Not Found</h4>
        @endif
      </div>
    </div>
    @include('admin.layout.footer') </div>
  <!--  END CONTENT AREA  --> 
</div>
<!-- END MAIN CONTAINER --> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<script type="text/javascript">
$('body').on('click', '.wanttoeditidspan', function() {
	 $('.showcls24mec').prop('disabled', false);
	 $('.wanttoeditidspan').remove();
	 $('.showhiddencls').show();
});	 


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
</body>
</html>