<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/forms/theme-checkbox-radio.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/assets/css/forms/switches.css') }}">
</head>

 <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->
<body class="form">
<div class="form-container outer background-colorv">
  <div class="form-form">
    <div class="form-form-wrap">
      <div class="form-container">
        <div class="form-content">
          <div class="login-logo">
          <img src="{{ asset('logo.png')}}" class="login-page-logo-width">
          </div>
          <!-- <h1 class="login-page-vnone">Login</h1>
          <p class="login-page-vnone">Log in to your account to continue.</p> -->
          <form class="text-left" method="post" action="{{ route('log-in') }}" enctype="multipart/form-data" id="form-login-id">
          @csrf
           <input type="hidden" id="hid" name="hid" value="0">
            <div class="form">
              <div id="username-field" class="field-wrapper input">
                <label for="username">USERNAME</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user login-profile-vcolor">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <input id="username" name="username" type="text" class="form-control forem-login-controlv" placeholder="Type Your username Or Email address">
              </div>
              <div id="password-field" class="field-wrapper input mb-2">

                  <label for="password">PASSWORD</label>
   
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock login-profile-vcolor">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input id="password" name="password" type="password" class="form-control forem-login-controlv" placeholder="Your Password">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye login-profile-vcolor">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
                </svg> </div>
              <div class="d-sm-flex justify-content-between">
                <div class="field-wrapper">
                  <button type="submit" class="btn btn-primary admin-button-add-vnew" value="">Log In</button>
                </div>
              </div>
              <div class="division"> <span></span> </div>
              
              <p class="signup-link"></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BEGIN GLOBAL MANDATORY SCRIPTS --> 
<script src="{{ asset('admin_assets/assets/js/libs/jquery-3.1.1.min.js') }}"></script> 
<script src="{{ asset('admin_assets/bootstrap/js/popper.min.js') }}"></script> 
<script src="{{ asset('admin_assets/bootstrap/js/bootstrap.min.js') }}"></script> 
<!-- END GLOBAL MANDATORY SCRIPTS --> 
<script src="{{ asset('admin_assets/assets/js/authentication/form-2.js') }}"></script>


</body>
</html>