@component('mail::message')
# 🧾 New Payment Notification

**Payment ID:** {{ $payment->id }}
**Status:** {{ $payment->status }}
**User:** {{ $payment->u_name ?? 'Guest' }}
**Amount:** {{ $payment->amount }} {{ $payment->currency }}

---

@component('mail::panel')
**Items Ordered**
@foreach($payment->cart_items as $item)
- {{ $item->baseProduct->title }} × {{ $item->quantity }} x {{ isset($item->size) ? $item->size : '' }} x {{ $item->default_img ? 'Default Design' : 'Custom Design' }}
@endforeach
@endcomponent

Thanks,<br>
@endcomponent
