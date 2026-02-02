<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.contact.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $validated['type'] = 'contact';
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();
        $validated['source'] = 'contact_form';

        Inquiry::create($validated);

        return back()->with('success', __('messages.contact_success'));
    }

    public function quote(): View
    {
        $products = Product::active()
            ->with('category')
            ->ordered()
            ->get();

        return view('pages.contact.quote', compact('products'));
    }

    public function submitQuote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'company' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'nullable|string|max:100',
            'specifications' => 'nullable|string|max:5000',
            'delivery_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:5000',
        ]);

        // Format products for storage
        $productDetails = [];
        foreach ($validated['products'] as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $productDetails[] = [
                    'product_id' => $productId,
                    'product_name' => $product->getTranslation('name', 'id'),
                    'quantity' => $validated['quantities'][$productId] ?? null,
                ];
            }
        }

        Inquiry::create([
            'type' => 'quote',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? null,
            'subject' => 'Permintaan Penawaran Harga',
            'message' => $this->formatQuoteMessage($productDetails, $validated),
            'meta' => [
                'products' => $productDetails,
                'specifications' => $validated['specifications'] ?? null,
                'delivery_location' => $validated['delivery_location'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'source' => 'quote_form',
        ]);

        return back()->with('success', __('messages.quote_success'));
    }

    protected function formatQuoteMessage(array $products, array $data): string
    {
        $lines = ["Permintaan Penawaran Harga:\n"];

        $lines[] = "Produk yang diminta:";
        foreach ($products as $product) {
            $qty = $product['quantity'] ? " ({$product['quantity']})" : '';
            $lines[] = "- {$product['product_name']}{$qty}";
        }

        if (!empty($data['specifications'])) {
            $lines[] = "\nSpesifikasi:\n{$data['specifications']}";
        }

        if (!empty($data['delivery_location'])) {
            $lines[] = "\nLokasi Pengiriman: {$data['delivery_location']}";
        }

        if (!empty($data['notes'])) {
            $lines[] = "\nCatatan Tambahan:\n{$data['notes']}";
        }

        return implode("\n", $lines);
    }
}
