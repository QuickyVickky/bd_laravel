<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/plugins/select2/select2.min.css')}}">
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
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
                  @endif 
               </div>
          </div>
        </div>
        <div class="widget-content widget-content-area">
        <form method="post" action="{{ route('create-new-order') }}" enctype="multipart/form-data">
          @csrf
          <h4>Customer Details</h4>
          <input type="hidden" name="user_id" id="user_id" value="{{ @$lastorder[0]->user_id }}">
          <input type="hidden" name="lastorderid" id="lastorderid" value="{{ @$lastorder[0]->id }}">
          <input type="hidden" name="createmultipleorderid" id="createmultipleorderid" value="1">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group required">
                <label>Select Customer </label>
                <select id="selUser" name="user_idx" class="form-control">
                  <option value=""></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4" class="business_name_rowid">
            <div class="form-group required">
              <label for="name">Business Name </label>
              <input type="text" name="business_name" class="form-control " id="business_name"  value="{{ @$userdata[0]->business_name }}" readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group required">
              <label>GST Number </label>
              <input type="text" name="GST_number" class="form-control" id="GST_number" value="{{ @$userdata[0]->GST_number }}"  readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group required">
              <label>PAN Number </label>
              <input type="text" name="pan_no" class="form-control " id="pan_no"  value="{{ @$userdata[0]->pan_no }}" readonly >
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group required">
              <label>Email </label>
              <input type="email" name="email" class="form-control " id="email" value="{{ @$userdata[0]->email }}"  readonly>
            </div>
          </div>
          <div class="col-sm-4" class="business_name_rowid">
          <div class="form-group required">
            <label for="name">Transporter Name </label>
            <input type="text" name="transporter_nameview" class="form-control " id="transporter_nameview"  value="{{ @$userdata[0]->transporter_name }}" readonly>
          </div>
          </div>
          <div class="col-sm-4" class="business_name_rowid">
          <div class="form-group required">
            <label for="name">Full Name </label>
            <input type="text" name="fullname" class="form-control " id="fullname"  value="{{ @$userdata[0]->fullname }}" readonly>
          </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group required">
              <label>Address </label>
              <input type="hidden" name="latitude"  id="latitudehid"  value="{{ @$address[0]->latitude }}" >
              <input type="hidden" name="longitude" id="longitudehid"  value="{{ @$address[0]->longitude }}" >
              <input type="hidden" id="contact_person_namehid"  value="{{ @$address[0]->contact_person_name }}" >
              <input type="hidden" id="contact_person_numberhid"  value="{{ @$address[0]->contact_person_number }}" >
              <input type="hidden" id="transporter_namehid"  value="{{ @$address[0]->transporter_name }}" >
              <input type="text" name="address" class="form-control " id="address"  value="{{ @$address[0]->address }}" readonly>
            </div>
          </div>
          </div>
          <hr>
          <h4>Pickup Details</h4>
          <div class="row" id=""> <br>
            <div class="col-sm-6">
              <div class="form-group  required">
                <label for="description">Pickup Location * </label>
                <span id="e2xf5c4rf7hyn" class="badge badge-primary admid-select-color " onClick="useabouveaddress(1)"> Use Above Address </span>
                <span class="badge badge-warning admid-select-color selectanotheraddress_spanidcls"  data-val="P">Select Another Address </span>
                <textarea name="pickup_address" class="form-control showcls24mec" rows="9" id="pickup_address" placeholder="Enter Address" required>{{ @$lastorder[0]->pickup_location }}</textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div id="map" style="height:255px;"></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Name *</label>
                <input type="text" name="contact_person_name" class="form-control " id="contact_person_name" placeholder="Contact Person Name" value="{{ @$lastorder[0]->contact_person_name }}" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Phone Number *</label>
                <input type="text" name="contact_person_phone_number" class="form-control allownumber" id="contact_person_phone_number" placeholder="Contact Person Phone Number" value="{{ @$lastorder[0]->contact_person_phone_number }}" maxlength="10" minlength="10" required >
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Transporter Name (optional)</label>
                <input type="text" name="transporter_name" class="form-control " id="transporter_name" placeholder="Transporter Name" value="{{ @$lastorder[0]->transporter_name }}">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Pickup Location Latitude *</label>
                <input type="text" name="pickup_latitude" class="form-control " id="pickup_latitude" placeholder="" value="{{ @$lastorder[0]->pickup_latitude }}" required >
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Pickup Location Longitude *</label>
                <input type="text" name="pickup_longitude" class="form-control " id="pickup_longitude" placeholder="" value="{{ @$lastorder[0]->pickup_longitude }}" required >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group required">
                <label>Other (optional)</label>
                <input type="text" name="other_field_pickup" class="form-control " id="other_field_pickup" placeholder="Other" value="{{ @$lastorder[0]->other_field_pickup }}" >
              </div>
            </div>
            <br>
          </div>
          <hr style="width:100%">
          <h4>Drop Details</h4>
          <div class="row" id=""> <br>
            <div class="col-sm-6">
              <div class="form-group  required">
                <label for="description">Drop Location *</label>
                <span id="ffghf54hghfgd243g" class="badge badge-primary admid-select-color" onClick="useabouveaddress(2)"> Use Above Address </span>
                <span class="badge badge-warning admid-select-color selectanotheraddress_spanidcls"  data-val="D">Select Another Address </span>
                <textarea name="drop_address" class="form-control showcls24mec" rows="9" id="drop_address" placeholder="Enter Address" required>{{ @$lastorder[0]->drop_location }}</textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div id="map_drop" style="height:255px;"></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Name *</label>
                <input type="text" name="contact_person_name_drop" class="form-control " id="contact_person_name_drop" placeholder="Contact Person Name" value="{{ @$lastorder[0]->contact_person_name_drop }}" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Phone Number * </label>
                <input type="text" name="contact_person_phone_number_drop" class="form-control allownumber" id="contact_person_phone_number_drop" placeholder="Contact Person Phone Number" value="{{ @$lastorder[0]->contact_person_phone_number_drop }}" maxlength="10" minlength="10" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Transporter Name (optional)</label>
                <input type="text" name="transporter_name_drop" class="form-control " id="transporter_name_drop" placeholder="Transporter Name" value="{{ @$lastorder[0]->transporter_name_drop }}" >
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Drop Location Latitude *</label>
                <input type="text" name="drop_latitude" class="form-control " id="drop_latitude" placeholder="" value="{{ @$lastorder[0]->drop_latitude }}" required>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Drop Location Longitude * </label>
                <input type="text" name="drop_longitude" class="form-control " id="drop_longitude" placeholder="" value="{{ @$lastorder[0]->drop_longitude }}" required>
              </div>
            </div>
            
            <div class="col-sm-6">
              <div class="form-group required">
                <label>Other (optional)</label>
                <input type="text" name="other_field_drop" class="form-control " id="other_field_drop" placeholder="Other" value="{{ @$lastorder[0]->other_field_drop }}">
              </div>
            </div>
            
          </div>
          <hr style="width:100%">
          <h4>Parcel Details <span id="dxtb45dcS5g7b" class="badge badge-success admid-select-color"> Add Another</span></h4>
          <div id="parcel-div-details-id">
            <div class="row" id="parceldetailsiddiv0">
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Select Goods Type *</label>
                  <select class="form-control form-control-sm valblankcls selectgoodstypecls"  name="goods_type_id[]" id="goods_type_id0" data-iv="0" required="">
                      		@foreach($goods_type as $row)
                    <option value="{{ $row->id }}"> {{ $row->name }}</option>
                       		@endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group required">
                  <label class="labelsmalltextcls">No of Parcel*</label>
                  <input type="text" name="no_of_parcel[]" class="form-control form-control-sm valblankcls total_weightcls noofparcelcls allownumber" id="no_of_parcel0" value="{{ isset($lastorder[0]->no_of_parcel) ? $lastorder[0]->no_of_parcel : 1 }}" placeholder="" maxlength="9" data-iv="0" required title="No of Parcel (Qty)">
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group required">
                  <label class="labelsmalltextcls">Weight(K.G.)*</label>
                  <input type="text" name="goods_weight[]" class="form-control form-control-sm valblankcls total_weightcls allowdecimal" id="goods_weight0" data-iv="0" placeholder="" value="{{ @$lastorder[0]->goods_weight }}" maxlength="9" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Total Weight  (K.G.) *</label>
                  <input type="text" name="total_weight[]" class="form-control form-control-sm valblankcls totalfinal_weightcls allowdecimal" id="total_weight0" value="" maxlength="9" placeholder="" data-iv="0" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Tempo Charge &#x20B9;  * </label>
                  <input type="text" name="tempo_charge[]" class="form-control form-control-sm valblankcls tempo_charge_costcls allowdecimal" data-iv="0" id="tempo_charge0" value="" placeholder="Tempo Charge" maxlength="10" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Service Charge &#x20B9;  * </label>
                  <input type="text" name="service_charge[]" class="form-control form-control-sm valblankcls service_charge_costcls allowdecimal" data-iv="0" id="service_charge0" value="" placeholder="Service Charge" maxlength="10" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Delivery Charge &#x20B9; </label>
                  <input type="text" class="form-control form-control-sm valblankcls delivery_charge_costcls allowdecimal" data-iv="0" id="delivery_charge0" value="" disabled>
                </div>
              </div>
              <div class="col-sm-3" style="display:none;" id="divid_other_text0">
                <div class="form-group required">
                  <label>Others </label>
                  <input type="text" name="other_text[]" class="form-control form-control-sm valblankcls" data-iv="0" id="other_text0" value="" placeholder="Others" maxlength="200">
                </div>
              </div>
              <div class="col-sm-3" id="divid_estimation_value0">
                <div class="form-group required">
                  <label>Goods Estimated Value &#x20B9;</label>
                  <input type="text" name="estimation_value[]" class="form-control form-control-sm valblankcls estimation_valuecls allowdecimal" data-iv="0" id="estimation_value0" value="" placeholder="Estimated Value" maxlength="12">
                </div>
              </div>
              
            </div>
          </div>
          <hr style="width:100%">
          <div class="row" >
            <div class="col-sm-2">
              <div class="form-group required">
                <label>Transporter Cost &#x20B9; </label>
                <input type="text" name="transport_cost" class="form-control valblankcls allowdecimal" id="transport_cost" placeholder="Transporter Cost" maxlength="10">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Goods Total Estimated Value &#x20B9;</label>
                 <input type="hidden" name="customer_estimation_asset_value" id="customer_estimation_asset_value" value="0" required>
                <input type="text" name="customer_estimation_asset_value1" class="form-control valblankcls allowdecimal" id="customer_estimation_asset_value1" value="" disabled>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Total Weight (K.G.)</label>
                <input type="hidden" name="totalfinal_weight" id="totalfinal_weight" value="0" required>
                <input type="text" name="totalfinal_weightview" class="form-control valblankcls allowdecimal" id="totalfinal_weightview" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Total Delivery Charges &#x20B9;</label>
                <input type="hidden" name="final_cost" id="final_cost" value="0" required>
                <input type="text" name="totalfinal_cost" class="form-control valblankcls allowdecimal" id="totalfinal_cost" value="0" disabled>
              </div>
            </div>
          </div>
          <div class="row" >
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Minimum Order Charge &#x20B9;</label>
                <input type="text" name="min_order_value_charge" class="form-control valblankcls allowdecimal" id="min_order_value_charge" placeholder="Minimum Order Charge" maxlength="10">
              </div>
            </div>
            
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Discount &#x20B9;</label>
                <input type="text" name="discount" class="form-control valblankcls allowdecimal" id="discount" placeholder="Discount Amount" maxlength="10">
              </div>
            </div>
            <input type="hidden" name="redeliver_charge" id="redeliver_charge" value="0">
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Total Charges &#x20B9;</label>
                <input type="hidden" name="total_charges total_chargescls" id="total_charges" value="0" required>
                <input type="text" name="total_charges1" class="form-control valblankcls allowdecimal total_chargescls" id="total_charges1" value="0" placeholder="Total Charges " maxlength="17" disabled>
              </div>
            </div>
          </div>
          
          <div class="row" >
          <div class="col-sm-4">
              <div class="form-group required">
                <label>BigDaddy LR Number</label>
                <input type="text" name="bigdaddy_lr_number" class="form-control allownumber" id="bigdaddy_lr_number" placeholder="BigDaddy LR Number" maxlength="20">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Transporter LR Number</label>
                <input type="text" name="transporter_lr_number" class="form-control valblankcls" id="transporter_lr_number" placeholder="Transporter LR Number" maxlength="50">
              </div>
            </div>
          </div>
          
          <div class="row" >
          <div class="col-sm-12">
              <div class="form-group required">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input isdefaultcls" name="is_sendsms" id="is_sendsms" value="1">
                  <label class="custom-control-label" for="is_sendsms">Send a Message With Payment Link & Customer Can Pay/Confirm/Cancel This Order.</label>
                </div>
              </div>
            </div>
          </div>
          
          
          <hr style="width:100%">
          <div class="row" id="fileLRProwid">
            <div class="col-sm-12" id="fileLRPdivid0">
              <div class="form-group required">
                <label>LR Image Pickup (optional) <span id="fileLRPspanid" class="badge badge-secondary admid-select-color"> Add Another</span></label>
                <input type="file" name="fileLRP[]" class="form-control form-control-new-admin" id="fileLRP0" accept="image/jpeg,image/jpg,image/gif,image/png,">
              </div>
            </div>
          </div>
          <hr>
          <div class="row" id="fileLRDrowid">
            <div class="col-sm-12" id="fileLRDdivid0">
              <div class="form-group required">
                <label>LR Image Drop (optional) <span id="fileLRDspanid" class="badge badge-secondary admid-select-color"> Add Another</span></label>
                <input type="file" name="fileLRD[]" class="form-control form-control-new-admin" id="fileLRD0" accept="image/jpeg,image/jpg,image/gif,image/png,">
              </div>
            </div>
          </div>
           <hr>
          <div class="row" id="fileGProwid">
            <div class="col-sm-12" id="fileGPdivid0">
              <div class="form-group required">
                <label>Goods Image Pickup (optional) <span id="fileGPspanid" class="badge badge-secondary admid-select-color"> Add Another</span></label>
                <input type="file" name="fileGP[]" class="form-control form-control-new-admin" id="fileGP0" accept="image/jpeg,image/jpg,image/gif,image/png,">
              </div>
            </div>
          </div>
           <hr>
          <div class="row" id="fileGDrowid">
            <div class="col-sm-12" id="fileGDdivid0">
              <div class="form-group required">
                <label>Goods Image Drop (optional) <span id="fileGDspanid" class="badge badge-secondary admid-select-color"> Add Another</span></label>
                <input type="file" name="fileGD[]" class="form-control form-control-new-admin" id="fileGD0" accept="image/jpeg,image/jpg,image/gif,image/png,">
              </div>
            </div>
          </div>
          
          
          
          <hr style="width:100%">
          <button type="submit" style="display:none" id="btn_submit">Create Order</button>
          <button type="button" class="btn btn-info float-right" onClick="createmultipleorder(1)">Create Order</button>
          <button type="button" class="btn btn-success float-right" onClick="createmultipleorder(0)">Create Order Multiple</button>
        </form>
        @if(isset($lastorder[0]->bigdaddy_lr_number))
        <hr style="width:100%">
        <h4>Recent Order Number : {{ $lastorder[0]->bigdaddy_lr_number }}</h4>
        @endif </div>
    </div>
  </div>
</div>
</div>
@include('admin.layout.footer')
</div>
<!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER --> 

<!----order multiple addres list by customer id Modal starts------>
<div class="modal fade" id="cmaModal" tabindex="-1" role="dialog" aria-labelledby="cmaModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="cmaModalLabel">Please Select Any Address</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <div class="modal-body">
        <table id="t2" class="table table-hover" style="width:100%">
          <thead>
            <tr>
              <th>Select</th>
              <th >Address</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <div class="modal-footer">
          <button class="btn close-button-new" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!----order multiple addres list by customer id Modal ends-------> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> 
<script src="https://maps.googleapis.com/maps/api/js?key={{ constants('googleAPIKey') }}&callback=initMap&libraries=&v=weekly" defer></script> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/plugins/select2/select2.min.js')}}"></script> 
<!----Add Custom Js ----start-----> 
<script>
var DefaultOtherGoodsTypeId = "{{ constants('goods_type_id_other') }}";
function createmultipleorder(v) {
	$("#createmultipleorderid").val(v);
     $('#btn_submit').click();
}
		
		
      function initMap() {
		 initMap1();
        const myLatlng_drop = { lat: 21.170240, lng: 72.831062 };
        const map_drop = new google.maps.Map(document.getElementById("map_drop"), {
          zoom: 12,
          center: myLatlng_drop ,
        });
        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
          content: "Click the map to get Lat/Lng!",
          position: myLatlng_drop ,
        });
        infoWindow.open(map_drop);
        // Configure the click listener.
        map_drop.addListener("click", (mapsMouseEvent) => {
          // Close the current InfoWindow.
          infoWindow.close();
          // Create a new InfoWindow.
          infoWindow = new google.maps.InfoWindow({
            position: mapsMouseEvent.latLng,
			
          });
          infoWindow.setContent(
           dropdata   = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
          );
		  j = JSON.parse(dropdata);
		  $("#drop_latitude").val(j.lat);
		  $("#drop_longitude").val(j.lng);
          infoWindow.open(map_drop);
        });
		 
      }
    </script> 
<script>

      function initMap1() {
        const myLatlng = { lat: 21.186010096299185, lng: 72.86710481633341 };
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 12,
          center: myLatlng,
        });
		
        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
          content: "Click the map to get  Latitude and Longitude!",
          position: myLatlng,
        });
        infoWindow.open(map);
        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
          // Close the current InfoWindow.
          infoWindow.close();
          // Create a new InfoWindow.
          infoWindow = new google.maps.InfoWindow({
            position: mapsMouseEvent.latLng,
			
          });
          infoWindow.setContent(
             pickupdata = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
          );
          infoWindow.open(map);
		   k = JSON.parse(pickupdata);
		  $("#pickup_latitude").val(k.lat);
		  $("#pickup_longitude").val(k.lng);
        });
      }
    </script> 
<script type="text/javascript" >
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
	 			  $("#user_id").val(id); 
	
   $.ajax({
    type:"POST",
	url: "{{ route('fill-existing-data-from-transporter-details') }}",
    data:{
		id:id,
		_token:'{{ csrf_token() }}',
		},
    dataType:"json",
    success: function(data){
	  	e = data.one;
		clearParcelDetails();
		$('#business_name').val(e[0].business_name);
		$('#transporter_nameview').val(e[0].transporter_name);
		$('#GST_number').val(e[0].GST_number);
		$('#fullname').val(e[0].fullname);
		$('#pan_no').val(e[0].pan_no);
		$('#email').val(e[0].email);
		
		if(data.address.length>0){
		$('#latitudehid').val(data.address[0].latitude);
		$('#longitudehid').val(data.address[0].longitude);
		$('#address').val(data.address[0].address);
		$('#contact_person_namehid').val(data.address[0].contact_person_name);
		$('#contact_person_numberhid').val(data.address[0].contact_person_number);
		$('#transporter_namehid').val(data.address[0].transporter_name);
		}
		if(data.lastOrderData!=null){
			$('#pickup_address').val(data.lastOrderData.pickup_location);
			$('#drop_address').val(data.lastOrderData.drop_location);
			$('#contact_person_name').val(data.lastOrderData.contact_person_name);
			$('#contact_person_phone_number').val(data.lastOrderData.contact_person_phone_number);
			$('#transporter_name').val(data.lastOrderData.transporter_name);
			$('#contact_person_name_drop').val(data.lastOrderData.contact_person_name_drop);
			$('#contact_person_phone_number_drop').val(data.lastOrderData.contact_person_phone_number_drop);
			$('#transporter_name_drop').val(data.lastOrderData.transporter_name_drop);
			$('#pickup_latitude').val(data.lastOrderData.pickup_latitude);
			$('#pickup_longitude').val(data.lastOrderData.pickup_longitude);
			$('#drop_latitude').val(data.lastOrderData.drop_latitude);
			$('#drop_longitude').val(data.lastOrderData.drop_longitude);
			$('#other_field_pickup').val(data.lastOrderData.other_field_pickup);
			$('#other_field_drop').val(data.lastOrderData.other_field_drop);
			$('#transport_cost').val(data.lastOrderData.transport_cost);
			$('#min_order_value_charge').val(0);
			$('#discount').val(0);
			$('#transporter_lr_number').val(data.lastOrderData.transporter_lr_number);
			
			
			if(data.lastOrderData.order_parcel.length>0){
				
				var orderID = 0;
				var increments;
					for (increments = 0; increments < data.lastOrderData.order_parcel.length; increments++) {
						pd = data.lastOrderData.order_parcel[increments];
						
						if(increments>0){
							orderID = pd.id;
							add_another_parcel_details_column(orderID);
						}
						$('#goods_type_id'+orderID).val('');
						$('#no_of_parcel'+orderID).val(pd.no_of_parcel);
						$('#goods_weight'+orderID).val(pd.goods_weight);
						$('#total_weight'+orderID).val(pd.total_weight);
						$('#tempo_charge'+orderID).val(pd.tempo_charge);
						$('#service_charge'+orderID).val(pd.service_charge);
						$('#estimation_value'+orderID).val(pd.estimation_value);
						$('#goods_type_id'+orderID).val(pd.goods_type_id);
						$('#goods_type_id'+orderID).trigger("change");
						$('#other_text'+orderID).val(pd.other_text);
					}
			}
			final_amount_show();
		}
    }, 
	error:function() {  showSweetAlert('Something Went Wrong!','please refresh page and try again', 0); }
   });
});

function clearParcelDetails(){
	$(".valblankcls").val(''); 
	$(".add_another_parcel_details_divrowcls").remove(); 
	$(".selectgoodstypecls").val($(".selectgoodstypecls option:first").val());
}

/*------------------get selected already use above address ----------------------------------*/
function useabouveaddress(which){
	latitudehid = $('#latitudehid').val();
	longitudehid = $('#longitudehid').val();
	addresshid = $('#address').val();
	contact_person_name = $('#contact_person_namehid').val();
	contact_person_number = $('#contact_person_numberhid').val();
	transporter_name = $('#transporter_namehid').val();
	if(which==1)
	{
		$('#pickup_address').val(addresshid);
		$('#pickup_latitude').val(latitudehid);
		$('#pickup_longitude').val(longitudehid);
		$("#contact_person_name").val(contact_person_name);
		 $("#contact_person_phone_number").val(contact_person_number);
		 $("#transporter_name").val(transporter_name);
	}
	else
	{
		$('#drop_address').val(addresshid);
		$('#drop_latitude').val(latitudehid);
		$('#drop_longitude').val(longitudehid);
		$("#contact_person_name_drop").val(contact_person_name);
		 $("#contact_person_phone_number_drop").val(contact_person_number);
		 $("#transporter_name_drop").val(transporter_name);
	}
}

/*------------------get selected already use above address ----------------------------------*/	

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

$('body').on('click', '#dxtb45dcS5g7b', function () {
	var r_val = makeid(9,1);
	add_another_parcel_details_column(r_val);
});

function add_another_parcel_details_column(r_val) {
	ehtml = '';
	ehtml +=  '<div class="row add_another_parcel_details_divrowcls" id="parceldetailsiddiv'+r_val+'"> <div class="col-sm-2"> <div class="form-group required"> <label>Goods Type * <span onclick="removethisid('+r_val+')" class="badge badge-danger spancursorcls">Delete</span></label> <select class="form-control form-control-sm selectgoodstypecls"  name="goods_type_id[]" id="goods_type_id'+r_val+'" data-iv="'+r_val+'" required=""> @foreach($goods_type as $row) <option value="{{ $row->id }}"> {{ $row->name }}</option> @endforeach </select> </div> </div> <div class="col-sm-1"> <div class="form-group required"> <label class="labelsmalltextcls">No of Parcel*</label> <input type="text" name="no_of_parcel[]" class="form-control form-control-sm total_weightcls noofparcelcls allownumber" id="no_of_parcel'+r_val+'" value="{{ isset($lastorder[0]->no_of_parcel) ? $lastorder[0]->no_of_parcel : 1 }}" maxlength="9" data-iv="'+r_val+'" required title="No of Parcel (Qty)"> </div> </div> <div class="col-sm-1"> <div class="form-group required"> <label class="labelsmalltextcls">Weight(K.G.)*</label> <input type="text" name="goods_weight[]" class="form-control form-control-sm total_weightcls allowdecimal" id="goods_weight'+r_val+'" maxlength="9" data-iv="'+r_val+'" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label>Total Weight  (K.G.) *</label> <input type="text" name="total_weight[]" class="form-control form-control-sm totalfinal_weightcls allowdecimal" id="total_weight'+r_val+'" value="" placeholder="" data-iv="'+r_val+'" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label>Tempo Charge &#x20B9;  * </label> <input type="text" name="tempo_charge[]" class="form-control form-control-sm tempo_charge_costcls allowdecimal" id="tempo_charge'+r_val+'" data-iv="'+r_val+'" value="" placeholder="Tempo Charge" maxlength="10" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label class="">Service Charge &#x20B9;  * </label> <input type="text" name="service_charge[]" class="form-control form-control-sm service_charge_costcls allowdecimal" id="service_charge'+r_val+'"  data-iv="'+r_val+'" value="" placeholder="Service Charge" maxlength="10" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label class="">Delivery Charge &#x20B9;</label> <input type="text" class="form-control form-control-sm delivery_charge_costcls allowdecimal" id="delivery_charge'+r_val+'" data-iv="'+r_val+'" value="" disabled> </div> </div> <div class="col-sm-3" style="display:none;" id="divid_other_text'+r_val+'"> <div class="form-group required"> <label>Others </label> <input type="text" name="other_text[]" class="form-control form-control-sm" data-iv="'+r_val+'" id="other_text'+r_val+'" value="" placeholder="Others" maxlength="200"> </div> </div> <div class="col-sm-3" id="divid_estimation_value'+r_val+'"> <div class="form-group required"> <label>Goods Estimated Value &#x20B9;</label> <input type="text" name="estimation_value[]" class="form-control form-control-sm estimation_valuecls allowdecimal" data-iv="'+r_val+'" id="estimation_value'+r_val+'" value="" placeholder="Estimated Value" maxlength="12"> </div> </div> </div>';
	$('#parcel-div-details-id').append(ehtml);
}
function removethisid(id) {
	x = confirm("do you want to delete this, are you sure ?");
	if(x==true){$("#parceldetailsiddiv"+id).remove(); final_amount_show();}
}



$('body').on('change', '.selectgoodstypecls', function () {
	var iv = $(this).data('iv');
	selectedId = $("#goods_type_id"+iv).val();
	if(selectedId==DefaultOtherGoodsTypeId){
		$("#divid_other_text"+iv).show();
	}
	else
	{
		$("#divid_other_text"+iv).hide();
	}
});
		
		
	
/*----select from multiple address------*/	
var t2;
$('body').on('click', '.selectanotheraddress_spanidcls', function () {
	var val = $(this).data('val');
	user_id = $("#user_id").val(); 
		if(user_id!='' && user_id >0 )
		{
			seeAddresses(user_id,val);
		}
		else
		{
			showSweetAlert('Select Please','Select Any Customer First', 0);
		}
});


function seeAddresses(uid=0,val){ 
					$("#cmaModal").modal("show");
                    t2 = $('#t2').DataTable({
                        processing: false,
						destroy: true,
						paging: true,
						searching: false,
						lengthMenu : [[10, 50, 100], [10, 50, 100]],
						pageLength: 10,
                        order: [[0, 'desc']],
                        serverSide: true,
						ajax: {
						"url": "{{ route('get-addresses-byid-customer') }}",
						"type": "get",
						"data":function(data) {
							data.uid =uid;
							data.val =val;
						},
						'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1],
                        }]
					},
                });
}

$('body').on('click', '.selectaddressit', function () {
	 var typeval = $(this).data('typeval');
	 var latitude = $(this).data('latitude');
	 var longitude = $(this).data('longitude');
	 var address = $(this).data('address');
	 var contact_person_name = $(this).data('contact_person_name');
	 var contact_person_number = $(this).data('contact_person_number');
	 var transporter_name = $(this).data('transporter_name');
	 
	 if(typeval=="P"){
		 $("#pickup_latitude").val(latitude); 
		 $("#pickup_longitude").val(longitude); 
		 $("#pickup_address").val(address);
		 $("#contact_person_name").val(contact_person_name);
		 $("#contact_person_phone_number").val(contact_person_number);
		 $("#transporter_name").val(transporter_name);
	 }
	 else
	 {
		 $("#drop_latitude").val(latitude); 
		 $("#drop_longitude").val(longitude); 
		 $("#drop_address").val(address);
		 $("#contact_person_name_drop").val(contact_person_name);
		 $("#contact_person_phone_number_drop").val(contact_person_number);
		 $("#transporter_name_drop").val(transporter_name);
	 }
	 
	 /*$("#latitudehid").val(latitude); 
	 $("#longitudehid").val(longitude); 
	 $("#address").val(address); */
	 $("#cmaModal").modal("hide");
});


/*----select from multiple address------*/	
</script> 
<script type="text/javascript" >
$('body').on('keyup', '.total_weightcls', function () {
	 var iv = $(this).data('iv');
	 var goods_weight = $("#goods_weight"+iv).val(); 
	 var no_of_parcel = $("#no_of_parcel"+iv).val(); 
	 $("#total_weight"+iv).val(parseFloat(no_of_parcel*goods_weight).toFixed(2)); 
});

$('body').on('keyup', '.totalfinal_weightcls', function () {
	 var iv = $(this).data('iv');
	 var total_weight = $("#total_weight"+iv).val(); 
	 var no_of_parcel = $("#no_of_parcel"+iv).val(); 
	 $("#goods_weight"+iv).val(parseFloat(total_weight/no_of_parcel).toFixed(2)); 
});

$('body').on('keyup', '.total_weightcls,.totalfinal_weightcls,#min_order_value_charge,#discount,#redeliver_charge,.tempo_charge_costcls,.service_charge_costcls,.noofparcelcls,.estimation_valuecls', function () {
	final_amount_show();
});



function final_amount_show(){
	var total_chargescls = 0;
	var min_order_value_charge = $("#min_order_value_charge").val(); 
	var discount = $("#discount").val(); 
	var estimation_value_total = 0; 
	var totalfinal_cost = 0; 
	var redeliver_charge = $("#redeliver_charge").val(); 
	
	  if(redeliver_charge>0){
	  redeliver_charge_c = parseFloat(redeliver_charge);
	  }
	  else 
	  {
		 redeliver_charge_c = 0;  
	  }
	
	  if(min_order_value_charge>0){
	  min_order_value_charge_c = parseFloat(min_order_value_charge);
	  }
	  else 
	  {
		 min_order_value_charge_c = 0;  
	  }
	  
	  if(discount>0){
	  discount_c = parseFloat(discount);
	  }
	  else 
	  {
		 discount_c = 0;  
	  }
	  
	
	$('.service_charge_costcls').each(function(){  
		var vv = $(this).data('iv');

	 
	  
	  if($("#estimation_value"+vv).val()>0){
	  estimation_value_c = parseFloat($("#estimation_value"+vv).val());
	  }
	  else 
	  {
		 estimation_value_c = 0;  
	  }
	  
	  if($("#service_charge"+vv).val()>0){
	  service_charge_c = parseFloat($("#service_charge"+vv).val());
	  }
	  else 
	  {
	   service_charge_c = 0;  
	  }
	  
	  if($("#no_of_parcel"+vv).val()>0){
	  no_of_parcel_c = parseFloat($("#no_of_parcel"+vv).val());
	  }
	  else 
	  {
	   no_of_parcel_c = 1;  
	  }
	  
	  if($("#tempo_charge"+vv).val()>0){
	  tempo_charge_c = parseFloat($("#tempo_charge"+vv).val());
	  }
	  else 
	  {
	   tempo_charge_c = 0;  
	  }
	  

	 $("#delivery_charge"+vv).val(parseFloat(no_of_parcel_c*tempo_charge_c+service_charge_c*no_of_parcel_c).toFixed(2));
	 totalfinal_cost += parseFloat(service_charge_c*no_of_parcel_c+no_of_parcel_c*tempo_charge_c);
	 estimation_value_total += parseFloat(estimation_value_c);
	});
	
	$("#customer_estimation_asset_value,#customer_estimation_asset_value1").val(estimation_value_total.toFixed(2)); 
	
	 total_chargescls +=  parseFloat(min_order_value_charge_c - discount_c + totalfinal_cost + redeliver_charge_c);
	
	 $(".total_chargescls").val(total_chargescls.toFixed(2)); 
	
	 $("#totalfinal_cost,#final_cost").val(totalfinal_cost.toFixed(2)); 
	 var totalfinal_weightsum = 0; 
	 $('.totalfinal_weightcls').each(function(){ 
	  if($(this).val()>0){
	  totalfinal_weight_c = parseFloat($(this).val());
	  }
	  else 
	  {
		 totalfinal_weight_c = 0;  
	  } 
	 totalfinal_weightsum += parseFloat(totalfinal_weight_c); 
	 });
	 $("#totalfinal_weight,#totalfinal_weightview").val(totalfinal_weightsum.toFixed(2)); 
}
</script> 

<script type="text/javascript">
$('body').on('click', '#fileLRPspanid', function () {
	var r_val = makeid(9,1);
	add_another_fileLRP_column(r_val);
});
function add_another_fileLRP_column(r_val) {
	var fileLRPextradivcls = $('.fileLRPextradivcls').length;
	if(fileLRPextradivcls>4){ showSweetAlert('Limit Reached','You can not Add more!', 0);  return false; }
	ehtml = '';
	ehtml +=  '<div class="col-sm-12 fileLRPextradivcls" id="fileLRPdivid'+r_val+'"> <div class="form-group required"> <label>LR Image Pickup (required) <span onclick="removethisidFile('+r_val+',\'fileLRPdivid\')" class="badge badge-danger spancursorcls">Delete</span></label> <input type="file" name="fileLRP[]" class="form-control form-control-new-admin" id="fileLRP'+r_val+'" accept="image/jpeg,image/jpg,image/gif,image/png," required> </div> </div>';
	$('#fileLRProwid').append(ehtml);
}

$('body').on('click', '#fileLRDspanid', function () {
	var r_val = makeid(9,1);
	add_another_fileLRD_column(r_val);
});
function add_another_fileLRD_column(r_val) {
	var fileLRDextradivcls = $('.fileLRDextradivcls').length;
	if(fileLRDextradivcls>4){ showSweetAlert('Limit Reached','You can not Add more!', 0);  return false; }
	ehtml = '';
	ehtml +=  '<div class="col-sm-12 fileLRDextradivcls" id="fileLRDdivid'+r_val+'"> <div class="form-group required"> <label>LR Image Drop (required) <span onclick="removethisidFile('+r_val+',\'fileLRDdivid\')" class="badge badge-danger spancursorcls">Delete</span></label> <input type="file" name="fileLRD[]" class="form-control form-control-new-admin" id="fileLRD'+r_val+'" accept="image/jpeg,image/jpg,image/gif,image/png," required> </div> </div>';
	$('#fileLRDrowid').append(ehtml);
}

$('body').on('click', '#fileGPspanid', function () {
	var r_val = makeid(9,1);
	add_another_fileGP_column(r_val);
});
function add_another_fileGP_column(r_val) {
	var fileGPextradivcls = $('.fileGPextradivcls').length;
	if(fileGPextradivcls>4){ showSweetAlert('Limit Reached','You can not Add more!', 0);  return false; }
	ehtml = '';
	ehtml +=  '<div class="col-sm-12 fileGPextradivcls" id="fileGPdivid'+r_val+'"> <div class="form-group required"> <label>Goods Image Pickup (required) <span onclick="removethisidFile('+r_val+',\'fileGPdivid\')" class="badge badge-danger spancursorcls">Delete</span></label> <input type="file" name="fileGP[]" class="form-control form-control-new-admin" id="fileGP'+r_val+'" accept="image/jpeg,image/jpg,image/gif,image/png," required> </div> </div>';
	$('#fileGProwid').append(ehtml);
}

$('body').on('click', '#fileGDspanid', function () {
	var r_val = makeid(9,1);
	add_another_fileGD_column(r_val);
});
function add_another_fileGD_column(r_val) {
	var fileGDextradivcls = $('.fileGDextradivcls').length;
	if(fileGDextradivcls>4){ showSweetAlert('Limit Reached','You can not Add more!', 0);  return false; }
	ehtml = '';
	ehtml +=  '<div class="col-sm-12 fileGDextradivcls" id="fileGDdivid'+r_val+'"> <div class="form-group required"> <label>Goods Image Drop (required) <span onclick="removethisidFile('+r_val+',\'fileGDdivid\')" class="badge badge-danger spancursorcls">Delete</span></label> <input type="file" name="fileGD[]" class="form-control form-control-new-admin" id="fileGD'+r_val+'" accept="image/jpeg,image/jpg,image/gif,image/png," required> </div> </div>';
	$('#fileGDrowid').append(ehtml);
}

function removethisidFile(id,id2) {
	x = confirm("do you want to delete this, are you sure ?");
	if(x==true){ $("#"+id2+id).remove(); }
}

</script>

<!----Add Custom Js --end------->

</body>
</html>