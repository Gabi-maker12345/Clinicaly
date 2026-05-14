<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php
    $role = auth()->user()->role ?? 'pacient';
    $isDoctor = in_array($role, ['doctor', 'medico', 'médico'], true);
    $roleLabel = $isDoctor ? 'Médico' : 'Paciente';
    $diagnosticosPendentes = ($isDoctor ? ($filaDiagnosticos ?? collect()) : $meusDiagnosticos)->where('status', 'pendente');
    $diagnosticosValidados = ($isDoctor ? $pacientes : $meusDiagnosticos)->where('status', 'validado');
    $prescricoes = $isDoctor ? ($prescricoesMedico ?? collect()) : $minhasPrescricoes;
    $prescricaoData = [];
    foreach ($prescricoes as $prescricao) {
        $prescricao->loadMissing(['diagnostico.paciente', 'diagnostico.medico', 'diagnostico.doenca', 'monitorings.intakeLogs']);
        $prescricaoData[$prescricao->id] = $prescricao->toArray();
    }
?>

<style>
    .view-shell{max-width:1180px;margin:0 auto}
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
    .pharmacy-list{display:flex;flex-direction:column;gap:10px;max-height:430px;overflow-y:auto;padding:18px 22px;scrollbar-width:none}
    .pharmacy-list::-webkit-scrollbar{width:0;height:0}
    .pharmacy-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px;border:1px solid var(--bd);border-radius:14px;background:var(--sf2)}
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

<div class="view-shell"
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
        prescriptions: <?php echo \Illuminate\Support\Js::from($prescricaoData)->toHtml() ?>,
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
        async completeDose(log){
            const response = await fetch('<?php echo e(route('monitoring.logs.complete', '__LOG_ID__')); ?>'.replace('__LOG_ID__', log.id), {
                method: 'PATCH',
                headers: {'Accept':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}
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
                    headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json'}
                });
                const data = await response.json().catch(() => ({}));
                if(!response.ok) throw new Error(data.message || 'Não foi possível iniciar esta prescrição.');
                if(data.prescription) this.applyPrescription(data.prescription);
                this.showNotice('Prescrição ativada', data.message);
            } catch (error) {
                this.showNotice('Erro', error.message || 'Não foi possível iniciar esta prescrição.');
            }
        },
        go(value){ this.tab = value; window.location.hash = value; }
     }"
     x-init="window.addEventListener('hashchange', () => tab = (window.location.hash || '#dashboard').replace('#','')); setInterval(() => nowTick = Date.now(), 15000)">

    <section x-show="tab === 'dashboard'" class="tab-card">
        <div class="view-head">
            <div>
                <span class="tag tag-green"><?php echo e($roleLabel); ?></span>
                <h1 class="view-title" style="margin-top:10px;">Bom dia, <span style="color:var(--in);"><?php echo e($isDoctor ? 'Dr. ' : ''); ?><?php echo e(explode(' ', $user->name)[0]); ?></span></h1>
                <span class="mono">Visão geral clínica</span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDoctor): ?>
                <a href="<?php echo e(route('discovery.index')); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Nova Consulta
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="grid-kpi">
            <article class="kpi-card"><div class="value"><?php echo e($isDoctor ? $pacientes->count() : $meusDiagnosticos->count()); ?></div><div class="label"><?php echo e($isDoctor ? 'Pacientes ativos' : 'Meus diagnósticos'); ?></div></article>
            <article class="kpi-card"><div class="value"><?php echo e($diagnosticosPendentes->count()); ?></div><div class="label">Pendentes</div></article>
            <article class="kpi-card"><div class="value"><?php echo e($diagnosticosValidados->count()); ?></div><div class="label">Validados</div></article>
            <article class="kpi-card"><div class="value"><?php echo e($prescricoes->count()); ?></div><div class="label">Prescrições</div></article>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($diagnosticosPendentes->count()): ?>
            <div class="soft-row" style="margin-top:18px;border-color:var(--rbd);background:var(--rb);color:var(--rd);">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p style="flex:1;margin:0;text-align:left;">
                    <strong><?php echo e($diagnosticosPendentes->count()); ?> diagnóstico(s)</strong> aguardam revisão.
                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDoctor): ?><button type="button" class="btn btn-danger btn-sm" style="margin-left:auto;" @click="go('fila')">Ver agora</button><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid-two" style="margin-top:18px;">
            <article class="card">
                <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-clock"></i> Atividade recente</h2>
                <div class="soft-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($isDoctor ? $pacientes : $meusDiagnosticos)->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="soft-row">
                            <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                                <span class="avatar-initial"><?php echo e(substr($diag->paciente->name ?? $user->name, 0, 2)); ?></span>
                                <div style="min-width:0;">
                                    <strong><?php echo e($diag->paciente->name ?? $user->name); ?></strong>
                                    <p class="muted" style="font-size:.84rem;"><?php echo e($diag->status); ?> · <?php echo e(optional($diag->created_at)->diffForHumans()); ?></p>
                                </div>
                            </div>
                            <span class="tag <?php echo e($diag->status === 'validado' ? 'success' : 'warn'); ?>"><?php echo e($diag->status); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="muted">Nenhuma atividade encontrada.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </article>

            <article class="card">
                <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-bolt"></i> Ações rápidas</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDoctor): ?><button class="btn btn-ghost" @click="go('fila')"><i class="fa-solid fa-list-check"></i>Fila</button><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <a class="btn btn-ghost" href="<?php echo e(route('discovery.index')); ?>"><i class="fa-solid fa-book-medical"></i>Dicionário Clínico</a>
                    <button class="btn btn-ghost" @click="go('prescricoes')"><i class="fa-solid fa-file-prescription"></i>Prescrições</button>
                    <a class="btn btn-ghost" href="<?php echo e(route('chat.index')); ?>"><i class="fa-solid fa-robot"></i>Chat de IA</a>
                    <a class="btn btn-ghost" href="<?php echo e(route('messages.index')); ?>"><i class="fa-solid fa-comments"></i>Conversas</a>
                    <button class="btn btn-ghost" @click="go('profile')"><i class="fa-solid fa-user"></i>Perfil</button>
                </div>
            </article>
        </div>
    </section>

    <section x-show="tab === 'fila'" x-cloak class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Fila de Validação</h1><p class="muted">Diagnósticos aguardando revisão médica.</p></div></div>
        <article class="card">
            <input type="search" x-model="searchFila" placeholder="Pesquisar por paciente ou diagnóstico..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $diagnosticosPendentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diagnostico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="patient-row" data-search="<?php echo e(strtolower(($diagnostico->paciente->name ?? '') . ' ' . collect($diagnostico->doencas_sugeridas ?? [])->pluck('nome')->join(' '))); ?>" x-show="$el.dataset.search.includes(searchFila.toLowerCase())">
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        <span class="avatar-initial"><?php echo e(substr($diagnostico->paciente->name ?? 'P', 0, 2)); ?></span>
                        <div>
                            <strong><?php echo e($diagnostico->paciente->name ?? 'Paciente'); ?></strong>
                            <p class="muted"><?php echo e(collect($diagnostico->doencas_sugeridas ?? [])->take(2)->pluck('nome')->join(' · ') ?: 'Diagnóstico pendente'); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('diagnostico.validar', $diagnostico->id)); ?>" class="btn btn-primary btn-sm">Validar</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted" style="text-align:center;padding:30px;">Fila vazia.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </article>
    </section>

    <section x-show="tab === 'agenda'" x-cloak class="tab-card">
        <?php echo $__env->make('appointments._content', $agendaData ?? [], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <section x-show="tab === 'analise1' || tab === 'analise2' || tab === 'analise3'" x-cloak class="tab-card">
        <article class="card placeholder">
            <div>
                <div style="font-size:2rem;color:var(--in);margin-bottom:12px;"><i class="fa-solid fa-chart-simple"></i></div>
                <span class="tag">Em breve</span>
                <h1 class="view-title" style="margin-top:12px;"><?php echo e(__('Módulo em branco')); ?></h1>
            </div>
        </article>
    </section>

    <section x-show="tab === 'patients'" x-cloak class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Pacientes</h1><p class="muted"><?php echo e($pacientes->count()); ?> pacientes ativos no sistema.</p></div></div>
        <article class="card">
            <input type="search" x-model="searchPatients" placeholder="Pesquisar paciente..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diagnostico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="patient-row" data-search="<?php echo e(strtolower(($diagnostico->paciente->name ?? '') . ' ' . ($diagnostico->status ?? ''))); ?>" x-show="$el.dataset.search.includes(searchPatients.toLowerCase())">
                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                        <span class="avatar-initial"><?php echo e(substr($diagnostico->paciente->name ?? 'P', 0, 2)); ?></span>
                        <div>
                            <strong><?php echo e($diagnostico->paciente->name ?? 'Paciente'); ?></strong>
                            <p class="muted"><?php echo e($diagnostico->status); ?> · <?php echo e(optional($diagnostico->updated_at)->format('d/m/Y')); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('patients.history', $diagnostico->id_paciente)); ?>" class="btn btn-ghost btn-sm">Ver histórico</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="muted" style="text-align:center;padding:30px;">Nenhum paciente encontrado.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </article>
    </section>

    <section x-show="tab === 'prescricoes'" x-cloak class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Prescrições</h1><p class="muted"><?php echo e($prescricoes->count()); ?> prescrição(ões) registrada(s).</p></div></div>
        <article class="card table-wrap">
            <input type="search" x-model="searchPrescriptions" placeholder="Pesquisar por paciente ou status..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
            <table class="data-table">
                <thead><tr><th>Paciente</th><th>Data</th><th>Status</th><th>Ação</th></tr></thead>
                <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $prescricoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescricao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-search="<?php echo e(strtolower(($prescricao->diagnostico?->paciente?->name ?? $user->name) . ' ' . ($prescricao->monitorings->first()?->status ?? 'pendente'))); ?>" x-show="$el.dataset.search.includes(searchPrescriptions.toLowerCase())">
                        <td><?php echo e($prescricao->diagnostico?->paciente?->name ?? $user->name); ?></td>
                        <td><?php echo e(optional($prescricao->created_at)->format('d/m/Y')); ?></td>
                        <td><span class="tag"><?php echo e($prescricao->monitorings->first()?->status ?? 'pendente'); ?></span></td>
                        <td><button type="button" class="btn btn-ghost btn-sm" @click="openPrescription(<?php echo e($prescricao->id); ?>)">Ver detalhes</button></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" style="text-align:center;color:var(--mu);padding:30px;">Nenhuma prescrição encontrada.</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </article>
    </section>

    <section x-show="tab === 'prescription_detail'" x-cloak class="tab-card">
        <button type="button" class="btn btn-ghost" style="margin-bottom:16px;" @click="go('prescricoes')"><i class="fa-solid fa-arrow-left"></i>Voltar</button>
        <div x-show="selectedPrescription">
            <section class="view-head" style="margin-bottom:14px;">
                <div>
                    <h1 class="view-title">Detalhe da Prescrição</h1>
                    <p class="mono" x-text="'Emitida em ' + (selectedPrescription?.created_at ? new Date(selectedPrescription.created_at).toLocaleDateString('pt-BR') : 'data não informada')"></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($isDoctor)): ?>
                    <button type="button" class="btn btn-primary" x-show="!isPrescriptionStarted()" @click="startPrescription()"><i class="fa-solid fa-play"></i>Iniciar</button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($isDoctor)): ?>
                                <button type="button" class="btn btn-green btn-sm" @click="completeDose(dose)"><i class="fa-solid fa-check"></i>Concluir</button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

    <section x-show="tab === 'profile'" x-cloak class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Meu Perfil</h1><p class="muted">Informações públicas da sua conta.</p></div></div>
        <article class="card" style="display:flex;align-items:center;gap:18px;flex-wrap:wrap;">
            <img src="<?php echo e(auth()->user()->profile_photo_url); ?>" alt="<?php echo e(auth()->user()->name); ?>" style="width:82px;height:82px;border-radius:50%;object-fit:cover;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:var(--tx);"><?php echo e(auth()->user()->name); ?></h2>
                <p class="muted"><?php echo e(auth()->user()->email); ?></p>
                <span class="tag success" style="margin-top:10px;"><?php echo e($roleLabel); ?></span>
                <button type="button" class="btn btn-ghost btn-sm" style="margin-top:12px;" @click="go('settings')"><i class="fa-solid fa-camera"></i>Trocar foto</button>
            </div>
        </article>
        <article class="card" style="margin-top:18px;">
            <h2 class="mono" style="margin-bottom:14px;"><i class="fa-solid fa-clock-rotate-left"></i> Histórico de diagnósticos</h2>
            <div class="soft-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($isDoctor ? ($filaDiagnosticos ?? collect())->merge($pacientes ?? collect())->unique('id') : $meusDiagnosticos); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="soft-row">
                        <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                            <span class="avatar-initial"><?php echo e(substr($diag->paciente->name ?? $user->name, 0, 2)); ?></span>
                            <div style="min-width:0;">
                                <strong><?php echo e($diag->confirmed_disease_name ?? ($diag->doencas_sugeridas[0]['nome'] ?? 'Diagnóstico clínico')); ?></strong>
                                <p class="muted" style="font-size:.84rem;"><?php echo e($diag->paciente->name ?? $user->name); ?> · <?php echo e(optional($diag->created_at)->format('d/m/Y H:i')); ?></p>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end;">
                            <span class="tag <?php echo e($diag->status === 'validado' ? 'success' : 'warn'); ?>"><?php echo e($diag->status); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDoctor): ?>
                                <a href="<?php echo e(route('diagnostico.resultado', $diag->id)); ?>" class="btn btn-ghost btn-sm">Ver diagnóstico</a>
                            <?php elseif($diag->prescricao): ?>
                                <button type="button" class="btn btn-primary btn-sm" @click="openPrescription(<?php echo e($diag->prescricao->id); ?>)">
                                    Ver prescrição
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="muted" style="text-align:center;padding:24px;">Nenhum diagnóstico encontrado.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </article>
    </section>

    <section x-show="tab === 'settings'" x-cloak class="tab-card">
        <div class="view-head"><div><h1 class="view-title">Configurações</h1><p class="muted">Dados pessoais, notificações e segurança.</p></div></div>
        <div class="settings-grid">
            <article class="card settings-card">
                <h2 class="mono" style="margin-bottom:16px;"><i class="fa-solid fa-id-card"></i> Dados pessoais</h2>
                <div class="settings-livewire">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.update-profile-information-form');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3421852664-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords())): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.update-password-form');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3421852664-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </article>
    </section>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\gcris\OneDrive\Documentos\Escola\Projetos Pessoais\Clinicaly\resources\views/profile/show.blade.php ENDPATH**/ ?>