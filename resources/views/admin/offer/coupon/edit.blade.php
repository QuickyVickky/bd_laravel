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
                  <h4>Edit {{ $control }} </h4>
                  @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button>
              </div>
              @endforeach 
              @endif
                  @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="fa fa-times" aria-hidden="true"></i></button>
                    {{ Session::get("msg") }} </div>
                  @endif </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <form method="post" action="{{ route('submit-coupon-update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hid" id="hid" value="{{ $one->id }}"> 
                <input type="hidden" name="created_at" id="created_at" value="{{ $one->created_at }}">                
                
                <div class="row">
            <div class="col-sm-12">
              <div class="form-group required">
                <label>Select Customer (required) <span class="badge badge-warning admid-select-color wanttoeditidspan">Want To Edit ?</span> <span class="badge badge-info admid-select-color clicktoapplyforallcustomercls  showhiddencls" style="display:none">Click to Apply For All Customer</span></label>
                <select id="selUser" name="user_idx" class="form-control showcls24mec" disabled required>
                  <option value="0" selected>Applied For All Customer</option>
                  @if($one->user_id > 0)
                  <option value="{{ $one->user_id }}" selected>{{ $one->customer->fullname. " | " .$one->customer->business_name. " | ".$one->customer->mobile. " | ".$one->customer->email}}</option>
                  @endif
                </select>
              </div>
            </div>

      
          </div>
          
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label>Coupon Code * <!--span class="badge badge-dark admid-select-color generaterandomcouponcodecls rounded bs-tooltip  showhiddencls" style="display:none" data-placement="top" data-original-title="It Will Generate 10 Digit Random Coupon Code.">Generate Random</span--></label>
                      <input type="text" name="coupon_code" class="form-control disallowspace" id="coupon_code" placeholder="Type Coupon Code" value="{{ $one->coupon_code }}" maxlength="50" minlength="2" disabled>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <div class="form-group required">
                      <label for="name">Coupon Title</label>
                      <input type="text" name="coupon_title" class="form-control showcls24mec" id="coupon_title" placeholder="Coupon Title Like DIWALI OFFER" value="{{ $one->coupon_title }}" maxlength="120" disabled>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label>Coupon Description</label>
                      <input type="text" name="coupon_description" class="form-control showcls24mec" id="coupon_description" placeholder="Coupon Description" value="{{ $one->coupon_description }}" maxlength="1000" disabled>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Coupon Terms & Conditions to Display Customer.">Coupon Terms</label>
                      <textarea type="text" rows="3" name="coupon_terms" class="form-control showcls24mec" id="coupon_terms" placeholder="Coupon Terms" maxlength="2500" disabled>{{ $one->coupon_terms }}</textarea>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Discount Type *</label>
                      <select name="discount_type" class="form-control showcls24mec" id="discount_type" disabled required>
                      @foreach(constants('discount_type') as $key => $value)
                      @if($one->discount_type==$key)
                      <option value="{{ $key }}" selected>{{ $value['name']}}</option>
                      @else
                      <option value="{{ $key }}">{{ $value['name']}}</option>
                      @endif
                      @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Payment Type For *</label>
                      <select name="applied_for" class="form-control showcls24mec" id="applied_for" disabled required>
                      <option value="0" {{ ($one->applied_for==0) ? 'selected' : '' }}>Both</option>
                      <option value="1" {{ ($one->applied_for==1) ? 'selected' : '' }}>Prepaid/Wallet</option>
                      <option value="2" {{ ($one->applied_for==2) ? 'selected' : '' }}>Cash On Delivery</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Platform Type For *</label>
                      <select name="applied_for_platform" class="form-control showcls24mec" id="applied_for_platform" disabled required>
                      <option value="0" {{ ($one->applied_for_platform==0) ? 'selected' : '' }}>Both</option>
                      <option value="1" {{ ($one->applied_for_platform==1) ? 'selected' : '' }}>Web</option>
                      <option value="2" {{ ($one->applied_for_platform==2) ? 'selected' : '' }}>App</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="If Discount Type is Flat Amount Then Amount Else Percentage.">Discount Value/Percent *</label>
                      <input type="text" name="discount_value" class="form-control allowdecimal showcls24mec" id="discount_value" placeholder="Discount Value/Percent " value="{{ $one->discount_value }}" maxlength="6" minlength="1" required disabled>
                    </div>
                  </div>
                  </div>
                  <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Minimum Order Amount to Use This Coupon.">Minimum Order Value &#x20B9; *</label>
                      <input type="text" name="min_order_value" class="form-control allownumber showcls24mec" id="min_order_value" placeholder="Minimum Order Value" value="{{ $one->min_order_value }}" maxlength="8" minlength="1" required disabled>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum Discount Amount Can Be Used With This Coupon.">Maximum Discount Value &#x20B9; *</label>
                      <input type="text" name="maximum_discount" class="form-control allownumber showcls24mec" id="maximum_discount" placeholder="Maximum Discount Value" value="{{ $one->maximum_discount }}" maxlength="10" minlength="1" required disabled>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                    <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum n Time Can Be Used This Coupon Per Customer.">Maximum Use Number/Customer *</label>
                      <input type="text" name="maximum_use_count_peruser" class="form-control allownumber showcls24mec" id="maximum_use_count_peruser" autocomplete="off" placeholder="Maximum Use Number" value="{{ $one->maximum_use_count_peruser }}" maxlength="7" minlength="1" required disabled>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                    <label class="rounded bs-tooltip" data-placement="top" data-original-title="Maximum n Time Can Be Used This Coupon For First n Order.">Maximum Use Number *</label>
                      <input type="text" name="maximum_use_count" class="form-control allownumber showcls24mec" id="maximum_use_count" placeholder="Maximum Use Number" autocomplete="off" value="{{ $one->maximum_use_count }}" maxlength="7" minlength="1" required disabled>
                    </div>
                  </div>
                  </div>
                
                 <div class="row">
                <div class="col-sm-3">
                    <div class="form-group required">
                      <label>Starting DateTime (required)</label>
                      <input type="text" name="start_datetime" class="form-control showcls24mec" id="start_datetime" value="{{ $one->start_datetime }}" placeholder="Required" disabled required>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label class="rounded bs-tooltip" data-placement="top" data-original-title="For Best Practice, You Must Fill Ending DateTime For Any Coupon.">Ending DateTime (optional)</label>
                      <input type="text" name="end_datetime" class="form-control showcls24mec" id="end_datetime" value="{{ $one->end_datetime }}" disabled>
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
        <div class="jumbotron" style="padding: 1rem 1rem;">
          <p class="lead mt-0 mb-1">1- While Applying Percent Discount, Discount Percent Value Must Be Less Than 90% .</p>
          <p class="lead mt-0 mb-1">2- While Applying Flat Discount, Minimum Order Value Must Be Greater Than Discount Value & Maximum Discount Value Must Be Equal to Discount Value. You Can Not Change Maximum Discount Value.</p>
          <p class="lead mt-0 mb-1">3- Ending DateTime is Optional But For Best Practice, You Should Fill it.</p>
    
        </div>
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
<script src="{{ asset('admin_assets/plugins/select2/select2.min.js')}}"></script> 
<link href="{{ asset('admin_assets/assets/css/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin_assets/assets/js/flatpickr.min.js')}}"></script> 
<!----Add Custom Js ----start-----> 
<script type="text/javascript">
  var start_datetime_flatpickr = flatpickr(document.getElementById('start_datetime'), {
    enableTime: true,
    dateFormat: "Y-m-d H:i:s",
	onChange: function(selectedDates, dateStr, instance) {
		$('#end_datetime').val('');
    },
});

var end_datetime_flatpickr = flatpickr(document.getElementById('end_datetime'), {
    enableTime: true,
    dateFormat: "Y-m-d H:i:s",
});




$('body').on('click', '.wanttoeditidspan', function() {
	 $('.showcls24mec').prop('disabled', false);
	 $('.wanttoeditidspan').remove();
	 $('.showhiddencls').show();
});	 

$('body').on('click', '#discount_type', function() {
	 $('#discount_value').val('');
});	 

$('body').on('change', '#end_datetime', function() {
	 end_datetime = $('#end_datetime').val();
	 start_datetime = $('#start_datetime').val();
	 if (new Date(start_datetime).getTime() > new Date(end_datetime).getTime()) {
		 showToastAlert('Ending DateTime Must Be Greater Than Starting DateTime!');
		  $('#end_datetime').val('');
     }
});	 


$('body').on('keyup', '#discount_value', function() {
	 discount_type = $('#discount_type').val();
	 discount_value = $('#discount_value').val();
	 if(discount_type=='P' && discount_value>90){
		 showToastAlert('Discount Percent Must Be Less Than 90% .');
		 $('#discount_value').val('');
	 }
	 else if(discount_type=='F')
	 {
		 $('#min_order_value').val(Math.round(discount_value*1.11));
		 $('#maximum_discount').val(discount_value);
		 
	 }
});	 

$('body').on('keyup', '#maximum_discount', function() {
	discount_type = $('#discount_type').val();
	discount_value = $('#discount_value').val();
	if(discount_type=='F')
	 {
		showToastAlert('You Can Not Change Maximum Discount Value For Flat Discount. Leave It.');
		$('#maximum_discount').val(discount_value);
	 }
	 else
	 {
		 
	 }
});	
	 


$('body').on('click', '.clicktoapplyforallcustomercls', function() {
	 $('#selUser').html('<option value="0" selected>Applied For All Customer</option>');
});	 

$('body').on('click', '.generaterandomcouponcodecls', function() {
	 coupon_code = generateRandomString(10);
	 $('#coupon_code').val(coupon_code);
});	 





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


const characters ='NJK456M9PQR0123CDESTUVWXOFGHIY78ABLZ';
function generateRandomString(length=10) {
    let result = '';
    const charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}


</script> 
<!----Add Custom Js --end-------> 
</body>
</html>