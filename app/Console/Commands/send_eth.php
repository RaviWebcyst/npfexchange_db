<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\payment;
use App\crypto_wallet;

class send_eth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:eth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send etherum gas fees';

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
        $payments = payment::where("chain","eth")->where("transaction_status",1)->where("gas_status",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send-eth";
          

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
            //     'walletPrivate' => "b8380247fef082d87b715912a5cccb6b6942df9dd256c40934f970676d7b8cd5",
            //     "contractAddress"=>"0xaA8E23Fb1079EA71e0a56F48a2aA51851D8433D0",
            //     'tokenNet' => 'testnet',
            // ];

            // for livenet
            $data = [
                'amount' => (string)$amount,
                'toAddress' => $address,
                'walletPrivate' => "8b5ac930ee7669ba2350e6578971a0efea89f4897179d9213a344cefd8da1402",
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
        \Log::info("etherum curl working");

        $payments = payment::where("chain","eth")->where("gas_status",1)->where("transfer",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send_eth_transaction";

           

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
            //     'toAddress' =>"0xBE68c8ECA56f6d0198ddCCBF21E8c667d341357b",
            //     'walletPrivate' => $private_key,
            //     'contractAddress' => "0xaA8E23Fb1079EA71e0a56F48a2aA51851D8433D0",
            //     'tokenNet' => 'testnet',
            // ];

            // for livenet
            $data = [
                'amount' => (string)$amount,
                'toAddress' =>"0xcca059391fd5dd5517f42ef159c3C80410cD6CFf",
                'walletPrivate' => $private_key,
                'contractAddress' => "0xdAC17F958D2ee523a2206206994597C13D831ec7",
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
}
