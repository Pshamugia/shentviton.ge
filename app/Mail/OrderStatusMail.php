<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Your Order Status – Payment #' . $this->payment->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-status',
            with: [
                'payment' => $this->payment,
                'statusUrl' => route('payment.status.public', ['id' => $this->payment->id]),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
