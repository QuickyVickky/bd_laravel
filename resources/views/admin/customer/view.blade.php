<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />
          <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/bootstrap-multiselect.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/daterangepicker.min.css') }}">
</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">

    <style>
    td span:first-child {
        margin-bottom: 5px;
    }
		.custom-select.multiselect
	 {
		padding: 10px 37px !important;
		border: 1px solid #061625 !important;
	 }
    </style>



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
                    <div class="row layout-top-spacing"> @if(count($one)>0)
                        <div class="col-lg-12 col-12 layout-spacing">
                        @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif 
                            <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
                                @if(is_allowedHtml('roleclass_view_liandultab_customer')==true)
                                <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab"
                                        href="#viewid" role="tab" aria-controls="home" aria-selected="true">View</a>
                                </li>
                                @endif
                                @if(is_allowedHtml('roleclass_order_liandultab_customer')==true)
                                <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab"
                                        href="#historyid" role="tab" aria-controls="contact"
                                        aria-selected="false">Orders</a> </li>
                                @endif
                                @if(is_allowedHtml('roleclass_addresses_liandultab_customer')==true)
                                <li class="nav-item"> <a class="nav-link" id="address-tab" data-toggle="tab"
                                        href="#addressid" role="tab" aria-controls="contact"
                                        aria-selected="false">Addresses</a> </li>
                                @endif
                                @if(is_allowedHtml('roleclass_logs_liandultab_customer')==true)
                                <li class="nav-item"> <a class="nav-link" id="log-tab" data-toggle="tab" href="#logid"
                                        role="tab" aria-controls="log" aria-selected="false">Logs</a> </li>
                                @endif
                                @if(is_allowedHtml('roleclass_invoice_liandultab_customer')==true)
                                <li class="nav-item"> <a class="nav-link" id="invoice-tab" data-toggle="tab" href="#invoicetabid"
                                        role="tab" aria-controls="log" aria-selected="false">Invoice</a> </li>
                                @endif
                                @if(is_allowedHtml('roleclass_transaction_liandultab_customer')==true)
                                <li class="nav-item"> <a class="nav-link" id="transaction-tab" data-toggle="tab"
                                        href="#historytransactionid" role="tab" aria-controls="contact"
                                        aria-selected="false">Transactions</a> </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="simpletabContent">
                                <div class="tab-pane fade show active" id="viewid" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    @if(is_allowedHtml('roleclass_view_liandultab_customer')==true)
                                    <div class="widget-content widget-content-area"> 
                                        @php
                                        $path = sendPath().'customer_files/';
                                        @endphp
                                        <form method="post" action="{{ route('submit-customer-update') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="hid" id="hid" value="{{ @$one[0]->id }}">
                                            <input type="hidden" name="customer_type" id="customer_type"
                                                value="{{ @$one[0]->customer_type }}">
                                            <div class="row">
                                                <?php 
				  $IndividualRequired = ($one[0]->customer_type=="Individual") ? "required" : "";
				  $TransporterRequired = ($one[0]->customer_type=="Transporter") ? "required" : "";
				  $BusinessRequired = ($one[0]->customer_type=="Business") ? "required" : ""; 
				  $TransporterStar = ($one[0]->customer_type=="Transporter") ? "*" : "";
				  $IndividualStar = ($one[0]->customer_type=="Individual") ? "*" : "";
				  ?>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Full Name {{ $IndividualStar }}</label>
                                                        <span id="wanttoeditidspan"
                                                            class="badge badge-warning float-right admid-select-color">
                                                            want to edit ? </span>
                                                        <input type="text" name="fullname"
                                                            class="form-control showcls24mec" id="fullname"
                                                            placeholder="Enter Full Name"
                                                            value="{{ @$one[0]->fullname }}" {{ $IndividualRequired}}
                                                            disabled>
                                                    </div>
                                                </div>
                                                @if(($one[0]->customer_type!="Individual"))
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Business Name *</label>
                                                        <input type="text" name="business_name"
                                                            class="form-control showcls24mec" id="business_name"
                                                            placeholder="Enter Business Name"
                                                            value="{{ @$one[0]->business_name }}" {{ ($one[0]->
                        fullname!='') ? 'required' : ''}} disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Business Type *</label>
                                                        <input type="text" name="business_type"
                                                            class="form-control showcls24mec" id="business_type"
                                                            placeholder="Enter Business Type"
                                                            value="{{ @$one[0]->business_type }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Ownership *</label>
                                                        <input type="text" name="ownership"
                                                            class="form-control showcls24mec" id="ownership"
                                                            placeholder="Enter Ownership"
                                                            value="{{ @$one[0]->ownership }}" disabled>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(($one[0]->customer_type=="Transporter"))
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Transporter Name *</label>
                                                        <input type="text" name="transporter_name"
                                                            class="form-control showcls24mec" id="transporter_name"
                                                            placeholder="Enter Transporter Name"
                                                            value="{{ @$one[0]->transporter_name }}" required disabled>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">PAN Number {{ $IndividualStar }} </label>
                                                        <input type="text" name="pan_no"
                                                            class="form-control showcls24mec" id="pan_no"
                                                            placeholder="Enter PAN No" value="{{ @$one[0]->pan_no }}"
                                                            {{ $IndividualRequired}} maxlength="10" minlength="10"
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.keyCode === 8;"
                                                            disabled {{ ($one[0]->
                        pan_no!='') ? 'required' : ''}}>
                                                    </div>
                                                </div>
                                                @if(($one[0]->customer_type!="Individual"))
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">GST Number {{  $TransporterStar }}</label>
                                                        <input type="text" name="GST_number"
                                                            class="form-control showcls24mec" id="GST_number"
                                                            placeholder="Enter GST Number"
                                                            value="{{ @$one[0]->GST_number }}"
                                                            {{ $TransporterRequired }} maxlength="15" minlength="15"
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.keyCode === 8;"
                                                            disabled {{ ($one[0]->
                        GST_number!='') ? 'required' : ''}}>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Email (optional)</label>
                                                        <input type="email" name="email"
                                                            class="form-control showcls24mec" id="email"
                                                            placeholder="Enter Email Address"
                                                            value="{{ @$one[0]->email }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Mobile Number (optional)</label>
                                                        <input type="text" name="mobile"
                                                            class="form-control showcls24mec" id="mobile"
                                                            placeholder="Enter Mobile Number"
                                                            value="{{ @$one[0]->mobile }}"
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;"
                                                            maxlength="10" minlength="10" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="password">Password (optional)</label>
                                                        <span id="dfsdf543hgdf56bxv"
                                                            class="badge badge-primary float-right rounded bs-tooltip admid-select-color"
                                                            title="Don Not Change if You do not want, otherwise Password will be changed.">
                                                            change Password </span>
                                                        <input type="password" name="password" class="form-control"
                                                            id="password" placeholder="Enter Password" maxlength="25"
                                                            value="1234567" disabled>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    <div class="form-group required">
                                                        <label for="name">Current Wallet Credit</label>
                                                        <input type="text" class="form-control" id="wallet_credit" value="{{ @$one[0]->wallet_credit }}" disabled>
                                                    </div>
                                                </div>
                                                
                                                
                                                <input type="hidden" name="existing_img" id="existing_img" value="{{ @$one[0]->profile_pic }}">
                                                <!--
                    <div class="col-sm-9">
                      <div class="form-group required">
                        <label for="name">Profile Pic</label>
                        <input type="file" name="profile_pic" class="form-control" id="profile_pic" accept="image/png,image/jpg,image/jpeg,image/PNG,image/JPG,image/JPEG," onChange="document.getElementById('img_src').src = window.URL.createObjectURL(this.files[0]);">
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required"> <img src="" id="img_src" class="img-responsive" style="max-width: 140px; max-height: 54px;" /> </div>
                    </div>
                    @if(isset($one[0]->mobile) && $one[0]->profile_pic!='') <img src='{{ @$path.@$one[0]->profile_pic}}' class="rounded-circle profile-img" alt="img" width="55px" height="55px"> @endif
                    -->
                                                <div class="col-sm-12">
                                                    <div class="form-group required">
                                                    <label>Customer Status *</label>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="status0" name="status" disabled=""
                                                                class="custom-control-input showcls24mec" value="0" {{ (!isset($one[0]->
                          is_active)) ? 'checked' : '' }} {{ (isset($one[0]->is_active) && ($one[0]->is_active==0)) ? 'checked' : '' }}>
                                                            <label class="custom-control-label showcls24mec"
                                                                for="status0">Active</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="status1" name="status" disabled=""
                                                                class="custom-control-input showcls24mec" value="1" {{ (isset($one[0]->
                          is_active) && ($one[0]->is_active==1)) ? 'checked' : '' }}>
                                                            <label class="custom-control-label showcls24mec"
                                                                for="status1">Deactive</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group required">
                                                    <label>Customer Payment Bill Type *</label>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="user_paymentbill_type0" name="user_paymentbill_type" disabled=""
                                                                class="custom-control-input showcls24mec" value="0" {{ (!isset($one[0]->
                          user_paymentbill_type)) ? 'checked' : '' }} {{ (isset($one[0]->user_paymentbill_type) && ($one[0]->user_paymentbill_type==0)) ? 'checked' : '' }}>
                                                            <label class="custom-control-label showcls24mec"
                                                                for="user_paymentbill_type0">To Pay</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="user_paymentbill_type1" name="user_paymentbill_type" disabled=""
                                                                class="custom-control-input showcls24mec" value="1" {{ (isset($one[0]->
                          user_paymentbill_type) && ($one[0]->user_paymentbill_type==1)) ? 'checked' : '' }}>
                                                            <label class="custom-control-label showcls24mec" for="user_paymentbill_type1">To Be Billed</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr style="width:100%">
                                            <button type="submit" class="btn btn-info float-right" id="btnsubmitid"
                                                style="display:none">Submit</button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                @if(is_allowedHtml('roleclass_order_liandultab_customer')==true)

                                <div class="tab-pane fade" id="historyid" role="tabpanel" aria-labelledby="contact-tab">
                                    <a class="btn btn-warning m-2 btn-rounded" href="javascript:void(0)"
                                        onClick="printInvoiceMultiple()"> Create New Invoice</a>
                                    <input type="hidden" name="ALL" id="ALL" value="">

                                    <table id="t1" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">BigDaddy LR Number <input type="checkbox"
                                                        id="selectAllid" onClick="selectAll(this)"></th>
                                                <th width="5%">Transporter LR Number</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
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
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="t1tbody">
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
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </tfoot>


                                    </table>
                                </div>
                                @endif
                                @if(is_allowedHtml('roleclass_addresses_liandultab_customer')==true)
                                <div class="tab-pane fade" id="addressid" role="tabpanel" aria-labelledby="address-tab">
                                    <h5>{{ $control }} Address</h5>
                                    <button type="button" class="btn btn-info btn-sm float-right addbtn">Add
                                        Address</button>
                                    <table id="t3" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="3%">#</th>
                                                <th>Address</th>
                                                <th>Pincode</th>
                                                <th>City</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                @if(is_allowedHtml('roleclass_logs_liandultab_customer')==true)
                                <div class="tab-pane fade" id="logid" role="tabpanel" aria-labelledby="contact-tab">
                                    <table id="t4" class="table table-hover" style="width:100%">
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
                                @if(is_allowedHtml('roleclass_invoice_liandultab_customer')==true)
                                <div class="tab-pane fade" id="invoicetabid" role="tabpanel" aria-labelledby="invoice-tab">
                                <div class="row" style="align-items: center;">
                                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <h4></h4>
                                    </div>
                                
                                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="col-sm-2">
                                            <select class="form-control-sm " name="payment_status" id="payment_status">
                                                <option value="" selected> All</option>
                                                @foreach($payment_status as $row)
                                                <option value="{{ $row->short }}"> {{ $row->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    
                                    </div>

                                    <table id="t5" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#Invoice</th>
                                            <th>Date</th>
                                            <th>Contains</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <thead class="searchnow">
                                        <tr>
                                            <th class="searchcls">#Invoice</th>
                                            <th>Date</th>
                                            <th>Contains</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#Invoice</th>
                                            <th>Date</th>
                                            <th>Contains</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                                @endif
                                @if(is_allowedHtml('roleclass_transaction_liandultab_customer')==true)
                                <div class="tab-pane fade" id="historytransactionid" role="tabpanel" aria-labelledby="transaction-tab">
                                <form method="post" action="{{ route('transactions_export_excel') }}" enctype="multipart/form-data" id="myExcelForm">
              @csrf
              <div class="card-header">
               <div class="row">
               <div class="col-md-3">
                <label>Banks</label>
                </div>
                <div class="col-md-5">
                <label>Category</label>
                </div>
                <div class="col-md-4">
                <label>Type</label>
                </div>
                </div>
                <div class="row">
                <div class="form-group col-md-3">
                    <select id="filter_global_accountid_from" name="filter_global_accountid_from[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
@foreach($accountBanksInDropDown as $ab)
<option value="{{ $ab->id }}" selected="selected"> {{ $ab->name }}</option>
@endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-5">
                    <select id="filter_global_transaction_subcategory_id" name="filter_global_transaction_subcategory_id[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
@foreach($transactionSubCategoryInDropDown as $tsb)
<option value="{{ $tsb->id }}" selected="selected"> {{ $tsb->name }}</option>
@endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-4">
                    <select id="filter_global_transaction_type" name="filter_global_transaction_type[]" class="form-control transaction_filter_multi_selectid" multiple="multiple">
						@foreach(constants('transaction_type_list') as $row)
                        <option value="{{ $row['key'] }}" selected> {{ $row['name2'] }} / {{ $row['name3'] }}</option>
                       		@endforeach
                            </select>
                  </div>
                  <input type="hidden" name="select_customer_from_select2_dropdown_id[]" value="{{ $one[0]->id }}" >
                  <div class="form-group col-md-3">
                    <input type="text" id="filter_global_transaction_date" class="form-control-sm" placeholder="Select Date" name="filter_global_transaction_date" required value="" autocomplete="off">
                  </div>
                  
                  </div>
                  <div class="row">
                  <div class="form-group col-md-3"> <a class="btn btn-outline-success" id="Apply_Filter_Btn_Id" onclick="getAllTransactionsData()"> Apply Filter</a> <a class="btn btn-outline-info" id="Export_Excel_Filter_Btn_Id" onclick="$('#myExcelForm').submit()"> Export Excel</a> </div>
                </div>
              </div>
            </form>
            <br>
             <table id="t6" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th >Amount</th>
                    <th>Date</th>
                    <th>Category & Details</th>
                    <th>Type</th>
                    <th>Bill No</th>
                    <th width="20%">Description</th>
                    <th width="20%">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th data-search="1">Amount</th>
                    <th>Date</th>
                    <th>Category & Details</th>
                    <th>Type</th>
                    <th>Bill No</th>
                    <th width="20%">Description</th>
                    <th width="20%">Action</th>
                  </tr>
                </tfoot>
              </table>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @include('admin.layout.footer')
            </div>
            <!--  END CONTENT AREA  -->
        </div>
        <!-- END MAIN CONTAINER -->

        <!----address display Modal start---->
        <div class="modal fade fadeInUp" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="editModalLabel">Edit</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <form id="addressform">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group required">
                                        <label for="name">Pincode</label>
                                        <input type="text" name="pincode"
                                            class="form-control showcls24mec fetchdatabypincodecls" id="pincode1"
                                            data-id="1" placeholder="Pincode" value="" maxlength="6" minlength="6"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group required">
                                        <label for="name">Country *</label>
                                        <input type="text" name="country" class="form-control" id="country1"
                                            placeholder="Country" value="India" required>
                                    </div>
                                </div>
                                <input type="hidden" name="addresshid" id="addresshid" value="0">
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label for="name">State *</label>
                                        <input type="text" name="state" class="form-control showcls24mec" id="state1"
                                            placeholder="State" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label for="name">City</label>
                                        <input type="text" name="city" class="form-control showcls24mec" id="city1"
                                            placeholder="City" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group required">
                                        <label for="name"> Type</label>
                                        <select class="form-control" name="address_type" id="address_type1" required="">

                                            @foreach($address_type as $row)

                                            <option value="{{ $row->short }}"> {{ $row->name }}</option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group  required">
                                        <label for="description">Address</label>
                                        <textarea name="address" class="form-control showcls24mec" rows="2"
                                            id="address1" placeholder="Enter Address" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group required">
                                        <label for="name">Landmark</label>
                                        <input type="text" name="landmark" class="form-control showcls24mec"
                                            id="landmark1" placeholder="Landmark" value="">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group required">
                                        <label for="name">Contact Person Name </label>
                                        <input type="text" name="contact_person_name" class="form-control showcls24mec"
                                            id="contact_person_name1" placeholder="Contact Person Name" value=""
                                            maxlength="140">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group required">
                                        <label for="name">Contact Person Number </label>
                                        <input type="text" name="contact_person_number"
                                            class="form-control showcls24mec" id="contact_person_number1"
                                            placeholder="Contact Person Number" value="" maxlength="13">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group required">
                                        <label for="name">Transporter Name </label>
                                        <input type="text" name="transporter_name" class="form-control showcls24mec"
                                            id="transporter_name1" placeholder="Transporter Name" value=""
                                            maxlength="140">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label>Latitude *</label>
                                        <input type="text" name="latitude" class="form-control  showcls24mec"
                                            id="address_latitude1" placeholder="" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label>Longitude *</label>
                                        <input type="text" name="longitude" class="form-control showcls24mec"
                                            id="address_longitude1" placeholder="" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="map1" style="height:255px;"></div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group required">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="address_status0" name="address_status"
                                                class="custom-control-input address_status" value="0" checked>
                                            <label class="custom-control-label" for="address_status0">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="address_status1" name="address_status"
                                                class="custom-control-input address_status" value="1"
                                                onClick="uncheck()">
                                            <label class="custom-control-label" for="address_status1">Deactive</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group required">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input isdefaultcls"
                                                name="is_default" id="customCheck1" value="1">
                                            <label class="custom-control-label" for="customCheck1">Check This to Make
                                                Default Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>
                                    Close</button>
                                <button type="button" class="btn btn-primary admin-button-add-vnew"
                                    id="btn_submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!----image display  Modal end---->

        <!----order logs Modal start---->
        <div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="logsexampleModalLabel">Order Logs</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <div class="modal-body">
                        <table id="t2" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Logs</th>
                                    <th>DateTime</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!----order logs  Modal end---->

        <!----print invoice modal start---->
        <div class="modal fade fadeInUp" id="piModal" tabindex="-1" role="dialog" aria-labelledby="piModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="piModalLabel">View</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <form method="post" action="{{ route('download-order-invoice') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="orderId" value="" id="iodhid" required>
                            <input type="hidden" name="user_idhid" value="{{ @$one[0]->id }}" id="user_idhid" required>
                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <label for="name">Invoice Number</label>
                                    <input type="text" name="invoice_number"
                                        class="form-control invoice_numbercls allownumber" maxlength="20"
                                        id="invoice_number" placeholder="Enter Invoice Number" value="" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <label>Invoice Date </label>
                                    <input type="text" name="invoice_date" class="form-control datepicker"
                                        id="invoice_date" value="{{ date('d-m-Y')}}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>
                                    Close</button>
                                <button type="submit" class="btn btn-primary downloadinvoicebtncls">Download
                                    Invoice</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!----print invoice modal end---->

         <!----order payment confirm display Modal starts---->
    <div class="modal fade fadeInUp" id="pModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="">Mark As Payment Paid.</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form id="paymentregisterformid">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Payment Date *</label>
                                    <br>
                                    <input type="text" name="payment_datetime" class="form-control datepicker"
                                        data-date-format="dd-mm-yyyy" id="payment_datetime" placeholder="Payment Date"
                                        value="{{ date('d-m-Y') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Payment Type *</label>
                                    <br>
                                    <select class="form-control payment_typecls" name="payment_type" id="payment_type">
                                        @foreach(constants('payment_type_manual') as $row)
                                        <option value="{{ $row['short'] }}"> {{ $row['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6" id="if_cheque_numberrowid" style="display: none;">
                                <div class="form-group required">
                                    <label>Cheque Number</label>
                                    <br>
                                    <input type="text" name="if_cheque_number" class="form-control"
                                        id="if_cheque_number" placeholder="Cheque Number" maxlength="20" required>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Payment Discount</label>
                                    <br>
                                    <input type="text" name="payment_discount" class="form-control allowdecimal"
                                        id="payment_discount" placeholder="Payment Discount" maxlength="10" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Transaction Id</label>
                                    <br>
                                    <input type="text" name="if_transaction_number" class="form-control"
                                        id="if_transaction_number" placeholder="Transaction Id" maxlength="25">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <label>Payment Comment</label>
                                    <br>
                                    <input type="text" name="payment_comment" class="form-control" id="payment_comment"
                                        placeholder="Comment" maxlength="200">
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            <button type="button" class="btn btn-primary" id="btn_submit_payment_status">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!----order payment confirm display Modal ends---->


        @include('admin.layout.js')
        <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/jquery-ui.js')}}"></script>
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/jquery-ui.css')}}">
<script src="{{ asset('admin_assets/assets/js/bootstrap-multiselect.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/daterangepicker.min.js') }}"></script> 

        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ constants('googleAPIKey') }}&callback=initMap&libraries=&v=weekly"
            defer></script>

        <!----Add Custom Js ----start----->
        <script type="text/javascript">
        function initMap(mapid = 1, latitude = 21.170240, longitude = 72.831062) {
            const myLatlng_drop = {
                lat: parseFloat(latitude),
                lng: parseFloat(longitude)
            };
            const map_address = new google.maps.Map(document.getElementById("map" + mapid), {
                zoom: 11,
                center: myLatlng_drop,
            });
            // Create the initial InfoWindow.
            let infoWindow = new google.maps.InfoWindow({
                content: "Click the map to get latitude / longitude !",
                position: myLatlng_drop,
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
                    addressdata = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
                );
                j = JSON.parse(addressdata);
                $("#address_latitude" + mapid).val(j.lat);
                $("#address_longitude" + mapid).val(j.lng);
                infoWindow.open(map_address);
            });

        }
        </script>
        <script type="text/javascript">
        $(function() {
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy"
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

        var tbl = '{{ $tbl }}';
        var uid = '{{ @$one[0]->id }}';
        var isdelete = 'N';
        var t1;
        var t2;
        var t3;
        var t4;
        var t5;
		var t6;
        cheque_payment_type = "{{ constants('payment_type_manual.CHQ.short') }}";
        $.fn.dataTable.ext.errMode = 'none';
		errorCount = 1;
		$('#t1').on('error.dt', function(e, settings, techNote, message) {
			if (errorCount > 3) {
				showSweetAlert('something went wrong', 'please refresh page and try again', 0);
			} else {
				t1.draw();
				t6.draw();
			}
			errorCount++;
		});



        $(document).ready(function() {
            invoiceDatatable();

            if (uid != '' && uid > 0) {
                /*-----------------*/
                t1 = $('#t1').DataTable({
                    processing: true,
                    language: {
                        processing: processingHTML_Datatable,
                    },
                    stateSave: true,
                    lengthMenu: [
                        [10, 50, 100, 500],
                        [10, 50, 100, 500]
                    ],
                    pageLength: 10,
                    order: [
                        [0, 'asc']
                    ],
                    serverSide: true,
					searching: true,
					searchDelay: 999,
                    ajax: {
                        data: {
                            "uid": uid
                        },
                        url: "{{ route('get-order-list-customerwise') }}",
                        type: "get"
                    },
                    aoColumnDefs: [{
                        'bSortable': false,
                        'aTargets': [-1, 0]
                    }],
                });
                /*-----------------*/

                t3 = $('#t3').DataTable({
                    processing: false,
                    lengthMenu: [
                        [10, 50, 100, 500],
                        [10, 50, 100, 500]
                    ],
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ],
                    serverSide: true,
					searching: true,
					searchDelay: 999,
                    ajax: {
                        data: {
                            "uid": uid
                        },
                        url: "{{ route('get-address-list-customerwise') }}",
                        type: "get"
                    },
                    aoColumnDefs: [{
                        'bSortable': false,
                        'aTargets': [-1]
                    }],
                });
                /*-------------*/
                /*-----------------*/
                /*------------------*/
                t4 = $('#t4').DataTable({
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
					searching: true,
					searchDelay: 999,
                    ajax: {
                        data: {
                            "id": uid
                        },
                        url: "{{ route('get-customer-logs') }}",
                        type: "get"
                    },
                    aoColumnDefs: [{
                        'bSortable': false,
                        'aTargets': [-1]
                    }],
                });
                /*------------------*/
                /*-------------*/

            }
        });


        $('#t1 .searchnow th').each(function(colIdx) {
            var abc = $("#t1").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 100px;" />');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t1.column(colIdx).search() !== this.value) {
                    console.log(this.value);
                    t1.column(colIdx).search(this.value).draw();
                }
            });
        });

        $('body').on('click', '.downloadinvoicebtncls', function() {
            setTimeout(function() {  t1.draw();  t5.draw();  }, 2789);
        });

        function invoiceDatatable() {
			
			payment_status = $("#payment_status").val();

					t5 = $('#t5').DataTable({
						processing: true,
						language: {
							processing: processingHTML_Datatable,
						},
						serverSide: true,
						destroy: true,
						paging: true,
						searching: true,
						searchDelay: 999,
						lengthMenu: [
							[10, 50, 100, 500, 1000],
							[10, 50, 100, 500, 1000]
						],
						pageLength: 50,
						order: [
							[1, "desc"]
						],
						ajax: {
							data: {
								uid: uid,
								payment_status: payment_status,
							},
							url: "{{ route('get-invoice-list-customerwise') }}",
							type: "get"
						},
						aoColumnDefs: [{
							'bSortable': false,
							'aTargets': [-1, -2, ]
						}],
					});
					
					}
					
					$('body').on('change', '#payment_status', function() {
        					invoiceDatatable();
    				});


$('#t5 .searchnow th').each(function(colIdx) {
            var abc = $("#t5").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 125px;"/>');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t5.column(colIdx).search() !== this.value) {
                    t5.column(colIdx).search(this.value).draw();
                }
            });
    });

    $('body').on('change', '.payment_typecls', function() {
        pt = $("#payment_type").val();
        if (cheque_payment_type == pt) {
            $("#if_cheque_numberrowid").show();
        } else {
            $("#if_cheque_numberrowid").hide();
        }
    });

    $('body').on('click', '.vieworderit', function() {
        var invid = $(this).data('id');
        $.ajax({
            url: "{{ route('get-orderhtml-by-invoice') }}",
            data: {
                _token: '{{ csrf_token() }}',
                id: invid,
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                $('.divremovecls').remove();
                $('#invtr' + invid).after(data.ehtml);
            },
            error: function() {
                showSweetAlert('Something Went Wrong!', 'please refresh page and try again', 0);
            }
        });

    });


    $('body').on('click', '.invoicepaymentregisterit', function() {
        $('#paymentregisterformid')[0].reset();
        var invoice_id = $(this).data('id');
        $("#pModal").modal("show");
        pt = $("#payment_type").val();

        if (cheque_payment_type == pt) {
            $("#if_cheque_numberrowid").show();
        } else {
            $("#if_cheque_numberrowid").hide();
        }

        $('body').on('click', '#btn_submit_payment_status', function() {
            payment_datetime = $("#payment_datetime").val();
            payment_type = $("#payment_type").val();
            if_cheque_number = $("#if_cheque_number").val();
            payment_discount = $("#payment_discount").val();
            if_transaction_number = $("#if_transaction_number").val();
            payment_comment = $("#payment_comment").val();


            if (payment_datetime.length != 10 || payment_type == '') {
                showSweetAlert('Invalid Date.', 0);
                return false;
            }

            $("#pModal").modal("hide");

            $.ajax({
                url: "{{ route('mark-as-payment-paid-invoice') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    invoice_id: invoice_id,
                    status: 1,
                    payment_datetime: payment_datetime,
                    payment_type: payment_type,
                    if_cheque_number: if_cheque_number,
                    payment_discount: payment_discount,
                    if_transaction_number: if_transaction_number,
                    payment_comment: payment_comment,
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    t5.draw();
                    if (data.success == 1) {
                        swal(
                            'Completed!',
                            data.msg,
                            'success'
                        );

                    } else {
                        swal(
                            'Wrong!',
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
        });
    });




    $('body').on('click', '.delete_invoice_btnidcls', function() {
        dataid = $(this).data('id');
        inv = $(this).data('inv');

        if (dataid == '' || inv == '') {
            return false;
        }


        swal({
            title: 'Are You Sure ?',
            text: 'Delete This Invoice.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            padding: '-2em'
        }).then(function(result) {
            if (result.value) {

                $.ajax({
                    url: "{{ route('delete-order-invoice') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: dataid,
                        invoice_number: inv,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        t5.draw();
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });

            }
        });
    });

  
  
    function seeLogs(oid) {
            t2 = $('#t2').DataTable({
                processing: false,
                destroy: true,
                paging: true,
                searching: false,
                lengthMenu: [
                    [10, 50, 100],
                    [10, 50, 100]
                ],
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                serverSide: true,

                ajax: {
                    data: {
                        "oid": oid
                    },
                    url: "{{ route('get-order-logs') }}",
                    type: "get"
                },
                aoColumnDefs: [{
                    'bSortable': false,
                    'aTargets': [-1]
                }],
            });
        }

        function uncheck() {
            $('#customCheck1').prop("checked", false);
        }


        $('body').on('click', '.addbtn', function() {
            initMap();
            $('#addresshid').val(0);
            $('.showcls24mec').val('');
            $('#editModalLabel').html('Add');
            $('#customCheck1').prop("checked", false);
            $("#editModal").modal('show');

        });

        $('body').on('click', '.editit', function() {
            var id = $(this).data('id');
            $('#addresshid').val(id);
            $('#editModalLabel').html('Edit');

            $.ajax({
                url: "{{ route('edit-useraddress-data') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if (data[0].is_active == 1) {
                        $('#address_status1').prop("checked", true);
                    } else {
                        $('#address_status0').prop("checked", true);
                    }
                    $('#country1').val(data[0].country);
                    $('#state1').val(data[0].state);
                    $('#city1').val(data[0].city);
                    $('#pincode1').val(data[0].pincode);
                    $('textarea#address1').val(data[0].address);
                    $('#landmark1').val(data[0].landmark);
                    $('#customCheck1').prop("checked", false);
                    $("#address_latitude1").val(data[0].latitude);
                    $("#address_longitude1").val(data[0].longitude);
                    $("#contact_person_name1").val(data[0].contact_person_name);
                    $("#contact_person_number1").val(data[0].contact_person_number);
                    $("#transporter_name1").val(data[0].transporter_name);
                    if (data[0].latitude != '' && data[0].latitude != '') {
                        initMap(1, data[0].latitude, data[0].longitude);
                    }
                    if (data[0].is_default == 1) {
                        $('#customCheck1').prop("checked", true);
                    }
                    $('#address_type1').val(data[0].address_type);
                    $("#editModal").modal('show');
                },
                error: function() {
                    showSweetAlert('Something Went Wrong!', 'please refresh page and try again', 0);
                }
            });
        });


        $('body').on('click', '#btn_submit', function() {
            var id = $('#addresshid').val();
            var userid = $('#hid').val();
            var country = $('#country1').val();
            var address_status = $('input[name=address_status]:checked').val();
            var state = $('#state1').val();
            var city = $('#city1').val();
            var pincode = $('#pincode1').val();
            var address_type1 = $('#address_type1').val();
            var address = $('textarea#address1').val();
            var landmark = $('#landmark1').val();
            if ($('#customCheck1').prop("checked") == true) {
                var is_default = 1;
            } else {
                var is_default = 0;
            }
            var longitude = $('#address_longitude1').val();
            var latitude = $('#address_latitude1').val();
            var contact_person_name = $("#contact_person_name1").val();
            var contact_person_number = $("#contact_person_number1").val();
            var transporter_name = $("#transporter_name1").val();


            if (id == '' || city == '' || userid < 1) {
                showSweetAlert('Something Went Wrong!', 'please refresh page and try again', 0);
                return false;
            }

            $.ajax({
                url: "{{ route('add-update-useraddress') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    hid: id,
                    user_id: userid,
                    country: country,
                    address_type: address_type1,
                    state: state,
                    city: city,
                    pincode: pincode,
                    address: address,
                    status: address_status,
                    landmark: landmark,
                    is_default: is_default,
                    longitude: longitude,
                    latitude: latitude,
                    contact_person_name: contact_person_name,
                    contact_person_number: contact_person_number,
                    transporter_name: transporter_name,
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    t3.draw(false);
                    $("#editModal").modal('hide');
                },
                error: function() {
                    showSweetAlert('Something Went Wrong!', 'please refresh page and try again', 0);
                }
            });
        });


        /*-------------------pincode ----------------------*/
        $('body').on('keyup', '.fetchdatabypincodecls', function() {
            var pincode = $(this).val();
            if (pincode.length == 6) {
                var idx = $(this).data('id');
                /*-----*/
                $.ajax({
                    url: "{{ route('gst-pincode-data-fill') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        'pincode': pincode,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(e) {
                        if (e.success == false) {
                            showToastAlert(e.msg);
                        } else {
                            $('#city' + idx).val(e.data.District);
                            $('#state' + idx).val(e.data.State);
                            $('#country' + idx).val(e.data.Country);
                        }
                        return false;
                    }
                });
                /*------*/
            }
        });

        /*-------------------pincode ----------------------*/
        </script>

        <script type="text/javascript">
        function selectAll(source) {

            checkboxes = document.getElementsByName('selectlist[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
            if ($("#selectAllid").prop('checked') == true) {
                var ck = [];
                $.each($("input[name='selectlist[]']:checked"), function() {
                    ck.push($(this).data('id'));
                    $('#ALL').val(ck);
                });
            } else {
                $('#ALL').val('');
            }

        }

        function printInvoiceMultiple() {
            var ck = [];
            $.each($("input[name='selectlist[]']:checked"), function() {
                ck.push($(this).data('id'));
            });
            len = ck.length;

            if (len == 0) {
                alert("none selected");
                return false;
            }
            var v = ck.toString();
            $('#piModalLabel').html('Invoice');
            $("#piModal").modal('show');
            $("#iodhid").val(v);
            getLatestInvoiceNumber();
        }


        function getLatestInvoiceNumber() {
            $.ajax({
                url: "{{ route('getlatestinvoicenumber') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                type: 'get',
                dataType: 'json',
                success: function(e) {
                    $('.invoice_numbercls').val(e.invoice_number);
                },
                error: function() {
                    return false;
                }
            });
        }
        </script>
        
     @if(isset($one[0]->id))
        <script type="text/javascript">
    $(document).ready(function() {
        $('.transaction_filter_multi_selectid').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
			maxHeight: 300,
        });
		getAllTransactionsData();
    });


	function getAllTransactionsData() {
 			filter_global_accountid_from = $("#filter_global_accountid_from").val();
			filter_global_transaction_subcategory_id = $("#filter_global_transaction_subcategory_id").val();
			filter_global_transaction_date =  $("#filter_global_transaction_date").val();
			filter_global_transaction_type = $("#filter_global_transaction_type").val();

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
                    accountid_from:filter_global_accountid_from,
					transaction_subcategory_id:filter_global_transaction_subcategory_id,
					transaction_date:filter_global_transaction_date,
					transaction_type:filter_global_transaction_type,
					select_customer_id:uid,
                },
                url: "{{ route('get-transaction-list-customerwise') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
        });
	}


$(function () {
            var start =  moment().clone().startOf('month').format('YYYY-MM-DD');
            var end = moment().clone().endOf('month').format('YYYY-MM-DD');

            function cb(start, end) {
                /*$('#filter_global_transaction_date').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));*/
            }

            $('#filter_global_transaction_date').daterangepicker({
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
   $('#filter_global_transaction_date').val('');
});


</script>
@endif

        <!----Add Custom Js --end------->
        @include('admin.layout.crudhelper')
    </body>

</html>