<?php

namespace App\Jobs;

use App\Mail\IdentityProcessedEmail;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Support\File;

class ProcessNationalIdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 400;
    public $tries = 1;

    private User $user;

    public function __construct($user, private $imagePath, private $outputPath, private $type = 'front')
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        $this->user = User::find($this->user->id);
        $process = Process::run(storage_path('model/myvenv/bin/python3 ' . storage_path('model/main.py') . " --file=" . $this->imagePath . ' --output=' . $this->outputPath));
        if(! Str::contains($process->output(), 'success')){
            throw new Exception('Failed to extract national id data'. $process->errorOutput());
        }

        $this->user->update([
            $this->type . '_national_id' => json_decode(file_get_contents($this->outputPath), true)
        ]);
        info('hi there');
        info($this->user);
        Process::run('rm '. $this->outputPath);
        Process::run('rm '. storage_path('model/runs'));

        if($this->user->front_national_id && $this->user->back_national_id){
            info('should be verified');
            $this->user->forceFill([
                'identity_verified' => true,
            ])->save();

            Mail::to($this->user->email)->send(new IdentityProcessedEmail());
        }

    }

    private function arabicToEnglishNumbers($string)
    {
        $arabic_numbers = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english_numbers = ['0','1','2','3','4','5','6','7','8','9'];

        return str_replace($arabic_numbers, $english_numbers, $string);
    }
}

