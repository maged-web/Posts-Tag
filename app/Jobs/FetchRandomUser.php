<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchRandomUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $randomUser=Http::get('https://randomuser.me/api/');
        if($randomUser->successful())
        {
            $data=$randomUser->json();
            Log::info('Random User API Response:',$data);
        }
        else
        {
            Log::error('Failed to fetch data from Random User API',[
                'status'=>$randomUser->status(),
                'body'=>$randomUser->body()
            ]);
        }
        //
    }
}
