<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>Chat Application</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="https://cdn.iconscout.com/icon/free/png-256/chat-2639493-2187526.png">

	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,500;0,600;0,700;1,400&amp;display=swap" rel="stylesheet">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{asset('front/assets/plugins/bootstrap/css/bootstrap.min.css')}}">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{asset('front/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('front/assets/plugins/fontawesome/css/all.min.css')}}">

	<!-- Owl Carousel CSS -->
	<link rel="stylesheet" href="{{asset('front/assets/plugins/owlcarousel/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('front/assets/plugins/owlcarousel/owl.theme.default.min.css')}}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
	<!-- Main CSS -->
	<link rel="stylesheet" href="{{asset('front/assets/css/style.css')}}">
	
</head>

<body>
	
	<!-- Loader -->
	<div class="page-loading">
		<div class="preloader-inner">
			<div class="preloader-square-swapping">
				<div class="cssload-square-part cssload-square-green"></div>
				<div class="cssload-square-part cssload-square-pink" style="background: #323031"></div>
				<div class="cssload-square-blend"></div>
			</div>
		</div>
	</div>
	<!-- /Loader -->
	

	<div class="main-wrapper">

	
		@include('front.layout.header')

		@yield('content')

		<!-- Footer -->
		<footer class="footer">

		

			<!-- Footer Bottom -->
			<div class="footer-bottom">
				<div class="container">
					<!-- Copyright -->
					<div class="copyright">
						<div class="row">
							<div class="col-md-6 col-lg-6">
								<div class="copyright-text">
									<p class="mb-0">&copy; 2020 <a href="{{route('index')}}">Chat Application</a>. All rights
										reserved.</p>
								</div>
							</div>
							<div class="col-md-6 col-lg-6">
								<!-- Copyright Menu -->
								
								<!-- /Copyright Menu -->
							</div>
						</div>
					</div>
					<!-- /Copyright -->
				</div>
			</div>
			<!-- /Footer Bottom -->

		</footer>
		<!-- /Footer -->

	</div>

	<!-- Provider Register Modal -->
	<div class="modal account-modal fade multi-step" id="user-login" data-keyboard="false"
		data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 border-0">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="login-header">
						<h3>LOGIN</h3>
					</div>

					<!-- Register Form -->
				
					<!-- /Register Form -->
					<form action="{{route('customerloginsubmit')}}" method="POST">
						@csrf
						<div class="alert alert-danger otp_error">
						    Invalid Username or Password
						</div>
						@if (Session::has('error'))
                            <div class="alert alert-danger text-center otp_error_2">
                                <i class="fa fa-times"></i> {{ Session::get('error') }}
                            </div>
                        @endif
						<div class="form-group form-focus form1">
							<label for="user" class="label">Username</label>
							<input id="user_email" name="email" type="text" class="form-control">
						</div>
						<div class="form-group form-focus form1">
							<label for="pass" class="label">Password</label>
							<input id="user_pass" name="password" type="password" class="form-control" data-type="password">
						</div>
						<div class="form-group form-focus form2">
							<label for="text" class="label">OTP</label>
							<input id="text" name="otp" type="text" required class="form-control">
						</div>
						<button class="btn btn-primary btn-block btn-sm login-btn" type="button" id="otp">LOGIN</button>
						
						<button class="btn btn-primary btn-block btn-sm login-btn" type="submit" id="login">Confirm OTP</button>

					</form>
					<div class="flex items-center justify-end mt-4 text-center">
						<h5>Or Sign in with Google</h5>
						<hr>
						<a href="{{ route('redirectToGoogle') }}">
							<img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" style="margin: 0px auto;">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Provider Register Modal -->

	<!-- User Register Modal -->
	<div class="modal account-modal fade multi-step" id="user-register" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 border-0">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="login-header">
						<h3>Register</h3>
					</div>

					<!-- Register Form -->
					<form action="{{route('customerregister')}}" method="POST">
						@csrf
						<div class="form-group form-focus">
							<label class="focus-label">Name</label>
							<input type="text" class="form-control" name="name" placeholder="Jhon Doe">
						</div>
						<div class="form-group form-focus">
							<label class="focus-label">Email</label>
							<input type="text" class="form-control" name="email" placeholder="johndoe@exapmle.com">
						</div>
						<div class="form-group form-focus">
							<label class="focus-label">Create Password</label>
							<input type="password" class="form-control" name="password" placeholder="********">
						</div>
						<button class="btn btn-primary btn-block btn-sm login-btn" type="submit">SIGN UP</button>
					</form>
					<!-- /Register Form -->

				</div>
			</div>
		</div>
	</div>
	<!-- /User Register Modal -->

	<script src="{{asset('front/assets/js/jquery-3.5.0.min.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous"></script>
	<!-- Bootstrap Core JS -->
	<script src="{{asset('front/assets/js/popper.min.js')}}"></script>
	<script src="{{asset('front/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

	<!-- Owl JS -->
	<script src="{{asset('front/assets/plugins/owlcarousel/owl.carousel.min.js')}}"></script>

	<!-- Custom JS -->
	<script src="{{asset('front/assets/js/script.js')}}"></script>

    @yield('js')
    <script>
        @if (Session::has('error'))
            $("#user-login").modal();
        @endif
        $(".otp_error").hide();
        $(".form2").hide();
        $("#login").hide();
        
        $(".submit_btn").hide();
        $("#otp").click(function() {
            
            $(".otp_error").hide();
            $(".otp_error_2").hide();
            var email=$("#user_email").val();
            var password=$("#user_pass").val();
            $.ajax({
                type: 'POST',
                url: "{{ route('otpsubmit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email,
                    password: password
                },
                success: function(response) {
                    if (response) {
                         $(".form1").hide();
                          $(".form2").show();
                        $("#otp").hide();
                        $("#login").show();

                    } else {
                        $(".otp_error").show();
                    }
                }
            });
        });
    </script>
</body>

</html>