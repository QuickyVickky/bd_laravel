<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<!--link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" /---->
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{ asset('admin_assets/plugins/drag-and-drop/dragula/dragula.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/plugins/drag-and-drop/dragula/example.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/bootstrap-multiselect.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/daterangepicker.min.css') }}">
</head>
<body data-spy="scroll" data-target="#navSection" data-offset="100">
@include('admin.layout.header') 

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
  <div class="overlay"></div>
  <div class="search-overlay"></div>
  @include('admin.layout.sidebar')
  <?php
                $path = sendPath().'driver_files/';
				$tendayafter = date('Y-m-d', strtotime('+11 days'));
                ?>
                
          <style>
    td span:first-child {
        margin-bottom: 5px;
    }
	.custom-select.multiselect
	 {
		padding: 10px 37px !important;
		border: 1px solid #061625 !important;
	 }
	 .redcolorcls {
		color:red;
	}
	.greencolorcls {
		color:green;
	}
    </style>
  <!--  BEGIN CONTENT AREA  -->
  <div id="content" class="main-content">
    <div class="layout-px-spacing">
      <div class="row layout-top-spacing">
   
                                        
      @if(count($one)>0)
        <div class="col-lg-12 col-12 layout-spacing">
           @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="fa fa-times" aria-hidden="true"></i></button>
                    {{ Session::get("msg") }} </div>
                  @endif 
                  
                  
         

          <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
           @if(is_allowedHtml('roleclass_assigned_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="assigned-tab" data-toggle="tab" href="#assignedid"
                                    role="tab" aria-controls="assigned" aria-selected="false">Assigned</a> </li>
            @endif
            @if(is_allowedHtml('roleclass_view_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="home-tab" data-toggle="tab"
                                    href="#viewid" role="tab" aria-controls="home" aria-selected="true">View</a> </li>
            @endif
            @if(is_allowedHtml('roleclass_images_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="contact-tab" data-toggle="tab"
                                    href="#historyid" role="tab" aria-controls="contact"
                                    aria-selected="false" onClick="t1DatatableRefresh()">Images</a> </li>
            @endif
            @if(is_allowedHtml('roleclass_delivered_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="log-tab" data-toggle="tab" href="#deliveredid"
                                    role="tab" aria-controls="delivered" aria-selected="false" onClick="t5DatatableRefresh()">Delivered</a> </li>
            @endif
             @if(is_allowedHtml('roleclass_payroll_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="payroll-tab" data-toggle="tab" href="#payrollid"
                                    role="tab" aria-controls="log" aria-selected="false" onClick="t6DatatableRefresh()">PayRoll</a> </li>
            @endif
            @if(is_allowedHtml('roleclass_logs_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="log-tab" data-toggle="tab" href="#logid"
                                    role="tab" aria-controls="log" aria-selected="false" onClick="t3DatatableRefresh()">Logs</a> </li>
            @endif
            @if(is_allowedHtml('roleclass_timingreports_liandultab_driver')==true)
            <li class="nav-item"> <a class="nav-link driverpageatabcls" id="timingreports-tab" data-toggle="tab" href="#timingreportsid"
                                    role="tab" aria-controls="log" aria-selected="false" onClick="t7DatatableRefresh()">TimingReports</a> </li>
            @endif
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade" id="viewid" role="tabpanel"
                                aria-labelledby="home-tab"> @if(is_allowedHtml('roleclass_view_liandultab_driver')==true)
              
              <div class="widget-content widget-content-area"> 
                <form method="post" action="{{ route('submit-driver-update') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="hid" value="{{ @$one[0]->id }}">
                  <div class="row"> @if(isset($one[0]->mobile) && $one[0]->profile_pic!='')
                    <div class="col-sm-12">
                      <div class="form-group required"> <img
                                                        src='{{ @$path.@$one[0]->profile_pic}}'
                                                        class="rounded-circle profile-img" alt="img" width="250px"
                                                        height="250px" onClick="imgDisplayInModal(this.src)"> </div>
                    </div>
                    @endif
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Full Name * </label>
                        <span id="wanttoeditidspan"
                                                        class="badge badge-warning float-right admid-select-color"> want
                        to edit ? </span>
                        <input type="text" name="fullname" class="form-control showcls24mec"
                                                        id="fullname" placeholder="Enter Full Name"
                                                        value="{{ @$one[0]->fullname }}" required disabled>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">PAN Number (optional)</label>
                        <input type="text" name="pan_card" class="form-control showcls24mec"
                                                        id="pan_card" placeholder="Enter PAN No"
                                                        value="{{ @$one[0]->pan_card }}" disabled>
                      </div>
                    </div>
                    <!--div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Vehicle Number *</label>
                        <input type="text" name="vehicle_number" class="form-control showcls24mec" id="vehicle_number" placeholder="Enter Vehicle Number" value="{{ @$one[0]->vehicle_number }}" required disabled >
                      </div>
                    </div-->
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Email (optional)</label>
                        <input type="email" name="email" class="form-control showcls24mec" id="email" placeholder="Enter Email Address" value="{{ @$one[0]->email }}" disabled>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Mobile Number *</label>
                        <input type="text" name="mobile" class="form-control showcls24mec"
                                                        id="mobile" placeholder="Enter Mobile Number"
                                                        value="{{ @$one[0]->mobile }}" required disabled>
                      </div>
                    </div>
                    <!--
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="password">Password (optional)</label>
                        <span id="dfsdf543hgdf56bxv" class="badge badge-primary float-right rounded bs-tooltip" title="Don Not Change if You do not want, otherwise Password will be changed."> change Password </span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" maxlength="25" value="1234567" disabled>
                      </div>
                    </div>
                    -->
                    <input type="hidden" name="existing_img" id="existing_img"
                                                value="{{ @$one[0]->profile_pic }}">
                    <div class="col-sm-9">
                      <div class="form-group required">
                        <label for="name">Change Profile Pic (optional)</label>
                        <input type="file" name="profile_pic"
                                                        class="form-control showcls24mec" id="profile_pic"
                                                        accept="image/png,image/jpg,image/jpeg,image/PNG,image/JPG,image/JPEG,"
                                                        onChange="document.getElementById('img_src').src = window.URL.createObjectURL(this.files[0]);"
                                                        disabled>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required"> <img src="" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 140px; max-height: 54px;" /> </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="status0" name="status"
                                                            class="custom-control-input showcls24mec" value="0" {{ (!isset($one[0]->
                          is_active)) ? 'checked' : '' }} {{ (isset($one[0]->is_active) && ($one[0]->is_active==0)) ? 'checked' : '' }}
                          disabled>
                          <label class="custom-control-label" for="status0">Active</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="status1" name="status"
                                                            class="custom-control-input showcls24mec" value="1"
                                                            {{ (isset($one[0]->
                          is_active) && ($one[0]->is_active==1)) ? 'checked' : '' }}
                          disabled>
                          <label class="custom-control-label"
                                                            for="status1">Deactive</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input showcls24mec"
                                                            {{ (isset($one[0]->
                          is_salary_based) && ($one[0]->is_salary_based==1)) ? 'checked' : '' }}
                          name="is_salary_based" id="is_salary_based" value="1"
                          disabled>
                          <label class="custom-control-label showcls24mec" for="is_salary_based">Check This to Make This Driver Salary
                            Based.</label>
                        </div>
                      </div>
                    </div>
                    
                    
                    <div class="col-sm-2" id="salary_amount_divrowid"
                                                style="display:{{ (isset($one[0]->is_salary_based) && ($one[0]->is_salary_based==1)) ? 'block' : 'none' }}">
                      <div class="form-group required">
                        <label for="name">Salary Amount &#x20B9; *</label>
                        <input type="text" name="salary_amount"
                                                        class="form-control allownumber showcls24mec" id="salary_amount"
                                                        placeholder="Salary Amount"
                                                        value="{{ $one[0]->salary_amount }}" maxlength="6"
                                                        minlength="3" min="100" disabled required>
                      </div>
                    </div>
                  
                  </div>
                     <div class="row">
                    <div class="col-sm-5">
                      <div class="form-group required">
                      <label for="name">Vendor Connect (Driver)</label>
                      @if($one[0]->vendor_id>0)
                            <a target="_blank" href="{{ route('view-vendor').'?id='.$one[0]->vendor_id }}" ><span class="badge badge-info float-right admid-select-color">View Vendor</span></a>
                     @endif
                    <select id="select_vendor_from_select2_dropdown_id" class="form-control showcls24mec" name="vendor_id" disabled required>
                    <option value="{{ $one[0]->vendor_id }}">{{ $one[0]->vdfullname }}</option>
                    </select>
                    
                    </div></div>
                    </div>
                  <hr>
                  <h6> {{ $control }} Address</h6>
                  <br>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Country *</label>
                        <input type="text" name="country" class="form-control showcls24mec"
                                                        id="country1" placeholder="Country"
                                                        value="{{ @$one[0]->country }}" required disabled>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label for="name">State *</label>
                        <input type="text" name="state" class="form-control showcls24mec"
                                                        id="state1" placeholder="State" value="{{ @$one[0]->state }}"
                                                        required disabled>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label for="name">City *</label>
                        <input type="text" name="city" class="form-control showcls24mec"
                                                        id="city1" placeholder="City" value="{{ @$one[0]->city }}"
                                                        disabled required>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Pincode (optional)</label>
                        <input type="text" name="pincode"
                                                        class="form-control allownumber showcls24mec" id="pincode1"
                                                        placeholder="Pincode" value="{{ @$one[0]->pincode }}"
                                                        maxlength="6" minlength="6" disabled>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group  required">
                        <label for="description">Address (optional)</label>
                        <textarea name="address" class="form-control showcls24mec" rows="2"
                                                        id="address1" placeholder="Enter Address"
                                                        disabled>{{ @$one[0]->address }}</textarea>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <h6> {{ $control }} Document <span id="dxtb45dcS5g7b"
                                                class="badge badge-success admid-select-color"> Add Other
                    Document</span></h6>
                  <p>Plesae Do not Upload a single File more than 1 MB</p>
                  <br>
                  <div id="append-div-id"> 
                    <!--------file add---------->
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group required">
                          <label>Aadhar Card (optional)</label>
                          <input accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf" multiple type="file" name="aadhar_card_file[]" class="form-control showcls24mec" id="aadhar_card_file" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <?php
                    if(isset($one[0]->aadhar_card_file) && $one[0]->aadhar_card_file!='') {
                    $aadhar_card_file = explode(',', $one[0]->aadhar_card_file);
                    if(is_array($aadhar_card_file) && !empty($aadhar_card_file)){
						$m=0;
						foreach($aadhar_card_file as $fx) {
					?>
                      <input type="hidden" name="existing_img_aadhar_card_file[]"
                                                    id="existing_img_aadhar_card_file{{ $m }}" value="{{ $fx }}">
                      <div class="col-sm-2">
                        <div class="form-group required"> @if(get_file_extension($fx)!='' &&
                          in_array(strtolower(get_file_extension($fx)),
                          constants('image_extension'))) <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                            class="img-responsive"
                                                            style="max-width: 60px; max-height: 60px;"
                                                            onClick="imgDisplayInModal(this.src)" /> @else <a href="{{ @$path.$fx}}" target="_blank">View File</a> @endif </div>
                      </div>
                      <?php
				  $m++;
				   }}}  
				   ?>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group required">
                          <label>PAN Card (optional)</label>
                          <input
                                                            accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                            multiple type="file" name="pan_card_file[]"
                                                            class="form-control showcls24mec" id="pan_card_file"
                                                            disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <?php
                    if(isset($one[0]->pan_card_file) && $one[0]->pan_card_file!='') {
                    $pan_card_file = explode(',', $one[0]->pan_card_file);
                    if(is_array($pan_card_file) && !empty($pan_card_file)){
						$m=0;
						foreach($pan_card_file as $fx) {
					?>
                      <input type="hidden" name="existing_img_pan_card_file[]"
                                                    id="existing_img_pan_card_file{{ $m }}" value="{{ $fx }}">
                      <div class="col-sm-2">
                        <div class="form-group required"> @if(get_file_extension($fx)!='' &&
                          in_array(strtolower(get_file_extension($fx)),
                          constants('image_extension'))) <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                            class="img-responsive"
                                                            style="max-width: 60px; max-height: 60px;"
                                                            onClick="imgDisplayInModal(this.src)" /> @else <a href="{{ @$path.$fx}}" target="_blank">View File</a> @endif </div>
                      </div>
                      <?php
				  $m++;
				   }}}  
				   ?>
                    </div>
                    <div class="row">
                      <div class="col-sm-9">
                        <div class="form-group required">
                          <label>License (optional)</label>
                          <input accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf" multiple type="file" name="license_file[]" class="form-control showcls24mec" id="license_file" disabled>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group required">
                          <label for="name">Expiry Date </label>
                          <?php
						$expired = "black";
						if(($one[0]->license_expiry!="" && $one[0]->license_expiry < $tendayafter)){
							$expired = "red";
						}
						?>
                          <input type="text" name="license_expiry"
                                                            class="form-control showcls24mec datepicker "
                                                            id="license_expiry" value="{{ $one[0]->license_expiry }}"
                                                            style="color:{{ $expired }}" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <?php
                    if(isset($one[0]->license_file) && $one[0]->license_file!='') {
                    $license_file = explode(',', $one[0]->license_file);
                    if(is_array($license_file) && !empty($license_file)){
						$m=0;
						foreach($license_file as $fx) {
					?>
                      <input type="hidden" name="existing_img_license_file[]"
                                                    id="existing_img_license_file{{ $m }}" value="{{ $fx }}">
                      <div class="col-sm-2">
                        <div class="form-group required"> @if(get_file_extension($fx)!='' &&
                          in_array(strtolower(get_file_extension($fx)),
                          constants('image_extension'))) <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                            class="img-responsive"
                                                            style="max-width: 60px; max-height: 60px;"
                                                            onClick="imgDisplayInModal(this.src)" /> @else <a href="{{ @$path.$fx}}" target="_blank">View File</a> @endif </div>
                      </div>
                      <?php
				  $m++;
				   }}}  
				   ?>
                    </div>
                  </div>
                  <!--------file add---------->
                  <hr style="width:100%">
                  <button type="submit" class="btn btn-info float-right" id="btnsubmitid"
                                            style="display:none">Submit</button>
                </form>
              </div>

              @endif </div>
            @if(is_allowedHtml('roleclass_images_liandultab_driver')==true)
            <div class="tab-pane fade" id="historyid" role="tabpanel" aria-labelledby="contact-tab">
              <table id="t1" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="3%">#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th width="10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            @endif
            @if(is_allowedHtml('roleclass_payroll_liandultab_driver')==true)
                                <div class="tab-pane fade" id="payrollid" role="tabpanel" aria-labelledby="payroll-tab">
                                <form method="post" action="{{ route('exportExcelDriverPayrollOrders') }}" enctype="multipart/form-data" id="myExcelForm">
              @csrf
              <div class="card-header">
                <div class="row">
                  <input type="hidden" name="select_driver_from_select2_dropdown_id[]" value="{{ @$one[0]->id }}" >
                  <div class="form-group col-md-6">
                    <select id="driver_assign_type_value" name="driver_assign_type[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
                    @foreach(constants('driver_assign_type_value') as $key => $value) 
                    <option value="{{ $key }}" selected="selected">{{ $value['name'] }}</option>
                    @endforeach
                    </select>
                  </div>
                  
                  
                  <div class="form-group col-md-3">
                    <input type="text" id="filter_global_order_date" class="form-control-sm" placeholder="Select Date" name="filter_global_order_date" required value="" autocomplete="off">
                  </div>
                  
                  </div>
                  <div class="row">
                  <div class="form-group col-md-3"> <a class="btn btn-outline-success" id="Apply_Filter_Btn_Id" onclick="t6DatatableRefresh()"> Apply Filter</a> <button type="submit" class="btn btn-outline-info" id="Export_Excel_Filter_Btn_Id"> Export Excel</button> </div>
                </div>
              </div>
            </form>
            <br>
             <table id="t6" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>BD LR Number</th>
                    <th>Transporter LR Number</th>
                    <th>Charges</th>
                    <th>PayRoll</th>
                    <th>Order Date</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                
              </table>
            </div>
           @endif
            @if(is_allowedHtml('roleclass_logs_liandultab_driver')==true)
            <div class="tab-pane fade" id="logid" role="tabpanel" aria-labelledby="contact-tab">
              <table id="t3" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="3%">#</th>
                    <th>Logs</th>
                    <th width="10%">DateTime</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            @endif
            
            @if(is_allowedHtml('roleclass_assigned_liandultab_driver')==true)
            <div class="tab-pane fade" id="assignedid" role="tabpanel" aria-labelledby="assigned-tab"> 
              <!------------------------assign html starts here....----------------> 
              @if(count($arrangementOrderData)>0)
              <h6>Order Assign Numbering <!--span class="badge badge-info admid-select-color orderassignnumberingclassaddanotherbuttonspan"> Add +</span--></h6>
              <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                  <div class="parent ex-1">
                    <div class="row">
                      <div class="col-sm-12">
                        <div id="left-defaults" class="dragula"> @foreach($arrangementOrderData as $row)
                          <div class="media d-md-flex d-block text-sm-left text-center">
                            <div class="media-body">
                              <input type="hidden" name="orderarrangehid[]" value="{{ $row->id }}">
                              <input type="hidden" name="orderarrangeidhid[]" value="{{ $row->order_id }}">
                              <input type="hidden" name="orderarrangetypehid[]" value="{{ $row->arrangement_type }}">
                              <div class="d-xl-flex d-block justify-content-between">
                                <div class="">
                                  <h6 class="">BD LR {{ $row->order->bigdaddy_lr_number }} | Transporter LR {{ $row->order->transporter_lr_number }} | {{ $row->order->customer->fullname }} | {{ $row->order->customer->business_name }}</h6>
                                  <p class=""> @if($row->arrangement_type==constants('arrangement_type.pickup'))
                                    {{ $row->order->pickup_location }}
                                    @else
                                    {{ $row->order->drop_location }}
                                    @endif </p>
                                </div>
                                <div> <span class="shadow-none badge badge-{{ constants('arrangement_typeName.'.$row->arrangement_type.'.classhtml')  }}">{{ constants('arrangement_typeName.'.$row->arrangement_type.'.name')  }}</span> </div>
                              </div>
                            </div>
                          </div>
                          @endforeach
                          <button type="button" onclick="checkNsubmit()" class="btn btn-dark float-left" id="btnsubmitidfororderarrangement">Save Changes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr width="100%">
              @endif 
              <!------------------------assign html ends here....---------------->
              
              <table id="t4" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="5%">BigDaddy LR Number</th>
                    <th width="5%">Transporter LR Number</th>
                    <th width="10%">Customer Details</th>
                    <th width="20%">Location</th>
                    <th width="5%">Delivery Charge</th>
                    <th width="8%">Date</th>
                    <th width="5%">Status</th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <thead class="searchnow">
                  <tr>
                    <th width="5%" class="searchcls">BigDaddy LR Number</th>
                    <th width="5%" class="searchcls">Transporter LR Number</th>
                    <th width="10%">Customer Details</th>
                    <th width="20%">Location</th>
                    <th width="5%" class="searchcls">Delivery Charge</th>
                    <th width="8%">Date</th>
                    <th width="5%">Status</th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <tbody id="t4tbody">
                </tbody>
                <tfoot>
                  <tr>
                    <th width="5%">#</th>
                    <th width="5%">#</th>
                    <th width="10%">Customer Details</th>
                    <th width="20%">Location</th>
                    <th width="5%">Delivery Charge</th>
                    <th width="8%">Date</th>
                    <th width="5%">Status</th>
                    <th width="15%">Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            @endif
            
            @if(is_allowedHtml('roleclass_timingreports_liandultab_driver')==true)
                                <div class="tab-pane fade" id="timingreportsid" role="tabpanel" aria-labelledby="timingreports-tab">
                                <form method="post" action="{{ route('export-excel-driver-timingreportsorders') }}" enctype="multipart/form-data" id="myExcelFormtimingreports">
              @csrf
              <div class="card-header">
                <div class="row">
                  <div class="form-group col-md-6">
                    <select id="driver_arrangement_type_value" name="driver_arrangement_type_value[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
                    @foreach(constants('arrangement_typeName') as $key => $value) 
                    <option value="{{ $key }}" selected="selected">{{ $value['name'] }}</option>
                    @endforeach
                    </select>
                  </div>
                  
                  
                  <div class="form-group col-md-3">
                    <input type="text" id="timingreports_filter_global_order_date" class="form-control-sm" placeholder="Select Date" name="timingreports_filter_global_order_date" required value="" autocomplete="off">
                  </div>
                  
                  </div>
                  <div class="row">
                  <div class="form-group col-md-3"> <a class="btn btn-outline-success" id="Apply_Filter_Btn_Idtimingreports" onclick="t7DatatableRefresh()"> Apply Filter</a> <button type="submit" class="btn btn-outline-info" id="Export_Excel_Filter_Btn_Idtimingreports"> Export Excel</button> </div>
                </div>
              </div>
            </form>
            <br>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
             <table id="t7" class="table table-hover" style="width:100%">
                <thead>
                                            <tr>
                                                <th width="5%">BD LR Number</th>
                                                <th width="5%">Transporter LR Number</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                <tbody></tbody>
              </table>
              </div>
              </div>
            </div>
           @endif
            
            @if(is_allowedHtml('roleclass_delivered_liandultab_driver')==true)
            <div class="tab-pane fade" id="deliveredid" role="tabpanel" aria-labelledby="delivered-tab">
              <table id="t5" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="5%">BigDaddy LR Number</th>
                    <th width="5%">Transporter LR Number</th>
                    <th width="10%">Customer Details</th>
                    <th width="20%">Location</th>
                    <th width="5%">Delivery Charge</th>
                    <th width="8%">Date</th>
                    <th width="5%">Status</th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <thead class="searchnow">
                  <tr>
                    <th width="5%" class="searchcls">BigDaddy LR Number</th>
                    <th width="5%" class="searchcls">Transporter LR Number</th>
                    <th width="10%">Customer Details</th>
                    <th width="20%">Location</th>
                    <th width="5%" class="searchcls">Delivery Charge</th>
                    <th width="8%">Date</th>
                    <th width="5%">Status</th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <tbody id="t5tbody">
                </tbody>
                <tfoot>
                  <tr>
                    <th width="5%">#</th>
                    <th width="5%">#</th>
                    <th width="10%">Customer Details</th>
                    <th width="20%">Location</th>
                    <th width="5%">Delivery Charge</th>
                    <th width="8%">Date</th>
                    <th width="5%">Status</th>
                    <th width="15%">Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            @endif </div>
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

<!----image display Modal start---->
<div class="modal fade fadeInUp" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="editModalLabel">View</h6>
      </div>
      <form method="post" action="{{ route('update-driver-files') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <input type="hidden" name="fshort" value="" id="fshort">
          <input type="hidden" name="did" value="{{ @$one[0]->id }}" id="did">
          <input type="hidden" name="img_type_name" value="" id="img_type_name">
          <input type="hidden" name="fid" value="" id="fid">
          <input type="hidden" name="existing_img0" value="" id="existing_img0">
          <div class="col-sm-9">
            <div class="form-group required">
              <label for="name">Select File (required)</label>
              <input type="file" name="otherfile[]" class="form-control" id="otherfile" accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-warning" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!----image display  Modal end----> 

@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="{{ asset('admin_assets/plugins/drag-and-drop/dragula/dragula.min.js')}}"></script> 
<script src="{{ asset('admin_assets/plugins/drag-and-drop/dragula/custom-dragula.js')}}"></script>
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script>  
        <script src="{{ asset('admin_assets/assets/js/bootstrap-multiselect.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/daterangepicker.min.js') }}"></script> 

<!----Add Custom Js ----start-----> 
        <script type="text/javascript">
    $(document).ready(function() {
        $('.transaction_filter_multi_selectid').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
			maxHeight: 300,
        });
    });
</script> 

<script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    var imgpath = '{{ @$path }}';

    $('body').on('click', '#dfsdf543hgdf56bxv', function() {
        $('#password').val('');
        var password = makeid(randomIntFromInterval(6, 12));
        $('#password').val(password).attr("type", "text").removeAttr("disabled");
    });
    $('body').on('click', '#wanttoeditidspan', function() {
        $('.showcls24mec').removeAttr("disabled");
        $('#btnsubmitid').show();
    });


    $("#is_salary_based").change(function() {
        if (this.checked) {
            $("#salary_amount_divrowid").show();
        } else {
            $("#salary_amount_divrowid").hide();
        }
    });

    var did = '{{ @$one[0]->id }}';
    var tendayafter = '{{ $tendayafter }}';
    var t1;
    var t2;
    var t3;
    var t4;
    var t5;
	var t6;
	var t7;

    $.fn.dataTable.ext.errMode = 'none';
    errorCount = 1;
    $('#t4').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 2) {
            //showSweetAlert('something went wrong', 'please refresh page and try again', 0);
        } else {
            t4.draw();
        }
        errorCount++;
    });


/*------if did>0-----starts-----*/
if (did > 0) {

    $(document).ready(function() {
		$(".driverpageatabcls:first").click();
        
            /*------------------*/
                t4 = $('#t4').DataTable({
                    processing: true,
                    language: {
                        processing: processingHTML_Datatable,
                    },
                    stateSave: false,
                    lengthMenu: [
                        [10, 20, 30, 50, 100, 500],
                        [10, 20, 30, 50, 100, 500]
                    ],
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ],
                    serverSide: true,
                    ajax: {
                        data: {
                            "did": did
                        },
                        url: "{{ route('get-order-list-assigned-driverwise') }}",
                        type: "get"
                    },
                    aoColumnDefs: [{
                        'bSortable': false,
                        'aTargets': [-1, 0]
                    }],
                });
 /*------------------*/
 
 t1 = $('#t1').DataTable({
                processing: false,
                stateSave: true,
                order: [
                    [1, 'desc']
                ],
                serverSide: true,
                ajax: {
                    "url": "{{ route('get-driver-files') }}",
                    "type": "get",
                    "data": function(data) {
                        data.did = did;
                    },
                    'aoColumnDefs': [{
                        'bSortable': false,
                        'aTargets': [-1],
                    }],
                },
            });   
       /*---------------------*/
	   	 t5 = $('#t5').DataTable({
                    processing: true,
                    language: {
                        processing: processingHTML_Datatable,
                    },
                    stateSave: false,
                    lengthMenu: [
                        [10, 50, 100, 500],
                        [10, 50, 100, 500]
                    ],
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ],
                    serverSide: true,
                    ajax: {
                        data: {
                            "did": did
                        },
                        url: "{{ route('get-order-list-delivered-driverwise') }}",
                        type: "get"
                    },
                    aoColumnDefs: [{
                        'bSortable': false,
                        'aTargets': [-1, 0]
                    }],
                });
				
			/*---------------------*/
			 t3 = $('#t3').DataTable({
                processing: false,
                paging: true,
                searching: false,
                lengthMenu: [
                    [10, 50, 100, 500],
                    [10, 50, 100, 500]
                ],
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                serverSide: true,
                ajax: {
                    "url": "{{ route('get-driver-logs') }}",
                    "type": "get",
                    "data": function(data) {
                        data.id = did;
                    },
                    'aoColumnDefs': [{
                        'bSortable': false,
                        'aTargets': [-1],
                    }]
                },
            });
			/*---------------------*/
			
    });
	
	
/**-------------------------------**/

	
function t5DatatableRefresh(){
	if(typeof t5 !== "undefined"){ t5.draw(); } 
}
/*------------------*/

function t3DatatableRefresh(){
	if(typeof t3 !== "undefined"){ t3.draw(); } 
}  



function t1DatatableRefresh(){
	if(typeof t1 !== "undefined"){ t1.draw(); } 
}  

function t7DatatableRefresh() {
			timingreports_filter_global_order_date =  $("#timingreports_filter_global_order_date").val();
			driver_arrangement_type_value = $("#driver_arrangement_type_value").val();
			
t7 = $('#t7').DataTable({
            processing: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            paging: true,
            searching: true,
			searchDelay: 999,
			stateSave: true,
			destroy: true,
			stateDuration: 60 * 60 * 2,
            lengthMenu: [
                [10, 50, 100, 500],
                [10, 50, 100, 500]
            ],
            pageLength: 50,
            order: [
                [0, "desc"]
            ],
			"search": {"regex": true },
            ajax: {
                data: {
					driver_id: did,
                    status: 0,
					timingreports_filter_global_order_date: timingreports_filter_global_order_date,
					driver_arrangement_type_value: driver_arrangement_type_value,
                },
                url: "{{ route('get-orderlist-timingreports-driverwise') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
			createdRow: function (row, data, dataIndex) { 
			 		//$(row).find("td:eq(5)").attr('id', 't0d'+data[0]); 
			},
        });
}
/**--------------------------------------**/
}
/*------if did>0-----ends-----*/

    $('#t4 .searchnow th').each(function(colIdx) {
            var abc4 = $("#t4").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 100px;" />');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t4.column(colIdx).search() !== this.value) {
                    t4.column(colIdx).search(this.value).draw();
                }
            });
    });

    $('#t5 .searchnow th').each(function(colIdx) {
            var abc5 = $("#t5").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 100px;" />');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t5.column(colIdx).search() !== this.value) {
                    t5.column(colIdx).search(this.value).draw();
                }
            });
    });



    $('body').on('click', '.editit', function() {
        var fid = $(this).data('id');
        var fshort = $(this).data('fshort');
        var if_expiry_date = $(this).data('if_expiry_date');
        var img = $(this).data('img');
        var img_type_name = $(this).data('img_type_name');
        $("#fid").val(fid);
        $("#fshort").val(fshort);
        $("#img_type_name").val(img_type_name);
        $("#existing_img0").val(img);
    });

    $('body').on('click', '.deleteit', function() {
        var id = $(this).data('id');
        var img = $(this).data('img');
        swal({
            title: 'Are You Sure ?',
            text: "Delete This ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            padding: '-2em'
        }).then(function(result) {
            if (result.value) {

                $.ajax({
                    url: "{{ route('delete-driver-files') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        img: img,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        t1.draw();
                        swal(
                            'Done!',
                            'Deleted Successfully',
                            'success'
                        );
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });

            }
        });
    });

    $('body').on('click', '#dxtb45dcS5g7b', function() {
        ehtml = '';
        var r_val = makeid(9, 1);
        ehtml += ' <div class="row" id="m' + r_val +
            '"><div class="col-sm-4" > <div class="form-group required"> <label>Document Name </label> <input type="text" name="justnameO[]" class="form-control"  id="jnO' +
            r_val + '"  value="" required> </div> </div> <div class="col-sm-6" id="m1' + r_val +
            '"> <div class="form-group required"> <label>Other Document <span onclick="removethisid(' + r_val +
            ')" class="badge badge-danger "> Delete This</span></label> <input type="file" name="justimgO[]" accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf" class="form-control" id="dftO' +
            r_val + '" required> </div> </div>  </div>';
        $('#append-div-id').append(ehtml);
    });

    function removethisid(id) {
        x = confirm("do you want to delete this row, are you sure ?");
        if (x == true) {
            $("#m" + id).remove();
        }
    }
    </script> 
<script type="text/javascript">
        function checkNsubmit() {
            var orderArrangeIds = $('input[name="orderarrangehid[]"]').map(function () {
                return $(this).val(); 
            }).get();
            var orderArrangeOrderIds = $('input[name="orderarrangeidhid[]"]').map(function () {
                return $(this).val(); 
            }).get();
            var orderArrangeTypeIds = $('input[name="orderarrangetypehid[]"]').map(function () {
                return $(this).val(); 
            }).get();


            $.ajax({
                    url: "{{ route('check-submit-order-arrangement') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        orderArrangeIds: orderArrangeIds,
                        orderArrangeOrderIds: orderArrangeOrderIds,
                        orderArrangeTypeIds: orderArrangeTypeIds,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(e) {
                       showAlert(e);
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });
        }
        
     var select_vendor_from_select2_dropdown_id = $("#select_vendor_from_select2_dropdown_id").select2({
    		placeholder: "  Select A Vendor ",
    		width:"100%",
                ajax: {
					url: "{{ route('vendorInDropDown') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'includeid': 0, 
							'vendor_type': "{{ constants('vendor_type.Driver.key') }}",
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
 </script> 
<script type="text/javascript">

$(document).ready(function () {
	 //t6DatatableRefresh();
});


	function t6DatatableRefresh() {
			filter_global_order_date =  $("#filter_global_order_date").val();
			driver_assign_type_value = $("#driver_assign_type_value").val();

     		t6 = $('#t6').DataTable({
            processing: true,
			destroy: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            paging: true,
            searching: true,
            lengthMenu: [
                [10, 50, 100, 500],
                [10, 50, 100, 500]
            ],
            pageLength: 50,
            order: [
                [0, "desc"]
            ],
            ajax: {
                data: {
					filter_global_order_date:filter_global_order_date,
					select_driver_id:[did],
					driver_assign_type:driver_assign_type_value,
                },
                url: "{{ route('get-payroll-orders-list-driverwise') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, ]
            }],
        });
	}
</script>
<script type="text/javascript">
$(function () {
            var start =  moment().clone().startOf('month').format('YYYY-MM-DD');
            var end = moment().clone().endOf('month').format('YYYY-MM-DD');

            function cb(start, end) {
                /*$('#filter_global_transaction_date,#timingreports_filter_global_order_date').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));*/
            }

            $('#filter_global_order_date,#timingreports_filter_global_order_date').daterangepicker({
                autoUpdateInput: true,
                //maxDate: moment().endOf("day"),
                startDate: start,
                endDate: end,
                ranges: {
					'Last 365 Days': [moment().subtract(365, 'days'), moment()],
					'Last 30 Days': [moment().subtract(30, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Today': [moment(), moment()],
                    'Last 7 Days': [moment().subtract(7, 'days'), moment()],
                }, locale: {
                    format: 'YYYY-MM-DD'
                }
            }, cb);

        });
		
$(document).ready(function () {
   $('#filter_global_order_date,#timingreports_filter_global_order_date').val('');
});
</script>

<!----Add Custom Js --end-------> 
@include('admin.layout.crudhelper')
    @include('admin.layout.imageview')
    @include('admin.order.order_logs')
</body>
</html>