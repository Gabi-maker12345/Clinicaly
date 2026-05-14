@extends('layouts.app')

@section('content')
<section class="card" style="min-height:360px;display:flex;align-items:center;justify-content:center;text-align:center;">
    <div>
        <div style="width:76px;height:76px;border-radius:24px;background:var(--is);color:var(--in);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:1.7rem;">
            <i class="fa-solid {{ $icon ?? 'fa-clock' }}"></i>
        </div>
        <span class="tag info">Em breve</span>
        <h1 style="font-size:1.8rem;font-weight:800;margin:16px 0 6px;color:var(--tx);">{{ $title ?? 'Funcionalidade' }}</h1>
        <p style="color:var(--mu);font-weight:600;">Este módulo será disponibilizado nas próximas versões do Clinicaly.</p>
    </div>
</section>
@endsection
