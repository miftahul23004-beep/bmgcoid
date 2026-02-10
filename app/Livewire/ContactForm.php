<?php

namespace App\Livewire;

use App\Mail\InquiryConfirmation;
use App\Mail\InquiryNotification;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:255')]
    public ?string $company = '';

    #[Validate('required|string|in:general,sales,support,partnership,other')]
    public string $subject_type = 'general';

    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|min:20|max:2000')]
    public string $message = '';

    public bool $submitted = false;

    /**
     * Subject type options
     */
    public function getSubjectTypes(): array
    {
        return [
            'general' => __('General Inquiry'),
            'sales' => __('Sales Inquiry'),
            'support' => __('Technical Support'),
            'partnership' => __('Partnership/Collaboration'),
            'other' => __('Other'),
        ];
    }

    /**
     * Submit contact form
     */
    public function submit(): void
    {
        $this->validate();

        $inquiry = Inquiry::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'subject' => $this->subject,
            'message' => $this->message,
            'type' => $this->subject_type,
            'status' => 'new',
        ]);

        // Send email notification to admin
        try {
            Mail::to('info@berkahmandiri.co.id')->send(new InquiryNotification($inquiry));
        } catch (\Throwable $e) {
            \Log::error('Failed to send inquiry email: ' . $e->getMessage());
        }

        // Send confirmation email to user
        try {
            Mail::to($inquiry->email)->send(new InquiryConfirmation($inquiry));
        } catch (\Throwable $e) {
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }

        $this->submitted = true;
        $this->dispatch('contact-submitted');
    }

    /**
     * Reset form to submit another message
     */
    public function resetForm(): void
    {
        $this->reset();
        $this->submitted = false;
    }

    public function render(): View
    {
        return view('livewire.contact-form', [
            'subjectTypes' => $this->getSubjectTypes(),
        ]);
    }
}
