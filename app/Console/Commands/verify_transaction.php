<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\upi;
use App\payment;
use App\wallet;
use App\User;
use Carbon\Carbon;


class verify_transaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:trans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify deposit transactions';

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
        
        \Log::info("cron working");

        $payments = payment::where("payment_status", 0)->where("hash", "!=", null)->where('type', 'deposit')->get();
       \Log::info('payments');
        \Log::info(json_encode($payments));

        $upi = upi::select("upi_id")->first();

        foreach ($payments as $payment) {

            $ch = curl_init();

            // Set the URL of the page you want to scrape
            // curl_setopt($ch, CURLOPT_URL, "https://bscscan.com/tx/" . $payment->hash);
            curl_setopt($ch, CURLOPT_URL, "https://testnet.bscscan.com/tx/" . $payment->hash);

            \Log::info("https://testnet.bscscan.com/tx/" . $payment->hash);

            // Return the content instead of printing it
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL and get the HTML content
            $html = curl_exec($ch);

            // Close the cURL session
            curl_close($ch);

            $payment = payment::findOrFail($payment->id);


            // Check if content is fetched
            if ($html === false) {
                $payment->remarks = "Invalid Transaction";
            }

            // Load the HTML content into DOMDocument
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true); // Ignore warnings related to malformed HTML
            $doc->loadHTML($html);
            libxml_clear_errors();

            // Use DOMXPath to query the DOM
            $xpath = new \DOMXPath($doc);

            //get span text of amount
            // $links = $xpath->query('//span[@class="text-muted  me-1"]'); //for livenet 
            $links = $xpath->query('//span[@class="d-inline-flex flex-wrap align-items-center"] /span[@class="me-1"]');


            //get address attribute
            // $elements = $xpath->query("//span[@data-highlight-target]"); //for livenet
            $elements = $xpath->query('//span[@class="d-flex align-items-center gap-1"]/span[@class="d-inline"] /span[@data-highlight-target]');

            $address  = "";

            if ($elements->length > 0) {
                // $address = $elements->item(2)->getAttribute("data-highlight-target"); //for livenet
                $address = $elements->item(3)->getAttribute("data-highlight-target");
            }


            $amount = 0;
            if ($links->length > 0) {
                $transferAmount = $links->item(0)->nodeValue;
                \Log::info("transferAmount ".$transferAmount);
                $amount = str_replace(array('$', '(', ')'), "", $transferAmount);
            }

            \Log::info("amount ".$amount);

            if ($amount != $payment->amount) {
                $payment->remarks = "Invalid amount";
            } else if ($upi->upi_id != $address) {
                $payment->remarks = "No transaction found with this hash";
            } else {
                $payment->remarks = "Payment confirmed";
                $payment->status = "Confirmed";
                $payment->transaction_status = 1;

                $user = User::where("id", $payment->user_id)->first();
                $wallet = new wallet();
                $wallet->user_id = $user->uid;
                $wallet->userId = $user->id;
                $wallet->amount = $payment->amount;
                $wallet->from = "deposit";
                $wallet->transaction_type = "deposit";
                $wallet->wallet_type = "epin";
                $wallet->type = "credit";
                $wallet->description = "credit " . $payment->amount . " from deposit";
                $wallet->save();

                $user->enable = 1;
                $user->paid_date = Carbon::now();
                $user->save();
            }

            $payment->payment_status = 1;
            $payment->save();
        }
    }
}
