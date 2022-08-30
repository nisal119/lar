<!-- Header -->
<header class="header">
    <nav class="navbar navbar-expand-lg header-nav">
        <div class="navbar-header">
            <a id="mobile_btn" href="javascript:void(0);">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>
            <a href="{{route('index')}}" class="navbar-brand logo">
                <img src="https://cdn.iconscout.com/icon/free/png-256/chat-2639493-2187526.png" class="img-fluid" alt="Logo">
            </a>
            <a href="{{route('index')}}" class="navbar-brand logo-small">
                <img src="https://cdn.iconscout.com/icon/free/png-256/chat-2639493-2187526.png" class="img-fluid" alt="Logo">
            </a>
        </div>
        <div class="main-menu-wrapper">
            <div class="menu-header">
                <a href="{{route('index')}}" class="menu-logo">
                    <img src="https://cdn.iconscout.com/icon/free/png-256/chat-2639493-2187526.png" style="height: 40px"  class="img-fluid" alt="Logo">
                </a>
                <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i
                        class="fas fa-times"></i></a>
            </div>
            <ul class="main-nav">
               
                @if(!Auth::guard('customer')->check())
                <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#user-register">Sign Up</a>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#user-login">Login</a>
                </li>
                @else
                @php
                    $customer=Auth::guard('customer')->user();
                    
                @endphp
                <li>
                    <a href="{{route('customerchat')}}">
                      Chat
                    </a>
                </li>
                <li>
                    <a href="{{route('customerprofile')}}">Profile</a>
                </li>
                <li>
                    <a href="{{route('customerlogout')}}">
                     Logout
                    </a>
                </li>
                <li class="nav-item dropdown has-arrow logged-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false">
                        <span class="user-img">
                            @if(!is_null($customer->image))
                                    <img class="rounded-circle" alt="profile image" src="{{asset(Auth::guard('customer')->user()->image)}}" alt="">
                                @else
                                    <img class="rounded-circle" alt="profile image" src="https://www.kindpng.com/picc/m/24-248253_user-profile-default-image-png-clipart-png-download.png" alt="">
                                @endif
                                {{$customer->first_name}}
                                <span class="badge badge-success">Logged In</span>
                        </span>
                    </a>
                    
                </li>
                @endif
            </ul>
        </div>
       
    </nav>
</header>
<!-- /Header -->