<?php

namespace App\Console\Command;

use App\AppointmentReminders\AppointmentReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders using Twilio';

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
     * @return void
     */
    public function handle()
    {

        $appointmentReminder = new AppointmentReminder();
        $appointmentReminder->sendReminders();
    }
}
