<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('admin_assets/plugins/font-icons/fontawesome/css/regular.css')}}">
<link rel="stylesheet" href="{{ asset('admin_assets/plugins/font-icons/fontawesome/css/fontawesome.css')}}">
<link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />
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
            <!--li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#addnewid" role="tab" aria-controls="contact" aria-selected="false">Add New</a> </li-->
          </ul>
          <div class="tab-content" id="simpletabContent">
            <div class="tab-pane fade show active" id="viewid" role="tabpanel" aria-labelledby="home-tab"> @if(count($one)>0)
              <div class="widget-content widget-content-area"> 
                <form method="post" action="{{ route('add-update-admins') }}" enctype="multipart/form-data" autocomplete="off" id="formid">
                  @csrf
                  <input type="hidden" name="hid" id="hid" value="{{ @$one[0]->id }}" >
                  <input type="hidden" name="roles_selected_ids" id="roles_selected_ids" value="{{ @$one[0]->assigned_role_management_ids }}" >
                  <input type="hidden" name="roles_notselected_ids" id="roles_notselected_ids" value="{{ @$one[0]->notassigned_role_management_ids }}" >
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Full Name (required) </label>
                        <span id="wanttoeditidspan" class="badge badge-warning float-right admid-select-color"> want to edit ? </span>
                        <input type="text" name="fullname" class="form-control showcls24mec" id="fullname" placeholder="Enter Full Name"  autocomplete="off" value="{{ @$one[0]->fullname }}"    required disabled>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Email (required)</label>
                        <input type="email" name="email" class="form-control showcls24mec" id="email" placeholder="Enter Email Address" value="{{ @$one[0]->email }}"  autocomplete="off" required disabled>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Mobile Number (optional)</label>
                        <input type="text" name="mobile" class="form-control showcls24mec" id="mobile" placeholder="Enter Mobile Number" value="{{ @$one[0]->mobile }}" maxlength="13" value=""  disabled >
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="name">Admin Type</label>
                        <select class="form-control showcls24mec" name="admins_type" id="admins_type1" required="" onChange="showroles(this.value)" disabled>
                      @foreach($admins_type as $row)
                      @if(isset($one[0]->role) && $one[0]->role==$row->short)
                     <option value="{{ $row->short }}" selected> {{ $row->name }}</option>
                        @else
                          <option value="{{ $row->short }}" > {{ $row->name }}</option>
                        @endif
                       @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group required">
                        <label for="password">Password </label>
                        <span id="dfsdf543hgdf56bxv" class="badge badge-primary float-right rounded bs-tooltip admid-select-color" title="Don Not Change if You do not want, otherwise Password will be changed."> change Password </span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" maxlength="25" value="1234567" disabled >
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group required">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="status0" name="status" class="custom-control-input showcls24mec" value="0" {{ (!isset($one[0]->
                          is_active)) ? 'checked' : '' }}  {{ (isset($one[0]->is_active) && ($one[0]->is_active==0)) ? 'checked' : '' }}  disabled>
                          <label class="custom-control-label" for="status0">Active</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="status1" name="status" class="custom-control-input showcls24mec" value="1" {{ (isset($one[0]->
                          is_active) && ($one[0]->is_active==1)) ? 'checked' : '' }} disabled>
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
					//dde($rr);
					?>
                      @foreach( $menu_list as $row)
                      @if($row->level==0)
                      <div class="col-sm-12">
                        <div class="form-group required">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input allcheckcls showcls24mec" name="menu_to_assign[]" id="menu_to_assignid{{ $row->id }}" value="{{ $row->id }}" data-cls="maincheckclsthisis{{ $row->id }}" disabled>
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
                                    <input type="checkbox" class="custom-control-input makedisabledonpageload allcheckcls maincheckclsthisis{{ $row->id }} showcls24mec" name="menu_to_assign[]" id="menu_to_assignid{{ $r1->id }}" value="{{ $r1->id }}" data-cls="maincheckclsthisis{{ $r1->id }}" disabled>
                                    <label class="custom-control-label" for="menu_to_assignid{{ $r1->id }}">{{ $r1->name }}</label>
                                    <div class="level2justonlyclass row" style="padding-left: 46px;"> 
                                      
                                      <!--------------------------2 level action buttons------------> 
                                      @foreach($menu_list as $r2)
                                      @if($r2->level==2 && $r2->path_to==$r1->id)
                                      <div class="col-sm-3">
                                        <input type="checkbox" class="custom-control-input makedisabledonpageload allcheckcls maincheckclsthisis{{ $row->id }} maincheckclsthisis{{ $r1->id }} showcls24mec" name="menu_to_assign[]" id="menu_to_assignid{{ $r2->id }}" value="{{ $r2->id }}" data-cls="maincheckclsthisis{{ $r2->id }}" disabled>
                                        <label class="custom-control-label rounded bs-tooltip" for="menu_to_assignid{{ $r2->id }}" title="{{ $r2->details }}" data-placement="right">{{ $r2->name }}</label>
                                        
                                        <!--------------------------3 level action buttons------------> 
                                        @foreach($menu_list as $r3)
                                        @if($r3->level==3 && $r3->path_to==$r2->id)
                                        <div class="col-sm-3">
                                          <input type="checkbox" class="custom-control-input makedisabledonpageload allcheckcls maincheckclsthisis{{ $row->id }} maincheckclsthisis{{ $r1->id }} maincheckclsthisis{{ $r2->id }}  showcls24mec" name="menu_to_assign[]" id="menu_to_assignid{{ $r3->id }}" value="{{ $r3->id }}" data-cls="maincheckclsthisis{{ $r3->id }}"  disabled>
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
              @else
              <div class="widget-content widget-content-area">
                <div class="row">
                  <h2>Not Found </h2>
                </div>
              </div>
              @endif </div>
            <div class="tab-pane fade" id="addnewid" role="tabpanel" aria-labelledby="contact-tab"> not </div>
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
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/jquery-ui.js')}}"></script>
<link rel="stylesheet" href="{{ asset('admin_assets/assets/css/jquery-ui.css')}}">

<!----Add Custom Js ----start-----> 
<script type="text/javascript" >
var assigned_role = '{{ isset($one[0]->assigned_role_management_ids) ? $one[0]->assigned_role_management_ids : 0 }}';

$('body').on('click', '.allcheckcls', function () {
	var cls = $(this).data('cls');
	var value = $(this).attr('value');
	var id = $(this).attr('id');

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
});

 $(document).ready(function () {
	 $(".makedisabledonpageload").prop('disabled',true);
	 var admins_type = $("#admins_type1").val();
	 if(admins_type=="A") {  $("#menuassigndivid").css("display","none");  }
	 else {
		 var assigned_roleArray = assigned_role.split(",");
		 var i;
		for (i = 0; i < assigned_roleArray.length; i++) {
	 	$("#menu_to_assignid"+assigned_roleArray[i]).prop('checked',true);
 	 	}
		//console.log(assigned_role);
	 }
		 

 });
function showroles(value){
	$("#menuassigndivid").css("display","block"); 
	var admins_type = $("#admins_type1").val();
	if(admins_type=="A") {  $("#menuassigndivid").css("display","none");  }
}
$('body').on('click', '#dfsdf543hgdf56bxv', function () {
	 $('#password').val('');
	 var password = makeid(randomIntFromInterval(6, 12));
	 $('#password').val(password).attr("type","text").removeAttr("disabled");
});
$('body').on('click', '#wanttoeditidspan', function () {
	 $('.showcls24mec').removeAttr("disabled");
	 $('#btnsubmitid').show();
});




			
			

	

 </script> 

<!----Add Custom Js --end-------> 
@include('admin.layout.crudhelper')
</body>
</html>