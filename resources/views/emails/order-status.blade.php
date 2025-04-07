@component('mail::message')
# Hello {{ $payment->u_name ?? 'Customer' }},

Thank you for your order!

**Payment ID:** {{ $payment->id }}
**Status:** {{ ucfirst($payment->status) }}
**Amount:** {{ $payment->amount }} {{ isset($payment->currency) ? $payment->currency : ''  }}

@component('mail::button', ['url' => $statusUrl])
View Order Status
@endcomponent

If you have any questions, feel free to contact us.

Thanks,
@endcomponent
