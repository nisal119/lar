@extends('front.layout.app')

@section('content')
@php
$current_customer=Auth::guard('customer')->user();
@endphp
<div class="content" id="contentsection">
    <div class="container">
        <div class="row"> 
        
					<div class="col-xl-9 col-md-8 offset-md-2">
					
    <div class="row chat-window">
    
        <!-- Chat User List -->
        <div class="col-lg-12 chat-cont-left">
            <div class="card mb-sm-3 mb-md-0 contacts_card flex-fill">
                <div class="card-header chat-search">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="search_btn"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" placeholder="Search" class="form-control search-chat ">
                    </div>
                </div>
                <div class="card-body contacts_body chat-users-list chat-scroll">
                    @foreach (App\Customer::where('id','!=',$current_customer->id)->get() as $item)
                    @php
                        $sender=App\Customer::find($item->id);
                        $datetime1 = new DateTime(date('Y-m-d h:i:s'));
                        $datetime2 = new DateTime($item->date);
                        $interval = $datetime1->diff($datetime2);

                        $unreadmessages=App\Chat::where(['sender_id'=>$sender->id,'sender'=>'customer','receiver_id'=>Auth::guard('customer')->user()->id,'receiver'=>'customer','seen'=>'0'])->count();
                       
                        $lastmessage=App\Chat::where(['sender_id'=>$item->id,'sender'=>'customer'])->orderBy('id','desc')->first();
                    @endphp
                    
                    <a onclick="window.location.href = '{{route('customerchatdetail',$item->id)}}';" style="cursor: pointer" class="media active">
                        <div class="media-img-wrap">
                            <div class="avatar">
                                <img src="{{is_null($sender->image) ? "https://www.kindpng.com/picc/m/24-248253_user-profile-default-image-png-clipart-png-download.png" :asset($sender->image)}}" alt="User Image" class="avatar-img rounded-circle">
                            </div>
                        </div>
                        <div class="media-body">
                            <div>
                                <div class="user-name">{{$sender->first_name}}</div>
                                @if(!is_null($lastmessage))
                                    @if($lastmessage->message_type=="media")
                                    <div class="user-last-chat"><i class="fa fa-file"></i> {{str_replace('storage/images/chat/','',$lastmessage->message)}}</div>
                                    @elseif($lastmessage->message_type=="voice")
                                    <div class="user-last-chat"><i class="fa fa-microphone"></i> Voice Message</div>
                                    @else
                                    <div class="user-last-chat">{{$lastmessage->message}}</div>
                                    @endif
                                @endif
                            </div>
                            <div>
                              
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Chat User List -->
        
        
        
    </div>

</div>
</div>
</div>
</div>


@endsection
