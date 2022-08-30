@extends('front.layout.app')

@section('content')
@php
    $customer=Auth::guard('customer')->user();
@endphp
<div class="content" id="contentsection">
    <div class="container">
        <div class="row"> 
        
					<div class="col-xl-9 col-md-8 offset-md-2">
                        @if(Session::has('success'))
                        <div class="alert alert-success text-center">
                            <i class="fa fa-check"></i> {{ Session::get('success') }}
                        </div>
                        @endif

                        @if(Session::has('error'))  
                        <div class="alert alert-danger text-center">
                            <i class="fa fa-times"></i> {{ Session::get('error') }}
                        </div>
                        @endif
						<div class="tab-content pt-0">
							<div class="tab-pane show active" id="user_profile_settings">
								<div class="widget">
									<div class="section-header text-center">
                                        <h2>Profile Settings</h2>
                                    </div>
									<form method="POST" action="{{route('customerprofilesubmit')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
											<div class="col-xl-12">
												<h5 class="form-title">Basic Information</h5>
											</div>
											<div class="form-group col-xl-12">
												<div class="media align-items-center mb-3">
                                                    @if(isset($customer->image))
                                                        <img class="user-image" id="profileimage" src="{{asset($customer->image)}}" alt="">
                                                    @else
                                                        <img class="user-image" id="profileimage" src="{{asset('front/placeholder.png')}}" alt="">
                                                    @endif
													<div class="media-body">
														<h5 class="mb-0">{{$customer->first_name}}</h5>
														<p>Max file size is 20mb</p>
														<input type="file" name="image" accept="image/*" onchange="document.getElementById('profileimage').src = window.URL.createObjectURL(this.files[0])" class="form-control">
														
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-xl-6">
												<label class="mr-sm-2">Name</label>
												<input class="form-control" type="text" value="{{$customer->first_name}}" name="first_name">
											</div>
											<div class="form-group col-xl-6">
												<label class="mr-sm-2">Email</label>
												<input class="form-control" type="email" value="{{$customer->email}}" name="email">
											</div>
											<div class="form-group col-xl-12">
												<label class="mr-sm-2">Password</label>
												<input class="form-control" type="password" value="{{$customer->pin}}" name="password">
											</div>
										
										
										
											<div class="form-group col-xl-12 text-right">
												<button name="form_submit" id="form_submit" class="btn btn-primary pl-5 pr-5" type="submit">Update</button>
											</div> 
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
