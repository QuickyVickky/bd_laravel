<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<!--link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" /---->
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
            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#viewid" role="tab" aria-controls="home" aria-selected="true">View</a> </li>
            <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#historyid" role="tab" aria-controls="contact" aria-selected="false">Images</a> </li>
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade show active" id="viewid" role="tabpanel" aria-labelledby="home-tab"> @if(count($one)>0)
              <div class="widget-content widget-content-area"> 
                @php
                $path = sendPath().'bill_files/';
                @endphp
                <form method="post" action="{{ route('submit-driver-update') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="hid" value="{{ @$one[0]->id }}" >
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Full Name * </label>
                        <span id="wanttoeditidspan" class="badge badge-warning float-right"> want to edit ? </span>
                        <input type="text" name="fullname" class="form-control showcls24mec" id="fullname" placeholder="Enter Full Name" value="{{ @$one[0]->fullname }}"  required disabled>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">PAN Number (optional)</label>
                        <input type="text" name="pan_card" class="form-control showcls24mec" id="pan_card" placeholder="Enter PAN No" value="{{ @$one[0]->pan_card }}" disabled >
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Vehicle Number (optional)</label>
                        <input type="text" name="vehicle_number" class="form-control showcls24mec" id="vehicle_number" placeholder="Enter Vehicle Number" value="{{ @$one[0]->vehicle_number }}" disabled >
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Email (optional)</label>
                        <input type="email" name="email" class="form-control showcls24mec" id="email" placeholder="Enter Email Address" value="{{ @$one[0]->email }}" disabled>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Mobile Number *</label>
                        <input type="text" name="mobile" class="form-control showcls24mec" id="mobile" placeholder="Enter Mobile Number" value="{{ @$one[0]->mobile }}" required disabled >
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="password">Password (optional)</label>
                        <span id="dfsdf543hgdf56bxv" class="badge badge-primary float-right rounded bs-tooltip" title="Don Not Change if You do not want, otherwise Password will be changed."> change Password </span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" maxlength="25" value="1234567" disabled>
                      </div>
                    </div>
                    <input type="hidden" name="existing_img"  id="existing_img" value="{{ @$one[0]->profile_pic }}">
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
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="status0" name="status" class="custom-control-input"  value="0"{{ (!isset($one[0]->
                          is_active)) ? 'checked' : '' }}  {{ (isset($one[0]->is_active) && ($one[0]->is_active==0)) ? 'checked' : '' }} >
                          <label class="custom-control-label" for="status0">Active</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="status1" name="status" class="custom-control-input" value="1" {{ (isset($one[0]->
                          is_active) && ($one[0]->is_active==1)) ? 'checked' : '' }}>
                          <label class="custom-control-label" for="status1">Deactive</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <h6> {{ $control }} Address</h6>
                  <br>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Country *</label>
                        <input type="text" name="country" class="form-control showcls24mec" id="country1"  placeholder="Country" value="{{ @$one[0]->country }}"  required disabled>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label for="name">State *</label>
                        <input type="text" name="state" class="form-control showcls24mec" id="state1" placeholder="State" value="{{ @$one[0]->state }}" required disabled>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group required">
                        <label for="name">City</label>
                        <input type="text" name="city" class="form-control showcls24mec" id="city1" placeholder="City" value="{{ @$one[0]->city }}" disabled>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group required">
                        <label for="name">Pincode</label>
                        <input type="text" name="pincode" class="form-control showcls24mec"  id="pincode1" placeholder="Pincode" value="{{ @$one[0]->pincode }}" maxlength="6" onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.keyCode === 8;" disabled>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group  required">
                        <label for="description">Address</label>
                        <textarea name="address" class="form-control showcls24mec" rows="2" id="address1" placeholder="Enter Address" disabled >{{ @$one[0]->address }}</textarea>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <hr style="width:100%">
                  <button type="submit" class="btn btn-info float-right" id="btnsubmitid" style="display:none">Submit</button>
                </form>
              </div>
              @else
              <div class="widget-content widget-content-area">
                <div class="row">
                  <h2>Not Found </h2>
                </div>
              </div>
              @endif </div>
            <div class="tab-pane fade" id="historyid" role="tabpanel" aria-labelledby="contact-tab">
              <table id="t2" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th width="3%">#</th>
                    <th>Type</th>
                    <th>Image</th>
                    <th width="10%">Action</th>
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
    @include('admin.layout.footer') </div>
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
      <div class="modal-body"> <img src="{{ @$path.@$order[0]->lr_img }}" alt="no-image" id="img_src" class="img-responsive" style="max-width: 800px; max-height: 500px;" />
        <div class="modal-footer">
          <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!----image display  Modal end----> 

<!----image display Modal start---->
<div class="modal fade fadeInUp" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="editModalLabel">View</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
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
              <label for="name">Select File</label>
              <input type="file" name="otherfile[]" class="form-control" id="otherfile" multiple required>
            </div>
          </div>
          
          		<div class="col-sm-3" style="display:none" id="expirydateiddiv">
                      <div class="form-group required">
                        <label for="name">Expiry Date </label>
                        <input type="text" name="expirydate" class="form-control datepicker" id="expirydate" value="" placeholder="">
                      </div>
                    </div>
          
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

<!----Add Custom Js ----start-----> 
<script type="text/javascript" >
$( function() {
     $( ".datepicker" ).datepicker({
  dateFormat: "dd-mm-yy"
});

  } );

var imgpath = '{{ @$path }}';

$('body').on('click', '#dfsdf543hgdf56bxv', function () {
	 $('#password').val('');
	 var password = makeid(randomIntFromInterval(6, 12));
	 $('#password').val(password).attr("type","text").removeAttr("disabled");
});
$('body').on('click', '#wanttoeditidspan', function () {
	 $('.showcls24mec').removeAttr("disabled");
	 $('#btnsubmitid').show();
	 
});


 var tbl = '{{ $tbl }}';
	 var isdelete = 'N';
	 var did = '{{ @$one[0]->id }}';
	 var tendayafter = '{{ date("Y-m-d", strtotime("+11 days")) }}';
     var dataTable;
	 
	 $.fn.dataTable.ext.errMode = 'none';
	$('#t2').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});



                $(document).ready(function () {
					if(did>0){
                    dataTable = $('#t2').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
                        stateSave: true,
                        order: [[1, 'desc']],
                        serverSide: true,
                        ajax: {
						"url": "{{ route('get-driver-files') }}",
						"type": "get",
						"data":function(data) {
							data.did =did;
						},
						'aoColumnDefs': [{'bSortable': false,'aTargets': [-1],}],
						},
						createdRow: function( row, c, dataIndex ){ 
							if (c[4]>0){ 
									$(row).attr({ style:"background-color:#dd91a2",title:"expired" }); 
										} 
							},
                    });
				}
     });

function seeFiles(){
	
}


$('body').on('click', '.editit', function () {
    var fid = $(this).data('id');
	var fshort = $(this).data('fshort');
	var if_expiry_date = $(this).data('if_expiry_date');
	var img = $(this).data('img');
	var img_type_name = $(this).data('img_type_name');
	
	
	$("#fid").val(fid);
	$("#fshort").val(fshort);
	$("#img_type_name").val(img_type_name);
	
	$("#existing_img0").val(img);
	$("#expirydate").val(if_expiry_date);
	if(if_expiry_date!=''){
	 $('#expirydateiddiv').show();
	}
	
 });




 </script> 

<!----Add Custom Js --end-------> 
@include('admin.layout.crudhelper')
</body>
</html>