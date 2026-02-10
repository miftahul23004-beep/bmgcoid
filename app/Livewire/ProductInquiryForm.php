<?php

namespace App\Livewire;

use App\Mail\InquiryConfirmation;
use App\Mail\InquiryNotification;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ProductInquiryForm extends Component
{
    public ?Product $product = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:255')]
    public string $company = '';

    #[Validate('required|string|max:100')]
    public string $subject = '';

    #[Validate('required|string|max:5000')]
    public string $message = '';

    #[Validate('required|numeric|min:1')]
    public int $quantity = 1;

    #[Validate('nullable|string|max:50')]
    public string $unit = 'pcs';

    public bool $submitted = false;

    public function mount(?Product $product = null): void
    {
        $this->product = $product;
        
        if ($product) {
            $this->subject = 'Inquiry: ' . $product->getTranslation('name', app()->getLocale());
            $this->unit = $product->price_unit ?? 'pcs';
        }
    }

    public function submit(): void
    {
        $this->validate();

        $inquiry = Inquiry::create([
            'product_id' => $this->product?->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company ?: null,
            'subject' => $this->subject,
            'message' => $this->message,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'status' => 'new',
            'source' => 'product_page',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Increment product inquiry count
        if ($this->product) {
            $this->product->incrementInquiryCount();
        }

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
        $this->dispatch('inquiry-submitted');
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'company', 'message', 'quantity']);
        $this->submitted = false;
    }

    public function render()
    {
        return view('livewire.product-inquiry-form');
    }
}
