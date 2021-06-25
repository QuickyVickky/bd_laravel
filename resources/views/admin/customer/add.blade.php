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
                  <h4>{{ $control }}</h4>
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
              <form method="post" action="{{ route('submit-customer-add') }}" enctype="multipart/form-data" id="formid">
                @csrf
                <div class="row">
                  <div class="col-sm-12">
                    <label for="name">Plesae Select Customer Type </label>
                    <div class="form-group required">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customer_type27" name="customer_type" onClick="setcustomer_type(27)" class="custom-control-input" value="Transporter" checked>
                        <label class="custom-control-label" for="customer_type27">Transporter</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customer_type28" name="customer_type" onClick="setcustomer_type(28)" value="Business" class="custom-control-input">
                        <label class="custom-control-label" for="customer_type28">Business</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customer_type29" name="customer_type" onClick="setcustomer_type(29)" value="Individual" class="custom-control-input">
                        <label class="custom-control-label" for="customer_type29">Individual</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12" id="customer_gst_exempted_typeidorshow" style="display:none">
                    <label for="name">Is exempted from GST ? </label>
                    <div class="form-group required">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customer_gst_exempted_type1" name="customer_gst_exempted_type" onClick="setcustomer_GST_exempted(this.value)" class="custom-control-input" value="1" >
                        <label class="custom-control-label" for="customer_gst_exempted_type1">Yes</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customer_gst_exempted_type0" name="customer_gst_exempted_type"  onClick="setcustomer_GST_exempted(this.value)" value="0" class="custom-control-input" checked>
                        <label class="custom-control-label" for="customer_gst_exempted_type0">No</label>
                      </div>
                    </div>
                  </div> 
                  <div class="col-sm-6" id="gstno_name_rowid">
                    <div class="form-group required">
                      <label id="gstnolabelid">GST Number/Transporter Id (required)</label>
                      <div class="input-group mb-2">
                        <input type="text" name="GST_number" class="form-control " id="GST_number" placeholder="Enter a Valid GST Number" value="" maxlength="15" minlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.keyCode === 8;" required>
                        <div class="input-group-append">
                          <button class="btn btn-primary admin-button-add-vnew" type="button" id="ty56hbT7Fr2s">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label id="pannolabelid">PAN Number </label>
                      <div class="input-group mb-2">
                        <input type="text" name="pan_no" class="form-control showcls24mec" id="pan_no" placeholder="Enter PAN Number" value=""  maxlength="10" minlength="10"  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.keyCode === 8;" disabled>
                        <div class="input-group-append"> 
                          <!--button class="btn btn-primary" type="button" id="tg56dfg53gs4d">Submit</button--> 
                        </div>
                      </div>
                    </div>
                    <div class="form-group required"> </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label id="fullnamelabelid">Full Name</label>
                      <input type="text" name="fullname" class="form-control showcls24mec" id="fullname" placeholder="Enter Full Name" value="" required>
                    </div>
                  </div>
                  <div class="col-sm-4" id="business_name_rowid">
                    <div class="form-group required">
                      <label id="">Business Name *</label>
                      <input type="text" name="business_name" class="form-control showcls24mec" id="business_name" placeholder="Enter Business Name" value="" required>
                    </div>
                  </div>
                  
                  <div class="col-sm-6" id="business_type_rowid">
                    <div class="form-group required">
                      <label for="name">Business Type *</label>
                      <input type="text" name="business_type" class="form-control showcls24mec" id="business_type" placeholder="i.e. Retail Business" value="" required>
                    </div>
                  </div>
                  <div class="col-sm-6" id="ownership_rowid">
                    <div class="form-group required">
                      <label for="name">OwnerShip * </label>
                      <input type="text" name="ownership" class="form-control showcls24mec" id="ownership" placeholder="i.e. Proprietorship" value="" required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Email (optional)</label>
                      <input type="email" name="email" class="form-control showcls24mec" id="email" placeholder="Enter Email Address" value="">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Mobile Number (optional)</label>
                      <input type="text" name="mobile" class="form-control showcls24mec" id="mobile" placeholder="Enter Mobile Number" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;" maxlength="10" minlength="10">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="password">Password *</label>
                      <span id="e2xf5c4rf7hyn" class="badge badge-primary admid-select-color float-right"> Generate Random Password </span>
                      <input type="text" name="password" class="form-control showcls24mec" id="password" placeholder="Enter Password" value="" required>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group required">
                    <label>Customer Status *</label>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status0" name="status" class="custom-control-input" value="0" checked>
                        <label class="custom-control-label" for="status0" >Active</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status1" name="status"  value="1" class="custom-control-input">
                        <label class="custom-control-label" for="status1">Deactive</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group required">
                    <label>Customer Payment Bill Type *</label>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="user_paymentbill_type0" name="user_paymentbill_type" class="custom-control-input" value="0" checked>
                        <label class="custom-control-label" for="user_paymentbill_type0" >To Pay</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="user_paymentbill_type1" name="user_paymentbill_type"  value="1" class="custom-control-input">
                        <label class="custom-control-label" for="user_paymentbill_type1">To Be Billed</label>
                      </div>
                    </div>
                  </div>
                  
                </div>
                <hr>
                <h6> Customer Address <span id="dxtb45dcS5g7b" class="badge badge-success admid-select-color"> Add Another Address</span></h6>
                <br>
                <div id="customer-div-address-id">
                  <div class="row" id="addressiddefaultdiv0">
                  <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Pincode </label>
                        <input type="text" name="pincode[]" class="form-control showcls24mec fetchdatabypincodecls" data-id="1"  id="pincode1" placeholder="Pincode" value="" maxlength="6" minlength="6" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;">
                      </div>
                    </div>
                    
                    <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Country *</label>
                        <input type="text" name="country[]" class="form-control showcls24mec" id="country1"  placeholder="Country" value="India" required>
                      </div>
                    </div>
                    <input type="hidden" name="addresslist[]" value="1" >
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label for="name">State *</label>
                        <input type="text" name="state[]" class="form-control showcls24mec" id="state1" placeholder="State" value="" required>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label for="name">City *</label>
                        <input type="text" name="city[]" class="form-control showcls24mec" id="city1" placeholder="City" value="" required>
                      </div>
                    </div>
                    
                    <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Address Type *</label>
                        <select class="form-control showcls24mec"  name="address_type[]" id="address_type1" required="">
                      @foreach($address_type as $row)
                          <option value="{{ $row->short }}"> {{ $row->name }}</option>
                       @endforeach

                        </select>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group  required">
                        <label for="description">Address *</label>
                        <textarea name="address[]" class="form-control showcls24mec" rows="2" id="address1" placeholder="Enter Address" required></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <label for="name">Landmark </label>
                        <input type="text" name="landmark[]" class="form-control showcls24mec" id="landmark1" placeholder="Landmark" value=""  maxlength="200">
                      </div>
                    </div>
                    <div class="col-sm-4">
              <div class="form-group required">
                <label for="name">Contact Person Name </label>
                <input type="text" name="contact_person_name[]" class="form-control showcls24mec" id="contact_person_name1" placeholder="Contact Person Name" value="" maxlength="140">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label for="name">Contact Person Number </label>
                <input type="text" name="contact_person_number[]" class="form-control showcls24mec" id="contact_person_number1" placeholder="Contact Person Number" value="" maxlength="13">
              </div>
            </div>
                <div class="col-sm-4">
                  <div class="form-group required">
                    <label for="name">Transporter Name </label>
                    <input type="text" name="transporter_name[]" class="form-control showcls24mec" id="transporter_name1" placeholder="Transporter Name" value="" maxlength="140">
                  </div>
                </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label>Latitude *</label>
                        <input type="text" name="latitude[]" class="form-control " id="address_latitude1" placeholder="" value="" required >
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label>Longitude *</label>
                        <input type="text" name="longitude[]" class="form-control " id="address_longitude1" placeholder="" value="" required >
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div id="map1" style="height:255px;"></div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <input type="hidden" name="is_default_check[]" value="1" class="is_defaultcheckcls" id="is_defaultcheckclsid1">
                          <input type="checkbox" class="custom-control-input isdefaultcls" name="is_default[]" id="customCheck1" value="1" checked>
                          <label class="custom-control-label" for="customCheck1" onclick="check_onlyone(1)">Check This to Make Default Address</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr style="width:100%">
                <button type="reset" class="btn btn-dark">Cancel</button>
                <button type="submit" class="btn btn-info float-right">Save</button>
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ constants('googleAPIKey') }}&callback=initMap&libraries=&v=weekly" defer></script> 

<!----Add Custom Js ----start-----> 
<script>
      function initMap(mapid=1) {
        const myLatlng_drop = { lat: 21.170240, lng: 72.831062 };
        const map_address = new google.maps.Map(document.getElementById("map"+mapid), {
          zoom: 11,
          center: myLatlng_drop ,
        });
        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
          content: "Click the map to get latitude / longitude !",
          position: myLatlng_drop ,
        });
        infoWindow.open(map_address);
        // Configure the click listener.
        map_address.addListener("click", (mapsMouseEvent) => {
          // Close the current InfoWindow.
          infoWindow.close();
          // Create a new InfoWindow.
          infoWindow = new google.maps.InfoWindow({
            position: mapsMouseEvent.latLng,
			
          });
          infoWindow.setContent(
           addressdata   = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
          );
		  j = JSON.parse(addressdata);
		  $("#address_latitude"+mapid).val(j.lat);
		  $("#address_longitude"+mapid).val(j.lng);
          infoWindow.open(map_address);
        });
		 
      }
    </script> 
<script type="text/javascript" >
$(document).ready(function(e) {
    $("#e2xf5c4rf7hyn").click();
});
function randomIntFromInterval(min, max) { 
  return Math.floor(Math.random() * (max - min + 1) + min);
}
function makeid(length,ifnumberonly=0) {
   var result           = '';
   if(ifnumberonly==0){
   var characters  = 'abcdefghijklmnopqrstuvwxyzASDFGHJUIOP1234506789@$#!&-=:][{}KLMNBVCXZQWERTY';
   }
   else
   {
	    var characters = '123456789';
   }
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return  result;
}



$('body').on('click', '#ty56hbT7Fr2s', function () {
	var gstno = $("#GST_number").val();
	
	if(gstno.length!=15){
		alert("Please Type Correct GST Number"); return false;
	}
	
	$.ajax({
            url: "{{ route('gst-customer-data-fill') }}",
            data: {
              _token:'{{ csrf_token() }}',
              'GST_number': gstno, 
            },
            type: 'post',
            dataType: 'json',
            success: function (e) {
				if(e.success==false){
					alert(e.msg);
					return false;
				}
				else
				{
					//$('.showcls24mec').prop("disabled", false);
					//console.log(e);
					$('#business_type').val(e.data.taxpayerInfo.pradr.ntr);
					$('#fullname').val(e.data.taxpayerInfo.lgnm);
					$('#business_name').val(e.data.taxpayerInfo.tradeNam);
					$('#pan_no').val(e.data.taxpayerInfo.panNo);
					if(typeof(e.data.taxpayerInfo.panNo) != "undefined" && e.data.taxpayerInfo.panNo !== null) {
    					$('#pan_no').prop("disabled", false);
					}
					$('#ownership').val(e.data.taxpayerInfo.ctb);
					$('#state1').val(e.data.taxpayerInfo.pradr.addr.stcd);
					$('#city1').val(e.data.taxpayerInfo.pradr.addr.dst);
					$('#pincode1').val(e.data.taxpayerInfo.pradr.addr.pncd);
					$('#address1').val(e.data.taxpayerInfo.pradr.addr.bno + ', ' +e.data.taxpayerInfo.pradr.addr.bnm + ', ' +e.data.taxpayerInfo.pradr.addr.flno + ', ' +e.data.taxpayerInfo.pradr.addr.st );
					$('#landmark1').val(e.data.taxpayerInfo.pradr.addr.flno + ', ' +e.data.taxpayerInfo.pradr.addr.st);
					return false;
				}
              
            }
        });
    
});




$('body').on('keyup', '.fetchdatabypincodecls', function () {
	var pincode = $(this).val();
	if(pincode.length==6){
		var idx = $(this).data('id');
		/*-----*/
	$.ajax({
            url: "{{ route('gst-pincode-data-fill') }}",
            data: {
              _token:'{{ csrf_token() }}',
              'pincode': pincode, 
            },
            type: 'post',
            dataType: 'json',
            success: function (e) {
				if(e.success==false){
					showToastAlert(e.msg);
				}
				else
				{
					$('#city'+idx).val(e.data.District);
					$('#state'+idx).val(e.data.State);
					$('#country'+idx).val(e.data.Country);
				}
				return false;
            }
        });
		/*------*/
	}
});

$('body').on('click', '#e2xf5c4rf7hyn', function () {
     var password = makeid(randomIntFromInterval(6, 12));
	 $('#password').val(password);
});

$('body').on('click', '#dxtb45dcS5g7b', function () {
    ehtml = '';
	var r_val = makeid(9,1);
		
	ehtml +=  '<m id="addressiddefaultdiv'+r_val+'"> <hr> <h6> Customer Address <span onclick="removethisid('+r_val+')" class="badge badge-danger"> Delete This Address</span></h6> <div class="row" > <input type="hidden" name="addresslist[]" value="1" > <div class="col-sm-2"> <div class="form-group required"> <label for="name">Pincode</label> <input type="text" name="pincode[]" class="form-control fetchdatabypincodecls" data-id="'+r_val+'" id="pincode'+r_val+'" placeholder="Pincode" value="" maxlength="6" minlength="6" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;"> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label for="name">Country *</label> <input type="text" name="country[]" class="form-control"  placeholder="Country" value="India"  id="country'+r_val+'" required> </div> </div> <div class="col-sm-3"> <div class="form-group required"> <label for="name">State *</label> <input type="text" name="state[]" class="form-control " placeholder="State" id="state'+r_val+'" value="" required> </div> </div> <div class="col-sm-3"> <div class="form-group required"> <label for="name">City</label> <input type="text" name="city[]" class="form-control " placeholder="City" id="city'+r_val+'" value="" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label for="name">Address Type</label> <select class="form-control" name="address_type[]" id="address_type'+r_val+'" required> @foreach($address_type as $row) <option value="{{ $row->short }}"> {{ $row->name }}</option> @endforeach </select> </div> </div> <div class="col-sm-12"> <div class="form-group  required"> <label for="description">Address</label> <textarea name="address[]" class="form-control  " rows="2" placeholder="Enter Address" id="address'+r_val+'"></textarea> </div> </div> <div class="col-sm-12"> <div class="form-group required"> <label for="name">Landmark</label> <input type="text" name="landmark[]" class="form-control " placeholder="Landmark" value=""  id="landmark'+r_val+'" maxlength="200"> </div> </div> <div class="col-sm-4"> <div class="form-group required"> <label for="name">Contact Person Name </label> <input type="text" name="contact_person_name[]" class="form-control" id="contact_person_name'+r_val+'" placeholder="Contact Person Name" value="" maxlength="140"> </div> </div> <div class="col-sm-4"> <div class="form-group required"> <label for="name">Contact Person Number </label> <input type="text" name="contact_person_number[]" class="form-control showcls24mec" id="contact_person_number'+r_val+'" placeholder="Contact Person Number" value="" maxlength="13"> </div> </div> <div class="col-sm-4"> <div class="form-group required"> <label for="name">Transporter Name </label> <input type="text" name="transporter_name[]" class="form-control showcls24mec" id="transporter_name'+r_val+'" placeholder="Transporter Name" value="" maxlength="140"> </div> </div> <div class="col-sm-3"> <div class="form-group required"> <label>Latitude *</label> <input type="text" name="latitude[]" class="form-control " id="address_latitude'+r_val+'" placeholder="" value="" required > </div> </div> <div class="col-sm-3"> <div class="form-group required"> <label>Longitude *</label> <input type="text" name="longitude[]" class="form-control " id="address_longitude'+r_val+'" placeholder="" value="" required > </div> </div> <div class="col-sm-6"> <div id="map'+r_val+'" style="height:255px;"></div> </div> <div class="col-sm-12"> <div class="form-group required"> <div class="custom-control custom-checkbox"> <input type="checkbox" class="custom-control-input isdefaultcls" name="is_default[]" id="customCheck'+r_val+'" value="1"> <input type="hidden" name="is_default_check[]" value="0" class="is_defaultcheckcls" id="is_defaultcheckclsid'+r_val+'"> <label class="custom-control-label" for="customCheck'+r_val+'" onclick="check_onlyone('+r_val+')">Check This to Make Default Address</label> </div> </div> </div> </div> </m>';
	$('#customer-div-address-id').append(ehtml);
	initMap(r_val);
});



function removethisid(id) {
	x = confirm("do you want to delete this row, are you sure ?");
	if(x==true){$("#addressiddefaultdiv"+id).remove();}
}
	

function check_onlyone(id) {
        $('.isdefaultcls').prop("checked", false);
		$(".is_defaultcheckcls").val(0);
		$("#is_defaultcheckclsid"+id).val(1);
}




function setcustomer_type(id) {
	$('#customer_gst_exempted_typeidorshow').css("display", "none");
	$('#fullname').prop("required", false);
	$("#fullnamelabelid").html("Full Name");
	
	$('#business_name').prop("required", false);
	$('#business_name_rowid,#gstno_name_rowid').css("display", "block");

	$('#pan_no,#GST_number,#business_name, #fullname,#ownership,#business_type,#email,#mobile,#password').val("");
	
	

	if(id==27){
		$('#business_name_rowid,#business_type_rowid, #ownership_rowid,#gstno_name_rowid').css("display", "block");
		$('#business_name').prop("disabled", false).prop("required", true);
		$('#ownership').prop("disabled", false).prop("required", true);
		$('#business_type').prop("disabled", false).prop("required", true);
		$('#ty56hbT7Fr2s').prop("disabled", false);
		$('#GST_number').prop("disabled", false).prop("required", true);
		$("#gstnolabelid").html("GST Number / Transporter Id (required)");
		$('#pan_no').prop("required", false).prop("disabled", true);
	}
	else if(id==28){
		$('#business_name_rowid,#business_type_rowid, #ownership_rowid,#gstno_name_rowid').css("display", "block");
		$('#customer_gst_exempted_type0').click();
		$('#business_name').prop("disabled", false).prop("required", true);
		$('#ownership').prop("disabled", false).prop("required", true);
		$('#business_type').prop("disabled", false).prop("required", true);
		$('#customer_document1').click();
		$("#gstnolabelid").html("GST Number (required)");
		$('#GST_number').prop("required", true).prop("disabled", false);
		$('#pan_no').prop("required", false).prop("disabled", true);
		$("#pannolabelid").html("PAN Number (not required)");
		$('#customer_gst_exempted_typeidorshow').css("display", "block");
		$('#business_name').prop("required", true);
	}
	else {
		$('#fullname').prop("required", true);
		$("#fullnamelabelid").html("Full Name (required)");
		$('#ty56hbT7Fr2s').prop("disabled", true);
		$('#pan_no').prop("required", true).prop("disabled", false);
		$("#pannolabelid").html("PAN Number (required)");
		$('#business_name,#business_type,#ownership,#GST_number').prop("disabled", true).prop("required", false);
		$('#business_name_rowid,#business_type_rowid, #ownership_rowid,#gstno_name_rowid').css("display", "none");
	}
	$("#e2xf5c4rf7hyn").click();
}

function setcustomer_document(id) {
	$('#ty56hbT7Fr2s').prop("disabled", false);
	$('#pan_no,#GST_number,#business_name, #fullname,#ownership,#business_type,#email,#mobile,#password').val("");
	if(id==1){
		$("#gstnolabelid").html("GST Number (required)");
		$('#GST_number').prop("required", true).prop("disabled", false);
		$('#pan_no').prop("required", false).prop("disabled", true);
		$("#pannolabelid").html("PAN Number (not required)");
	}
	else {
		$("#pannolabelid").html("PAN Number (required)");
		$("#gstnolabelid").html("GST Number (not required)");
		$('#ty56hbT7Fr2s').prop("disabled", true);
		$('#pan_no').prop("required", true).prop("disabled", false);
		$('#GST_number').prop("disabled", true).prop("required", false);
	}
	$("#e2xf5c4rf7hyn").click();
}

function setcustomer_GST_exempted(id) {
	if(id==0){
	$('#customer_document1').click();
	setcustomer_document(1)
	}
	else if(id==1)
	{
		$('#customer_document2').click();
		setcustomer_document(2)
	}
}




</script> 
<!----Add Custom Js --end------->

</body>
</html>