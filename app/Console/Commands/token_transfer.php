<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\payment;
use App\crypto_wallet;
use App\wallet;
use App\User;
use Carbon\Carbon;


class token_transfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer token from user account to main account';

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
        die;

        \Log::info("token curl working");

        $payments = payment::where("gas_status",1)->where("transfer",0)->get();

        foreach($payments as $pay){

            $url = env("token_url")."send_transaction";

           

                    $amount=  number_format((float) ($pay->amount), 0, '.', '');
            
                    $private_key = crypto_wallet::where('user_id',$pay->user_id)->pluck("privateKey");

                    if(count($private_key) == 0){
                        continue;
                    }

                    $private_key = ltrim($private_key[0],"0x");
            


            // The data you want to send via POST request
            $data = [
                'amount' => (string)$amount,
                'toAddress' =>"0xd156B3974665FDE327280682b11490e6301dEDd4",
                'walletPrivate' => $private_key,
                'contractAddress' => "0x337610d27c682E347C9cD60BD4b3b107C9d34dDd",
                'tokenNet' => 'testnet',
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
            else if(isset(json_decode($response)->name) && json_decode($response)->name == "ContractExecutionError"){
                \Log::info(json_encode($response));
            }
            else {
                
                \Log::info("else in token curl");
                \Log::info(json_encode($response));
                \Log::info("in else");
                // Print the response from the API
                echo $response;

              

                
                $pay->remarks = "Payment confirmed";
                $pay->status = "Confirmed";
                $pay->transaction_status = 1;
                $pay->transfer = 1;
                $pay->save();

                // $user = User::where("id", $pay->user_id)->first();
                // $wallet = new wallet();
                // $wallet->user_id = $user->uid;
                // $wallet->userId = $user->id;
                // $wallet->amount = $pay->amount;
                // $wallet->from = "deposit";
                // $wallet->transaction_type = "deposit";
                // $wallet->wallet_type = "epin";
                // $wallet->type = "credit";
                // $wallet->description = "credit " . $pay->amount . " from deposit";
                // $wallet->save();

                // $user->enable = 1;
                // $user->paid_date = Carbon::now();
                // $user->save();
            }
            
            curl_close($ch);

        }
    }

    
}
