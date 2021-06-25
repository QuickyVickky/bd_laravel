<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">
    @include('admin.layout.header')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>
        @include('admin.layout.sidebar')

        <?php
        $path = sendPath().constants('dir_name.vehicle').'/';
        $tendayafter = date('Y-m-d', strtotime('+11 days'));
    ?>


        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-lg-12 col-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <h4>{{ $control }} </h4>
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
                                    </div>
                                </div>
                            </div>
                            @if($id>0 && empty($one))
                            @else
                            <div class="widget-content widget-content-area">
                                <form method="post" action="{{ route('add-update-vehicle') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="hid" id="hid"
                                        value="{{ isset($one->id) ? $one->id : 0}}">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group required">
                                                <label for="name">Vehicle Number ( required )</label>
                                                <input type="text" name="vehicle_no" class="form-control"
                                                    id="vehicle_no" placeholder="Vehicle Number"
                                                    value="{{ @$one->vehicle_no}}" required maxlength="15">
                                            </div>
                                            <input type="hidden" name="dvalueid" id="dvalueid" value="0">
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group  required">
                                                <label for="description">Details ( optional )</label>
                                                <input type="text" name="about" class="form-control" id="about"
                                                    placeholder="Any Details About This Vehicle"
                                                    value="{{ @$one->about}}" maxlength="250">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group required">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="status0" name="is_active"
                                                        class="custom-control-input" value="0" {{ (!isset($one->
                          is_active)) ? 'checked' : '' }}
                                                        {{ (isset($one->is_active) && ($one->is_active==0)) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status0">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="status1" name="is_active"
                                                        class="custom-control-input" value="1" {{ (isset($one->
                          is_active) && ($one->is_active==1)) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status1">Deactive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group required">
                                                <label>RC Book (optional)</label>
                                                <input
                                                    accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                    multiple type="file" name="rc_book_file[]"
                                                    class="form-control showcls24mec" id="rc_book_file">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                    if(isset($one->rc_book_file) && $one->rc_book_file!='') {
                    $rc_book_file = explode(',', $one->rc_book_file);
                    if(is_array($rc_book_file) && !empty($rc_book_file)){
            $m=0;
            foreach($rc_book_file as $fx) {
          ?>
                                        <input type="hidden" name="existing_img_rc_book_file[]"
                                            id="existing_img_rc_book_file{{ $m }}" value="{{ $fx }}">
                                        <div class="col-sm-2">
                                            <div class="form-group required">
                                                @if(get_file_extension($fx)!='' &&
                                                in_array(strtolower(get_file_extension($fx)),
                                                constants('image_extension')))
                                                <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                    class="img-responsive" style="max-width: 60px; max-height: 60px;"
                                                    onClick="imgDisplayInModal(this.src)" />
                                                @else
                                                <a href="{{ @$path.$fx}}" target="_blank">View File</a>
                                                @endif
                                            </div>
                                        </div>
                                        <?php
          $m++;
           }}}  
           ?>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group required">
                                                <label>Insurance (optional)</label>
                                                <input
                                                    accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                    multiple type="file" name="insurance_file[]"
                                                    class="form-control showcls24mec" id="insurance_file">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group required">
                                                <label for="name">Expiry Date </label>
                                                <?php
            $expired = "black";
            if(isset($one->insurance_expiry) && $one->insurance_expiry!="" && $one->insurance_expiry < $tendayafter){
              $expired = "red";
            }
            ?>
                                                <input type="text" name="insurance_expiry"
                                                    class="form-control showcls24mec datepicker" id="insurance_expiry"
                                                    value="{{ @$one->insurance_expiry }}" style="color:{{ $expired }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                    if(isset($one->insurance_file) && $one->insurance_file!='') {
                    $insurance_file = explode(',', $one->insurance_file);
                    if(is_array($insurance_file) && !empty($insurance_file)){
            $m=0;
            foreach($insurance_file as $fx) {
          ?>
                                        <input type="hidden" name="existing_img_insurance_file[]"
                                            id="existing_img_insurance_file{{ $m }}" value="{{ $fx }}">
                                        <div class="col-sm-2">
                                            <div class="form-group required">
                                                @if(get_file_extension($fx)!='' &&
                                                in_array(strtolower(get_file_extension($fx)),
                                                constants('image_extension')))
                                                <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                    class="img-responsive" style="max-width: 60px; max-height: 60px;"
                                                    onClick="imgDisplayInModal(this.src)" />
                                                @else
                                                <a href="{{ @$path.$fx}}" target="_blank">View File</a>
                                                @endif
                                            </div>
                                        </div>
                                        <?php
          $m++;
           }}}  
           ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group required">
                                                <label>Permit (optional)</label>
                                                <input
                                                    accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                    multiple type="file" name="permit_file[]"
                                                    class="form-control showcls24mec" id="permit_file">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group required">
                                                <label for="name">Expiry Date </label>
                                                <?php
            $expired = "black";
            if(isset($one->permit_expiry) && $one->permit_expiry!="" && $one->permit_expiry < $tendayafter){
              $expired = "red";
            }
            ?>
                                                <input type="text" name="permit_expiry"
                                                    class="form-control showcls24mec datepicker" id="permit_expiry"
                                                    value="{{ @$one->permit_expiry }}" style="color:{{ $expired }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                    if(isset($one->permit_file) && $one->permit_file!='') {
                    $permit_file = explode(',', $one->permit_file);
                    if(is_array($permit_file) && !empty($permit_file)){
            $m=0;
            foreach($permit_file as $fx) {
          ?>
                                        <input type="hidden" name="existing_img_permit_file[]"
                                            id="existing_img_permit_file{{ $m }}" value="{{ $fx }}">
                                        <div class="col-sm-2">
                                            <div class="form-group required">
                                                @if(get_file_extension($fx)!='' &&
                                                in_array(strtolower(get_file_extension($fx)),
                                                constants('image_extension')))
                                                <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                    class="img-responsive" style="max-width: 60px; max-height: 60px;"
                                                    onClick="imgDisplayInModal(this.src)" />
                                                @else
                                                <a href="{{ @$path.$fx}}" target="_blank">View File</a>
                                                @endif
                                            </div>
                                        </div>
                                        <?php
          $m++;
           }}}  
           ?>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-9">
                                            <div class="form-group required">
                                                <label>PUC (optional)</label>
                                                <input
                                                    accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                    multiple type="file" name="puc_file[]"
                                                    class="form-control showcls24mec" id="puc_file">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group required">
                                                <label for="name">Expiry Date </label>
                                                <?php
            $expired = "black";
            if(isset($one->puc_expiry) && $one->puc_expiry!="" && $one->puc_expiry < $tendayafter){
              $expired = "red";
            }
            ?>
                                                <input type="text" name="puc_expiry"
                                                    class="form-control showcls24mec datepicker"  id="puc_expiry"
                                                    value="{{ @$one->puc_expiry }}" style="color:{{ $expired }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                    if(isset($one->puc_file) && $one->puc_file!='') {
                    $puc_file = explode(',', $one->puc_file);
                    if(is_array($puc_file) && !empty($puc_file)){
            $m=0;
            foreach($puc_file as $fx) {
          ?>
                                        <input type="hidden" name="existing_img_puc_file[]"
                                            id="existing_img_puc_file{{ $m }}" value="{{ $fx }}">
                                        <div class="col-sm-2">
                                            <div class="form-group required">
                                                @if(get_file_extension($fx)!='' &&
                                                in_array(strtolower(get_file_extension($fx)),
                                                constants('image_extension')))
                                                <img src="{{ @$path.$fx}}" alt="no-image" id="img_src"
                                                    class="img-responsive" style="max-width: 60px; max-height: 60px;"
                                                    onClick="imgDisplayInModal(this.src)" />
                                                @else
                                                <a href="{{ @$path.$fx}}" target="_blank">View File</a>
                                                @endif
                                            </div>
                                        </div>
                                        <?php
          $m++;
           }}}  
           ?>
                                    </div>
                            </div>


                            <hr style="width:100%">
                            <button type="submit" class="btn btn-info float-right admin-button-add-vnew">Submit</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('admin.layout.footer')
        </div>
        
    
    <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    @include('admin.layout.js')
    <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: "dd-mm-yy"
        });
    });
    var imgpath = '{{ @$path }}';
    </script>
    <!----Add Custom Js --end------->

    @include('admin.layout.imageview')

</body>

</html>