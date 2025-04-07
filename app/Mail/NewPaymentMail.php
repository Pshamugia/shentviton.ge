<?php

namespace App\Mail;

use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public string $status;
    public string $payment_id;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment->cart_items = Cart::whereIn('id', $payment->cart_ids)->get();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 New Payment Received: ' . $this->payment->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-payment',
            with: [
                'payment' => $this->payment,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];


        foreach ($this->payment->cart_ids as $item) {
            $item = Cart::find($item);
            foreach (['design_front_image', 'design_back_image', 'front_assets', 'back_assets'] as $key) {
                $path = $item->$key ?? null;

                if ($path && Storage::disk('public')->exists($path)) {
                    $filename = "cart_item_{$item->id}_" . $key . '.' . pathinfo($path, PATHINFO_EXTENSION);

                    $attachments[] = Attachment::fromStorageDisk('public', $path)
                        ->as($filename)
                        ->withMime('image/png');
                }
            }
        }

        return $attachments;
    }
}
