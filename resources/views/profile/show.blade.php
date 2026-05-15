<x-app-layout>
@php
    $role = auth()->user()->role ?? 'pacient';
    $isDoctor = auth()->user()->isDoctor();
    $isClinic = auth()->user()->isClinic();
    $roleLabel = auth()->user()->roleLabel();
    $diagnosticosPendentes = ($isDoctor ? ($filaDiagnosticos ?? collect()) : $meusDiagnosticos)->where('status', 'pendente');
    $diagnosticosValidados = ($isDoctor ? $pacientes : $meusDiagnosticos)->where('status', 'validado');
    $prescricoes = $isDoctor ? ($prescricoesMedico ?? collect()) : $minhasPrescricoes;
    $prescricaoData = [];
    foreach ($prescricoes as $prescricao) {
        $prescricao->loadMissing(['diagnostico.paciente', 'diagnostico.medico', 'diagnostico.doenca', 'monitorings.intakeLogs']);
        $prescricaoData[$prescricao->id] = $prescricao->toArray();
    }
@endphp

<style>
    .view-shell{max-width:1180px;margin:0 auto}
    .view-shell *{scrollbar-width:none}
    .view-shell *::-webkit-scrollbar{width:0;height:0}
    .view-head{display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px}
    .view-title{font-size:1.7rem;font-weight:800;line-height:1.1;color:var(--tx)}
    .muted{color:var(--mu);font-weight:600}
    .mono{font-family:'Space Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu)}
    .grid-kpi{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px}
    .grid-two{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px}
    .tab-card{animation:fadeUp .25s ease both}
    .placeholder{min-height:320px;display:flex;align-items:center;justify-content:center;text-align:center}
    .table-wrap{overflow-x:auto}
    .soft-list{display:flex;flex-direction:column;gap:12px}
    .soft-row{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px;border:1px solid var(--bd);border-radius:12px;background:var(--sf2);text-align:left}
    .avatar-initial{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--is);border:2px solid var(--bd);color:var(--in);font-weight:800;flex-shrink:0}
    .detail-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px}
    .detail-box{background:var(--sf2);border:1px solid var(--bd);border-radius:12px;padding:14px}
    .med-row{display:flex;align-items:center;gap:14px;padding:16px;background:var(--sf2);border:1.5px solid var(--bd);border-radius:16px;margin-bottom:12px;flex-wrap:wrap}
    .med-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0;background:var(--is);color:var(--in)}
    .med-info{flex:1;min-width:220px}
    .med-name{font-weight:800;color:var(--tx)}
    .med-detail{font-size:.78rem;color:var(--mu);margin-top:3px}
    .prog-wrap{height:7px;background:var(--sf3);border-radius:4px;overflow:hidden}
    .prog-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--in),var(--il))}
    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:200;align-items:center;justify-content:center;padding:20px}
    .modal-overlay.open{display:flex}
    .modal-box{background:var(--sf);border:1px solid var(--bd);border-radius:24px;box-shadow:var(--sh3);width:100%;max-width:760px;max-height:88vh;overflow-y:auto;scrollbar-width:none}
    .modal-box::-webkit-scrollbar{width:0;height:0}
    .modal-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;border-bottom:1px solid var(--bd)}
    .modal-close{background:none;border:0;cursor:pointer;color:var(--mu);font-size:1.1rem;padding:6px;border-radius:999px}
    .modal-close:hover{color:var(--rd);background:var(--rb)}
    .modal-field{display:flex;flex-direction:column;gap:7px}
    .modal-field label{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--mu);font-weight:800}
    .modal-field input,.modal-field select,.modal-field textarea{width:100%;border:1px solid var(--bd);border-radius:12px;background:var(--sf);color:var(--tx);padding:11px 12px;font-weight:700;outline:none}
    .modal-field textarea{resize:vertical;min-height:92px}
    .modal-field select[multiple]{min-height:132px}
    .modal-field input:focus,.modal-field select:focus,.modal-field textarea:focus{border-color:var(--in);box-shadow:0 0 0 3px rgba(109,85,177,.12)}
    .consultation-modal{width:min(980px,calc(100vw - 32px));border-radius:22px}
    .consultation-modal .modal-head{padding:22px 26px}
    .consultation-body{padding:24px 26px 26px;display:grid;gap:20px}
    .consultation-picker{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
    .consultation-panel{background:var(--sf2);border:1px solid var(--bd);border-radius:16px;padding:16px;min-width:0}
    .consultation-list{display:grid;gap:10px}
    .consultation-option{width:100%;display:flex;align-items:center;justify-content:space-between;gap:14px;padding:13px 14px;border:1px solid var(--bd);border-radius:14px;background:var(--sf);color:var(--tx);text-align:left;cursor:pointer;min-width:0}
    .consultation-option:hover,.consultation-option.active{border-color:var(--in);background:var(--is)}
    .consultation-option span{min-width:0}.consultation-option strong{display:block;overflow-wrap:anywhere}.consultation-option small{display:block;margin-top:2px}
    .consultation-form{display:grid;gap:18px}
    .consultation-section{background:var(--sf2);border:1px solid var(--bd);border-radius:16px;padding:16px;min-width:0}
    .consultation-biometry{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
    .consultation-notes{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
    .consultation-notes .wide{grid-column:1/-1}
    .consultation-modal textarea{min-height:108px;line-height:1.45}
    .consultation-modal .std-actions{margin-top:0;padding-top:4px}
    @media(max-width:860px){.consultation-picker,.consultation-notes{grid-template-columns:1fr}.consultation-biometry{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:560px){.consultation-modal{width:calc(100vw - 20px)}.consultation-body,.consultation-modal .modal-head{padding:18px}.consultation-biometry{grid-template-columns:1fr}}
    input[type=number]{appearance:textfield;-moz-appearance:textfield}
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
    .pharmacy-list{display:flex;flex-direction:column;gap:10px;max-height:430px;overflow-y:auto;padding:18px 22px;scrollbar-width:none}
    .pharmacy-list::-webkit-scrollbar{width:0;height:0}
    .pharmacy-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px;border:1px solid var(--bd);border-radius:14px;background:var(--sf2)}
    .modal-field select::-webkit-scrollbar,.modal-field input::-webkit-scrollbar,.modal-field textarea::-webkit-scrollbar{width:0;height:0}
    .modal-field select[multiple]{scrollbar-width:none}
    .map-panel{width:100%;min-height:260px;background:var(--sf2);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;border-bottom:1px solid var(--bd);text-align:center;padding:28px}
    .active-dose{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px;border:1px solid var(--wbd);background:var(--wb);border-radius:14px;margin-top:10px;flex-wrap:wrap}
    .std-modal{width:min(420px,100%);background:var(--sf);border:1px solid var(--bd);border-radius:20px;box-shadow:var(--sh3);padding:22px}
    .std-modal h2{font-size:1.05rem;font-weight:800;color:var(--tx);margin-bottom:8px}.std-modal p{color:var(--mu);font-weight:600;line-height:1.45}.std-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:18px;flex-wrap:wrap}
    .settings-grid{display:grid;grid-template-columns:minmax(0,1.25fr) minmax(280px,.75fr);gap:18px;align-items:start}
    .settings-card{overflow:hidden}
    .settings-livewire [class*="md:grid"],
    .settings-livewire [class*="md:grid-cols-3"]{display:block!important}
    .settings-livewire [class*="md:col-span-2"]{margin-top:14px!important}
    .settings-livewire [class*="grid-cols-6"]{display:grid!important;grid-template-columns:1fr!important;gap:16px!important}
    .settings-livewire [class*="col-span-6"],
    .settings-livewire [class*="sm:col-span-4"]{grid-column:auto!important}
    .settings-livewire .shadow{box-shadow:none!important}
    .settings-livewire form>div:first-child{padding:0!important;border:0!important;background:transparent!important}
    .settings-livewire form>div:last-child{margin:16px -24px -24px!important;border-width:1px 0 0!important;border-radius:0!important}
    .settings-livewire label{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--mu);font-weight:800}
    .settings-livewire input[type="text"],
    .settings-livewire input[type="email"],
    .settings-livewire input[type="password"]{width:100%;border:1px solid var(--bd);border-radius:12px;background:var(--sf2);color:var(--tx);padding:11px 14px;box-shadow:none}
    .settings-livewire button{border-radius:999px!important;font-family:'Dosis',sans-serif!important;font-weight:800!important;text-transform:none!important;letter-spacing:0!important}
    .settings-livewire img{border:3px solid var(--bd);box-shadow:var(--sh)}
    .settings-panel{display:flex;flex-direction:column;gap:18px}
    .settings-option{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px;border:1px solid var(--bd);border-radius:14px;background:var(--sf2)}
    @media(max-width:900px){.settings-grid{grid-template-columns:1fr}.settings-livewire form>div:last-child{margin:16px -20px -20px!important}}
    @keyframes fadeUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
</style>

<div class="view-shell" id="profileShell"
     x-data="{
        tab: (window.location.hash || '#dashboard').replace('#',''),
        selectedPrescription: null,
        searchFila: '',
        searchPatients: '',
        searchPrescriptions: '',
        nowTick: Date.now(),
        noticeOpen: false,
        noticeTitle: '',
        noticeMessage: '',
        pharmacyOpen: false,
        pharmacyLoading: false,
        pharmacyError: '',
        pharmacyNotice: '',
        pharmacyMedication: '',
        pharmacies: [],
        prescriptions: @js($prescricaoData),
        clinicModalOpen: false,
        clinics: @js($clinicOptions ?? []),
        selectedClinic: null,
        selectedDoctor: null,
        openClinicModal(){
            this.selectedClinic = this.clinics[0] || null;
            this.selectedDoctor = this.selectedClinic?.doctors?.[0] || null;
            this.clinicModalOpen = true;
        },
        chooseClinic(clinic){
            this.selectedClinic = clinic;
            this.selectedDoctor = clinic?.doctors?.[0] || null;
        },
        openPrescription(id){ this.selectedPrescription = this.prescriptions[id]; this.tab = 'prescription_detail'; window.location.hash = 'prescription_detail'; },
        showNotice(title, message){ this.noticeTitle = title; this.noticeMessage = message; this.noticeOpen = true; },
        closePharmacies(){ this.pharmacyOpen = false; this.pharmacies = []; this.pharmacyError = ''; this.pharmacyNotice = ''; },
        async openPharmacies(medication){
            this.pharmacyOpen = true;
            this.pharmacyMedication = medication;
            this.pharmacies = [];
            this.pharmacyError = '';
            this.pharmacyNotice = '';
            await this.requestPharmacyLocation();
        },
        async runPharmacySearch(lat, lon){
            const url = new URL('/farmacias/buscar', window.location.origin);
            url.searchParams.set('lat', lat);
            url.searchParams.set('lon', lon);
            url.searchParams.set('medication', this.pharmacyMedication);
            const response = await fetch(url, {headers: {'Accept': 'application/json'}});
            const data = await response.json();
            if(!response.ok) throw new Error(data.error || 'Não foi possível buscar farmácias.');
            this.pharmacies = data.pharmacies || [];
            this.pharmacyNotice = data.notice || '';
            if(this.pharmacies.length === 0) this.pharmacyError = 'Nenhuma farmácia encontrada num raio de 5km.';
        },
        async requestPharmacyLocation(){
            this.pharmacyLoading = true;
            this.pharmacyError = '';
            this.pharmacyNotice = '';
            this.pharmacies = [];
            try {
                if(!navigator.geolocation) throw new Error('Este navegador não disponibiliza localização neste endereço.');
                const pos = await new Promise((resolve, reject) => navigator.geolocation.getCurrentPosition(resolve, reject, {enableHighAccuracy:true, timeout:9000}));
                await this.runPharmacySearch(pos.coords.latitude, pos.coords.longitude);
            } catch (error) {
                this.pharmacyError = error.message || 'Não foi possível obter sua localização. Verifique a permissão do navegador.';
            } finally {
                this.pharmacyLoading = false;
            }
        },
        async searchDefaultLocation(){
            this.pharmacyLoading = true;
            this.pharmacyError = '';
            try { await this.runPharmacySearch(-8.8390, 13.2894); }
            catch (error) { this.pharmacyError = error.message || 'Não foi possível buscar farmácias.'; }
            finally { this.pharmacyLoading = false; }
        },
        pharmacyMapUrl(){
            const p = this.pharmacies?.[0];
            if(!p?.lat || !p?.lon) return '';
            const lat = Number(p.lat);
            const lon = Number(p.lon);
            const delta = 0.018;
            return `https://www.openstreetmap.org/export/embed.html?bbox=${lon-delta}%2C${lat-delta}%2C${lon+delta}%2C${lat+delta}&layer=mapnik&marker=${lat}%2C${lon}`;
        },
        isPrescriptionStarted(){
            return (this.selectedPrescription?.monitorings || []).some(m => ['active','completed'].includes(m.status) || (m.intake_logs || []).length > 0);
        },
        isDosePending(log){ return log?.status === 'pending' && (!log.due_until || new Date(log.due_until).getTime() > this.nowTick); },
        doseStatus(log){ return this.isDosePending(log) ? 'Pendente' : (log.status === 'completed' ? 'Concluído' : 'Falho'); },
        pendingDoses(){
            return (this.selectedPrescription?.monitorings || []).flatMap(m => (m.intake_logs || []).filter(log => this.isDosePending(log)).map(log => ({...log, medication_name: m.medication_name, monitoring_id: m.id})));
        },
        applyPrescription(prescription){
            this.selectedPrescription = prescription;
            this.prescriptions[prescription.id] = prescription;
        },
        requestSelectedSymptoms: [],
        allConsultationSymptoms: @js($consultationSymptoms ?? collect()),
        addReqSymptom(id, name){ window.addRequestSymptom(this, id, name); },
        removeReqSymptom(id){ window.removeRequestSymptom(this, id); },
        renderReqSymptoms(){ window.renderRequestSelectedSymptoms(this); },
        prepareClinicSubmit(event){ window.prepareClinicFormSubmit(this, event); },
        async completeDose(log){
            const response = await fetch('{{ route('monitoring.logs.complete', '__LOG_ID__') }}'.replace('__LOG_ID__', log.id), {
                method: 'PATCH',
                headers: {'Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
            });
            const data = await response.json();
            if(response.ok){
                (this.selectedPrescription.monitorings || []).forEach(m => {
                    m.intake_logs = (m.intake_logs || []).map(item => item.id === data.log.id ? data.log : item);
                });
                this.showNotice('Dose concluída', data.message || 'Toma confirmada com sucesso.');
            } else {
                if(data.log){
                    (this.selectedPrescription.monitorings || []).forEach(m => {
                        m.intake_logs = (m.intake_logs || []).map(item => item.id === data.log.id ? data.log : item);
                    });
                }
                this.showNotice('Prazo expirado', data.message || 'Não foi possível concluir esta dose.');
            }
        },
        async startPrescription(){
            if(!this.selectedPrescription) return;
            try {
                const response = await fetch(`/prescriptions/${this.selectedPrescription.id}/start`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
                });
                const data = await response.json().catch(() => ({}));
                if(!response.ok) throw new Error(data.message || 'Não foi possível iniciar esta prescrição.');
                if(data.prescription) this.applyPrescription(data.prescription);
                this.showNotice('Prescrição ativada', data.message);
            } catch (error) {
                this.showNotice('Erro', error.message || 'Não foi possível iniciar esta prescrição.');
            }
        },
        syncHashTab(){ this.tab = (window.location.hash || '#dashboard').replace('#',''); window.syncProfileHashTabs?.(); },
        go(value){ this.tab = value; window.location.hash = value; window.syncProfileHashTabs?.(); }
     }"
     x-init="window.addEventListener('hashchange', () => syncHashTab()); syncHashTab(); setInterval(() => this.nowTick = Date.now(), 15000)">

    <section x-show="tab === 'dashboard'" data-profile-tab="dashboard" class="tab-card">
        <div class="view-head">
            <div>
                <span class="tag tag-green">{{ $roleLabel }}</span>
                <h1 class="view-title" style="margin-top:10px;">Bom dia, <span style="color:var(--in);">{{ $isDoctor ? 'Dr. ' : '' }}{{ explode(' ', $user->name)[0] }}</span></h1>
                <span class="mono">Visão geral clínica</span>
            </div>
            @if($isDoctor)
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <form method="POST" action="{{ route('doctor.availability.update') }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $user->is_available ? 'btn-green' : 'btn-danger' }}">
                            <i class="fa-solid {{ $user->is_available ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            {{ $user->is_available ? 'Livre' : 'Ocupado' }}
                        </button>
                    </form>
                    <a href="{{ route('discovery.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Nova Consulta
                    </a>
                </div>
            @else
                <button type="button" class="btn btn-primary" @click="openClinicModal()">
                    <i class="fa-solid fa-calendar-plus"></i> Nova consulta
                </button>
            @endif
        </div>

        <div class="grid-kpi">
            <article class="kpi-card"><div class="value">{{ $isDoctor ? $pacientes->count() : $meusDiagnosticos->count() }}</div><div class="label">{{ $isDoctor ? 'Pacientes ativos' : 'Meus diagnósticos' }}</div></article>
            <article class="kpi-card"><div class="value">{{ $diagnosticosPendentes->count() }}</div><div class="label">Pendentes</div></article>
            <article class="kpi-card"><div class="value">{{ $diagnosticosValidados->count() }}</div><div class="label">Validados</div></article>
            <article class="kpi-card"><div class="value">{{ $prescricoes->count() }}</div><div class="label">Prescrições</div></article>
        </div>

        @if($diagnosticosPendentes->count())
            <div class="soft-row" style="margin-top:18px;border-color:var(--rbd);background:var(--rb);color:var(--rd);">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p style="flex:1;margin:0;text-align:left;">
                    <strong>{{ $diagnosticosPendentes->count() }} diagnóstico(s)</strong> aguardam revisão.
                </p>
                @if($isDoctor)<button type="button" class="btn btn-danger btn-sm" style="margin-left:auto;" @click="go('fila')">Ver agora</button>@endif
            </div>
        @endif

        <div class="grid-two" style="margin-top:18px;">
            <article class="card">
                <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-clock"></i> Atividade recente</h2>
                <div class="soft-list">
                    @forelse(($isDoctor ? $pacientes : $meusDiagnosticos)->take(4) as $diag)
                        <div class="soft-row">
                            <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                                <span class="avatar-initial">{{ substr($diag->paciente->name ?? $user->name, 0, 2) }}</span>
                                <div style="min-width:0;">
                                    <strong>{{ $diag->paciente->name ?? $user->name }}</strong>
                                    <p class="muted" style="font-size:.84rem;">{{ $diag->status }} · {{ optional($diag->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="tag {{ $diag->status === 'validado' ? 'success' : 'warn' }}">{{ $diag->status }}</span>
                        </div>
                    @empty
                        <p class="muted">Nenhuma atividade encontrada.</p>
                    @endforelse
                </div>
            </article>

            <article class="card">
                <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-bolt"></i> Ações rápidas</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;">
                    @if($isDoctor)<button class="btn btn-ghost" @click="go('fila')"><i class="fa-solid fa-list-check"></i>Fila</button>@endif
                    <a class="btn btn-ghost" href="{{ route('discovery.index') }}"><i class="fa-solid fa-book-medical"></i>Dicionário Clínico</a>
                    <button class="btn btn-ghost" @click="go('prescricoes')"><i class="fa-solid fa-file-prescription"></i>Prescrições</button>
                    <a class="btn btn-ghost" href="{{ route('chat.index') }}"><i class="fa-solid fa-robot"></i>Chat de IA</a>
                    <a class="btn btn-ghost" href="{{ route('messages.index') }}"><i class="fa-solid fa-comments"></i>Conversas</a>
                    <button class="btn btn-ghost" @click="go('profile')"><i class="fa-solid fa-user"></i>Perfil</button>
                </div>
            </article>
        </div>
    </section>

    <section x-show="tab === 'fila'" x-cloak data-profile-tab="fila" class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Fila de Validação</h1><p class="muted">Diagnósticos aguardando revisão médica.</p></div></div>
        <article class="card">
            <input type="search" x-model="searchFila" placeholder="Pesquisar por paciente ou diagnóstico..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
            @forelse($diagnosticosPendentes as $diagnostico)
                <div class="patient-row" data-search="{{ strtolower(($diagnostico->paciente->name ?? '') . ' ' . collect($diagnostico->doencas_sugeridas ?? [])->pluck('nome')->join(' ')) }}" x-show="$el.dataset.search.includes(searchFila.toLowerCase())">
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        <span class="avatar-initial">{{ substr($diagnostico->paciente->name ?? 'P', 0, 2) }}</span>
                        <div>
                            <strong>{{ $diagnostico->paciente->name ?? 'Paciente' }}</strong>
                            <p class="muted">{{ collect($diagnostico->doencas_sugeridas ?? [])->take(2)->pluck('nome')->join(' · ') ?: 'Diagnóstico pendente' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('diagnostico.validar', $diagnostico->id) }}" class="btn btn-primary btn-sm">Validar</a>
                </div>
            @empty
                <p class="muted" style="text-align:center;padding:30px;">Fila vazia.</p>
            @endforelse
        </article>
    </section>

    <section x-show="tab === 'agenda'" x-cloak data-profile-tab="agenda" class="tab-card">
        @include('appointments._content', $agendaData ?? [])
    </section>

    <section x-show="tab === 'analise1' || tab === 'analise2' || tab === 'analise3'" x-cloak data-profile-tab="analise1 analise2 analise3" class="tab-card">
        <article class="card placeholder">
            <div>
                <div style="font-size:2rem;color:var(--in);margin-bottom:12px;"><i class="fa-solid fa-chart-simple"></i></div>
                <span class="tag">Em breve</span>
                <h1 class="view-title" style="margin-top:12px;">{{ __('Módulo em branco') }}</h1>
            </div>
        </article>
    </section>

    <section x-show="tab === 'patients'" x-cloak data-profile-tab="patients" class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Pacientes</h1><p class="muted">{{ $pacientes->count() }} pacientes ativos no sistema.</p></div></div>
        <article class="card">
            <input type="search" x-model="searchPatients" placeholder="Pesquisar paciente..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
            @forelse($pacientes as $diagnostico)
                <div class="patient-row" data-search="{{ strtolower(($diagnostico->paciente->name ?? '') . ' ' . ($diagnostico->status ?? '')) }}" x-show="$el.dataset.search.includes(searchPatients.toLowerCase())">
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        <span class="avatar-initial">{{ substr($diagnostico->paciente->name ?? 'P', 0, 2) }}</span>
                        <div>
                            <strong>{{ $diagnostico->paciente->name ?? 'Paciente' }}</strong>
                            <p class="muted">{{ $diagnostico->status }} · {{ optional($diagnostico->updated_at)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('patients.history', $diagnostico->id_paciente) }}" class="btn btn-ghost btn-sm">Ver histórico</a>
                </div>
            @empty
                <p class="muted" style="text-align:center;padding:30px;">Nenhum paciente encontrado.</p>
            @endforelse
        </article>
    </section>

    <section x-show="tab === 'prescricoes'" x-cloak data-profile-tab="prescricoes" class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Prescrições</h1><p class="muted">{{ $prescricoes->count() }} prescrição(ões) registrada(s).</p></div></div>
        <article class="card table-wrap">
            <input type="search" x-model="searchPrescriptions" placeholder="Pesquisar por paciente ou status..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
            <table class="data-table">
                <thead><tr><th>Paciente</th><th>Data</th><th>Status</th><th>Ação</th></tr></thead>
                <tbody>
                @forelse($prescricoes as $prescricao)
                    <tr data-search="{{ strtolower(($prescricao->diagnostico?->paciente?->name ?? $user->name) . ' ' . ($prescricao->monitorings->first()?->status ?? 'pendente')) }}" x-show="$el.dataset.search.includes(searchPrescriptions.toLowerCase())">
                        <td>{{ $prescricao->diagnostico?->paciente?->name ?? $user->name }}</td>
                        <td>{{ optional($prescricao->created_at)->format('d/m/Y') }}</td>
                        <td><span class="tag">{{ $prescricao->monitorings->first()?->status ?? 'pendente' }}</span></td>
                        <td><button type="button" class="btn btn-ghost btn-sm" @click="openPrescription({{ $prescricao->id }})">Ver detalhes</button></td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--mu);padding:30px;">Nenhuma prescrição encontrada.</td></tr>
                @endforelse
                </tbody>
            </table>
        </article>
    </section>

    <section x-show="tab === 'prescription_detail'" x-cloak data-profile-tab="prescription_detail" class="tab-card">
        <button type="button" class="btn btn-ghost" style="margin-bottom:16px;" @click="go('prescricoes')"><i class="fa-solid fa-arrow-left"></i>Voltar</button>
        <div x-show="selectedPrescription">
            <section class="view-head" style="margin-bottom:14px;">
                <div>
                    <h1 class="view-title">Detalhe da Prescrição</h1>
                    <p class="mono" x-text="'Emitida em ' + (selectedPrescription?.created_at ? new Date(selectedPrescription.created_at).toLocaleDateString('pt-BR') : 'data não informada')"></p>
                </div>
                @unless($isDoctor)
                    <button type="button" class="btn btn-primary" x-show="!isPrescriptionStarted()" @click="startPrescription()"><i class="fa-solid fa-play"></i>Iniciar</button>
                @endunless
            </section>

            <section class="soft-row" style="margin-bottom:14px;border-color:var(--gbd);background:var(--gb);color:var(--gr);">
                <i class="fa-solid fa-stethoscope"></i>
                <p style="flex:1;margin:0;text-align:left;">
                    Diagnóstico confirmado:
                    <strong x-text="selectedPrescription?.diagnostico?.confirmed_disease_name || selectedPrescription?.diagnostico?.doenca?.name || selectedPrescription?.diagnostico?.doenca_final || 'Diagnóstico clínico'"></strong>
                    · Paciente:
                    <strong x-text="selectedPrescription?.diagnostico?.paciente?.name || 'Paciente'"></strong>
                </p>
            </section>

            <article class="card" style="margin-bottom:14px;">
                <h2 class="card-title" style="padding-bottom:12px;border-bottom:1px solid var(--bd);margin-bottom:16px;"><i class="fa-solid fa-microscope"></i> Dados do diagnóstico</h2>
                <div class="detail-grid">
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Doença confirmada</p>
                        <p style="font-weight:800;" x-text="selectedPrescription?.diagnostico?.confirmed_disease_name || selectedPrescription?.diagnostico?.doenca?.name || selectedPrescription?.diagnostico?.doenca_final || 'Não informada'"></p>
                    </div>
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Médico responsável</p>
                        <p style="font-weight:800;" x-text="selectedPrescription?.diagnostico?.medico?.name || 'Equipe clínica'"></p>
                    </div>
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Paciente</p>
                        <p style="font-weight:800;" x-text="selectedPrescription?.diagnostico?.paciente?.name || 'Paciente'"></p>
                    </div>
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Período da prescrição</p>
                        <p style="font-weight:800;">
                            <span x-text="selectedPrescription?.start_date ? new Date(selectedPrescription.start_date).toLocaleDateString('pt-BR') : 'Início pendente'"></span>
                            <span> - </span>
                            <span x-text="selectedPrescription?.finish_date ? new Date(selectedPrescription.finish_date).toLocaleDateString('pt-BR') : 'sem fim definido'"></span>
                        </p>
                    </div>
                </div>
            </article>

            <article class="card" style="margin-bottom:14px;">
                <h2 class="card-title" style="padding-bottom:12px;border-bottom:1px solid var(--bd);margin-bottom:16px;"><i class="fa-solid fa-pills"></i> Medicamentos prescritos</h2>
                <template x-for="med in selectedPrescription?.monitorings || []" :key="med.id">
                    <div class="med-row">
                        <div class="med-icon"><i class="fa-solid fa-capsules"></i></div>
                        <div class="med-info">
                            <p class="med-name" x-text="med.medication_name"></p>
                            <p class="med-detail">
                                <i class="fa-regular fa-clock"></i>
                                A cada <span x-text="med.interval_hours || '-'"></span> horas
                                · duração <span x-text="med.duration_days || '-'"></span> dias
                                · próxima notificação:
                                <span x-text="med.next_notification_at ? new Date(med.next_notification_at).toLocaleString('pt-BR') : 'não iniciado'"></span>
                            </p>
                            <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                                <div class="prog-wrap" style="flex:1;">
                                    <div class="prog-fill" :style="'width:' + (((med.intake_logs || []).filter(log => log.status === 'completed').length / Math.max((med.intake_logs || []).length, 1)) * 100) + '%'"></div>
                                </div>
                                <span class="mono" x-text="((med.intake_logs || []).filter(log => log.status === 'completed').length) + '/' + ((med.intake_logs || []).length || 0)"></span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                            <span class="tag" :class="med.status === 'active' ? 'success' : (med.status === 'completed' ? 'success' : 'warn')" x-text="med.status || 'pendente'"></span>
                            <button type="button" class="btn btn-ghost btn-xs" @click="openPharmacies(med.medication_name)">
                                <i class="fa-solid fa-location-dot"></i> Farmácias
                            </button>
                        </div>
                    </div>
                </template>
            </article>

            <article class="card" style="margin-bottom:14px;">
                <h2 class="card-title" style="padding-bottom:12px;border-bottom:1px solid var(--bd);margin-bottom:16px;"><i class="fa-solid fa-chart-line"></i> Adesão à medicação</h2>
                <p class="muted" x-text="selectedPrescription?.recommendations || 'Sem observações adicionais.'"></p>
                <div style="margin-top:16px;" x-show="pendingDoses().length">
                    <p class="mono" style="margin-bottom:8px;">Doses pendentes</p>
                    <template x-for="dose in pendingDoses()" :key="'pending-' + dose.id">
                        <div class="active-dose">
                            <div>
                                <strong x-text="dose.medication_name"></strong>
                                <p class="muted" style="font-size:.8rem;">
                                    Confirmar até
                                    <span x-text="dose.due_until ? new Date(dose.due_until).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '15 min'"></span>
                                </p>
                            </div>
                            @unless($isDoctor)
                                <button type="button" class="btn btn-green btn-sm" @click="completeDose(dose)"><i class="fa-solid fa-check"></i>Concluir</button>
                            @endunless
                        </div>
                    </template>
                </div>
                <div class="detail-grid" style="margin-top:16px;">
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Concluídas</p>
                        <p style="font-size:1.5rem;font-weight:800;color:var(--gr);" x-text="(selectedPrescription?.monitorings || []).flatMap(m => m.intake_logs || []).filter(log => log.status === 'completed').length"></p>
                    </div>
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Falhas</p>
                        <p style="font-size:1.5rem;font-weight:800;color:var(--rd);" x-text="(selectedPrescription?.monitorings || []).flatMap(m => m.intake_logs || []).filter(log => log.status === 'missed' || (log.status === 'pending' && !isDosePending(log))).length"></p>
                    </div>
                    <div class="detail-box">
                        <p class="mono" style="margin-bottom:6px;">Pendentes</p>
                        <p style="font-size:1.5rem;font-weight:800;color:var(--wn);" x-text="pendingDoses().length"></p>
                    </div>
                </div>
            </article>

            <article class="card">
                <h2 class="card-title" style="padding-bottom:12px;border-bottom:1px solid var(--bd);margin-bottom:16px;"><i class="fa-solid fa-clock-rotate-left"></i> Histórico de tomas</h2>
                <div class="soft-list">
                <template x-for="med in selectedPrescription?.monitorings || []" :key="'logs-' + med.id">
                    <div>
                        <template x-for="log in (med.intake_logs || [])" :key="log.id">
                            <div class="soft-row" style="margin-bottom:8px;" x-show="!isDosePending(log)">
                                <div>
                                    <strong x-text="med.medication_name"></strong>
                                    <p class="muted" x-text="'Agendada: ' + new Date(log.scheduled_at).toLocaleString('pt-BR')"></p>
                                </div>
                                <span class="tag" :class="doseStatus(log) === 'Concluído' ? 'success' : 'danger'" x-text="doseStatus(log)"></span>
                            </div>
                        </template>
                    </div>
                </template>
                </div>
            </article>
        </div>
    </section>

    <div class="modal-overlay" :class="pharmacyOpen ? 'open' : ''" x-show="pharmacyOpen" x-cloak @click.self="closePharmacies()" role="dialog" aria-modal="true">
        <div class="modal-box">
            <div class="modal-head">
                <div>
                    <h2 style="font-size:1rem;font-weight:800;color:var(--tx);"><i class="fa-solid fa-location-dot" style="color:var(--in);"></i> Farmácias próximas</h2>
                    <p class="mono" x-text="pharmacyMedication"></p>
                </div>
                <button type="button" class="modal-close" @click="closePharmacies()" aria-label="Fechar"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="map-panel">
                <template x-if="pharmacies.length">
                    <iframe :src="pharmacyMapUrl()" title="Mapa de farmácias próximas" style="width:100%;min-height:260px;border:0;border-radius:14px;background:var(--sf2);"></iframe>
                </template>
                <template x-if="!pharmacies.length">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                        <i class="fa-solid fa-map-location-dot" style="font-size:3rem;color:var(--in);opacity:.45;"></i>
                        <p class="mono">Farmácias próximas</p>
                        <p class="muted" style="max-width:360px;">Use sua localização para buscar farmácias num raio de 5km.</p>
                    </div>
                </template>
                <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
                    <button type="button" class="btn btn-primary btn-sm" @click="requestPharmacyLocation()"><i class="fa-solid fa-location-crosshairs"></i>Usar minha localização</button>
                    <button type="button" class="btn btn-ghost btn-sm" @click="searchDefaultLocation()">Usar Luanda</button>
                </div>
            </div>
            <div class="pharmacy-list">
                <template x-if="pharmacyLoading">
                    <div class="pharmacy-row"><i class="fa-solid fa-spinner fa-spin"></i><strong>Buscando farmácias próximas...</strong></div>
                </template>
                <template x-if="pharmacyError">
                    <div class="pharmacy-row" style="border-color:var(--wbd);background:var(--wb);color:var(--wn);"><i class="fa-solid fa-circle-info"></i> <span x-text="pharmacyError"></span></div>
                </template>
                <template x-if="pharmacyNotice">
                    <div class="pharmacy-row" style="border-color:var(--bbd);background:var(--bb);color:var(--bl);"><i class="fa-solid fa-circle-info"></i> <span x-text="pharmacyNotice"></span></div>
                </template>
                <template x-for="pharmacy in pharmacies" :key="pharmacy.osm_id">
                    <div class="pharmacy-row">
                        <div style="min-width:0;">
                            <strong x-text="pharmacy.name"></strong>
                            <p class="muted" style="font-size:.8rem;">
                                <span x-text="pharmacy.has_stock ? 'Estoque provável' : 'Estoque não confirmado'"></span>
                                <template x-if="pharmacy.price"><span> · <span x-text="pharmacy.price"></span> Kz</span></template>
                            </p>
                        </div>
                        <a class="btn btn-ghost btn-sm" :href="pharmacy.maps_url" target="_blank" rel="noopener"><i class="fa-solid fa-map-location-dot"></i>Mapa</a>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="modal-overlay" :class="noticeOpen ? 'open' : ''" x-show="noticeOpen" x-cloak @click.self="noticeOpen=false" role="dialog" aria-modal="true">
        <div class="std-modal">
            <h2 x-text="noticeTitle"></h2>
            <p x-text="noticeMessage"></p>
            <div class="std-actions">
                <button type="button" class="btn btn-primary" @click="noticeOpen=false">Entendi</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" :class="clinicModalOpen ? 'open' : ''" x-show="clinicModalOpen" x-cloak @click.self="clinicModalOpen=false" role="dialog" aria-modal="true">
        <form method="POST" action="{{ route('clinical-requests.store') }}" class="modal-box consultation-modal" @submit="prepareClinicSubmit($event)">
            @csrf
            <input type="hidden" name="clinic_id" :value="selectedClinic?.id">
            <input type="hidden" name="doctor_id" :value="selectedDoctor?.id">
            <div class="modal-head">
                <div>
                    <h2 class="view-title">Nova consulta</h2>
                    <p class="muted">Escolha uma clínica aberta agora, um médico livre e envie o seu quadro clínico.</p>
                </div>
                <button type="button" class="modal-close" @click="clinicModalOpen=false"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="consultation-body">
                <template x-if="!clinics.length">
                    <div class="alert warning"><i class="fa-solid fa-circle-info"></i><span>Nenhuma clínica aberta neste momento.</span></div>
                </template>
                <div class="consultation-picker" x-show="clinics.length">
                    <article class="consultation-panel">
                        <p class="mono" style="margin-bottom:10px;">Clínica</p>
                        <div class="consultation-list">
                            <template x-for="clinic in clinics" :key="clinic.id">
                            <button type="button" class="consultation-option" :class="selectedClinic?.id === clinic.id ? 'active' : ''" @click="chooseClinic(clinic)">
                                <span><strong x-text="clinic.name"></strong><small class="muted" style="display:block;" x-text="'Aberta: ' + clinic.activity_hours"></small></span>
                                <span class="tag" x-text="clinic.doctors.length + ' livre(s)'"></span>
                            </button>
                            </template>
                        </div>
                    </article>
                    <article class="consultation-panel">
                        <p class="mono" style="margin-bottom:10px;">Médico livre</p>
                        <template x-if="selectedClinic && !(selectedClinic.doctors || []).length">
                            <div class="alert warning"><i class="fa-solid fa-circle-info"></i><span>Esta clínica está aberta, mas não tem médicos livres agora.</span></div>
                        </template>
                        <div class="consultation-list">
                            <template x-for="doctor in selectedClinic?.doctors || []" :key="doctor.id">
                            <button type="button" class="consultation-option" :class="selectedDoctor?.id === doctor.id ? 'active' : ''" @click="selectedDoctor = doctor">
                                <span><strong x-text="doctor.name"></strong><small class="muted" style="display:block;" x-text="doctor.specialty || 'Clínico geral'"></small></span>
                                <span class="tag success">Livre</span>
                            </button>
                            </template>
                        </div>
                    </article>
                </div>
                <div x-show="clinics.length && selectedDoctor" class="consultation-form">
                    <div class="consultation-section">
                        <p class="mono" style="margin-bottom:10px;">Pedido de análise</p>
                        <div class="modal-field" style="position:relative;">
                            <label for="requestSymptomSearch">Sintomas</label>
                            <input type="text" id="requestSymptomSearch" placeholder="Pesquisar sintoma..." oninput="searchRequestSymptoms()" style="position:relative;z-index:10;border-radius:12px 12px 0 0;border-bottom:none;" onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                            <div id="requestSymptomsDropdown" style="display:none;position:absolute;top:100%;left:0;right:0;background:var(--sf);border:1px solid var(--bd);border-top:none;border-radius:0 0 12px 12px;max-height:240px;overflow-y:auto;z-index:15;box-shadow:var(--sh2);scrollbar-width:none;">
                                <style scoped>
                                    #requestSymptomsDropdown::-webkit-scrollbar{width:0;height:0}
                                </style>
                            </div>
                            <p class="muted" style="font-size:.78rem;margin-top:8px;">Pode deixar em branco se não tiver certeza.</p>
                        </div>
                        <div id="requestSelectedSymptoms" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;min-height:20px;">
                            <p class="muted" style="font-size:.85rem;margin:0;">Nenhum sintoma adicionado</p>
                        </div>
                        <input type="hidden" id="requestSymptomIds" name="symptom_ids" value="[]">
                    </div>

                    <div class="consultation-biometry">
                        <div class="modal-field">
                            <label for="requestAge">Idade</label>
                            <input id="requestAge" name="age" type="number" min="0" max="130" required>
                        </div>
                        <div class="modal-field">
                            <label for="requestWeight">Peso (kg)</label>
                            <input id="requestWeight" name="weight" type="number" min="1" max="500" step="0.1" required>
                        </div>
                        <div class="modal-field">
                            <label for="requestHeight">Altura (m)</label>
                            <input id="requestHeight" name="height" type="number" min="0.3" max="2.8" step="0.01" placeholder="1.75" required>
                        </div>
                        <div class="modal-field">
                            <label for="requestGender">Gênero</label>
                            <select id="requestGender" name="gender" required>
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                    </div>

                    <div class="consultation-notes">
                        <div class="modal-field wide">
                            <label for="requestDescription">Descrição do que sente em todos os detalhes</label>
                            <textarea id="requestDescription" name="description" required placeholder="Descreva dor, intensidade, localização, frequência e outros sinais."></textarea>
                        </div>
                        <div class="modal-field">
                            <label for="requestEvolution">Quando começou e como evoluiu</label>
                            <textarea id="requestEvolution" name="evolution" required></textarea>
                        </div>
                        <div class="modal-field">
                            <label for="requestTriggers">O que melhora ou piora os sintomas</label>
                            <textarea id="requestTriggers" name="triggers" required></textarea>
                        </div>
                        <div class="modal-field">
                            <label for="requestHistory">Historial médico e medicamentos</label>
                            <textarea id="requestHistory" name="medical_history" required></textarea>
                        </div>
                        <div class="modal-field">
                            <label for="requestContext">Contexto</label>
                            <textarea id="requestContext" name="context" required placeholder="Viagens, contactos, stress, alimentação ou outros fatores relevantes."></textarea>
                        </div>
                    </div>
                </div>
                <div class="std-actions">
                    <button type="button" class="btn btn-ghost" @click="clinicModalOpen=false">Cancelar</button>
                    <button type="submit" class="btn btn-primary" x-show="clinics.length && selectedDoctor"><i class="fa-solid fa-paper-plane"></i>Solicitar análise</button>
                </div>
            </div>
        </form>
    </div>

    @if($checkoutPrescription)
        <div class="modal-overlay open" role="dialog" aria-modal="true">
            <form method="POST" action="{{ route('clinic.stock.checkout', $checkoutPrescription) }}" class="modal-box" style="max-width:720px;">
                @csrf
                <div class="modal-head">
                    <div>
                        <h2 class="view-title">Checkout de estoque</h2>
                        <p class="muted">Informe quais itens foram utilizados na prescrição de {{ $checkoutPrescription->diagnostico?->paciente?->name ?? 'paciente' }}.</p>
                    </div>
                    <a class="modal-close" href="{{ route('dashboard') }}"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div style="padding:22px;">
                    @forelse($checkoutStockItems as $index => $item)
                        <div class="soft-row" style="margin-bottom:10px;">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                            <div>
                                <strong>{{ $item->name }}</strong>
                                <p class="muted">Disponível: {{ $item->quantity }} {{ $item->unit }}</p>
                            </div>
                            <input name="items[{{ $index }}][quantity]" type="number" min="0" max="{{ $item->quantity }}" value="0" style="width:110px;border:1px solid var(--bd);border-radius:12px;background:var(--sf);color:var(--tx);padding:10px 12px;font-weight:800;">
                        </div>
                    @empty
                        <div class="alert warning"><i class="fa-solid fa-circle-info"></i><span>A clínica ainda não possui itens cadastrados no estoque.</span></div>
                    @endforelse
                    <div class="std-actions">
                        <a class="btn btn-ghost" href="{{ route('dashboard') }}">Pular</a>
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check"></i>Registrar uso</button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <section x-show="tab === 'profile'" x-cloak data-profile-tab="profile" class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Meu Perfil</h1><p class="muted">Informações públicas da sua conta.</p></div></div>
        <article class="card" style="display:flex;align-items:center;gap:18px;flex-wrap:wrap;">
            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" style="width:82px;height:82px;border-radius:50%;object-fit:cover;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:var(--tx);">{{ auth()->user()->name }}</h2>
                <p class="muted">{{ auth()->user()->email }}</p>
                <span class="tag success" style="margin-top:10px;">{{ $roleLabel }}</span>
                <button type="button" class="btn btn-ghost btn-sm" style="margin-top:12px;" @click="go('settings')"><i class="fa-solid fa-camera"></i>Trocar foto</button>
            </div>
        </article>
        <article class="card" style="margin-top:18px;">
            <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-clock-rotate-left"></i> Histórico de diagnósticos</h2>
            <div class="soft-list">
                @forelse(($isDoctor ? ($filaDiagnosticos ?? collect())->merge($pacientes ?? collect())->unique('id') : $meusDiagnosticos) as $diag)
                    <div class="soft-row">
                        <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                            <span class="avatar-initial">{{ substr($diag->paciente->name ?? $user->name, 0, 2) }}</span>
                            <div style="min-width:0;">
                                <strong>{{ $diag->confirmed_disease_name ?? ($diag->doencas_sugeridas[0]['nome'] ?? 'Diagnóstico clínico') }}</strong>
                                <p class="muted" style="font-size:.84rem;">{{ $diag->paciente->name ?? $user->name }} · {{ optional($diag->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end;">
                            <span class="tag {{ $diag->status === 'validado' ? 'success' : 'warn' }}">{{ $diag->status }}</span>
                            @if($isDoctor)
                                <a href="{{ route('diagnostico.resultado', $diag->id) }}" class="btn btn-ghost btn-sm">Ver diagnóstico</a>
                            @elseif($diag->prescricao)
                                <button type="button" class="btn btn-primary btn-sm" @click="openPrescription({{ $diag->prescricao->id }})">
                                    Ver prescrição
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="muted" style="text-align:center;padding:24px;">Nenhum diagnóstico encontrado.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section x-show="tab === 'settings'" x-cloak data-profile-tab="settings" class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Configurações</h1><p class="muted">Dados pessoais, notificações e segurança.</p></div></div>
        <div class="settings-grid">
            <article class="card settings-card">
                <h2 class="mono" style="margin-bottom:16px;"><i class="fa-solid fa-id-card"></i> Dados pessoais</h2>
                <div class="settings-livewire">
                    @livewire('profile.update-profile-information-form')
                </div>
            </article>
            <div class="settings-panel">
                <article class="card">
                    <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-bell"></i> Notificações</h2>
                    <div class="settings-option">
                        <div>
                            <strong style="color:var(--tx);">Alertas clínicos</strong>
                            <p class="muted" style="font-size:.82rem;margin-top:2px;">Controle quais alertas aparecem na conta.</p>
                        </div>
                        <select id="alertas-clinicos" style="max-width:180px;width:100%;padding:10px 12px;border:1px solid var(--bd);border-radius:12px;background:var(--sf);color:var(--tx);">
                            <option value="all">Todos</option>
                            <option value="critical">Críticos</option>
                            <option value="none">Desativados</option>
                        </select>
                    </div>
                </article>
                <article class="card">
                    <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-shield-halved"></i> Segurança</h2>
                    <p class="muted" style="font-size:.86rem;line-height:1.5;">Atualize a sua senha abaixo. Use uma senha forte e exclusiva para manter a conta protegida.</p>
                </article>
            </div>
        </div>
        <article class="card settings-card settings-livewire" style="margin-top:18px;">
            <h2 class="mono" style="margin-bottom:16px;"><i class="fa-solid fa-key"></i> Alterar senha</h2>
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                @livewire('profile.update-password-form')
            @endif
        </article>
    </section>
</div>

<script>
window.syncProfileHashTabs = function() {
    const shell = document.getElementById('profileShell');
    if (!shell) return;

    const rawTab = (window.location.hash || '#dashboard').slice(1) || 'dashboard';
    const panels = Array.from(shell.querySelectorAll('[data-profile-tab]'));
    const hasMatch = panels.some(panel => (panel.dataset.profileTab || '').split(/\s+/).includes(rawTab));
    const activeTab = hasMatch ? rawTab : 'dashboard';

    panels.forEach(panel => {
        const keys = (panel.dataset.profileTab || '').split(/\s+/);
        panel.removeAttribute('x-cloak');
        panel.style.display = keys.includes(activeTab) ? '' : 'none';
    });
};

window.addEventListener('hashchange', window.syncProfileHashTabs);
window.addEventListener('DOMContentLoaded', window.syncProfileHashTabs);
window.addEventListener('pageshow', window.syncProfileHashTabs);

// Funções globais para gerenciar sintomas do modal de nova consulta
window.addRequestSymptom = function(appState, id, name) {
    console.log('addRequestSymptom called with:', { appState, id, name, currentSymptoms: appState.requestSelectedSymptoms });
    
    if(!appState.requestSelectedSymptoms.find(s => s.id == id)){
        appState.requestSelectedSymptoms.push({id, name});
        console.log('Symptom added, new list:', appState.requestSelectedSymptoms);
        window.renderRequestSelectedSymptoms(appState);
    } else {
        console.log('Symptom already exists');
    }
    document.getElementById('requestSymptomSearch').value = '';
    document.getElementById('requestSymptomsDropdown').style.display = 'none';
};

window.removeRequestSymptom = function(appState, id) {
    appState.requestSelectedSymptoms = appState.requestSelectedSymptoms.filter(s => s.id != id);
    window.renderRequestSelectedSymptoms(appState);
};

window.renderRequestSelectedSymptoms = function(appState) {
    console.log('Rendering symptoms:', appState.requestSelectedSymptoms);
    const container = document.getElementById('requestSelectedSymptoms');
    if(!container) {
        console.error('Container not found');
        return;
    }
    
    if(appState.requestSelectedSymptoms.length === 0) {
        container.innerHTML = '<p class="muted" style="font-size:.85rem;margin:0;">Nenhum sintoma adicionado</p>';
        return;
    }
    
    window.__clinicalyAppState = appState;
    
    container.innerHTML = appState.requestSelectedSymptoms.map(s => {
        return '<div style="background:var(--in);border:1.5px solid var(--il);color:#fff;padding:7px 14px;border-radius:999px;font-size:.85rem;font-weight:800;display:inline-flex;align-items:center;gap:8px;"><span>'+s.name+'</span><button type="button" onclick="window.removeRequestSymptom(window.__clinicalyAppState, '+s.id+');" style="background:none;border:none;color:#fff;cursor:pointer;font-size:.9rem;"><i class="fa-solid fa-xmark"></i></button></div>';
    }).join('');
    const ids = appState.requestSelectedSymptoms.map(s => s.id);
    const hiddenInput = document.getElementById('requestSymptomIds');
    if(hiddenInput) hiddenInput.value = JSON.stringify(ids);
    console.log('Symptoms rendered, hidden input updated with IDs:', ids);
};

window.prepareClinicFormSubmit = function(appState, event) {
    const symptomIds = appState.requestSelectedSymptoms.map(s => s.id);
    symptomIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'symptoms[]';
        input.value = id;
        event.target.appendChild(input);
    });
    return true;
};

window.searchRequestSymptoms = function() {
    const query = document.getElementById('requestSymptomSearch').value.toLowerCase();
    const dropdown = document.getElementById('requestSymptomsDropdown');
    const input = document.getElementById('requestSymptomSearch');
    
    // Procura o elemento x-data mais próximo (pai)
    let el = input ? input.closest('[x-data]') : null;
    if(!el) el = document.querySelector('[x-data]');
    
    // Alpine 3.x armazena dados em _x_dataStack
    let appState = el?._x_dataStack?.[0];
    if(!appState) {
        console.error('Alpine component not found', { el, dataStack: el?._x_dataStack });
        return;
    }
    
    console.log('Search query:', query);
    console.log('All symptoms:', appState.allConsultationSymptoms);
    
    if (query.length < 1) {
        dropdown.style.display = 'none';
        return;
    }
    
    const filtered = appState.allConsultationSymptoms.filter(s => 
        s.name && s.name.toLowerCase().includes(query)
    );
    
    console.log('Filtered results:', filtered);
    
    if (filtered.length === 0) {
        dropdown.innerHTML = '<div style="padding:12px 16px;color:var(--mu);font-size:.9rem;">Nenhum sintoma encontrado</div>';
        dropdown.style.display = 'block';
        return;
    }
    
    // Armazena o appState no escopo global para os botões acessarem
    window.__clinicalyAppState = appState;
    
    dropdown.innerHTML = filtered.map(s => {
        return '<button type="button" onclick="window.addRequestSymptom(window.__clinicalyAppState, '+s.id+', \''+s.name.replace(/'/g, "\\'")+'\');" style="width:100%;text-align:left;padding:12px 16px;border-bottom:1px solid var(--bd);background:transparent;color:var(--tx);border:none;cursor:pointer;transition:background .2s;font-weight:600;font-size:.9rem;font-family:\'Dosis\',sans-serif;" onmouseover="this.style.background=\'var(--is)\'" onmouseout="this.style.background=\'transparent\'">+ '+s.name+'</button>';
    }).join('');
    
    dropdown.style.display = 'block';
};

function searchRequestSymptomsAddItem(id, name) {
    const el = document.querySelector('[x-data]');
    let appState = el?._x_dataStack?.[0];
    if(appState) {
        window.addRequestSymptom(appState, id, name);
    }
}
</script>

</x-app-layout>
