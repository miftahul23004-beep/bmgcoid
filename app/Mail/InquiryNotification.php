<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inquiry $inquiry
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->inquiry->product_id
            ? "[Penawaran Produk] {$this->inquiry->subject}"
            : "[Kontak] {$this->inquiry->subject}";

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address('info@berkahmandiri.co.id', 'PT. Berkah Mandiri Globalindo'),
            subject: $subject,
            replyTo: [$this->inquiry->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-notification',
        );
    }
}
