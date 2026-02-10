<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public array $companyInfo;

    public function __construct(
        public Inquiry $inquiry
    ) {
        $this->companyInfo = app(SettingService::class)->getCompanyInfo();
    }

    public function envelope(): Envelope
    {
        $companyName = $this->companyInfo['company_name'] ?? 'PT. Berkah Mandiri Globalindo';
        $email = $this->companyInfo['email'] ?? 'info@berkahmandiri.co.id';

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($email, $companyName),
            subject: "Konfirmasi Penerimaan Pesan Anda - {$companyName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-confirmation',
        );
    }
}
