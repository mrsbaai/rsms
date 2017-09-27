<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\number;
use Carbon\Carbon;
class removeExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check all numbers and remove user if number is expired';

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
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        $numbers = Number::where('email','!=','null')->get();
        $expiration = Carbon::now()->addYears(20);

        foreach($numbers as $number){
            $date = Carbon::parse($number['expiration']);
            $diff = $now->diffInDays($date, false);
            if ($diff <= 0){
                Number::where('id', '=', $number['id'])->update(['email' => null]);
                Number::where('id', '=', $number['id'])->update(['expiration' => $expiration]);
            }
        }

        return;
    }
}
