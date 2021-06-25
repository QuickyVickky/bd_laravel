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
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>Add {{ $control }} </h4>
                  @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif  </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <form method="post" action="{{ route('submit-account-add') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hid" id="hid" value="0" >
                <div class="row">
                  <div class="col-sm-2">
                    <div class="form-group required">
                      <label for="name"> Date ( required )</label>
                      <input type="text" name="account_datetime" class="form-control datepicker" id="account_datetime" placeholder="Required">
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group required">
                      <label for="name">Amount (required)</label>
                      <input type="text" name="amount" class="form-control showcls24mec allowdecimal" id="amount" placeholder="Amount Rs" value="" required maxlength="10">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">Main Category ( required )</label>
                      <select class="form-control"  name="select_main_category" id="select_main_category" required="">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group required">
                      <label for="name">Select ( optional )</label>
                      <select class="form-control"  name="select_sub_category" id="select_sub_category">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group required">
                      <label>Type ( required )</label>
                      <select class="form-control"  name="account_type" id="account_type" required="">
                      		@foreach($account_type as $row)
                        <option value="{{ $row->short }}"> {{ $row->name }}</option>
                       		@endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group required">
                      <label for="name">Bill No ( optional )</label>
                      <input type="text" name="anybillno" class="form-control showcls24mec" id="anybillno" placeholder="Bill No" value="" maxlength="24">
                    </div>
                  </div>
                  <input type="hidden" name="is_active" id="is_active" value="0" >
                  <div class="col-sm-10">
                    <div class="form-group  required">
                      <label for="description">Bill No Document ( optional ) [file size should be less than 1 MB]</label>
                      <input type="file" name="anybillno_document" class="form-control showcls24mec" id="anybillno_document" accept="application/pdf,image/jpeg,image/jpg,image/png,image/gif">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group  required">
                      <label for="description">Details ( optional )</label>
                      <input type="text" name="comments" class="form-control showcls24mec" id="comments" placeholder="Any Details" value="">
                    </div>
                  </div>
                </div>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script type="text/javascript" >

	var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('#account_datetime').datepicker({
      dateFormat: "yy-mm-dd",
      todayHighlight: true,
      autoclose: true
    });
    $('#account_datetime').datepicker('setDate', today);
	
	
	function formatState() {
		return "<option>56565</option>";
	}
	
	
	$("#select_main_category").select2({
    		placeholder: "Search...",
    		width:"100%",
                ajax: {
					url: "{{ route('get-allmaincategory-data') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
						 $("#select_sub_category").html('');
						 $("#select_sub_category").append('<button type="button">ABCD</button>');
						 
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'includeid': 0, 
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
	  
	  $("#select_sub_category").select2({
    		placeholder: "Search...",
    		width:"100%",
                ajax: {
					url: "{{ route('get-allsubcategory-data') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                     data: function (params) {
                        return {
                            searchTerm: params.term ,
							_token:'{{ csrf_token() }}',
							'main_category_id': $("#select_main_category").val(), 
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

</body>
</html>