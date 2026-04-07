<x-mail::message>

{{-- Header Branding --}}
<div style="text-align:center; margin-bottom:20px;">
    <h1 style="margin:0; color:#1E293B; font-size:22px;">
        💼 {{ config('app.name') }}
    </h1>
    <p style="margin:5px 0 0; color:#64748B; font-size:14px;">
        Sistem Manajemen Keuangan
    </p>
</div>

<hr style="border:none; border-top:1px solid #E2E8F0; margin:20px 0;">

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# Reset Password Akun Anda
@endif

<p style="color:#475569; font-size:14px; line-height:1.6;">
Kami menerima permintaan untuk mengatur ulang password akun Anda.
Klik tombol di bawah ini untuk membuat password baru.
</p>

{{-- Action Button --}}
@isset($actionText)
<x-mail::button :url="$actionUrl" color="primary">
{{ $actionText }}
</x-mail::button>
@endisset

<p style="color:#64748B; font-size:13px; margin-top:20px;">
Link ini akan kedaluwarsa dalam <strong>60 menit</strong> demi keamanan akun Anda.
</p>

<p style="color:#64748B; font-size:13px;">
Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini dengan aman.
</p>

<hr style="border:none; border-top:1px solid #E2E8F0; margin:25px 0;">

{{-- Footer --}}
<p style="font-size:12px; color:#94A3B8; text-align:center;">
Email ini dikirim secara otomatis oleh sistem {{ config('app.name') }}.<br>
Mohon tidak membalas email ini.
</p>

</x-mail::message>
