<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Chat;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use DateTime;
use Laravel\Socialite\Facades\Socialite;

class HomeController extends Controller
{
    public function index()
    {
        return view("front.index");
    }



    public function register(Request $request)
    {
        if (!count(Customer::where('email', $request->email)->get())) {
            $customer = new Customer;
            $customer->email = $request->email;
            $customer->first_name = $request->name;
            $customer->password = Hash::make($request->password);
            $customer->pin = $request->password;
            $customer->save();
            return back()->with('success', 'Customer Account Created');
        } else {
            return back()->with('error', 'Profile with email already exist');
        }
    }
    
    
    
    public function otpsubmit(Request $request)
    {
        $customer = Customer::where(['email' => $request->email, 'pin' => $request->password])->first();
        if (!is_null($customer)) {
            $otp = $this->generateRand(5);
            $customer->otp = $otp;
            $customer->save();
            $this->login_otp($customer);

            return true;
        } else {
            return false;
        }
    }

    public function loginsubmit(Request $request)
    {

        if (Auth::guard('customer')->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'otp'=>$request->input('otp')
        ])) {
            $customer = Auth::guard('customer')->user();
            return redirect()->route('index');
        } else {
            return redirect()->route('index')
                ->with('error', 'Ooops! Invalid OTP')
                ->withInput();
        }
    }



    public function customerprofile()
    {
        return view("front.customer.profile");
    }

    public function customerprofilesubmit(Request $request)
    {
        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $customer->first_name = $request->first_name;
        $customer->email = $request->email;
        $customer->pin = $request->password;
        $customer->password = Hash::make($request->password);
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->country = $request->country_id;
        $customer->state = $request->state;
        $customer->city = $request->city;
        $customer->postal_code = $request->postal_code;
        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('image')->storeAs("images/customer/", $fileNameToStore);
            $customer->image = "storage/images/customer/" . $fileNameToStore;
        }
        $customer->save();

        return back()->with('success', 'Profile Updated Successfully');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('index');
    }

    public function chat()
    {
        $id = Auth::guard('customer')->user()->id;
        $chat = DB::select("select * from chats where receiver_id='$id' and receiver='customer' GROUP by sender_id");
        return view('front.customer.chat', compact('chat'));
    }

    public function chatdetail($id)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $chat = DB::select("select * from chats where receiver_id='$customer_id' and receiver='customer' GROUP by sender_id");
        $customer = Customer::find($id);

        $message = DB::select("select * from chats where receiver_id='$customer_id' and sender_id='$id' or receiver_id='$id' and sender_id='$customer_id' ");
        DB::update("update chats set seen=1 where receiver_id='$customer_id' and sender_id='$id' or receiver_id='$id' and sender_id='$customer_id'");
        return view('front.customer.chatdetail', compact("id", "chat", "customer", "message"));
    }

    public function chatsave(Request $request)
    {
        $chat = new Chat;

        $chat->sender_id = $request->sender_id;
        $chat->sender = $request->sender;
        $chat->receiver_id = $request->receiver_id;
        $chat->receiver = $request->receiver;
        $chat->message = $request->message;
        $chat->message_type = $request->message_type;
        $chat->seen = 0;
        $chat->date = date('Y-m-d h:i:s');
        $chat->save();
    }

    public function chatsavemedia(Request $request)
    {
        $chat = new Chat;

        $chat->sender_id = $request->sender_id;
        $chat->sender = $request->sender;
        $chat->receiver_id = $request->receiver_id;
        $chat->receiver = $request->receiver;
        if ($request->hasFile('file')) {
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('file')->storeAs("images/chat/", $fileNameToStore);
            $chat->message = "storage/images/chat/" . $fileNameToStore;
        }
        $chat->message_type = 'media';
        $chat->seen = 0;
        $chat->date = date('Y-m-d h:i:s');
        $chat->save();
    }

    public function chatsavevoice(Request $request)
    {
        $audio = $_POST['audio'];
        $audio = str_replace('data:audio/wav;base64,', '', $audio);
        $decoded = base64_decode($audio);
        $random = rand(0, 1020300);
        $file_location = './public/storage/chat/voice/recorded_audio' . $random . time() . '.wav';

        file_put_contents($file_location, $decoded);

        $chat = new Chat;

        $chat->sender_id = Auth::guard('customer')->user()->id;
        $chat->sender = 'customer';
        $chat->receiver_id = $request->receiver_id;
        $chat->receiver = 'customer';
        $chat->message = $file_location;
        $chat->message_type = 'voice';
        $chat->seen = 0;
        $chat->date = date('Y-m-d h:i:s');
        $chat->save();
    }

    public function chatget(Request $request)
    {
        $chat = Chat::where(['receiver_id' => $request->sender_id, 'receiver' => $request->sender, 'sender_id' => $request->receiver_id, 'sender' => $request->receiver, 'seen' => 0])->get();
        $customer = Customer::find($request->receiver_id);
        $output = "";
        foreach ($chat as $item) {
            $datetime1 = new DateTime(date('Y-m-d h:i:s'));
            $datetime2 = new DateTime($item->date);
            $interval = $datetime1->diff($datetime2);

            $timeoutput = "";
            if ($interval->days > 0) {
                $timeoutput = '<span>' . $interval->days . '</span>';
            } elseif ($interval->format('%h') > 0) {
                $timeoutput = '<span>' . $interval->format('%h') . ' hours ago</span>';
            } elseif ($interval->format('%i') > 0) {
                $timeoutput = '<span>' . $interval->format('%i') . ' mint ago</span>';
            } else {
                $timeoutput = '<span>' . $interval->format('%s') . ' sec ago</span>';
            }

            $customer_image = is_null($customer->image) ? "https://www.kindpng.com/picc/m/24-248253_user-profile-default-image-png-clipart-png-download.png" : asset($customer->image);

            if ($item->message_type == 'message') {

                $output .= '
            <li class="media received">
                <div class="avatar">
                    <img src="' . $customer_image . '" alt="User Image" class="avatar-img rounded-circle">
                </div>     
                <div class="media-body">
                    <div class="msg-box">
                        <div>
                            <p>' . $item->message . '</p>
                            <ul class="chat-msg-info">
                                <li>
                                    <div class="chat-time">
                                        ' . $timeoutput . '
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            ';
            } else if ($item->message_type == 'voice') {
                $output .= '
                <li class="media received">
                <div class="avatar">
                    <img src="' . $customer_image . '" alt="User Image" class="avatar-img rounded-circle">
                </div>     
                <div class="media-body">
                    <div class="msg-box">
                        <div>
                           
                            <p><audio src="' . url('/') . $item->message . '" controls style="outline: none"></audio></p>

                            <ul class="chat-msg-info">
                                <li>
                                    <div class="chat-time">
                                        ' . $timeoutput . '
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
                ';
            } else {
                if (strpos($item->message, "jpg") !== false || strpos($item->message, "png") !== false || strpos($item->message, "jpeg") !== false) {
                    $fileoutput = '<p><a href="' . asset($item->message) . '" download><img src="' . asset($item->message) . '" height="120px" width="auto"></a></p>';
                } else {
                    $fileoutput = '<p><a href="' . asset($item->message) . '" download><i class="fa fa-download"></i> ' . str_replace('storage/images/chat/', '', $item->message) . '</a></p>';
                }
                $output .= '
                <li class="media received">
                <div class="avatar">
                    <img src="' . $customer_image . '" alt="User Image" class="avatar-img rounded-circle">
                </div>     
                <div class="media-body">
                    <div class="msg-box">
                        <div>
                            ' . $fileoutput . '
                            <ul class="chat-msg-info">
                                <li>
                                    <div class="chat-time">
                                        ' . $timeoutput . '
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
                ';
            }

            DB::update("update chats set seen=1 where id='$item->id'");
        }
        echo $output;
    }

    public function customerlogout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('index');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
            $user = Socialite::driver('google')->user();
            
            $finduser = Customer::where('email', $user->email)->first();
            if($finduser){
       
                if (Auth::guard('customer')->attempt(['email' => $finduser->email,'password' => $finduser->pin])) {
            
                    $customer = Auth::guard('customer')->user();
                    return redirect()->route('index');
                }
       
            }else{
                
                $pass=Hash::make("google123");
                $newUser = Customer::create([
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => $pass,
                    'pin'=>"google123"
                ]);
      
               
                if (Auth::guard('customer')->attempt(['email' => $user->email,'password' => $newUser->pin])) {
            
                    $customer = Auth::guard('customer')->user();
                    return redirect()->route('index');
                }
            }
      
    }
}
