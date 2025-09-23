<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PurchaesPdfCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testpurcheaspdf:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // Define the directory where the PDF files are stored
        $directory = public_path('pdf'); // This points to the public/pdf directory

        // Check if the directory exists
        if (File::exists($directory)) {
            // Get all files in the directory
            $files = File::files($directory);

            // Loop through each file
            foreach ($files as $file) {
                // Check if the file extension is PDF
                if ($file->getExtension() === 'pdf') {
                    // Delete the PDF file
                    File::delete($file);
                    $this->info('Deleted: ' . $file->getFilename());
                }
            }

            $this->info('All PDF files have been deleted from ' . $directory);
        } else {
            $this->error('Directory does not exist: ' . $directory);
        }
    }
}
