<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
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
                  <h4>{{ $control }} </h4>
                  @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></button>
                    {{ Session::get("msg") }}</div>
                  @endif </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <form method="post" action="{{ route('update-company-details') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="hid" name="hid"  value="{{ constants('company_configurations_id') }}">
                <input type="hidden" id="typehid" name="typehid" value="Company Information" >
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Company Brand Name *</label>
                      <input type="text" name="company_name" class="form-control " id="company_name" placeholder="Enter Company Brand Name" value="{{ @$one->company_name }}" required >
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Email *</label>
                      <input type="email" name="email" class="form-control " id="email" placeholder="Enter Email Address" value="{{ @$one->email }}" required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Mobile Number *</label>
                      <input type="text" name="mobile" class="form-control " id="mobile" placeholder="Enter Mobile Number" value="{{ @$one->mobile }}" required >
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Emergency No For Driver *</label>
                      <input type="text" name="emergency_no_for_driver" class="form-control" id="emergency_no_for_driver" value="{{ @$one->emergency_no_for_driver }}" maxlength="13" minlength="10" required >
                    </div>
                  </div>
                </div>
                <hr>
                <h6> {{ $control }} Address</h6>
                <br>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">Country *</label>
                      <input type="text" name="country" class="form-control " id="country"  placeholder="Country" value="{{ isset($one->country) ? $one->country : 'India' }}" required >
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">State *</label>
                      <input type="text" name="state" class="form-control " id="state" placeholder="State" value="{{ isset($one->state) ? $one->state : 'Gujarat' }}" required >
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">City *</label>
                      <input type="text" name="city" class="form-control " id="city" placeholder="City" value="{{ isset($one->city) ? $one->city : 'surat' }}" required >
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">Pincode *</label>
                      <input type="text" name="pincode" class="form-control allownumber"  id="pincode" placeholder="Pincode" value="{{ @$one->pincode }}" maxlength="6" minlength="6" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">Address (optional)</label>
                      <textarea name="address" class="form-control " rows="3" id="address" placeholder="Enter Address" >{{ @$one->address }}</textarea>
                    </div>
                  </div>
                  
                  
                  <div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">About Us</label>
                      <textarea name="about_us" class="form-control " rows="15" id="about_us" placeholder="Enter About Us" >{{ @$one->about_us }}</textarea>
                    </div>
                  </div>
                  
                  <div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">Privacy Policy</label>
                      <textarea name="privacy_policy" class="form-control " rows="15" id="privacy_policy" placeholder="Enter Privacy Policy" >{{ @$one->privacy_policy }}</textarea>
                    </div>
                  </div>
                  
                  <div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">Terms & Condition</label>
                      <textarea name="terms_condition" class="form-control " rows="15" id="terms_condition" placeholder="Enter Terms Condition" >{{ @$one->terms_condition }}</textarea>
                    </div>
                  </div>
                  
                  <!--div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">About Us</label>
                      <textarea name="about_us" class="form-control " rows="25" id="about_us" placeholder="" ></textarea>
                    </div>
                  </div>
                </div>
                <hr>
                
                <!--p>Plesae Do not Upload a single File more than 1 MB</p>
                <br>
                <div id="driver-div-document-id">
                  <div class="row" id="append-div-id">
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <label for="name">Profile Pic (optional)</label>
                        <input type="file" name="profile_pic" class="form-control" id="profile_pic" accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp">
                      </div>
                    </div>
                   </div>
                </div-->
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
<script type="text/javascript" >

                       



</script> 
<!----Add Custom Js --end------->
</body>
</html>