<!----Add here Crud Js ----start----->
<script type="text/javascript" >
function randomIntFromInterval(min, max) { 
  return Math.floor(Math.random() * (max - min + 1) + min);
}
function makeid(length,ifnumberonly=0) {
   var result           = '';
   if(ifnumberonly==0){
   var characters  = 'abcdefghijklmVCXZQUIOP1234506789@$#!&-=:][{}KLnopqrstuvwxyzASDFGHJMNBWERTY';
   }
   else
   {
	    var characters = '123456789';
   }
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return  result;
}

$(document).ready(function () {
	/*--------------*/  
 $('body').on('click', '.change_status', function () {
     var id = $(this).data('id');
     var val = $(this).data('val');

	 if(val==2 && isdelete=='Y'){
		 var x = confirm("are you sure to delete this ?");
		 if(x==false){ return false;}
	 }
	 else if(val==2 && isdelete=='N'){
		 alert("No delete permission."); return false;
	 }
		 $.ajax({
					url: "{{ route('change_status') }}",
					data: {
					  _token:'{{ csrf_token() }}',
					  'id': id, 
					  'value': val,
					  'tbl' : tbl
					},
					type: 'get',
					dataType: 'json',
					success: function (data) {
					  dataTable.draw(false);
					}
				});

  });
  
  
/*--------------*/  
});


</script>
<!----Add here Crud Js --end------->