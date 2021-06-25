<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RozerPay Example</title>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha512-M5KW3ztuIICmVIhjSqXe01oV2bpe248gOxqmlcYrEzAvws7Pw3z6BK0iGbrwvdrUQUhi3eXgtxp5I8PDo9YfjQ==" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha512-MoRNloxbStBcD8z3M/2BmnT+rg4IsMxPkXaGh2zD6LGNNFE80W3onsAhRcMAMrSoyWL9xD7Ert0men7vR8LUZg==" crossorigin="anonymous" />
</head>
<body>
	@if($token)
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-3 col-md-offset-6">
                        @if($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Error!</strong> {{ $message }}
                            </div>
                        @endif
                            <div class="alert alert-success success-alert alert-dismissible fade show" role="alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Success!</strong> <span class="success-message"></span>
                            </div>
                        {{ Session::forget('success') }}
                        <div class="card card-default">
                            <div class="card-header">
                                Razorpay Example
                            </div>

                            <div class="card-body text-center">
                                <div class="form-group mt-1 mb-1">
                                <input type="hidden" class="temptoken" value="">
                                <input type="hidden" class="razor_orderid" value="">
                                    <input type="number" name="amount" class="form-control amount" value="1000" placeholder="Enter Amount" maxlength="8" required>
                                </div>
                                <button id="rzp-button1" class="btn btn-info btn-sm">Pay & Add to Wallet</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </main>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
		var RAZOR_KEY = "{{ env('RAZOR_KEY') }}";
				
        $('body').on('click','#rzp-button1',function(e){
			e.preventDefault();
		    var amount = $('.amount').val();
           var total_amount = amount * 100;
		   
			
			$.ajax({
                        type:'POST',
						dataType: 'json',
                        url: "http://192.168.0.115/bigdaddy/public/api/customer/precheck-wallet",
						headers: {
							"token":"{{ $token }}",
						},
                        data:{ 
								amount:amount, 
								},
                        success:function(data){
							if(data.success==1){
								$(".temptoken").val(data.temptoken);
								$(".razor_orderid").val(data.data.razor_orderid);
							}
							else{
								$(".temptoken").val("");
								$(".razor_orderid").val("");
								alert(data.message);
								return false;
							}
                       },  error: function(){  $(".temptoken").val("");  $(".razor_orderid").val(""); alert('Something Went Wrong!'); return false; }
             });
			 
			 
			 
			 
			 var temptoken = $('.temptoken').val();
			 var razor_orderid = $('.razor_orderid').val();
			 
			 console.log($('.temptoken').val());
			
					
           if(temptoken!=""){
			   
            var RazorPayOptionsWalletAdd = {
                "key": RAZOR_KEY,
                "amount": total_amount,
                "currency": "INR",
                "name": "BigDaddy Testing",
                "description": "Wallet Payment Transaction OK",
                "image": "https://bigdaddylogistics.com/logo.png",
				"order_id": razor_orderid,
                "handler": function (response){
                    console.log(response);
                    
                    $.ajaxSetup({
                        headers: {
							//"temptoken": temptoken,
							"token":"{{ $token }}",
                        }
                    });
					
                    $.ajax({
                        type:'POST',
                        url: "http://192.168.0.115/bigdaddy/public/api/customer/add-razorpay-payment-wallet",
                        data:{ 
								razorpay_payment_id:response.razorpay_payment_id, 
								razorpay_signature:response.razorpay_signature, 
								razor_orderid: razor_orderid 
								},
                        success:function(data){
							// code after payment success
                        }
                    });
                },
                "prefill": {
                    "name": "Test name",
                    "email": "gautam.technomads@gmail.com",
                    "contact": "918905402995",
					"method": "card", // card, upi, netbanking
                },
                "notes": {
                    "address": "wallet payment test.",
                },
                "theme": {
                    "color": "#F37254"
                }
            };
            var rzp1 = new Razorpay(RazorPayOptionsWalletAdd);
			rzp1.on('payment.failed', function (response){
        		console.log(response);
			});
            rzp1.open();
			
		   }
        });
		
  
  
    </script>
    @else
    <h4>Not Found</h4>
    @endif


   
</body>
</html>