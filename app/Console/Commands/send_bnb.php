<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\payment;
use App\crypto_wallet;
use App\wallet;
use App\User;
use Carbon\Carbon;


class send_bnb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:bnb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send bnb to user account from main wallet';

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

        $payments = payment::where("chain","bsc")->where("transaction_status",1)->where("gas_status",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send-bnb";
          

        //    $amount= number_format((($pay->amount * 0.05) / $this->bnb_price()),7,".","");

        //     // $amount = round((($pay->amount * 0.0003) / $this->bnb_price()),5);
        //     echo $amount;


            // echo $this->bnb_price();

            // The data you want to send via POST request

           $amount=  number_format((float) ($pay->amount), 0, '.', '');

           

            // $amount = $pay->amount;

            $address = $pay->address;

            //for testnet
            // $data = [
            //     'amount' => (string)$amount,
            //     'toAddress' => $address,
            //     'walletPrivate' => "67f54a0c38ce5f78403d7342d169c4aed4b6ed071dd90fe3b4ebe6f4f562f9a2",
            //     "contractAddress"=>"0x337610d27c682E347C9cD60BD4b3b107C9d34dDd",
            //     'tokenNet' => 'testnet',
            // ];

            // for livenet
            $data = [
                'amount' => (string)$amount,
                'toAddress' => $address,
                'walletPrivate' => "8b5ac930ee7669ba2350e6578971a0efea89f4897179d9213a344cefd8da1402",
                "contractAddress"=>"0x55d398326f99059fF775485246999027B3197955",
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
                \Log::info("error in bnb curl");
                
            }
            else if(isset(json_decode($response)->error)){
                \Log::info(json_decode($response)->error);
            }
            else {
                
                \Log::info("else in bnb curl");
                \Log::info(json_encode($response));
                // Print the response from the API
                echo $response;

                $pay->gas_status = 1;
                $pay->save();
            }
            
            curl_close($ch);

        }

        $this->debitUsdt();
    }

    public function debitUsdt(){
        \Log::info("token curl working");

        $payments = payment::where("chain","bsc")->where("gas_status",1)->where("transfer",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send_transaction";

           

                    $amount=  number_format((float) ($pay->amount), 0, '.', '');
            
                    $private_key = crypto_wallet::where('user_id',$pay->user_id)->pluck("privateKey");

                    if(count($private_key) == 0){
                        continue;
                    }

                    $private_key = ltrim($private_key[0],"0x");
            


            // The data you want to send via POST request

            // for testnet 
            // $data = [
            //     'amount' => (string)$amount,
            //     'toAddress' =>"0xd156B3974665FDE327280682b11490e6301dEDd4",
            //     'walletPrivate' => $private_key,
            //     'contractAddress' => "0x337610d27c682E347C9cD60BD4b3b107C9d34dDd",
            //     'tokenNet' => 'testnet',
            // ];

            // for livenet
            $data = [
                'amount' => (string)$amount,
                'toAddress' =>"0xcca059391fd5dd5517f42ef159c3C80410cD6CFf",
                'walletPrivate' => $private_key,
                'contractAddress' => "0x55d398326f99059fF775485246999027B3197955",
                'tokenNet' => 'livenet',
            ];

            print_r($data);

            
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
            echo "hereeeeee";

            // \Log::info("token curl response ".json_encode($response));
            

            // print_r($response);
            
            // Check for errors
            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
                \Log::info("error in token curl");
            }
            else if(isset(json_decode($response)->error)){
                \Log::info(json_decode($response)->error);
            }
            else if(isset(json_decode($response)->name)){
                \Log::info(json_encode($response));
            }
            else {
                
                \Log::info("else in token curl");
                \Log::info(json_encode($response));
                \Log::info("in else");
                // Print the response from the API
                echo $response;

              

                
                // $pay->remarks = "Payment confirmed";
                // $pay->status = "Confirmed";
                // $pay->transaction_status = 1;
                $pay->transfer = 1;
                $pay->save();

                
            }
            
            curl_close($ch);

        }

       
    }




    // public function bnb_price()
    // {
    //        $api=  new \Binance\API('C2wqJilWgYJDmeeHHMHnFfNycjpQto1BcxMSsANhe1pxRuCeaCjXetsNJySU1wZ0','J1kKGR0Bqwikgia7uwdkqvvQDcamuBEShH54PooIOiCSnUO7yBCjtSUPthPQ2seS',true);
    //        $price = $api->price("BNBUSDT");
    //        return $price;
    // }
}
