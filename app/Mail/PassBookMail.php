<?php
  
namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  

class PassBookMail extends Mailable
{
  
    public $pdfPath;

    public function __construct($pdfPath)
    {
        
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->view('email.passbook_report')
                    ->attach($this->pdfPath, [
                        'as' => 'expense_report.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}