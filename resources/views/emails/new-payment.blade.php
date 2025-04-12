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
@component('mail::button', ['url' => $item->baseProduct->subtype == 'custom'
    ? route('products.customize', ['id' => $item->baseProduct->id])
    : route('products.show', ['id' => $item->baseProduct->id])])
View Product
@endcomponent
@endforeach
@endcomponent

Thanks,<br>
@endcomponent
