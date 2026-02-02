<?php

namespace App\Livewire;

use App\Models\Contact;
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

        Contact::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'subject' => $this->subject,
            'message' => $this->message,
            'type' => $this->subject_type,
            'source' => 'contact_form',
            'status' => 'new',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // TODO: Send email notification to admin

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
