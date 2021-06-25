<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/plugins/select2/select2.min.css')}}">
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
          <div class="statbox widget box box-shadow">
          @if(!isset($one->id))
            <h4>Not Found</h4>
            @else
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>{{ $control }} 
                  @if($one->is_editable!=constants('is_editable_yes'))
                  | This Transaction is Not Editable.
                  @endif
                  </h4>
                  
                   </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
            @if($one->is_editable==constants('is_editable_yes'))
              <form method="post" action="{{ route('update-transaction-submit') }}" enctype="multipart/form-data">
              @endif
                @csrf
                <input type="hidden" name="hid" id="hid" value="{{ $one->id }}" >
                <input type="hidden" name="hidtransactionid" id="hidtransactionid" value="{{ $one->transaction_uuid }}" >
                <input type="hidden" name="hidincludeidselect_vendor_from_select2_dropdown_id" id="hidincludeidselect_vendor_from_select2_dropdown_id" value="{{ $one->vendor_id }}" >
                <input type="hidden" name="hidincludeselect_transaction_subcategory_from_select2_dropdownid" id="hidincludeselect_transaction_subcategory_from_select2_dropdownid" value="{{ $one->transaction_subcategory_id }}" >
                <input type="hidden" name="is_active" id="is_active" value="{{ $one->is_active }}">
                <div class="row">
                <div class="col-sm-2">
                    <div class="form-group required">
                      <label>Type (required)</label>
                      <select class="form-control"  name="transaction_type" id="transaction_type" required="">
                      @foreach(constants('transaction_type_list') as $row)
                        @if($one->transaction_type== $row['key'])
                       <option value="{{ $row['key'] }}" selected> {{ $row['name2'] }} / {{ $row['name3'] }}</option>
                      @else
                      <option value="{{ $row['key'] }}"> {{ $row['name2'] }} / {{ $row['name3'] }}</option>
                      @endif
                      @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group required">
                      <label for="name">Date (required)</label>
                      <input type="text" name="transaction_date" class="form-control datepicker" id="transaction_date" value="{{ $one->transaction_date }}" placeholder="Required">
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group required">
                      <label for="name">&#x20B9; Amount (required)</label>
                      <input type="text" name="amount" class="form-control showcls24mec allowdecimal" id="amount" placeholder="Amount Rs" value="{{ $one->amount }}" required maxlength="10">
                    </div>
                  </div>
                  </div>

                  <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label for="name">Select Vendor (optional)</label>
                      <select class="form-control"  name="select_vendor_from_select2_dropdown_id" id="select_vendor_from_select2_dropdown_id">
                      @if(isset($one->vendor->id))
                      <option value="{{ $one->vendor->id }}" selected>{{ $one->vendor->fullname." | ".$one->vendor->vendor_type }}  </option>
                      @endif
                      </select>
                    </div>
                  </div>
                  </div>
                  
                  
                  <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">Account Bank (required)</label>
                      <select class="form-control"  name="accountid_from" id="accountid_from" required>
                      @foreach($dataAccAccountBanks as $row)
                        @if($one->accountid_from==$row->id)
                        <option value="{{ $row->id }}" selected> {{ $row->name }} | {{ $row->account_id }}</option>
                      @else
                       <option value="{{ $row->id }}"> {{ $row->name }} | {{ $row->account_id }}</option>
                      @endif
                      
                       @endforeach
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">Payment Method (required)</label>
                      <select class="form-control"  name="payment_method" id="payment_method" required>
                     @foreach(constants('payment_method_for_accounting') as $row)
                     @if($one->payment_method==$row['short'])
                        <option value="{{ $row['short'] }}" selected> {{ $row['name'] }}</option>
                      @else
                       <option value="{{ $row['short'] }}"> {{ $row['name'] }}</option>
                      @endif
                     @endforeach
                      </select>
                    </div>
                  </div>
                  </div>
                  
                  <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label for="name">Select Category (required)</label>
                      <select class="form-control"  name="select_transaction_subcategory_from_select2_dropdownid" id="select_transaction_subcategory_from_select2_dropdownid">
                      @if(isset($one->transaction_subcategory->id))
                      <option value="{{ $one->transaction_subcategory->id }}" selected>{{ $one->transaction_subcategory->name }} </option>
                      @endif
                      </select>
                    </div>
                  </div>
                  </div>
                  
                  <div class="row" id="select_accounts_or_banks_id_transfer_from_select2_rowid" style="display:none;">
                  <div class="col-sm-6">
                    <div class="form-group required">
                      <label for="name">Select Bank (required)</label>
                      <select class="form-control"  name="select_accountid_transferredto_from_select2_dropdownid" id="select_accountid_transferredto_from_select2_dropdownid">
                      </select>
                    </div>
                  </div>
                  </div>
                  
                  <div class="row">
                   <div class="col-sm-2">
                    <div class="form-group required">
                      <label for="name">Bill No (optional)</label>
                      <input type="text" name="anybillno" class="form-control showcls24mec" id="anybillno" placeholder="Bill No" value="{{ $one->anybillno }}" maxlength="24">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group  required">
                      <label for="description">Document (optional) [file size should be less than 1 MB]</label>
                      <input type="file" name="anybillno_document" class="form-control showcls24mec" id="anybillno_document" accept="application/pdf,image/jpeg,image/jpg,image/png,image/gif">
                    </div>
                  </div>
                  </div>
                  
                  <input type="hidden" name="existing_img" id="existing_img" value="{{ $one->anybillno_document }}" >
                 
                  @if($one->anybillno_document!='')
                   <div class="row">
                  <div class="col-sm-12">
                  @if(in_array(get_file_extension($one->anybillno_document),constants('image_extension')))
                  <img src="{{ sendPath().constants('dir_name.bill').'/'.$one->anybillno_document }}" onClick="imgDisplayInModal(this.src)" alt="no-file" id="img_src" class="img-responsive" style="max-width: 400px; max-height: 250px;" />
                  @else
                  <a href="{{ sendPath().constants('dir_name.bill').'/'.$one->anybillno_document }}" target="_blank">View File</a>
                  @endif
                  </div></div>
                  @endif

                  <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">Description (optional)</label>
                      <input type="text" name="description" class="form-control showcls24mec" id="description" placeholder="Any Details" value="{{ $one->description }}" maxlength="990">
                    </div>
                  </div>
                </div>
                
                
                <hr style="width:100%">
                @if($one->is_editable==constants('is_editable_yes'))
                <button type="submit" class="btn btn-info float-right admin-button-add-vnew">Submit</button>
              </form>
              @endif
            </div>
            @endif
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
<script src="{{ asset('admin_assets/plugins/select2/select2.min.js')}}"></script> 
<link href="{{ asset('admin_assets/assets/css/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin_assets/assets/js/flatpickr.min.js')}}"></script> 
<script type="text/javascript" >
var Transfer_From_Bank_Subcategory_Id = "{{ constants('Transfer_From_Bank_Subcategory_Id') }}";
var Transfer_To_Bank_Subcategory_Id = "{{ constants('Transfer_To_Bank_Subcategory_Id') }}";
/*$('.datepicker').datepicker({autoclose: true,todayHighlight: true, orientation: 'bottom', });*/
 var transaction_date_flatpickr = flatpickr(document.getElementById('transaction_date'), {
    enableTime: true,
    dateFormat: "Y-m-d H:i:s",
});

</script> 
<script type="text/javascript">
$('body').on('change', '#transaction_type', function () {
	$("#select_transaction_subcategory_from_select2_dropdownid").select2("val", 0);
});
$('body').on('change', '#transaction_type,#accountid_from,#select_transaction_subcategory_from_select2_dropdownid', function () {
	$("#select_accountid_transferredto_from_select2_dropdownid").select2("val", 0);
	var select_transaction_subcategory_from_select2_dropdownid = $('#select_transaction_subcategory_from_select2_dropdownid').val();
	if((Transfer_From_Bank_Subcategory_Id==select_transaction_subcategory_from_select2_dropdownid || Transfer_To_Bank_Subcategory_Id==select_transaction_subcategory_from_select2_dropdownid)){
		$("#select_accounts_or_banks_id_transfer_from_select2_rowid").show();
		$("#select_accountid_transferredto_from_select2_dropdownid").prop('required',true);
	}
	else
	{
		$("#select_accounts_or_banks_id_transfer_from_select2_rowid").hide();
		$("#select_accountid_transferredto_from_select2_dropdownid").prop('required',false);
	}
});


$("#select_transaction_subcategory_from_select2_dropdownid").select2({
    		placeholder: "Select A Category",
    		width:"100%",
                ajax: {
					url: "{{ route('transactionSubCategoryInDropDown') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'transaction_type': $("#transaction_type").val(), 
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
 $("#select_accountid_transferredto_from_select2_dropdownid").select2({
    		placeholder: "Select An Account",
    		width:"100%",
                ajax: {
					url: "{{ route('accountsOrBanksInDropdown') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'excludeids': $('#accountid_from').val(), 
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
<!----Add Custom Js --end------->
@include('admin.layout.snippets.add_newvendor') 
 @include('admin.layout.imageview')
</body>
</html>