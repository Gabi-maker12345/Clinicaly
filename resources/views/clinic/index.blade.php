<x-app-layout>
@php
    $tabs = [
        'employees' => ['label' => 'Meus funcionários', 'icon' => 'fa-user-doctor'],
        'patients' => ['label' => 'Pacientes', 'icon' => 'fa-hospital-user'],
        'stock' => ['label' => 'Estoque', 'icon' => 'fa-boxes-stacked'],
        'appointments' => ['label' => 'Agendamentos', 'icon' => 'fa-calendar-days'],
    ];

    $employeePayload = $employees->map(fn ($employee) => [
        'id' => $employee->id,
        'name' => $employee->name,
        'email' => $employee->email,
        'specialty' => $employee->specialty,
        'phone' => $employee->phone,
        'is_available' => $employee->is_available,
    ])->values();

    $stockPayload = $stockItems->map(fn ($item) => [
        'id' => $item->id,
        'name' => $item->name,
        'category' => $item->category,
        'unit' => $item->unit,
        'quantity' => $item->quantity,
        'minimum_quantity' => $item->minimum_quantity,
        'description' => $item->description,
    ])->values();

    $patientPayload = $patients->map(fn ($entry) => [
        'id' => $entry['patient']->id,
        'name' => $entry['patient']->name,
        'email' => $entry['patient']->email,
        'consultations' => $entry['consultations']->map(fn ($diagnostic) => [
            'date' => optional($diagnostic->created_at)->format('d/m/Y H:i'),
            'doctor' => $diagnostic->medico?->name ?? 'Equipe clínica',
            'disease' => $diagnostic->confirmed_disease_name,
            'status' => $diagnostic->status,
        ])->values(),
    ])->values();

    $feedbackType = session('success') ? 'success' : (session('error') ? 'error' : (session('info') ? 'info' : null));
    $feedbackMessage = session('success') ?? session('error') ?? session('info');
@endphp

<style>
    .clinic-shell{width:100%;max-width:none;margin:0}
    .clinic-head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:18px}
    .clinic-title{font-size:1.9rem;font-weight:800;line-height:1.05;color:var(--tx)}
    .clinic-sub{color:var(--mu);font-weight:650;margin-top:5px}
    .clinic-nav{display:flex;gap:8px;flex-wrap:wrap;margin:18px 0 22px;border-bottom:1px solid var(--bd);padding-bottom:12px}
    .clinic-nav a{display:inline-flex;align-items:center;gap:8px;border:1px solid var(--bd);background:var(--sf);color:var(--mu);border-radius:10px;padding:10px 14px;font-weight:800;text-decoration:none}
    .clinic-nav a.active,.clinic-nav a:hover{background:var(--is);border-color:var(--id);color:var(--in)}
    .clinic-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px}
    .grid-two{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px}
    .agenda-card-title{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--in);font-weight:800;display:flex;align-items:center;gap:8px}
    .clinic-form{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;align-items:end}
    .clinic-form.one{grid-template-columns:1fr}
    .clinic-field label{display:block;font:800 .55rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;color:var(--mu);margin-bottom:6px}
    .clinic-field input,.clinic-field select,.clinic-field textarea{width:100%;border:1px solid var(--bd);border-radius:12px;background:var(--sf2);color:var(--tx);padding:10px 12px;font-weight:700}
    .clinic-table-wrap{overflow-x:auto}
    .clinic-table{width:100%;border-collapse:separate;border-spacing:0}
    .clinic-table th{padding:12px;color:var(--mu);font:800 .58rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;text-align:left;border-bottom:1px solid var(--bd)}
    .clinic-table td{padding:13px 12px;border-bottom:1px solid var(--bd);vertical-align:middle;color:var(--tx)}
    .clinic-actions{display:flex;gap:8px;flex-wrap:wrap}
    .clinic-panel{display:none}.clinic-panel.active{display:block;animation:fadeUp .2s ease both}
    .patient-list{display:grid;gap:8px}
    .patient-button{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;border:1px solid var(--bd);background:var(--sf);color:var(--tx);border-radius:12px;padding:13px 15px;text-align:left;cursor:pointer}
    .patient-button:hover,.patient-button.active{border-color:var(--in);background:var(--is)}
    .timeline{position:relative;margin-top:16px;padding-left:20px}
    .timeline:before{content:'';position:absolute;left:6px;top:5px;bottom:5px;width:2px;border-radius:10px;background:linear-gradient(var(--in),var(--gr))}
    .timeline-item{position:relative;margin-bottom:12px;border:1px solid var(--bd);background:var(--sf2);border-radius:12px;padding:13px}
    .timeline-item:before{content:'';position:absolute;left:-19px;top:17px;width:10px;height:10px;border-radius:999px;background:var(--in)}
    .stock-low{color:var(--rd);font-weight:800}.stock-ok{color:var(--gr);font-weight:800}
    .clinic-modal-overlay{position:fixed;inset:0;z-index:230;background:rgba(5,4,14,.62);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;padding:18px}
    .clinic-modal{width:min(720px,100%);max-height:88vh;overflow:auto;background:var(--sf);border:1px solid var(--bd);border-radius:18px;box-shadow:var(--sh3)}
    .clinic-modal.small{width:min(460px,100%)}
    .clinic-modal-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 20px;border-bottom:1px solid var(--bd)}
    .clinic-modal-body{padding:20px}
    .clinic-modal-close{width:34px;height:34px;border-radius:10px;border:1px solid var(--bd);background:var(--sf2);color:var(--mu);display:inline-flex;align-items:center;justify-content:center;cursor:pointer}
    .clinic-modal-close:hover{background:var(--rb);border-color:var(--rbd);color:var(--rd)}
    @media(max-width:760px){.clinic-head{align-items:flex-start}.clinic-nav a{flex:1;justify-content:center}.clinic-form{grid-template-columns:1fr}.clinic-actions .btn{width:100%}}
</style>

<div class="clinic-shell"
     x-data="{
        tab: '{{ $activeTab }}',
        modal: @js($feedbackType ? 'feedback' : null),
        feedback: { type: @js($feedbackType), message: @js($feedbackMessage) },
        selectedPatient: null,
        selectedEmployee: null,
        selectedStockItem: null,
        selectedAppointment: null,
        patients: @js($patientPayload),
        employees: @js($employeePayload),
        stockItems: @js($stockPayload),
        apptFilter: 'pending',
        routes: {
            employeeUpdate: @js(route('clinic.employees.update', '__ID__')),
            employeeDestroy: @js(route('clinic.employees.destroy', '__ID__')),
            stockUpdate: @js(route('clinic.stock.update', '__ID__')),
            stockDestroy: @js(route('clinic.stock.destroy', '__ID__')),
            appointmentAccept: @js(route('clinic.appointments.accept', '__ID__')),
        },
        route(template, id){ return template.replace('__ID__', id); },
        open(name, payload = null){
            this.modal = name;
            this.selectedEmployee = payload?.employee || null;
            this.selectedStockItem = payload?.stockItem || null;
            this.selectedAppointment = payload?.appointment || null;
        },
        close(){ this.modal = null; this.selectedEmployee = null; this.selectedStockItem = null; this.selectedAppointment = null; },
        scopedRole: new URLSearchParams(window.location.search).get('as_role'),
        scopedUrl(url){
            const next = new URL(url, window.location.origin);
            if (this.scopedRole) next.searchParams.set('as_role', this.scopedRole);
            return next.pathname + next.search + next.hash;
        },
        switchTab(value, url){ this.tab = value; history.replaceState(null, '', this.scopedUrl(url)); }
     }">
    <header class="clinic-head">
        <div>
            <span class="tag tag-purple">Clínica</span>
            <h1 class="clinic-title" style="margin-top:10px;">{{ $clinic->name }}</h1>
            <p class="clinic-sub">Gestão da unidade, equipe, pacientes, estoque e agenda.</p>
        </div>
        <div class="clinic-actions">
            <button type="button" class="btn btn-ghost" @click="open('hours')"><i class="fa-solid fa-clock"></i>{{ $clinic->activity_hours ?: 'Definir horário' }}</button>
            <button type="button" class="btn btn-primary" x-show="tab === 'employees'" @click="open('employeeCreate')"><i class="fa-solid fa-user-plus"></i>Novo funcionário</button>
            <button type="button" class="btn btn-primary" x-show="tab === 'stock'" @click="open('stockCreate')"><i class="fa-solid fa-box"></i>Novo item</button>
            <button type="button" class="btn btn-ghost" x-show="tab === 'stock'" @click="open('stockMovement')"><i class="fa-solid fa-right-left"></i>Movimentar</button>
        </div>
    </header>

    <nav class="clinic-nav" aria-label="Menu da clínica">
        @foreach($tabs as $key => $item)
            <a href="{{ route('clinic.index', ['tab' => $key]) }}" :class="tab === '{{ $key }}' ? 'active' : ''" @click.prevent="switchTab('{{ $key }}', '{{ route('clinic.index', ['tab' => $key]) }}')">
                <i class="fa-solid {{ $item['icon'] }}"></i>{{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <section class="clinic-panel" :class="tab === 'employees' ? 'active' : ''">
        <div class="card">
            <div class="clinic-head" style="margin-bottom:8px;">
                <h2 class="agenda-card-title"><i class="fa-solid fa-user-doctor"></i> Equipe cadastrada</h2>
                <button type="button" class="btn btn-primary btn-sm" @click="open('employeeCreate')"><i class="fa-solid fa-plus"></i>Adicionar</button>
            </div>
            <div class="clinic-table-wrap">
                <table class="clinic-table">
                    <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Especialidade</th><th>Número</th><th>Estado</th><th>Ações</th></tr></thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>#{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->specialty ?? 'Sem especialidade' }}</td>
                                <td>{{ $employee->phone ?? 'Sem número' }}</td>
                                <td><span class="tag {{ $employee->is_available ? 'success' : 'warn' }}">{{ $employee->is_available ? 'Livre' : 'Ocupado' }}</span></td>
                                <td>
                                    <div class="clinic-actions">
                                        <button type="button" class="btn btn-ghost btn-sm" @click="open('employeeEdit', {employee: employees.find(item => item.id === {{ $employee->id }})})"><i class="fa-solid fa-pen"></i>Editar</button>
                                        <a class="btn btn-blue btn-sm" href="{{ route('clinic.employees.activities', $employee) }}"><i class="fa-solid fa-chart-line"></i>Ver atividades</a>
                                        <button type="button" class="btn btn-danger btn-sm" @click="open('employeeDelete', {employee: employees.find(item => item.id === {{ $employee->id }})})"><i class="fa-solid fa-trash"></i>Excluir</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="muted" style="text-align:center;padding:28px;">Nenhum funcionário cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="clinic-panel" :class="tab === 'patients' ? 'active' : ''">
        <div class="grid-two">
            <article class="card">
                <h2 class="agenda-card-title"><i class="fa-solid fa-users"></i> Pacientes tratados</h2>
                <div class="patient-list" style="margin-top:14px;">
                    <template x-for="patient in patients" :key="patient.id">
                        <button type="button" class="patient-button" :class="selectedPatient?.id === patient.id ? 'active' : ''" @click="selectedPatient = patient">
                            <span><strong x-text="patient.name"></strong><small class="muted" style="display:block;" x-text="patient.email"></small></span>
                            <span class="tag" x-text="patient.consultations.length + ' consulta(s)'"></span>
                        </button>
                    </template>
                    @if($patients->isEmpty())
                        <p class="muted" style="text-align:center;padding:28px;">Nenhum paciente tratado por esta clínica ainda.</p>
                    @endif
                </div>
            </article>
            <article class="card">
                <h2 class="agenda-card-title"><i class="fa-solid fa-timeline"></i> Linha do tempo</h2>
                <template x-if="!selectedPatient">
                    <p class="muted" style="padding:30px 0;text-align:center;">Selecione um paciente para ver as consultas realizadas.</p>
                </template>
                <div class="timeline" x-show="selectedPatient">
                    <template x-for="entry in selectedPatient?.consultations || []" :key="entry.date + entry.doctor">
                        <section class="timeline-item">
                            <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                                <strong x-text="entry.disease"></strong>
                                <span class="mono" x-text="entry.date"></span>
                            </div>
                            <p class="muted" style="margin-top:6px;">Médico: <strong x-text="entry.doctor"></strong></p>
                            <span class="tag" style="margin-top:8px;" x-text="entry.status"></span>
                        </section>
                    </template>
                </div>
            </article>
        </div>
    </section>

    <section class="clinic-panel" :class="tab === 'stock' ? 'active' : ''">
        <div class="card">
            <div class="clinic-head" style="margin-bottom:10px;">
                <h2 class="agenda-card-title"><i class="fa-solid fa-warehouse"></i> Itens em estoque</h2>
                <div class="clinic-actions">
                    <button type="button" class="btn btn-primary btn-sm" @click="open('stockCreate')"><i class="fa-solid fa-plus"></i>Novo item</button>
                    <button type="button" class="btn btn-ghost btn-sm" @click="open('stockMovement')"><i class="fa-solid fa-right-left"></i>Movimentar</button>
                    <a class="btn btn-ghost btn-sm" href="{{ route('clinic.stock.movements') }}"><i class="fa-solid fa-clock-rotate-left"></i>Movimentos</a>
                </div>
            </div>
            <div class="clinic-table-wrap">
                <table class="clinic-table">
                    <thead><tr><th>Item</th><th>Categoria</th><th>Quantidade</th><th>Mínimo</th><th>Ações</th></tr></thead>
                    <tbody>
                        @forelse($stockItems as $item)
                            <tr>
                                <td>{{ $item->name }} <small class="muted">/{{ $item->unit }}</small></td>
                                <td>{{ $item->category ?? 'Geral' }}</td>
                                <td class="{{ $item->quantity <= $item->minimum_quantity ? 'stock-low' : 'stock-ok' }}">{{ $item->quantity }}</td>
                                <td>{{ $item->minimum_quantity }}</td>
                                <td>
                                    <div class="clinic-actions">
                                        <button type="button" class="btn btn-ghost btn-sm" @click="open('stockEdit', {stockItem: stockItems.find(item => item.id === {{ $item->id }})})"><i class="fa-solid fa-pen"></i>Editar</button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="open('stockDelete', {stockItem: stockItems.find(item => item.id === {{ $item->id }})})"><i class="fa-solid fa-trash"></i>Excluir</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="muted" style="text-align:center;padding:28px;">Nenhum item cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="clinic-panel" :class="tab === 'appointments' ? 'active' : ''">
        <div class="card">
            <div class="clinic-head" style="margin-bottom:12px;">
                <h2 class="agenda-card-title"><i class="fa-solid fa-calendar-check"></i> Consultas da clínica</h2>
                <div class="clinic-actions">
                    <button type="button" class="btn btn-ghost btn-sm" :class="apptFilter === 'pending' ? 'btn-primary' : ''" @click="apptFilter='pending'">Pendentes</button>
                    <button type="button" class="btn btn-ghost btn-sm" :class="apptFilter === 'accepted' ? 'btn-primary' : ''" @click="apptFilter='accepted'">Aceites</button>
                    <button type="button" class="btn btn-ghost btn-sm" :class="apptFilter === 'all' ? 'btn-primary' : ''" @click="apptFilter='all'">Todas</button>
                </div>
            </div>
            @forelse($appointments as $appointment)
                <section class="soft-row" x-show="apptFilter === 'all' || apptFilter === '{{ $appointment->status }}'">
                    <div>
                        <strong>{{ $appointment->patient?->name ?? 'Paciente' }}</strong>
                        <p class="muted">{{ $appointment->scheduled_for->format('d/m/Y H:i') }} · Dr(a). {{ $appointment->doctor?->name ?? 'Médico' }} · {{ $appointment->mode }}</p>
                        @if($appointment->reason)<p class="muted">{{ $appointment->reason }}</p>@endif
                    </div>
                    <div class="clinic-actions">
                        <span class="tag {{ $appointment->status === 'accepted' ? 'success' : ($appointment->status === 'rejected' ? 'danger' : 'warn') }}">{{ $appointment->status }}</span>
                        @if($appointment->status === 'pending')
                            <button type="button" class="btn btn-green btn-sm" @click="open('appointmentAccept', {appointment: {id: {{ $appointment->id }}, patient: @js($appointment->patient?->name), scheduled: @js($appointment->scheduled_for->format('d/m/Y H:i'))}})"><i class="fa-solid fa-check"></i>Aceitar</button>
                        @endif
                    </div>
                </section>
            @empty
                <p class="muted" style="text-align:center;padding:28px;">Nenhum agendamento ligado à clínica.</p>
            @endforelse
        </div>
    </section>

    <template x-if="modal === 'feedback'">
        <div class="clinic-modal-overlay" @click.self="close()">
            <section class="clinic-modal small">
                <div class="clinic-modal-head">
                    <h2 class="agenda-card-title"><i class="fa-solid" :class="feedback.type === 'success' ? 'fa-circle-check' : (feedback.type === 'error' ? 'fa-triangle-exclamation' : 'fa-circle-info')"></i>Mensagem</h2>
                    <button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="clinic-modal-body">
                    <p class="muted" x-text="feedback.message"></p>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;">
                        <button type="button" class="btn btn-primary" @click="close()">Entendi</button>
                    </div>
                </div>
            </section>
        </div>
    </template>

    <template x-if="modal === 'hours'">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" action="{{ route('clinic.hours.update') }}" class="clinic-modal small">
                @csrf
                @method('PATCH')
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-clock"></i>Horário de atividade</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <div class="clinic-field"><label>Horário aberto</label><input name="activity_hours" value="{{ old('activity_hours', $clinic->activity_hours) }}" placeholder="Ex.: 12-16h" required></div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-primary" type="submit">Salvar</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'employeeCreate'">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" action="{{ route('clinic.employees.store') }}" class="clinic-modal">
                @csrf
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-user-plus"></i>Novo funcionário</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <div class="clinic-form">
                        <div class="clinic-field"><label>Nome</label><input name="name" required></div>
                        <div class="clinic-field"><label>Email</label><input name="email" type="email" required></div>
                        <div class="clinic-field"><label>Especialidade</label><input name="specialty" placeholder="Cardiologia"></div>
                        <div class="clinic-field"><label>Número</label><input name="phone" placeholder="+244 ..."></div>
                        <div class="clinic-field"><label>Senha inicial</label><input name="password" type="password" minlength="8" required></div>
                    </div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-primary" type="submit">Cadastrar</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'employeeEdit' && selectedEmployee">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" :action="route(routes.employeeUpdate, selectedEmployee.id)" class="clinic-modal">
                @csrf
                @method('PATCH')
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-pen"></i>Editar funcionário</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <div class="clinic-form">
                        <div class="clinic-field"><label>Nome</label><input name="name" :value="selectedEmployee.name" required></div>
                        <div class="clinic-field"><label>Email</label><input name="email" type="email" :value="selectedEmployee.email" required></div>
                        <div class="clinic-field"><label>Especialidade</label><input name="specialty" :value="selectedEmployee.specialty"></div>
                        <div class="clinic-field"><label>Número</label><input name="phone" :value="selectedEmployee.phone"></div>
                        <div class="clinic-field"><label>Nova senha</label><input name="password" type="password" minlength="8"></div>
                    </div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-primary" type="submit">Salvar</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'employeeDelete' && selectedEmployee">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" :action="route(routes.employeeDestroy, selectedEmployee.id)" class="clinic-modal small">
                @csrf
                @method('DELETE')
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-trash"></i>Remover funcionário</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <p class="muted">Remover <strong x-text="selectedEmployee.name"></strong> da clínica?</p>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-danger" type="submit">Remover</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'stockCreate'">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" action="{{ route('clinic.stock.store') }}" class="clinic-modal">
                @csrf
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-box"></i>Novo item</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <div class="clinic-form">
                        <div class="clinic-field"><label>Item</label><input name="name" required></div>
                        <div class="clinic-field"><label>Categoria</label><input name="category"></div>
                        <div class="clinic-field"><label>Unidade</label><input name="unit" value="unidade" required></div>
                        <div class="clinic-field"><label>Quantidade</label><input name="quantity" type="number" min="0" value="0" required></div>
                        <div class="clinic-field"><label>Mínimo</label><input name="minimum_quantity" type="number" min="0" value="0"></div>
                    </div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-primary" type="submit">Adicionar</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'stockEdit' && selectedStockItem">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" :action="route(routes.stockUpdate, selectedStockItem.id)" class="clinic-modal">
                @csrf
                @method('PATCH')
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-pen"></i>Editar item</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <div class="clinic-form">
                        <div class="clinic-field"><label>Item</label><input name="name" :value="selectedStockItem.name" required></div>
                        <div class="clinic-field"><label>Categoria</label><input name="category" :value="selectedStockItem.category"></div>
                        <div class="clinic-field"><label>Unidade</label><input name="unit" :value="selectedStockItem.unit" required></div>
                        <div class="clinic-field"><label>Mínimo</label><input name="minimum_quantity" type="number" min="0" :value="selectedStockItem.minimum_quantity"></div>
                    </div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-primary" type="submit">Salvar</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'stockDelete' && selectedStockItem">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" :action="route(routes.stockDestroy, selectedStockItem.id)" class="clinic-modal small">
                @csrf
                @method('DELETE')
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-trash"></i>Remover item</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <p class="muted">Remover <strong x-text="selectedStockItem.name"></strong> do estoque?</p>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-danger" type="submit">Remover</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'stockMovement'">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" action="{{ route('clinic.stock.movement') }}" class="clinic-modal">
                @csrf
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-right-left"></i>Movimentar estoque</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <div class="clinic-form">
                        <div class="clinic-field"><label>Item</label><select name="clinic_stock_item_id" required><template x-for="item in stockItems" :key="item.id"><option :value="item.id" x-text="item.name"></option></template></select></div>
                        <div class="clinic-field"><label>Tipo</label><select name="type"><option value="entrada">Entrada</option><option value="saida">Saída</option><option value="ajuste">Ajuste</option></select></div>
                        <div class="clinic-field"><label>Quantidade</label><input name="quantity" type="number" min="1" required></div>
                        <div class="clinic-field"><label>Nota</label><input name="notes"></div>
                    </div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-primary" type="submit">Registrar</button></div>
                </div>
            </form>
        </div>
    </template>

    <template x-if="modal === 'appointmentAccept' && selectedAppointment">
        <div class="clinic-modal-overlay" @click.self="close()">
            <form method="POST" :action="route(routes.appointmentAccept, selectedAppointment.id)" class="clinic-modal small">
                @csrf
                @method('PATCH')
                <div class="clinic-modal-head"><h2 class="agenda-card-title"><i class="fa-solid fa-calendar-check"></i>Aceitar agendamento</h2><button type="button" class="clinic-modal-close" @click="close()"><i class="fa-solid fa-xmark"></i></button></div>
                <div class="clinic-modal-body">
                    <p class="muted"><strong x-text="selectedAppointment.patient || 'Paciente'"></strong> · <span x-text="selectedAppointment.scheduled"></span></p>
                    <div class="clinic-field" style="margin-top:14px;"><label>Nota opcional</label><textarea name="doctor_response" rows="3"></textarea></div>
                    <div class="clinic-actions" style="justify-content:flex-end;margin-top:18px;"><button type="button" class="btn btn-ghost" @click="close()">Cancelar</button><button class="btn btn-green" type="submit">Aceitar</button></div>
                </div>
            </form>
        </div>
    </template>
</div>
</x-app-layout>
