<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin_assets/assets/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">
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
            @if(count($order)>0)
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-lg-12 col-12 layout-spacing">
                        <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab"
                                    href="#viewid" role="tab" aria-controls="home" aria-selected="true">View</a> </li>
                            <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab"
                                    href="#historyid" role="tab" aria-controls="contact"
                                    aria-selected="false">History</a> </li>
                        </ul>
                        <div class="tab-content" id="simpletabContent">
                            <div class="tab-pane fade show active" id="viewid" role="tabpanel"
                                aria-labelledby="home-tab">

                                @if(Session::get("msg")!='')
                                <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-x close" data-dismiss="alert">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg></button>
                                    {{ Session::get("msg") }}
                                </div>
                                @endif




                                <div class="widget-content widget-content-area">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4>BigDaddy LR Number :
                                                    <strong>{{ $order[0]->bigdaddy_lr_number }}</strong>
                                                    @if(in_array($order[0]->status,constants('orderEditableStatus')) || Session::get("adminrole")==constants('admins_type.SuperAdmin'))
                                                    <a href="{{ route('edit-order').'/'.$order[0]->id }}" class="btn btn-info btn-sm">Edit</a>
                                                    @endif
                                                    <?php
                      $totalCost = $order[0]->final_cost + $order[0]->min_order_value_charge + $order[0]->redeliver_charge - $order[0]->discount;
                      ?>
                                                    <strong class="float-right">Total Payable Amount : {{ $order[0]->total_payable_amount  }}
                                                        Rs</strong>
                                                </h4>
                                            </div>
                                            <hr style="width:100%">
                                            <div class="col-sm-12">
                                            <p><span class="badge badge-{{ $order[0]->shostclasshtml }}">Current Order Status : {{ $order[0]->shostdetails }}</span></p>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label for="name">Business Name <a target="_blank"
                                                            href="{{ route('view-customer') }}/{{ $order[0]->user_id }}"><span
                                                                class="badge badge-primary admid-select-color">view customer</span></a></label>

                                                    <input type="text" name="business_name" class="form-control "
                                                        id="business_name" value="{{ $user[0]->business_name }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>GST Number </label>
                                                    <input type="text" name="GST_number" class="form-control"
                                                        id="GST_number" value="{{ $user[0]->GST_number }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label for="name">Full Name </label>
                                                    <input type="text" name="fullname" class="form-control "
                                                        id="fullname" value="{{ @$user[0]->fullname }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>PAN Number </label>
                                                    <input type="text" name="pan_no" class="form-control " id="pan_no"
                                                        value="{{ $user[0]->pan_no }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Email </label>
                                                    <input type="email" name="email" class="form-control " id="email"
                                                        value="{{ $user[0]->email }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Mobile </label>
                                                    <input type="text" name="mobile" class="form-control " id="mobile"
                                                        value="{{ $user[0]->mobile }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-6" style="border-right: 1px solid #f1f2f3">
                                                <h4>Pickup Details</h4>
                                                <div class="" id="">
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Contact Person Name </label>
                                                            <input type="text" name="contact_person_name"
                                                                class="form-control " id="contact_person_name"
                                                                value="{{ $order[0]->contact_person_name }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Contact Person Phone Number </label>
                                                            <input type="text" name="contact_person_phone_number"
                                                                class="form-control " id="contact_person_phone_number"
                                                                value="{{ $order[0]->contact_person_phone_number }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Transporter Name </label>
                                                            <input type="text" name="transporter_name"
                                                                class="form-control " id="transporter_name"
                                                                value="{{ $order[0]->transporter_name }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group  required">
                                                            <label for="description">Pickup Location</label>
                                                            <textarea name="pickup_address"
                                                                class="form-control showcls24mec" rows="5"
                                                                id="pickup_address"
                                                                readonly>{{ $order[0]->pickup_location }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Other </label>
                                                            <input type="text" name="other_field_pickup"
                                                                class="form-control " id="other_field_pickup"
                                                                value="{{ @$order[0]->other_field_pickup }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4>Drop Details</h4>
                                                <div class="" id="">
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Contact Person Name </label>
                                                            <input type="text" name="contact_person_name_drop"
                                                                class="form-control " id="contact_person_name_drop"
                                                                value="{{ $order[0]->contact_person_name_drop }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Contact Person Phone Number </label>
                                                            <input type="text" name="contact_person_phone_number_drop"
                                                                class="form-control "
                                                                id="contact_person_phone_number_drop"
                                                                value="{{ $order[0]->contact_person_phone_number_drop }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Transporter Name </label>
                                                            <input type="text" name="transporter_name_drop"
                                                                class="form-control " id="transporter_name_drop"
                                                                value="{{ $order[0]->transporter_name_drop }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group  required">
                                                            <label for="description">Drop Location</label>
                                                            <textarea name="drop_address"
                                                                class="form-control showcls24mec" rows="5"
                                                                id="drop_address"
                                                                readonly>{{ $order[0]->drop_location }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group required">
                                                            <label>Other </label>
                                                            <input type="text" name="other_field_drop"
                                                                class="form-control " id="other_field_drop"
                                                                value="{{ @$order[0]->other_field_drop }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="width:100%">
                                        <h4>Parcel Details</h4>
                                        @if(isset($parcel))
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-4">
                                                <thead>
                                                    <tr>
                                                        <th>Goods Type Name</th>
                                                        <th>Parcel (Qty)</th>
                                                        <th>Weight (KG)</th>
                                                        <th>Total Weight (KG)</th>
                                                        <th>Tempo Charge &#x20B9;</th>
                                                        <th>Service Charge &#x20B9;</th>
                                                        <th>Delivery Charge &#x20B9;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach($parcel as $p)
                                                    <tr>
                                                        <td class="goods_type_name_here">
                                                            {{ $p->goods_type_name }}
                                                            @if($p->goods_type_id==constants('goods_type_id_other'))
                                                            <br>{{ $p->other_text }}
                                                            @endif
                                                        </td>
                                                        <td class="no_of_parcel_here">{{ $p->no_of_parcel }}</td>
                                                        <td class="goods_weight_here">{{ $p->goods_weight }}</td>
                                                        <td class="total_weight_here">{{ $p->total_weight }}</td>
                                                        <td class="tempo_charge_here">{{ $p->tempo_charge }}</td>
                                                        <td class="service_charge_here">{{ $p->service_charge }}</td>
                                                        <td class="delivery_charge_here">{{ $p->delivery_charge }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    <label>Transporter Cost &#x20B9; * </label>
                                                    <input type="text" name="transport_cost" class="form-control"
                                                        id="transport_cost" placeholder="Transporter Cost"
                                                        value="{{ @$order[0]->transport_cost }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Customer Parcel Estimation Value (optional) &#x20B9;</label>
                                                    <input type="text" name="customer_estimation_asset_value"
                                                        class="form-control " id="customer_estimation_asset_value"
                                                        placeholder="Customer Parcel Estimation Value"
                                                        value="{{ @$order[0]->customer_estimation_asset_value }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label>Total Weight (K.G.)</label>
                                                    <input type="hidden" name="totalfinal_weight" id="totalfinal_weight"
                                                        value="0" required>
                                                    <input type="text" name="totalfinal_weightview"
                                                        class="form-control " id="totalfinal_weightview"
                                                        value="{{ @$order[0]->total_weight }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label>Total Delivery Charges &#x20B9;</label>
                                                    <input type="text" name="totalfinal_cost" class="form-control "
                                                        id="totalfinal_cost" value="{{ @$order[0]->final_cost }}"
                                                        disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label>Minimum Order Charge &#x20B9;</label>
                                                    <input type="text" name="min_order_value_charge"
                                                        class="form-control " id="min_order_value_charge"
                                                        value="{{ @$order[0]->min_order_value_charge }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label>Discount &#x20B9; *</label>
                                                    <input type="text" name="discount" class="form-control "
                                                        id="discount" value="{{ @$order[0]->discount }}" disabled>
                                                </div>
                                            </div>


                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label>Redeliver Charge &#x20B9;</label>
                                                    <input type="text" name="redeliver_charge" class="form-control "
                                                        id="redeliver_charge" value="{{ @$order[0]->redeliver_charge }}"
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label>Total Charges &#x20B9;</label>
                                                    <?php
               $total_charges = floatval($order[0]->final_cost) + floatval($order[0]->min_order_value_charge) - floatval($order[0]->discount) + floatval($order[0]->redeliver_charge); 
				?>
                                                    <input type="hidden" name="total_charges total_chargescls"
                                                        id="total_charges" value="0" required>
                                                    <input type="text" name="total_charges1"
                                                        class="form-control allowdecimal total_chargescls"
                                                        id="total_charges1" value="{{ floatval($total_charges) }}"
                                                        placeholder="Total Charges " maxlength="17" disabled>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Transporter LR Number</label>
                                                    <input type="text" name="transporter_lr_number" class="form-control"
                                                        id="transporter_lr_number" placeholder="Transporter LR Number"
                                                        value="{{ @$order[0]->transporter_lr_number }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6>LR Image Pickup</h6>
                                        <div class="row">
                                            <?php
                    if(is_object($dataOrderFile) && !empty($dataOrderFile)){
						$m=0;
						foreach($dataOrderFile as $fx) {
							if(constants('order_file_type.lrpickup')==$fx->img_type) {
					?>
                                            <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"
                                                id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}"
                                                value="{{ $fx->img }}">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    @if(get_file_extension($fx->img )!='' &&
                                                    in_array(strtolower(get_file_extension($fx->img)),
                                                    constants('image_extension')))
                                                    <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 100px; max-height: 100px;"
                                                        onClick="imgDisplayInModal(this.src)" />
                                                    @else
                                                    <a href="{{ @$path.$fx->img }}" target="_blank"><img
                                                            src="{{ asset('admin_assets/assets/etc/fileicon.png') }}"
                                                            alt="no-image" id="img_src" class="img-responsive"
                                                            style="max-width: 100px; max-height: 100px;" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
				  $m++;
				   }}}
				   ?>
                                        </div>

                                        <hr>
                                        <h6>LR Image Drop</h6>
                                        <div class="row">
                                            <?php
                    if(is_object($dataOrderFile) && !empty($dataOrderFile)){
						$m=0;
						foreach($dataOrderFile as $fx) {
							if(constants('order_file_type.lrdrop')==$fx->img_type) {
					?>
                                            <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"
                                                id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}"
                                                value="{{ $fx->img }}">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    @if(get_file_extension($fx->img )!='' &&
                                                    in_array(strtolower(get_file_extension($fx->img)),
                                                    constants('image_extension')))
                                                    <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 100px; max-height: 100px;"
                                                        onClick="imgDisplayInModal(this.src)" />
                                                    @else
                                                    <a href="{{ @$path.$fx->img }}" target="_blank"><img
                                                            src="{{ asset('admin_assets/assets/etc/fileicon.png') }}"
                                                            alt="no-image" id="img_src" class="img-responsive"
                                                            style="max-width: 100px; max-height: 100px;" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
				  $m++;
				   }}}
				   ?>
                                        </div>


                                        <hr>
                                        <h6>Goods Image Pickup</h6>
                                        <div class="row">
                                            <?php
                    if(is_object($dataOrderFile) && !empty($dataOrderFile)){
						$m=0;
						foreach($dataOrderFile as $fx) {
							if(constants('order_file_type.goodspickup')==$fx->img_type) {
					?>
                                            <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"
                                                id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}"
                                                value="{{ $fx->img }}">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    @if(get_file_extension($fx->img )!='' &&
                                                    in_array(strtolower(get_file_extension($fx->img)),
                                                    constants('image_extension')))
                                                    <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 100px; max-height: 100px;"
                                                        onClick="imgDisplayInModal(this.src)" />
                                                    @else
                                                    <a href="{{ @$path.$fx->img }}" target="_blank"><img
                                                            src="{{ asset('admin_assets/assets/etc/fileicon.png') }}"
                                                            alt="no-image" id="img_src" class="img-responsive"
                                                            style="max-width: 100px; max-height: 100px;" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
				  $m++;
				   }}}
				   ?>
                                        </div>

                                        <hr>
                                        <h6>Goods Image Drop</h6>
                                        <div class="row">
                                            <?php
                    if(is_object($dataOrderFile) && !empty($dataOrderFile)){
						$m=0;
						foreach($dataOrderFile as $fx) {
							if(constants('order_file_type.goodsdrop')==$fx->img_type) {
					?>
                                            <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"
                                                id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}"
                                                value="{{ $fx->img }}">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    @if(get_file_extension($fx->img )!='' &&
                                                    in_array(strtolower(get_file_extension($fx->img)),
                                                    constants('image_extension')))
                                                    <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 100px; max-height: 100px;"
                                                        onClick="imgDisplayInModal(this.src)" />
                                                    @else
                                                    <a href="{{ @$path.$fx->img }}" target="_blank"><img
                                                            src="{{ asset('admin_assets/assets/etc/fileicon.png') }}"
                                                            alt="no-image" id="img_src" class="img-responsive"
                                                            style="max-width: 100px; max-height: 100px;" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
				  $m++;
				   }}}
				   ?>
                                        </div>


                                        <hr>
                                        <h6>Signature Image Pickup</h6>
                                        <div class="row">
                                            <?php
                    if(is_object($dataOrderFile) && !empty($dataOrderFile)){
						$m=0;
						foreach($dataOrderFile as $fx) {
							if(constants('order_file_type.signaturepickup')==$fx->img_type) {
					?>
                                            <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"
                                                id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}"
                                                value="{{ $fx->img }}">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    @if(get_file_extension($fx->img )!='' &&
                                                    in_array(strtolower(get_file_extension($fx->img)),
                                                    constants('image_extension')))
                                                    <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 100px; max-height: 100px;"
                                                        onClick="imgDisplayInModal(this.src)" />
                                                    @else
                                                    <a href="{{ @$path.$fx->img }}" target="_blank"><img
                                                            src="{{ asset('admin_assets/assets/etc/fileicon.png') }}"
                                                            alt="no-image" id="img_src" class="img-responsive"
                                                            style="max-width: 100px; max-height: 100px;" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
				  $m++;
				   }}}
				   ?>
                                        </div>



                                        <hr>
                                        <h6>Signature Image Drop</h6>
                                        <div class="row">
                                            <?php
                    if(is_object($dataOrderFile) && !empty($dataOrderFile)){
						$m=0;
						foreach($dataOrderFile as $fx) {
							if(constants('order_file_type.signaturedrop')==$fx->img_type) {
					?>
                                            <input type="hidden" name="existing_img_file{{ $fx->img_type }}[]"
                                                id="existing_img_file{{ $fx->img_type }}{{ $fx->id }}"
                                                value="{{ $fx->img }}">
                                            <div class="col-sm-2">
                                                <div class="form-group required">
                                                    @if(get_file_extension($fx->img )!='' &&
                                                    in_array(strtolower(get_file_extension($fx->img)),
                                                    constants('image_extension')))
                                                    <img src="{{ @$path.$fx->img }}" alt="no-image" id="img_src"
                                                        class="img-responsive"
                                                        style="max-width: 100px; max-height: 100px;"
                                                        onClick="imgDisplayInModal(this.src)" />
                                                    @else
                                                    <a href="{{ @$path.$fx->img }}" target="_blank"><img
                                                            src="{{ asset('admin_assets/assets/etc/fileicon.png') }}"
                                                            alt="no-image" id="img_src" class="img-responsive"
                                                            style="max-width: 100px; max-height: 100px;" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
				  $m++;
				   }}}
				   ?>
                                        </div>
                                        
                                        
                                        <hr style="width:100%">
                                        <h4>Order Details</h4>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>BigDaddy LR Number</label>
                                                    <input type="text" name="bigdaddy_lr_number" class="form-control " id="bigdaddy_lr_number" value="{{ $order[0]->bigdaddy_lr_number }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @if(in_array($order[0]->status,constants('order_status.cancelled_orders')))
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group required">
                                                    <label>Order Cancelled Reason</label>
                                                    <textarea type="text" rows="2" name="if_cancelled_reason_text" class="form-control " id="if_cancelled_reason_text" readonly>{{ $order[0]->if_cancelled_reason_text }}</textarea>
                                                </div>
                                            </div>
                                          </div>
                                           @endif
                                        @if(in_array($order[0]->status,constants('order_status.undelivered_orders')))
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group required">
                                                    <label>Order Undelivered Reason</label>
                                                    <textarea type="text" rows="2" name="if_undelivered_reason_text" class="form-control " id="if_undelivered_reason_text" readonly>{{ $order[0]->if_undelivered_reason_text }}</textarea>
                                                </div>
                                            </div>
                                          </div>
                                           @endif


                                        <hr style="width:100%">
                                        <h4>Driver Details</h4>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Name <a target="_blank"
                                                            href="{{ route('view-driver') }}/{{ $order[0]->did }}"><span
                                                                class="badge badge-primary admid-select-color">view
                                                                driver</span></a>
                                                    </label>
                                                    <input type="text" name="Driver_Name" class="form-control "
                                                        id="Driver_Name" value="{{ @$order[0]->dfullname }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Email </label>
                                                    <input type="text" name="Driver_Email" class="form-control "
                                                        id="Driver_Email" value="{{ @$order[0]->demail }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Mobile </label>
                                                    <input type="text" name="Driver_Mobile" class="form-control "
                                                        id="Driver_Mobile" value="{{ @$order[0]->dmobile }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Vehicle No </label>
                                                    <input type="text" name="Vehicle_No" class="form-control "
                                                        id="Vehicle_No" value="{{ @$order[0]->vehicle_no }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <hr style="width:100%">
                                        <h4>Payment Details</h4>
                                        <div class="row">
                                        
                                        @if($order[0]->wallet_amount_used>0)
                                        <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Wallet Amount Used</label>
                                                    <input type="text" name="wallet_amount_used" class="form-control " id="wallet_amount_used"
                                                        value="{{ $order[0]->wallet_amount_used }}" readonly>
                                                </div>
                                            </div>
                                           @endif
                                          @if($order[0]->prepaid_amount_used>0)
                                        <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Prepaid Amount Used</label>
                                                    <input type="text" name="prepaid_amount_used" class="form-control " id="prepaid_amount_used"
                                                        value="{{ $order[0]->prepaid_amount_used }}" readonly>
                                                </div>
                                            </div>
                                           @endif
                                           @if($order[0]->cod_amount_used>0)
                                        <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>COD Amount Used</label>
                                                    <input type="text" name="cod_amount_used" class="form-control " id="cod_amount_used"
                                                        value="{{ $order[0]->cod_amount_used }}" readonly>
                                                </div>
                                            </div>
                                           @endif
                                            
                                            
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Payment Date </label>
                                                    <input type="text" name="Payment_Date" class="form-control "
                                                        id="Payment_Date"
                                                        value="{{ isset($order[0]->payment_datetime) ? date('d-m-Y', strtotime($order[0]->payment_datetime)) : '' }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Payment Status </label>
                                                    <input type="text" name="Payment_Status" class="form-control "
                                                        id="Payment_Status" value="{{ @$order[0]->shpsname }}" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Order Method </label>
                                                    <input type="text" name="Payment_Type" class="form-control "
                                                        id="Payment_Type" value="{{ @$order[0]->shptname }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Payment Type </label>
                                                    <input type="text" name="payment_type_manual" class="form-control "
                                                        id="payment_type_manual" value="{{ constants('payment_type_manual.'.$order[0]->payment_type_manual.'.name') }}" readonly>
                                                </div>
                                            </div>

                                            @if($order[0]->payment_discount>0)
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Payment Discount &#x20B9;</label>
                                                    <input type="text" name="Payment_Discount" class="form-control "
                                                        id="Payment_Discount" value="{{ $order[0]->payment_discount }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            @endif
                                            @if($order[0]->payment_comment!='')
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Payment Comment</label>
                                                    <input type="text" name="Payment_Comment" class="form-control "
                                                        id="Payment_Comment" value="{{ $order[0]->payment_comment }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            @endif
                                            @if($order[0]->if_cheque_number!='')
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Cheque Number</label>
                                                    <input type="text" name="if_cheque_number" class="form-control "
                                                        id="if_cheque_number" value="{{ $order[0]->if_cheque_number }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            @endif
                                            @if($order[0]->if_transaction_number!='')
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Transaction Number</label>
                                                    <input type="text" name="if_transaction_number"
                                                        class="form-control " id="if_transaction_number"
                                                        value="{{ $order[0]->if_transaction_number }}" readonly>
                                                </div>
                                            </div>
                                            @endif

                                            @if($order[0]->invoice_id>0)
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Invoice Number <a target="_blank"
                                                            href="{{ sendPath().constants('dir_name.invoice').'/'.$order[0]->invoice_file }}"><span
                                                                class="badge badge-primary admid-select-color">view
                                                                invoice</span></a></label>
                                                    <input type="text" name="Invoice_Number" class="form-control "
                                                        id="Invoice_Number" value="{{ $order[0]->invoice_number }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                        
                                        @if(isset($order[0]->coupon_code_id) && $order[0]->coupon_code_id>0)
                                        <hr style="width:100%">
                                        <h4>Coupon Discount Details</h4>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Coupon Amount Discount &#x20B9;</label>
                                                    <input type="text" name="coupon_benefit_amount" class="form-control " id="coupon_benefit_amount" value="{{ $order[0]->coupon_benefit_amount }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Coupon Code Applied</label>
                                                    <input type="text" name="coupon_code_applied" class="form-control " id="coupon_code_applied" value="{{ $order[0]->coupon_code_applied }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if(isset($order[0]->subscription_benefit_amount) && $order[0]->subscription_benefit_amount>0)
                                        <hr style="width:100%">
                                        <h4>Subscription Discount Details</h4>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group required">
                                                    <label>Subscription Amount Discount &#x20B9;</label>
                                                    <input type="hidden" name="subscription_purchase_id" id="subscription_purchase_id" value="{{ $order[0]->subscription_purchase_id }}" >
                                                    <input type="text" name="subscription_benefit_amount" class="form-control " id="subscription_benefit_amount" value="{{ $order[0]->subscription_benefit_amount }}" readonly>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        @endif
                                        
                                        
                                        @if(isset($order[0]->dfullname) && $order[0]->dfullname!='')
                                        <hr style="width:100%">
                                        @if(isset($order[0]->invoice_number) && $order[0]->invoice_number!='')
                                        <button type="button" class="btn btn-danger float-right delete_invoice_btnidcls"
                                            data-id="{{ $order[0]->id }}"
                                            data-inv="{{ $order[0]->invoice_number }}">Delete Invoice</button>
                                        @else
                                        <button type="button" id="print_invoice_btnid"
                                            class="btn btn-info float-right">Create New Invoice</button>
                                        @endif
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="historyid" role="tabpanel" aria-labelledby="contact-tab">
                                <table id="t2" class="table table-hover" style="width:100%">
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
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <h2>No Order Found </h2>
                </div>
            </div>
            @endif
            @include('admin.layout.footer')
        </div>

        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <!----image display Modal start---->
    <div class="modal fade fadeInUp" id="iModal" tabindex="-1" role="dialog" aria-labelledby="iModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="iModalLabel">View</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">@if(isset($order[0]->lr_img) && $order[0]->lr_img!='') <img
                        src="{{ @$path.@$order[0]->lr_img }}" alt="no-image" id="img_src" class="img-responsive"
                        style="max-width: 800px; max-height: 500px;" />@endif
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!----image display  Modal end---->

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
                        <input type="hidden" name="orderId" value="{{ @$orderId }}" id="iodhid">
                        <input type="hidden" name="user_idhid" value="{{ @$order[0]->user_id }}" id="user_idhid"
                            required>
                        <div class="col-sm-12">
                            <div class="form-group required">
                                <label for="name">Invoice Number</label>
                                <input type="text" name="invoice_number"
                                    class="form-control invoice_numbercls allownumber" id="invoice_number"
                                    placeholder="Enter Invoice Number" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group required">
                                <label>Invoice Date </label>
                                <input type="text" name="invoice_date" class="form-control datepicker" id="invoice_date"
                                    placeholder="required" data-date-format="dd-mm-yyyy" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            <button type="submit" class="btn btn-primary">Download Invoice</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!----print invoice modal end---->

    @include('admin.layout.js')
    <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/bootstrap-datepicker.min.js')}}"></script>
    <!----Add Custom Js ----start----->
    <script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
    });
    $('.datepicker').datepicker("setDate", new Date());



    var t2;
    var oid = '{{ @$orderId }}';
    $.fn.dataTable.ext.errMode = 'none';
    errorCount = 1;
    $('#t2').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 1) {
            showSweetAlert('something went wrong', 'please refresh page and try again', 0);
        } else {
            t1.draw(false);
        }
        errorCount++;
    });
	
	

    $(document).ready(function() {
        t2 = $('#t2').DataTable({
            processing: false,
            paging: true,
            searching: false,
            lengthMenu: [
                [10, 50, 100, 500],
                [10, 50, 100, 500]
            ],
            pageLength: 50,
            order: [
                [0, 'desc']
            ],
            serverSide: true,
            ajax: {
                "url": "{{ route('get-order-logs') }}",
                "type": "get",
                "data": function(data) {
                    data.oid = oid;
                },
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': [-1],
                }]
            },
        });
    });
    </script>
    <script type="text/javascript">
    $('body').on('click', '#print_invoice_btnid', function() {
        getLatestInvoiceNumber();
        $('#piModalLabel').html('Invoice');
        $("#piModal").modal('show');
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
                        window.location.reload();
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });

            }
        });
    });


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

    <!----Add Custom Js --end------->
    @include('admin.layout.imageview')
</body>

</html>