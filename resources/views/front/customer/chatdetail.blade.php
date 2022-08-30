@extends('front.layout.app')

@section('content')
@php
$current_customer=Auth::guard('customer')->user();
@endphp
<style>
    .theiaStickySidebar{
        display: none;
    }
</style>
<script src="{{asset('front/voice/src/recorder.js')}}"></script>
<script src="{{asset('front/voice/src/Fr.voice.js')}}"></script>
<script src="{{asset('front/voice/js/jquery.js')}}"></script>
{{-- <script src="{{asset('front/voice/js/app.js')}}"></script> --}}
<div class="content" id="contentsection">
    <div class="container">
        <div class="row"> 
        
            <div class="col-lg-12">
            
                <div class="row chat-window">
                
                    <!-- Chat User List -->
                    <div class="col-lg-5 col-xl-4 chat-cont-left">
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
                                            <img src="{{is_null($sender->image) ? asset("149071.png") :asset($sender->image)}}" alt="User Image" class="avatar-img rounded-circle">
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
                                            @if($interval->days > 0)
                                            <div class="last-chat-time">{{$interval->days}} days ago</div>
                                            @elseif($interval->format('%h') > 0)
                                            <div class="last-chat-time">{{$interval->format('%h')}} hr ago</div>
                                            
                                            @elseif($interval->format('%i') > 0)
                                            <div class="last-chat-time">{{$interval->format('%i')}} mint ago</div>
                                            
                                            @else
                                            <div class="last-chat-time">{{$interval->format('%s')}} sec ago</div>
                                            @endif
                                            @if($unreadmessages>0)
                                            <div class="badge badge-success badge-pill">{{$unreadmessages}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Chat User List -->
                    
                    <!-- Chat Content -->
                    <div class="col-lg-7 col-xl-8 chat-cont-right">
                    
                        <!-- Chat History -->
                        <div class="card mb-0">

                            <div class="card-header msg_head">
                                <div class="d-flex bd-highlight">
                                    <a id="back_user_list" href="javascript:void(0)" class="back-user-list">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <div class="img_cont">
                                        <img class="rounded-circle user_img" src="{{is_null($customer->image) ? asset("149071.png") :asset($customer->image)}}" alt="">
                                    </div>
                                    <div class="user_info">
                                        <span><strong id="receiver_name">{{$customer->first_name}}</strong></span>
                                        <p class="mb-0">Messages</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body msg_card_body">
                                <ul class="list-unstyled" id="chatmessagearea" style="overflow-y:auto;height:400px">
                                    @foreach ($message as $item)
                                        @php
                                            $datetime1 = new DateTime(date('Y-m-d h:i:s'));
                                            $datetime2 = new DateTime($item->date);
                                            $interval = $datetime1->diff($datetime2);
                                        @endphp
                                        @if($item->sender_id==Auth::guard('customer')->user()->id)
                                        <li class="media sent">
                                       
                                            <div class="media-body">
                                                <div class="msg-box">
                                                    <div>
                                                        @if($item->message_type=="media")
                                                        @if(strpos($item->message,"jpg")!==false || strpos($item->message,"png")!==false || strpos($item->message,"jpeg")!==false)
                                                            <p><a href="{{asset($item->message)}}" download style="color:#272b41"><img src="{{asset($item->message)}}" height="120px" width="auto"></a></p>

                                                        @else
                                                            <p><a href="{{asset($item->message)}}" download style="color:#272b41"><i class="fa fa-download"></i> {{str_replace('storage/images/chat/','',$item->message)}}</a></p>
                                                        @endif
                                                        @elseif($item->message_type=="voice")
                                                            <p><audio src="{{url('/').$item->message}}" controls style="outline: none"></audio></p>

                                                        @else
                                                            <p>{{$item->message}}</p>
                                                        @endif
                                                        <ul class="chat-msg-info">
                                                            <li>
                                                                <div class="chat-time">
                                                                    @if($interval->days > 0)
                                                                    <span>{{$interval->days}} days ago</span>
                                                                    @elseif($interval->format('%h') > 0)
                                                                    <span>{{$interval->format('%h')}} hours ago</span>
                                                                    
                                                                    @elseif($interval->format('%i') > 0)
                                                                    <span>{{$interval->format('%i')}} mint ago</span>
                                                                    
                                                                    @else
                                                                    <span>{{$interval->format('%s')}} sec ago</span>
                                                                    @endif
                                                                   
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @else
                                       
                                        <li class="media received">
                                            <div class="avatar">
                                                <img src="{{is_null($customer->image) ? asset("149071.png") :asset($customer->image)}}" alt="User Image" class="avatar-img rounded-circle">
                                            </div>
                                            <div class="media-body">
                                                <div class="msg-box">
                                                    <div>
                                                        @if($item->message_type=="media")
                                                        @if(strpos($item->message,"jpg")!==false || strpos($item->message,"png")!==false || strpos($item->message,"jpeg")!==false)
                                                            <p><a href="{{asset($item->message)}}" download style="color:white"><img src="{{asset($item->message)}}" height="120px" width="auto"></a></p>

                                                        @else
                                                            <p><a href="{{asset($item->message)}}" download style="color:white"><i class="fa fa-download"></i> {{str_replace('storage/images/chat/','',$item->message)}}</a></p>
                                                        @endif
                                                        @elseif($item->message_type=="voice")
                                                            <p><audio src="{{url('/').$item->message}}" controls style="outline: none"></audio></p>

                                                        @else
                                                        
                                                            <p>{{$item->message}}</p>
                                                        @endif
                                                       
                                                        <ul class="chat-msg-info">
                                                            <li>
                                                                <div class="chat-time">
                                                                    @if($interval->days > 0)
                                                                    <span>{{$interval->days}} days ago</span>
                                                                    @elseif($interval->format('%h') > 0)
                                                                    <span>{{$interval->format('%h')}} hours ago</span>
                                                                    
                                                                    @elseif($interval->format('%i') > 0)
                                                                    <span>{{$interval->format('%i')}} mint ago</span>
                                                                    @else
                                                                    <span>{{$interval->format('%s')}} sec ago</span>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </li>

                                        @endif
                                    @endforeach
                                   
                                   
                                  
                                </ul>
                            
                            </div>
                            <form action="#0" id="chatform" enctype="multipart/form-data" method="POST">
                                @csrf
                            <div class="card-footer">
                                <div class="" style="display: flex">
                                  
                                    <input type="hidden" name="sender_id" value="{{Auth::guard('customer')->user()->id}}">
                                    <input type="hidden" name="sender" value="customer">
                                    <input type="hidden" name="receiver_id" value="{{$customer->id}}">
                                    <input type="hidden" name="receiver" value="customer">
                                    <label for="filechat" class="btn btn-primary" style="height: 100%;margin-top:5px;margin-right:5px" id="sendfile">
                                        <i class="fa fa-paperclip"></i>
                                    </label>
                                    <label for="" style="display: none" class="btn btn-primary recordButton" id="record" style="height: 100%;margin-top:5px;margin-right:5px" id="sendfile">
                                        <i class="fa fa-microphone"></i>
                                    </label>
                                    <label for="" class="btn btn-primary" id="pause" style="height: 100%;margin-top:5px;margin-right:5px" id="sendfile">
                                        <i class="fa fa-stop"></i>
                                    </label>
                                  
                                    <label for="" class="btn btn-primary" id="play" style="height: 100%;margin-top:5px;margin-right:5px" id="sendfile">
                                        <i class="fa fa-play"></i>
                                    </label>
                                    <audio controls id="audio" style="width: 100%"></audio>
                                    <label for="" class="btn btn-primary" id="save" style="height: 100%;margin-top:5px;margin-left:5px" id="sendfile">
                                        <i class="fa fa-paper-plane"></i>
                                    </label>
                                      
                                    
                                  
                                    <input class="form-control type_msg mh-auto empty_check" name="message" id="chatmessage" placeholder="Type your message...">
                                   
                                    <label class="btn btn-primary" style="height: 100%;margin-top:5px;margin-left:5px" id="chatbtn">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </label>
                                       
                                  
                                    <input type="hidden" name="message_type" id="message_type" value="message">
                                    <input type="file" name="file" id="filechat" style="display: none">
                                 
                                   
                                   
                                    
                                </div>
                            </div>
                        </form>
                            
                        </div>

                    </div>
                    <!-- Chat Content -->
                    
                </div>

            </div>
        </div>
    </div>
</div>
<form action="" id="getform">
    @csrf
    <input type="hidden" name="sender_id" value="{{Auth::guard('customer')->user()->id}}">
    <input type="hidden" name="sender" value="customer">
    <input type="hidden" name="receiver_id" value="{{$customer->id}}">
    <input type="hidden" name="receiver" value="customer">
</form>

@endsection
@section('js')
    <script>
         var height = 0;
              
              $('#chatmessagearea .media').each(function(i, value){
                  height += parseInt($(this).height());
              });
              height += '';
              $("#chatmessagearea").animate({scrollTop: height+1},2000);

        $("#chatbtn").click(function(){
            var chatmessage=$("#chatmessage").val();
            $("#chatmessagearea").append('<li class="media sent"> <div class="media-body"> <div class="msg-box"> <div> <p>'+chatmessage+'</p> <ul class="chat-msg-info"> <li> <div class="chat-time"> <span>now</span> </div> </li> </ul> </div> </div> </div> </li>');
            $.ajax({
            type: "POST",
            data:$('#chatform').serialize(),
            url: "{{route('customerchatsave')}}",
            success: function(data){
                $("#chatmessage").val("");
                var height = 0;
              
                $('#chatmessagearea .media').each(function(i, value){
                    height += parseInt($(this).height());
                });
                height += '';
                $("#chatmessagearea").animate({scrollTop: height+1},2000);

            }
            });
           
          
        });

        $("#filechat").change(function(){
            
            var formData = new FormData(this.form);
            formData.append('file', $("#filechat")[0].files[0]);
            var filename=$("#filechat")[0].files[0].name;
            $("#chatmessagearea").append('<li class="media sent"> <div class="media-body"> <div class="msg-box"> <div> <p><i class="fa fa-file"></i> '+filename+'</p> <ul class="chat-msg-info"> <li> <div class="chat-time"> <span>now</span> </div> </li> </ul> </div> </div> </div> </li>');
            $("#message_type").val("media");
            $.ajax({
            type: "POST",
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            url: "{{route('customerchatsavemedia')}}",
            success: function(data){
                $("#chatmessage").val("");
                var height = 0;
              
              $('#chatmessagearea .media').each(function(i, value){
                  height += parseInt($(this).height());
              });
              height += '';
              $("#chatmessagearea").animate({scrollTop: height+1},2000);

            }
            });
        });
        loadmessages();
        setInterval(() => {
            loadmessages();
        }, 2000);

        function loadmessages() {
            $.ajax({
            type: "POST",
            data: $('#getform').serialize(),
            url: "{{route('customerchatget')}}",
            success: function(data){
                $("#chatmessagearea").append(data);
               if(data!=""){
                var height = 0;
              
              $('#chatmessagearea .media').each(function(i, value){
                  height += parseInt($(this).height());
              });
              height += '';
              $("#chatmessagearea").animate({scrollTop: height+1},2000);

               }
            }
            });
        }
        </script>

        <script>
            function restore(){
  $("#record, #live").removeClass("disabled");
  $("#pause").replaceWith('<a class="button one" id="pause">Pause</a>');
  $(".one").addClass("disabled");
  Fr.voice.stop();
}

function makeWaveform(){
  var analyser = Fr.voice.recorder.analyser;

  var bufferLength = analyser.frequencyBinCount;
  var dataArray = new Uint8Array(bufferLength);

  /**
   * The Waveform canvas
   */
  var WIDTH = 500,
      HEIGHT = 200;

  var canvasCtx = $("#level")[0].getContext("2d");
  canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);

  function draw() {
    var drawVisual = requestAnimationFrame(draw);

    analyser.getByteTimeDomainData(dataArray);

    canvasCtx.fillStyle = 'rgb(200, 200, 200)';
    canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);
    canvasCtx.lineWidth = 2;
    canvasCtx.strokeStyle = 'rgb(0, 0, 0)';

    canvasCtx.beginPath();

    var sliceWidth = WIDTH * 1.0 / bufferLength;
    var x = 0;
    for(var i = 0; i < bufferLength; i++) {
      var v = dataArray[i] / 128.0;
      var y = v * HEIGHT/2;

      if(i === 0) {
        canvasCtx.moveTo(x, y);
      } else {
        canvasCtx.lineTo(x, y);
      }

      x += sliceWidth;
    }
    canvasCtx.lineTo(WIDTH, HEIGHT/2);
    canvasCtx.stroke();
  };
  draw();
}

$("#audio").hide();
$("#pause").hide();
$(document).ready(function(){
  $(document).on("click", "#record:not(.disabled)", function(){
    $("#chatbtn").hide();
      $("#audio").show();
      $("#chatmessage").hide();
      $(this).hide();
      $("#pause").show();
    Fr.voice.record($("#live").is(":checked"), function(){
      $(".recordButton").addClass("disabled");

      $("#live").addClass("disabled");
      $(".one").removeClass("disabled");

      makeWaveform();
    });
  });

  $("#play").hide();
  $("#save").hide();
  
  $(document).on("click", "#pause:not(.disabled)", function(){
      $("#record").show();
      $("#play").show();
      $("#save").show();
      $(this).hide();
    if($(this).hasClass("resume")){
      Fr.voice.resume();
    }else{
      Fr.voice.pause();
  
    }
  });

  
  $(document).on("click", "#play:not(.disabled)", function(){
  
    if($(this).parent().data("type") === "mp3"){
      Fr.voice.exportMP3(function(url){
        $("#audio").attr("src", url);
        $("#audio")[0].play();
      }, "URL");
    }else{
      Fr.voice.export(function(url){
        $("#audio").attr("src", url);
        $("#audio")[0].play();
      }, "URL");
    }
  
  });

  $(document).on("click", "#download:not(.disabled)", function(){
    if($(this).parent().data("type") === "mp3"){
      Fr.voice.exportMP3(function(url){
        $("<a href='" + url + "' download='MyRecording.mp3'></a>")[0].click();
      }, "URL");
    }else{
      Fr.voice.export(function(url){
        
        $("<a href='" + url + "' download='MyRecording.wav'></a>")[0].click();
      }, "URL");
    }
    restore();
  });


  $(document).on("click", "#save", function(){
   

    $("#chatmessagearea").append('<li class="media sent"> <div class="media-body"> <div class="msg-box"> <div> <p><i class="fa fa-microphone"></i> Voice Message</p> <ul class="chat-msg-info"> <li> <div class="chat-time"> <span>now</span> </div> </li> </ul> </div> </div> </div> </li>');

    Fr.voice.export(function(base64){
       
  $.post("{{route('customerchatsavevoice')}}", {"audio" : base64,'_token': "{{ csrf_token() }}",'receiver_id': {{$id}}}, function(){
   $("#play").hide();
   $("#save").hide();
   $("#pause").hide();
   $("#audio").hide();
   $("#chatmessage").show();
   $("#chatbtn").show();
   var height = 0;
              $('#chatmessagearea .media').each(function(i, value){
                  height += parseInt($(this).height());
              });
              height += '';
              $("#chatmessagearea").animate({scrollTop: height+1},2000);

  });
}, "base64");
        
  });
});

$('#chatmessage').keypress(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });
        </script>
@endsection