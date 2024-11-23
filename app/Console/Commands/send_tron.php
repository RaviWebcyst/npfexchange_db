<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\payment;
use App\crypto_wallet;

class send_tron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:tron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send tron and transfer usdt';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        \Log::info("send tron curl working");


        $payments = payment::where("chain","tron")->where("transaction_status",1)->where("gas_status",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send-tron";

            $amount = 3;

            // $amount = $pay->amount;

            $address = $pay->address;

            //for testnet
            // $data = [
            //     'amount' => (string)$amount,
            //     'toAddress' => $address,
            //     'walletPrivate' => "1794114e2c890b34191a7eb184a97b9183058f95f5a121f50764c4a90af2f0df",
            //     "contractAddress"=>"TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj",
            //     'tokenNet' => 'testnet',
            // ];

            // for livenet
            // $data = [
            //     'amount' => (string)$amount,
            //     'toAddress' => $address,
            //     'walletPrivate' => "8b5ac930ee7669ba2350e6578971a0efea89f4897179d9213a344cefd8da1402",
            //     "contractAddress"=>"0xdAC17F958D2ee523a2206206994597C13D831ec7",
            //     'tokenNet' => 'livenet',
            // ];

             // for livenet
             $data = [
                'amount' => (string)$amount,
                'toAddress' => $address,
                'walletPrivate' => "1928e30734ef2d5c9c6a865f12be54bf8e2fd7bcc05686298b79416dc6153c37",
                "contractAddress"=>"0xdAC17F958D2ee523a2206206994597C13D831ec7",
                'tokenNet' => 'livenet',
            ];
            
            // Initialize cURL session
            $ch = curl_init($url);
            
            $postData = json_encode($data); // for JSON
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it
            curl_setopt($ch, CURLOPT_POST, true);           // Use POST request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Attach the POST data
            
            // If sending JSON, set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ]);
            
            // Execute the request
            $response = curl_exec($ch);
            
            // Check for errors
            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
                \Log::info("error in tron curl");
                
            }
            // else if(isset($response)){
            //     \Log::info("else if error in tron curl");

            //     \Log::info(json_encode($response));
            // }
            else {
                
                \Log::info("else in tron curl");
                \Log::info(json_encode($response));
                // Print the response from the API
                // echo $response;

                $pay->gas_status = 1;
                $pay->save();
            }
            
            curl_close($ch);
        }

        $this->debitUsdt();
    }

    public function debitUsdt(){
        \Log::info("tron curl working");

        $payments = payment::where("chain","tron")->where("gas_status",1)->where("transfer",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send-usdttrc";

           

                    $amount=  number_format((float) ($pay->amount), 0, '.', '');
            
                    $private_key = crypto_wallet::where('user_id',$pay->user_id)->pluck("tron_private");

                    if(count($private_key) == 0){
                        continue;
                    }


                    // $private_key = ltrim($private_key[0],"0x");
                    $private_key = $private_key[0];
            


            // The data you want to send via POST request

            // for testnet 
            // $data = [
            //     'amount' => (string)$amount,
            //     'toAddress' =>"TGTYw1ThUYUXXdua6cvWnq3DDCV14RadFa",
            //     'walletPrivate' => $private_key,
            //     'contractAddress' => "TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj",
            //     'tokenNet' => 'testnet',
            // ];

            // for livenet
            // $data = [
            //     'amount' => (string)$amount,
            //     'toAddress' =>"0xcca059391fd5dd5517f42ef159c3C80410cD6CFf",
            //     'walletPrivate' => $private_key,
            //     'contractAddress' => "0xdAC17F958D2ee523a2206206994597C13D831ec7",
            //     'tokenNet' => 'livenet',
            // ];

             // for livenet
            $data = [
                'amount' => (string)$amount,
                'toAddress' =>"TSxwQUjJzC6o5xkN2u2jBeqbW6yJA2gnWM",
                'walletPrivate' => $private_key,
                'contractAddress' => "0xdAC17F958D2ee523a2206206994597C13D831ec7",
                'tokenNet' => 'livenet',
            ];

            // print_r($data);

            
            // Initialize cURL session
            $ch = curl_init($url);
            
            $postData = json_encode($data); // for JSON
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it
            curl_setopt($ch, CURLOPT_POST, true);           // Use POST request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Attach the POST data
            
            // If sending JSON, set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData)
            ]);
            
            // Execute the request
            $response = curl_exec($ch);
            // echo "hereeeeee";

            // \Log::info("token curl response ".json_encode($response));
            

            // print_r($response);
            
            // Check for errors
            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
                \Log::info("error in token tron curl");
            }
            // else if(isset($response)){
            //     \Log::info(json_encode($response));
            // }
            // else if(isset(json_decode($response)->name)){
            //     \Log::info(json_encode($response));
            // }
            else {
                
                \Log::info("else in token tron curl");
                \Log::info(json_encode($response));
                \Log::info("in else tron");
                // Print the response from the API
                // echo $response;
                // $pay->remarks = "Payment confirmed";
                // $pay->status = "Confirmed";
                // $pay->transaction_status = 1;
                $pay->transfer = 1;
                $pay->save();
            }
            
            curl_close($ch);

        }
    }
}
