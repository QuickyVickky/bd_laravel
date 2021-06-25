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
  <style>
  
  </style>
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
              <form method="post" action="{{ route('add-update-admins') }}" enctype="multipart/form-data" autocomplete="off" id="formid">
                @csrf
                <input type="hidden" name="hid" id="hid" value="0" >
                <input type="hidden" name="roles_selected_ids" id="roles_selected_ids" value="" >
                <input type="hidden" name="roles_notselected_ids" id="roles_notselected_ids" value="" >
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Full Name (required) </label>
                      <input type="text" name="fullname" class="form-control showcls24mec" id="fullname" placeholder="Enter Full Name" value="" autocomplete="off"   required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Email (required)</label>
                      <input type="email" name="email" class="form-control showcls24mec" id="email" placeholder="Enter Email Address" value="" autocomplete="off" required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Mobile Number (optional)</label>
                      <input type="text" name="mobile" class="form-control showcls24mec" id="mobile" placeholder="Enter Mobile Number" maxlength="13" value=""  >
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Admin Type (required)</label>
                      <select class="form-control showcls24mec" name="admins_type" id="admins_type1" required="" onChange="showroles(this.value)" >
                      @foreach($admins_type as $row)
                        <option value="{{ $row->short }}"> {{ $row->name }}</option>
                       @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group required">
                      <label for="password">Password (required)</label>
                      <span id="e2xf5c4rf7hyn" class="badge badge-primary float-right admid-select-color"> Generate Random Password </span>
                      <input type="text" name="password" class="form-control" id="password" placeholder="Enter Password" maxlength="25" value="" required >
                    </div>
                  </div>
                  
                  <div class="col-sm-12">
                    <div class="form-group required">
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status0" name="status" class="custom-control-input" value="0" checked>
                        <label class="custom-control-label" for="status0">Active</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="status1" name="status" class="custom-control-input" value="1">
                        <label class="custom-control-label" for="status1">Deactive</label>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div id="menuassigndivid" class="col-sm-12">
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <label for="name">Please Select At Least Any Menu to Assign (required)</label>
                      </div>
                    </div>
                    <!--------------------------0 level main menu------------>
                    
                    <?php
					$rr = ($menu_list);
					?>
                    @foreach( $menu_list as $row)
                    @if($row->level==0)
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input allcheckcls" name="menu_to_assign[]" id="menu_to_assignid{{ $row->id }}" value="{{ $row->id }}" data-cls="maincheckclsthisis{{ $row->id }}">
                          <label class="custom-control-label" for="menu_to_assignid{{ $row->id }}">{{ $row->name }}</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-checkbox">
                          <div class="col-sm-12"> 
                            <!--------------------------1 level menus------------> 
                            @foreach($menu_list as $r1)
                            @if($r1->level==1 && $r1->path_to==$row->id)
                            <div class="fghhfgrfyghtgh">
                              <div class="col-sm-12">
                                <div class="level1justonlyclass">
                                  <input type="checkbox" class="custom-control-input makedisabledonpageload allcheckcls maincheckclsthisis{{ $row->id }}" name="menu_to_assign[]" id="menu_to_assignid{{ $r1->id }}" value="{{ $r1->id }}" data-cls="maincheckclsthisis{{ $r1->id }}" >
                                  <label class="custom-control-label" for="menu_to_assignid{{ $r1->id }}">{{ $r1->name }}</label>
                                  <div class="level2justonlyclass row" style="padding-left: 46px;"> 
                                    
                                    <!--------------------------2 level action buttons------------> 
                                    @foreach($menu_list as $r2)
                                    @if($r2->level==2 && $r2->path_to==$r1->id)
                                    <div class="col-sm-3">
                                      <input type="checkbox" class="custom-control-input makedisabledonpageload allcheckcls maincheckclsthisis{{ $row->id }} maincheckclsthisis{{ $r1->id }} " name="menu_to_assign[]" id="menu_to_assignid{{ $r2->id }}" value="{{ $r2->id }}" data-cls="maincheckclsthisis{{ $r2->id }}" >
                                      <label class="custom-control-label rounded bs-tooltip" for="menu_to_assignid{{ $r2->id }}" title="{{ $r2->details }}" data-placement="right">{{ $r2->name }}</label>
                                      
                                      <!--------------------------3 level action buttons------------> 
                                      @foreach($menu_list as $r3)
                                      @if($r3->level==3 && $r3->path_to==$r2->id)
                                      <div class="col-sm-3">
                                        <input type="checkbox" class="custom-control-input makedisabledonpageload allcheckcls maincheckclsthisis{{ $row->id }} maincheckclsthisis{{ $r1->id }} maincheckclsthisis{{ $r2->id }}  " name="menu_to_assign[]" id="menu_to_assignid{{ $r3->id }}" value="{{ $r3->id }}" data-cls="maincheckclsthisis{{ $r3->id }}"  >
                                        <label class="custom-control-label rounded bs-tooltip" for="menu_to_assignid{{ $r3->id }}" title="{{ $r3->details }}" data-placement="right">{{ $r3->name }}</label>
                                      </div>
                                      @endif
                                      @endforeach 
                                      <!--------------------------3 level action buttons------------> 
                                      
                                    </div>
                                    @endif
                                    @endforeach 
                                    <!--------------------------2 level action buttons------------> 
                                    
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif
                            @endforeach 
                            <!--------------------------1 level menus------------> 
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!--------------------------0 level main menu------------>
                    <hr style="width:100%">
                    @endif
                    @endforeach </div>
                </div>
                <hr style="width:100%">
                <button type="submit" class="btn btn-info float-right admin-button-add-vnew" id="btnsubmitid">Submit</button>
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

 $(document).ready(function () {
	 $(".makedisabledonpageload").prop('disabled',true);
	 var admins_type = $("#admins_type1").val();
	 if(admins_type=="A") {  $("#menuassigndivid").css("display","none");  }
 });

$('body').on('click', '.allcheckcls', function () {
	var cls = $(this).data('cls');
	var value = $(this).attr('value');
	var id = $(this).attr('id');
	
	console.log(cls );
	console.log(value );
	console.log(id);


                if($("#"+id).prop('checked') == true){
                      $("."+cls).prop('checked',true);
					  $("."+cls).prop('disabled',false);
                 }
                else 
				{   
					$("."+cls).prop('checked',false);
					$("."+cls).prop('disabled',true);
				}
				
				 var ck1 = []; var ck2 = [];
                 $.each($("input[name='menu_to_assign[]']:checked"),function(){ ck1.push($(this).attr('value'));});
                 $.each($("input[name='menu_to_assign[]']:not(:checked)"),function(){ ck2.push($(this).attr('value'));});
				 $('#roles_selected_ids').val(ck1); $('#roles_notselected_ids').val(ck2);

			console.log( $('#roles_selected_ids').val()); 
			console.log( $('#roles_notselected_ids').val()); 
	
});


function showroles(value){
	$("#menuassigndivid").css("display","block"); 
	var admins_type = $("#admins_type1").val();
	console.log(admins_type);
	if(admins_type=="A") {  $("#menuassigndivid").css("display","none");  }
}


</script> 
<script type="text/javascript" >
$('body').on('click', '#e2xf5c4rf7hyn', function () {
     var password = makeid(randomIntFromInterval(5, 12));
	 $('#password').val(password);
});
	



</script> 
<!----Add Custom Js --end-------> 
@include('admin.layout.crudhelper')
</body>
</html>