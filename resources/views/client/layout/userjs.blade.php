<!-- BEGIN GLOBAL MANDATORY SCRIPTS --> 
<script type="text/javascript">
    window.PUSHER_APP_KEY = '{{ env("PUSHER_APP_KEY") }}';
    window.PUSHER_APP_CLUSTER = '{{ env("PUSHER_APP_CLUSTER") }}';
    window.PUSHER_APP_CHANNELNAME = '{{ env("PUSHER_APP_CHANNELNAME_USER_WEB") }}';
    window.PUSHER_APP_EVENTNAME = '{{ env("PUSHER_APP_EVENTNAME_USER_WEB") }}';
    window.PUSHER_APP_USERWEBID = '0';
</script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="{{ asset('js/customuser.js')}}"></script> 
<!-- END GLOBAL MANDATORY SCRIPTS --> 
<!----Add here global Js ----start----->
<script type="text/javascript" >
</script>
<!----Add here global Js --end------->