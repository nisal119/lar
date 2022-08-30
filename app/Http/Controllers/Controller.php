<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Company;
use App\MessageCenter;
use App\Vendor;
use App\Customer;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function generateRand($length = 10)
      {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
      }

    public function login_otp($customer)
    {
        
        $to = $customer->email;
        $from="muhammadahmer.004@gmail.com";
        $subject = "Your OTP";
        $message = "<!DOCTYPE html>
            <html lang='en'>
    
            <head>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css'>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js'></script>
            </head>
    
            <body>
    
                <div class='container body'>
                    Dear $customer->first_name,<br>
                    Your OTP is
                    $customer->otp
                </div>
    
            </body>
    
            </html>
            ";
    
        $headers = "From: Cosmetic Validation Detection" . "\r\n";;
        // boundary
        $headers.= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
        // Additional headers
    
    
        $ok = @mail($to, $subject, $message, $headers, "-f " . $from);
        dd($ok);
            return $ok;
      }
}
