<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuperAddUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnniversaryEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendAnniversaryEmails extends Command
{
    protected $signature = 'email:send-anniversaries';
    protected $description = 'Send anniversary emails to employees on their work anniversary';

    public function handle()
    {
        $today = Carbon::today();

        $users = SuperAddUser::where('status', 1)
            ->whereNotNull('dob')
            ->whereMonth('dob', $today->month)
            ->whereDay('dob', $today->day)
            ->get();

        if ($users->isEmpty()) {
            $this->info("No anniversaries today.");
            Log::info('AnniversaryEmail: No anniversaries found on ' . $today->toDateString());
            return;
        }

        foreach ($users as $user) {
            $cacheKey = null;

            try {
                $joiningDate = Carbon::parse($user->dob)->startOfDay();
                $completedYears = (int) $joiningDate->diffInYears($today);

                if ($completedYears < 1) {
                    continue;
                }

                $cacheKey = "anniversary_email_sent:{$user->id}:{$completedYears}";

                if (! Cache::store('file')->add($cacheKey, true, $today->copy()->addDays(370))) {
                    $this->info("Already sent {$completedYears}-year anniversary email to: {$user->email}");
                    Log::info("AnniversaryEmail: Skipped duplicate for user ID {$user->id}, Email: {$user->email}, Years: {$completedYears}");
                    continue;
                }

                Mail::to($user->email)->send(new AnniversaryEmail($user, $completedYears));
                $this->info("{$completedYears}-year anniversary email sent to: {$user->email}");
                Log::info("AnniversaryEmail: Sent to user ID {$user->id}, Email: {$user->email}, Years: {$completedYears}");
            } catch (\Exception $e) {
                if ($cacheKey) {
                    Cache::store('file')->forget($cacheKey);
                }

                $this->error("Failed to send email to: {$user->email}");
                Log::error("AnniversaryEmail: Failed for user ID {$user->id}, Email: {$user->email}, Error: " . $e->getMessage());
            }
        }
    }
}
