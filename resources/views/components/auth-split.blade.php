@props([
    'title',
    'subtitle',
    'eyebrow' => 'Clinicaly',
])

<style>
    body {
        font-family: "Dosis", sans-serif !important;
    }

    .auth-split-shell {
        min-height: 100vh;
        background: #ffffff;
    }

    .auth-split-grid {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(0, 0.92fr) minmax(420px, 1.08fr);
    }

    .auth-form-side {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px clamp(24px, 6vw, 78px);
        background:
            linear-gradient(180deg, rgba(248, 250, 252, 0.92), rgba(255, 255, 255, 0.98)),
            radial-gradient(circle at top left, rgba(20, 184, 166, 0.08), transparent 34%);
    }

    .auth-form-wrap {
        width: min(100%, 470px);
    }

    .auth-eyebrow {
        color: #0f766e;
        font: 800 0.68rem "Space Mono", monospace;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .auth-title {
        color: #17132b;
        font-size: clamp(2.25rem, 5vw, 3.7rem);
        font-weight: 800;
        line-height: 0.95;
        margin: 0;
    }

    .auth-subtitle {
        margin-top: 14px;
        color: #64748b;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.45;
        max-width: 420px;
    }

    .auth-brand-side {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background:
            linear-gradient(125deg, rgba(72, 64, 163, 0.96), rgba(2, 25, 80, 0.9), rgba(15, 23, 42, 0.98));
        background-size: 220% 220%;
        animation: clinicalyGradient 12s ease-in-out infinite;
        isolation: isolate;
    }

    .auth-brand-side::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            repeating-linear-gradient(115deg, rgba(255, 255, 255, 0.12) 0 1px, transparent 1px 28px),
            repeating-linear-gradient(25deg, rgba(255, 255, 255, 0.08) 0 1px, transparent 1px 38px);
        opacity: 0.52;
        animation: clinicalyGrid 18s linear infinite;
        z-index: -1;
    }

    .auth-brand-side::after {
        content: "";
        position: absolute;
        inset: 12%;
        border: 1px solid rgba(255, 255, 255, 0.22);
        transform: skewY(-7deg);
        animation: clinicalyFrame 7s ease-in-out infinite;
        z-index: -1;
    }

    .auth-logo-stage {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        padding: 32px;
        text-align: center;
        animation: clinicalyFloat 5.6s ease-in-out infinite;
    }

    .auth-logo-stage img {
        width: min(380px, 58vw);
        max-height: 190px;
        object-fit: contain;
        filter: drop-shadow(0 30px 50px rgba(15, 23, 42, 0.34));
    }

    .auth-brand-note {
        color: rgba(255, 255, 255, 0.88);
        font: 800 0.72rem "Space Mono", monospace;
        text-transform: uppercase;
        letter-spacing: 0.16em;
    }

    .auth-field {
        margin-top: 18px;
    }

    .auth-field label,
    .auth-field-label {
        display: block;
        margin: 0 0 7px 2px;
        color: #334155;
        font-size: 0.88rem;
        font-weight: 800;
    }

    .auth-input {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 14px;
        background: #f8fafc;
        color: #172033;
        padding: 14px 16px;
        outline: none;
        font-weight: 700;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .auth-input:focus {
        border-color: #14b8a6;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.14);
    }

    .auth-action {
        width: 100%;
        margin-top: 26px;
        border: 0;
        border-radius: 14px;
        background: #4840a3;
        color: #ffffff;
        padding: 15px 18px;
        font-weight: 800;
        box-shadow: 0 18px 35px rgba(72, 64, 163, 0.23);
        transition: transform 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
    }

    .auth-action:hover {
        background: #0f766e;
        box-shadow: 0 18px 35px rgba(15, 118, 110, 0.21);
        transform: translateY(-1px);
    }

    .auth-link-row {
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #e8edf5;
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 650;
    }

    .auth-link-row a,
    .auth-inline-link {
        color: #4840a3;
        font-weight: 800;
        text-decoration: none;
    }

    .auth-link-row a:hover,
    .auth-inline-link:hover {
        color: #0f766e;
        text-decoration: underline;
    }

    .auth-role-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .auth-role-option {
        display: flex;
        min-height: 92px;
        cursor: pointer;
    }

    .auth-role-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .auth-role-card {
        width: 100%;
        border: 1.5px solid #dbe3ef;
        border-radius: 14px;
        background: #f8fafc;
        padding: 12px;
        color: #334155;
        transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .auth-role-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0369a1;
        margin-bottom: 8px;
    }

    .auth-role-name {
        display: block;
        font-weight: 800;
        line-height: 1.1;
    }

    .auth-role-copy {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .auth-role-option input:checked + .auth-role-card {
        border-color: #14b8a6;
        background: #f0fdfa;
        box-shadow: 0 14px 28px rgba(20, 184, 166, 0.12);
        transform: translateY(-1px);
    }

    .auth-role-option input:checked + .auth-role-card .auth-role-icon {
        background: #4840a3;
        color: #ffffff;
    }

    .auth-inline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    @keyframes clinicalyGradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes clinicalyGrid {
        from { transform: translate3d(0, 0, 0); }
        to { transform: translate3d(-56px, 42px, 0); }
    }

    @keyframes clinicalyFrame {
        0%, 100% { transform: skewY(-7deg) scale(1); opacity: 0.55; }
        50% { transform: skewY(-3deg) scale(1.03); opacity: 0.8; }
    }

    @keyframes clinicalyFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }

    @media (max-width: 960px) {
        .auth-split-grid {
            grid-template-columns: 1fr;
        }

        .auth-brand-side {
            order: -1;
            min-height: 280px;
        }

        .auth-logo-stage img {
            width: min(300px, 72vw);
        }
    }

    @media (max-width: 560px) {
        .auth-form-side {
            padding: 32px 18px;
        }

        .auth-role-grid {
            grid-template-columns: 1fr;
        }

        .auth-title {
            font-size: 2.15rem;
        }
    }
</style>

<div class="auth-split-shell">
    <main class="auth-split-grid">
        <section class="auth-form-side">
            <div class="auth-form-wrap">
                <div class="auth-eyebrow">{{ $eyebrow }}</div>
                <h1 class="auth-title">{{ $title }}</h1>
                <p class="auth-subtitle">{{ $subtitle }}</p>

                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
        </section>

        <aside class="auth-brand-side" aria-label="Marca Clinicaly">
            <div class="auth-logo-stage">
                <img src="{{ asset('Proosta-logo4.png') }}" alt="Clinicaly">
                <span class="auth-brand-note">Ecossistema clínico inteligente</span>
            </div>
        </aside>
    </main>
</div>
