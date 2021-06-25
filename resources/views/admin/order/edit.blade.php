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

  @php
  $path = asset('storage'.'/order_files').'/';
  @endphp 
  
  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="layout-px-spacing">
      <div class="row layout-top-spacing">
        <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
        @if(isset($order->id) && $order->id>0)
        <div class="widget-header">
        
          <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-6 col-6">
              <h4>{{ $control }} Bigdaddy LR Number : {{ @$order->bigdaddy_lr_number }} <a href="{{ route('view-order').'/'.$order->id }}" class="btn btn-dark btn-sm">View</a></h4>
              @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-info alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif  </div>
          </div>
          <div class="col-sm-12">
          <p><span class="badge badge-{{ $order->order_status->classhtml }}">Current Order Status : {{ $order->order_status->details }}</span></p>
		</div>
        </div>
        
        <div class="widget-content widget-content-area">
        <form method="post" action="{{ route('update-existing-order') }}" enctype="multipart/form-data">
          @csrf
          <h4>Customer Details</h4>
          <input type="hidden" name="user_id" id="user_id" value="{{ $order->user_id }}">
          <input type="hidden" name="hidorderid" id="hidorderid" value="{{ $order->id }}">
          <input type="hidden" name="created_at" id="created_at" value="{{ $order->created_at }}">
          <div class="row">
            
            <div class="col-sm-4" class="business_name_rowid">
            <div class="form-group required">
              <label for="name">Business Name </label>
              <input type="text" name="business_name" class="form-control " id="business_name"  value="{{ @$order->customer->business_name }}" readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group required">
              <label>GST Number </label>
              <input type="text" name="GST_number" class="form-control" id="GST_number" value="{{ @$order->customer->GST_number }}"  readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group required">
              <label>PAN Number </label>
              <input type="text" name="pan_no" class="form-control " id="pan_no"  value="{{ @$order->customer->pan_no }}"  readonly>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group required">
              <label>Email </label>
              <input type="email" name="email" class="form-control " id="email" value="{{ @$order->customer->email }}"  readonly>
            </div>
          </div>
          
          <div class="col-sm-4" class="business_name_rowid">
          <div class="form-group required">
            <label for="name">Full Name </label>
            <input type="text" name="fullname" class="form-control " id="fullname"  value="{{ @$order->customer->fullname }}" readonly>
          </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group required">
              <label>Address </label>
         
              <input type="hidden" name="latitude"  id="latitudehid"  value="{{ @$order->customer->customerAddressFirst->latitude }}" >
              <input type="hidden" name="longitude" id="longitudehid"  value="{{ @$order->customer->customerAddressFirst->longitude }}" >
              <input type="text" name="address" class="form-control " id="address"  value="{{ @$order->customer->customerAddressFirst->address }}"  readonly>
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
                <span class="badge badge-warning admid-select-color selectanotheraddress_spanidcls" data-val="P">Select Another Address </span>
                <textarea name="pickup_address" class="form-control showcls24mec" rows="9" id="pickup_address" placeholder="Enter Address" required>{{ $order->pickup_location }}</textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div id="map" style="height:255px;"></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Name *</label>
                <input type="text" name="contact_person_name" class="form-control " id="contact_person_name" placeholder="Contact Person Name" value="{{ @$order->contact_person_name }}" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Phone Number *</label>
                <input type="text" name="contact_person_phone_number" class="form-control allownumber" id="contact_person_phone_number" placeholder="Contact Person Phone Number" value="{{ @$order->contact_person_phone_number }}" maxlength="10" minlength="10" required >
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Transporter Name (optional)</label>
                <input type="text" name="transporter_name" class="form-control " id="transporter_name" placeholder="Transporter Name" value="{{ @$order->transporter_name }}">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Pickup Location Latitude *</label>
                <input type="text" name="pickup_latitude" class="form-control " id="pickup_latitude" placeholder="" value="{{ @$order->pickup_latitude }}" required >
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Pickup Location Longitude *</label>
                <input type="text" name="pickup_longitude" class="form-control " id="pickup_longitude" placeholder="" value="{{ @$order->pickup_longitude }}" required >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group required">
                <label>Other (optional)</label>
                <input type="text" name="other_field_pickup" class="form-control " id="other_field_pickup" placeholder="Other" value="{{ @$order->other_field_pickup }}" >
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
                <span class="badge badge-warning admid-select-color selectanotheraddress_spanidcls" data-val="D">Select Another Address </span>
                <textarea name="drop_address" class="form-control showcls24mec" rows="9" id="drop_address" placeholder="Enter Address" required>{{ @$order->drop_location }}</textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div id="map_drop" style="height:255px;"></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Name *</label>
                <input type="text" name="contact_person_name_drop" class="form-control " id="contact_person_name_drop" placeholder="Contact Person Name" value="{{ @$order->contact_person_name_drop }}" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Contact Person Phone Number * </label>
                <input type="text" name="contact_person_phone_number_drop" class="form-control " id="contact_person_phone_number_drop" placeholder="Contact Person Phone Number" value="{{ @$order->contact_person_phone_number_drop }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;" maxlength="10" minlength="10" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Transporter Name (optional)</label>
                <input type="text" name="transporter_name_drop" class="form-control " id="transporter_name_drop" placeholder="Transporter Name" value="{{ @$order->transporter_name_drop }}" >
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Drop Location Latitude *</label>
                <input type="text" name="drop_latitude" class="form-control " id="drop_latitude" placeholder="" value="{{ @$order->drop_latitude }}" required>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Drop Location Longitude * </label>
                <input type="text" name="drop_longitude" class="form-control " id="drop_longitude" placeholder="" value="{{ @$order->drop_longitude }}" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group required">
                <label>Other (optional)</label>
                <input type="text" name="other_field_drop" class="form-control " id="other_field_drop" placeholder="Other" value="{{ @$order->other_field_drop }}">
              </div>
            </div>
          </div>
          <hr style="width:100%">
          
          <h4>Parcel Details <span id="dxtb45dcS5g7b" class="badge badge-success admid-select-color"> Add Another</span></h4>
          
          <div id="parcel-div-details-id">
          <?php
          $j = 0;
		  ?>
          @foreach($order->orderParcel as $op)
            <div class="row" id="parceldetailsiddiv{{ $op->id }}">
              <div class="col-sm-2">
                <div class="form-group required">
                @if($j==0)
                  <label class="">Select Goods Type *</label>
                  @else
                  <label class="">Goods Type * <span onclick="removethisid({{ $op->id }})" class="badge badge-danger spancursorcls">Delete</span></label>
                  @endif
                  <select class="form-control form-control-sm selectgoodstypecls"  name="goods_type_id[]" id="goods_type_id{{ $op->id }}" data-iv="{{ $op->id }}" required="">
                      @foreach($goods_type as $row)
                      @if($row->id==$op->goods_type_id)
                    <option value="{{ $row->id }}" selected> {{ $row->name }}</option>
                    @else
                    <option value="{{ $row->id }}" > {{ $row->name }}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <input type="hidden" name="hidparcelid[]" id="hidparcelid{{ $op->id }}"  value="{{ $op->id }}" required >
              <div class="col-sm-1">
                <div class="form-group required">
                  <label class="labelsmalltextcls">No of Parcel*</label>
                  <input type="text" name="no_of_parcel[]" class="form-control form-control-sm total_weightcls noofparcelcls allownumber" id="no_of_parcel{{ $op->id }}" value="{{ @$op->no_of_parcel }}"  placeholder="" required title="No of Parcel (Qty)">
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group required">
                  <label class="labelsmalltextcls">Weight(K.G.)*</label>
                  <input type="text" name="goods_weight[]" class="form-control form-control-sm total_weightcls allowdecimal" id="goods_weight{{ $op->id }}" data-iv="{{ $op->id }}" placeholder="" value="{{ @$op->goods_weight }}" maxlength="9" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Total Weight  (K.G.) *</label>
                  <input type="text" name="total_weight[]" class="form-control form-control-sm totalfinal_weightcls allowdecimal" id="total_weight{{ $op->id }}" value="{{ @$op->total_weight }}" maxlength="9" placeholder="" data-iv="{{ $op->id }}" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Tempo Charge &#x20B9;  * </label>
                  <input type="text" name="tempo_charge[]" class="form-control form-control-sm tempo_charge_costcls allowdecimal" data-iv="{{ $op->id }}" id="tempo_charge{{ $op->id }}" value="{{ @$op->tempo_charge }}" placeholder="Tempo Charge" maxlength="10" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Service Charge &#x20B9;  * </label>
                  <input type="text" name="service_charge[]" class="form-control form-control-sm service_charge_costcls allowdecimal" data-iv="{{ $op->id }}" id="service_charge{{ $op->id }}" value="{{ @$op->service_charge }}" placeholder="Service Charge" maxlength="10" required>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group required">
                  <label class="">Delivery Charge &#x20B9; </label>
                  <input type="text" class="form-control form-control-sm delivery_charge_costcls allowdecimal" data-iv="{{ $op->id }}" id="delivery_charge{{ $op->id }}"  value="{{ @$op->delivery_charge }}" disabled>
                </div>
              </div>
              <div class="col-sm-3" style="display:{{ ($op->goods_type_id==constants('goods_type_id_other')) ? 'block' : 'none' }};" id="divid_other_text{{ $op->id }}">
                <div class="form-group required">
                  <label>Others </label>
                  <input type="text" name="other_text[]" class="form-control form-control-sm" data-iv="{{ $op->id }}" id="other_text{{ $op->id }}" value="{{ $op->other_text }}" placeholder="Others" maxlength="200">
                </div>
              </div>
              <div class="col-sm-3" id="divid_estimation_value{{ $op->id }}">
                <div class="form-group required">
                  <label>Goods Estimated Value &#x20B9;</label>
                  <input type="text" name="estimation_value[]" class="form-control form-control-sm estimation_valuecls allowdecimal" data-iv="{{ $op->id }}" id="estimation_value{{ $op->id }}" value="{{ $op->estimation_value }}" placeholder="Estimated Value" maxlength="12">
                </div>
              </div>
              
            </div>
            <?php
          $j++;
		  ?>
          @endforeach
          </div>
          <hr style="width:100%">
          <div class="row" >
            <div class="col-sm-2">
              <div class="form-group required">
                <label>Transporter Cost &#x20B9; * </label>
                <input type="text" name="transport_cost" class="form-control" id="transport_cost allowdecimal" placeholder="Transporter Cost" maxlength="10" value="{{ @$order->transport_cost }}"  required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Goods Total Estimated Value &#x20B9;</label>
                 <input type="hidden" name="customer_estimation_asset_value" id="customer_estimation_asset_value" value="{{ @$order->customer_estimation_asset_value }}" required>
                <input type="text" name="customer_estimation_asset_value1" class="form-control allowdecimal" id="customer_estimation_asset_value1" value="{{ @$order->customer_estimation_asset_value }}" placeholder="Customer Parcel Estimation Value" disabled>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Total Weight (K.G.)</label>
                <input type="hidden" name="totalfinal_weight" id="totalfinal_weight" value="0" value="{{ @$order->total_weight }}" required>
                <input type="text" name="totalfinal_weightview" class="form-control allowdecimal" id="totalfinal_weightview" value="{{ @$order->total_weight }}" disabled>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Total Delivery Charges &#x20B9;</label>
                <input type="hidden" name="final_cost" id="final_cost" value="0" required>
                <input type="text" name="totalfinal_cost" class="form-control allowdecimal" id="totalfinal_cost" value="{{ @$order->final_cost }}" disabled>
              </div>
            </div>
          </div>
          <div class="row" >
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Minimum Order Charge &#x20B9; </label>
                <input type="text" name="min_order_value_charge" class="form-control allowdecimal" id="min_order_value_charge" value="{{ @$order->min_order_value_charge }}" placeholder="Minimum Order Charge"  maxlength="10">
              </div>
            </div>
            
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Discount &#x20B9;</label>
                <input type="text" name="discount" class="form-control allowdecimal" id="discount" value="{{ @$order->discount }}" placeholder="Discount Amount" maxlength="10">
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group required">
                <label>Redeliver Charge &#x20B9;</label>
                <input type="text" name="redeliver_charge" class="form-control allowdecimal" id="redeliver_charge" value="{{ @$order->redeliver_charge }}" placeholder="Redeliver Charge" maxlength="10">
              </div>
            </div>
            
            <div class="col-sm-3">
              <div class="form-group required">
                <label>Total Charges &#x20B9;</label>
                <input type="hidden" name="total_charges total_chargescls" id="total_charges" value="0" required>
                <input type="text" name="total_charges1" class="form-control allowdecimal total_chargescls" id="total_charges1" value="0" placeholder="Total Charges " maxlength="17" disabled>
              </div>
            </div>
         
          </div>
          <div class="row" >
          @if(!in_array($order->status,constants('order_status.temp_orders')))
          <div class="col-sm-4">
              <div class="form-group required">
                <label>BigDaddy LR Number</label>
                <input type="text" name="bigdaddy_lr_number" class="form-control allownumber" id="bigdaddy_lr_number" placeholder="BigDaddy LR Number" value="{{ @$order->bigdaddy_lr_number }}" maxlength="20">
              </div>
            </div>
            @else
             <input type="hidden" name="bigdaddy_lr_number" id="bigdaddy_lr_number" value="" maxlength="20">
            @endif
            <div class="col-sm-4">
              <div class="form-group required">
                <label>Transporter LR Number</label>
                <input type="text" name="transporter_lr_number" class="form-control" id="transporter_lr_number" placeholder="Transporter LR Number" value="{{ @$order->transporter_lr_number }}" maxlength="50">
              </div>
            </div>
        </div>
          @if(isset($order->status) && in_array($order->status, constants('order_status.requested_orders')))
           <div class="row" >
          <div class="col-sm-12">
              <div class="form-group required">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input isdefaultcls" name="ApproveThisOrderCheck" id="ApproveThisOrderCheck" value="OA" required>
                  <label class="custom-control-label" for="ApproveThisOrderCheck">Check This to Approve This Order</label>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group required">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input isdefaultcls" name="is_sendsms" id="is_sendsms" value="1" checked>
                  <label class="custom-control-label" for="is_sendsms">Send a Message With Payment Link.</label>
                </div>
              </div>
            </div>
            </div>
             @endif       
                    
            
          		<hr>
                <h6>LR Image Pickup <span id="fileLRPspanid" class="badge badge-secondary admid-select-color"> Add Another</span></h6>
          		<div class="row" style="margin-top:20px;">
                    <?php
                    if(is_object($order->orderFile) && !empty($order->orderFile)){
						$m=0;
						foreach($order->orderFile as $fx) {
							if(constants('order_file_type.lrpickup')==$fx->img_type) {
					?>
                    <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"  id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}" value="{{ $fx->img }}">
                    <div class="col-sm-2">
                    <div class="form-group required">
                      <div data-id="{{ $fx->id }}" class="deletelrimgcls"><span class="badge badge-danger spancursorcls" >Delete</span></div>
                    @if(get_file_extension($fx->img )!='' && in_array(strtolower(get_file_extension($fx->img)), constants('image_extension')))
                  <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;" onClick="imgDisplayInModal(this.src)"  /> 
                 @else
                <a href="{{ @$path.$fx->img }}" target="_blank">
                <img src="{{ asset('admin_assets/assets/etc/fileicon.png') }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;"  />
                </a>
                 @endif
                 </div>
                    </div>
                  <?php
				  $m++;
				   }}}
				   ?>
             </div>
              <div class="row" id="fileLRProwid"></div>
             
             <hr>
                <h6>LR Image Drop <span id="fileLRDspanid" class="badge badge-secondary admid-select-color"> Add Another</span></h6>
          		<div class="row">
                    <?php
                   if(is_object($order->orderFile) && !empty($order->orderFile)){
						$m=0;
						foreach($order->orderFile as $fx) {
							if(constants('order_file_type.lrdrop')==$fx->img_type) {
					?>
                    <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"  id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}" value="{{ $fx->img }}">
                    <div class="col-sm-2">
                    <div class="form-group required">
                    <div data-id="{{ $fx->id }}" class="deletelrimgcls"><span class="badge badge-danger spancursorcls" >Delete</span></div>
                    @if(get_file_extension($fx->img )!='' && in_array(strtolower(get_file_extension($fx->img)), constants('image_extension')))
                  <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;" onClick="imgDisplayInModal(this.src)"  /> 
                 @else
                <a href="{{ @$path.$fx->img }}" target="_blank"><img src="{{ asset('admin_assets/assets/etc/fileicon.png') }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;"  /></a>
                 @endif
                 </div>
                    </div>
                  <?php
				  $m++;
				   }}}
				   ?>
             </div>
             <div class="row" id="fileLRDrowid"></div>
             
             
             <hr>
                <h6>Goods Image Pickup <span id="fileGPspanid" class="badge badge-secondary admid-select-color"> Add Another</span></h6>
          		<div class="row">
                    <?php
                    if(is_object($order->orderFile) && !empty($order->orderFile)){
						$m=0;
						foreach($order->orderFile as $fx) {
							if(constants('order_file_type.goodspickup')==$fx->img_type) {
					?>
                    <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"  id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}" value="{{ $fx->img }}">
                    <div class="col-sm-2">
                    <div class="form-group required">
                    <div data-id="{{ $fx->id }}" class="deletelrimgcls"><span class="badge badge-danger spancursorcls" >Delete</span></div>
                    @if(get_file_extension($fx->img )!='' && in_array(strtolower(get_file_extension($fx->img)), constants('image_extension')))
                  <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;" onClick="imgDisplayInModal(this.src)"  /> 
                 @else
                <a href="{{ @$path.$fx->img }}" target="_blank"><img src="{{ asset('admin_assets/assets/etc/fileicon.png') }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;"  /></a>
                 @endif
                 </div>
                    </div>
                  <?php
				  $m++;
				   }}}
				   ?>
             </div>
             <div class="row" id="fileGProwid"></div>
             <hr>
                <h6>Goods Image Drop <span id="fileGDspanid" class="badge badge-secondary admid-select-color"> Add Another</span></h6>
          		<div class="row">
                    <?php
                    if(is_object($order->orderFile) && !empty($order->orderFile)){
						$m=0;
						foreach($order->orderFile as $fx) {
							if(constants('order_file_type.goodsdrop')==$fx->img_type) {
					?>
                    <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"  id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}" value="{{ $fx->img }}">
                    <div class="col-sm-2">
                    <div class="form-group required">
                    <div data-id="{{ $fx->id }}" class="deletelrimgcls"><span class="badge badge-danger spancursorcls" >Delete</span></div>
                    @if(get_file_extension($fx->img )!='' && in_array(strtolower(get_file_extension($fx->img)), constants('image_extension')))
                  <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;" onClick="imgDisplayInModal(this.src)"  /> 
                 @else
                <a href="{{ @$path.$fx->img }}" target="_blank"><img src="{{ asset('admin_assets/assets/etc/fileicon.png') }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 100px; max-height: 100px;"  /></a>
                 @endif
                 </div>
                    </div>
                  <?php
				  $m++;
				   }}}
				   ?>
             </div>
             <div class="row" id="fileGDrowid"></div>

          <hr style="width:100%">
          <button type="submit" id="btn_submit" class="btn btn-info float-right">Save Order</button>
        </form>
        @if(Session::get("adminrole")==constants('admins_type.SuperAdmin'))
        <button type="button" id="btn_delete_for_order" data-oid="{{ @$order->id }}" data-bigdaddylrnumberid="{{ @$order->bigdaddy_lr_number }}" data-orderdateid="{{ @$order->created_at }}" data-uid="{{ @$order->user_id }}" class="btn btn-danger float-left">Delete Order</button>
        @endif
         <h4>Bigdaddy LR Number : {{ @$order->bigdaddy_lr_number }}</h4>
</div>
		@else
         <div class="widget-header">
          <div class="row">
            <div class="col-md-12">
        <h4>Order Not Found</h4>
        </div>
        </div>
        </div>
        @endif
    </div>
  </div>
</div>
</div>
@include('admin.layout.footer')
</div>
<!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->

<!----image display Modal start---->
<div class="modal fade fadeInUp" id="iModal" tabindex="-1" role="dialog" aria-labelledby="iModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="iModalLabel">View</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <div class="modal-body">@if(isset($order->lr_img) && $order->lr_img!='') <img src="{{ @$path.@$order->lr_img }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 800px; max-height: 500px;" />@endif
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!----image display  Modal end---->  

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

      function initMap() {
		 initMap1();
        const myLatlng_drop = { lat: 21.170240, lng: 72.831062 };
        const map_drop = new google.maps.Map(document.getElementById("map_drop"), {
          zoom: 6,
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
          zoom: 3,
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
/*------------------get selected already use above address ----------------------------------*/
function useabouveaddress(which){
	latitudehid = $('#latitudehid').val();
	longitudehid = $('#longitudehid').val();
	addresshid = $('#address').val();
	if(which==1)
	{
		$('#pickup_address').val(addresshid);
		$('#pickup_latitude').val(latitudehid);
		$('#pickup_longitude').val(longitudehid);
	}
	else
	{
		$('#drop_address').val(addresshid);
		$('#drop_latitude').val(latitudehid);
		$('#drop_longitude').val(longitudehid);
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
    ehtml = '';
	var r_val = makeid(15,1);
	
	
	ehtml +=  '<div class="row" id="parceldetailsiddiv'+r_val+'"> <div class="col-sm-2"> <div class="form-group required"> <label>Goods Type * <span onclick="removethisid('+r_val+')" class="badge badge-danger spancursorcls">Delete</span></label> <select class="form-control form-control-sm selectgoodstypecls"  name="goods_type_id[]" id="goods_type_id'+r_val+'" required="" data-iv="'+r_val+'"> @foreach($goods_type as $row) <option value="{{ $row->id }}"> {{ $row->name }}</option> @endforeach </select> </div> </div> <div class="col-sm-1"> <div class="form-group required"> <label class="labelsmalltextcls">No of Parcel*</label> <input type="text" name="no_of_parcel[]" class="form-control form-control-sm allownumber total_weightcls noofparcelcls" id="no_of_parcel'+r_val+'" value="{{ isset($lastorder[0]->no_of_parcel) ? $lastorder[0]->no_of_parcel : 1 }}" maxlength="9" data-iv="'+r_val+'" required title="No of Parcel (Qty)"> </div> </div> <div class="col-sm-1"> <div class="form-group required"> <label class="labelsmalltextcls">Weight(K.G.)*</label> <input type="text" name="goods_weight[]" class="form-control form-control-sm allowdecimal total_weightcls" id="goods_weight'+r_val+'" maxlength="9" data-iv="'+r_val+'" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label>Total Weight  (K.G.) *</label> <input type="text" name="total_weight[]" class="form-control form-control-sm allowdecimal totalfinal_weightcls" id="total_weight'+r_val+'" data-iv="'+r_val+'" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label>Tempo Charge &#x20B9;  * </label> <input type="text" name="tempo_charge[]" class="form-control form-control-sm allowdecimal tempo_charge_costcls " id="tempo_charge'+r_val+'" data-iv="'+r_val+'" placeholder="Tempo Charge" maxlength="10" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label class="">Service Charge &#x20B9;  * </label> <input type="text" name="service_charge[]" class="form-control form-control-sm allowdecimal service_charge_costcls " id="service_charge'+r_val+'"  data-iv="'+r_val+'" placeholder="Service Charge" maxlength="10" required> </div> </div> <div class="col-sm-2"> <div class="form-group required"> <label class="">Delivery Charge &#x20B9;</label> <input type="text" class="form-control form-control-sm allowdecimal delivery_charge_costcls" id="delivery_charge'+r_val+'" data-iv="'+r_val+'" disabled> </div> </div> <div class="col-sm-3" style="display:none;" id="divid_other_text'+r_val+'"> <div class="form-group required"> <label>Others </label> <input type="text" name="other_text[]" class="form-control form-control-sm" data-iv="'+r_val+'" id="other_text'+r_val+'" value="" placeholder="Others" maxlength="200"> </div> </div> <div class="col-sm-3" id="divid_estimation_value'+r_val+'"> <div class="form-group required"> <label>Goods Estimated Value &#x20B9;</label> <input type="text" name="estimation_value[]" class="form-control form-control-sm estimation_valuecls allowdecimal" data-iv="'+r_val+'" id="estimation_value'+r_val+'" value="" placeholder="Estimated Value" maxlength="12"> </div> </div> </div>';
	
	
	$('#parcel-div-details-id').append(ehtml);
});



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
	 
	 if(typeval=="P"){
		 $("#pickup_latitude").val(latitude); 
		 $("#pickup_longitude").val(longitude); 
		 $("#pickup_address").val(address);
	 }
	 else
	 {
		 $("#drop_latitude").val(latitude); 
		 $("#drop_longitude").val(longitude); 
		 $("#drop_address").val(address);
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


$('body').on('keyup', '.total_weightcls,.totalfinal_weightcls,#min_order_value_charge,#discount,#redeliver_charge', function () {
	final_amount_show();
});


$('body').on('keyup', '.tempo_charge_costcls,.service_charge_costcls,.noofparcelcls,.estimation_valuecls', function () {
	 var iv = $(this).data('iv');
	 var no_of_parcel = $("#no_of_parcel"+iv).val(); 
	 var tempo_charge = $("#tempo_charge"+iv).val(); 
	 var service_charge = $("#service_charge"+iv).val(); 
	 $("#delivery_charge"+iv).val(parseFloat(no_of_parcel*tempo_charge+service_charge*no_of_parcel).toFixed(2)); 
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
	  
	 totalfinal_cost += parseFloat(service_charge_c*no_of_parcel_c+no_of_parcel_c*tempo_charge_c);
	 estimation_value_total += parseFloat(estimation_value_c);
	});
	
	$("#customer_estimation_asset_value,#customer_estimation_asset_value1").val(estimation_value_total.toFixed(2)); 
	
	 total_chargescls +=  parseFloat(min_order_value_charge_c - discount_c + totalfinal_cost + redeliver_charge_c);
	
	 $(".total_chargescls").val(total_chargescls.toFixed(2)); 
	
	 $("#totalfinal_cost,#final_cost").val(totalfinal_cost.toFixed(2)); 
	 var totalfinal_weightsum = 0; $('.totalfinal_weightcls').each(function(){ totalfinal_weightsum += parseFloat($(this).val()); });
	 $("#totalfinal_weight,#totalfinal_weightview").val(totalfinal_weightsum.toFixed(2)); 
}

$(document).ready(function(){
	final_amount_show();
});
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


$('body').on('click', '.deletelrimgcls', function () {
	var dataid = $(this).data('id');
	x = confirm("do you want to delete this, are you sure ?");
	if(x==true){ 
	$.ajax({
            url: "{{ route('delete-order-files') }}",
            data: {
              _token : '{{ csrf_token() }}',
              id: dataid, 
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
				window.location.reload();
	            },  error: function(){ showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });
	 }
});

</script>
<script type="text/javascript">
    $('body').on('click', '#btn_delete_for_order', function() {
        var bigdaddylrnumberid = $(this).data('bigdaddylrnumberid');
        var oid = $(this).data('oid');
		var uid = $(this).data('uid');
        var orderdateid = $(this).data('orderdateid');

        swal({
            title: 'Are You Sure ?',
            text: "Do You Want to Delete This Order ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            padding: '-2em'
        }).then(function(result) {
            if (result.value) {
				var answers = prompt("Please Type 'delete' to Delete This Order", "type");
				if (answers != "delete") {
					swal(
                            'Wrong!',
                            "Please Type Correctly to Delete This Order",
                            'error',
                        );
					return false;
				}
				
                $.ajax({
                    url: "{{ route('delete-this-order') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        oid: oid, bigdaddylrnumberid: bigdaddylrnumberid, uid: uid, orderdateid: orderdateid,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
						if(data.success==1){
							 swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        );
						}
						else
						{
							 swal(
                            'Not Deleted!',
                            data.msg,
                            'error'
                        );
						}
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });
            }
        });
    });
</script>
<!----Add Custom Js --end------->
<!-- The Modal -->
@include('admin.layout.imageview')
</body>
</html>