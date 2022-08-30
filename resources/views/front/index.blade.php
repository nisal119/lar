@extends('front.layout.app')

@section('content')
<style>
    #contentsection{
        display: none;
    }
	.home-banner{
		background-image: url('https://neilpatel.com/wp-content/uploads/2017/04/chat.jpg');
		background-size: contain;
	}
</style>
</div>
</div>
</div>
    <!-- Hero Section -->
		<section class="hero-section" style="height:400px">
			<div class="layer">
				<div class="home-banner"></div>
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-12">
						
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /Hero Section -->


		<!-- How It Works -->
		<section class="how-work">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="heading howitworks">
							<h2>How It Works</h2>
						</div>
						<div class="howworksec">
							<div class="row">
								<div class="col-lg-4">
									<div class="howwork">
										<div class="iconround">
											<div class="steps">01</div>
											<i class="fa fa-search" style="font-size:40px;color:#323031"></i>
										</div>
										<h3>Register</h3>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="howwork">
										<div class="iconround">
											<div class="steps">02</div>
											<i class="fa fa-users" style="font-size:40px;color:#323031"></i>
										</div>
										<h3>Login</h3>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="howwork">
										<div class="iconround">
											<div class="steps">03</div>
											<i class="fa fa-truck" style="font-size:40px;color:#323031"></i>
										</div>
										<h3>Start Chatting</h3>
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /How It Works -->

@endsection