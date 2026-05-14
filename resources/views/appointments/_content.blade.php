@php
    $pendingAppointments = $appointments->where('status', 'pending');
    $acceptedAppointments = $appointments->where('status', 'accepted');
    $rejectedAppointments = $appointments->where('status', 'rejected');
    $doctorModalOptions = $doctorOptions->map(function ($option) {
        return [
            'id' => $option['doctor']->id,
            'name' => $option['doctor']->name,
            'email' => $option['doctor']->email,
            'initials' => collect(explode(' ', $option['doctor']->name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->join(''),
            'prescription_id' => $option['prescription']->id,
            'diagnosis' => $option['diagnosis'] ?? 'Acompanhamento clínico',
            'prescriptions_count' => $option['prescriptions_count'],
            'last_date' => optional($option['last_date'])->format('d/m/Y'),
        ];
    })->values();
@endphp

<style>
    .agenda-shell{max-width:1180px;margin:0 auto}
    .agenda-head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:22px}
    .agenda-eyebrow{font-family:'Space Mono',monospace;font-size:.55rem;text-transform:uppercase;letter-spacing:.14em;color:var(--in);font-weight:800}
    .agenda-title{font-size:1.8rem;font-weight:800;line-height:1.05;color:var(--tx);margin-top:6px}
    .agenda-title span{background:linear-gradient(135deg,var(--in),var(--gr));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .agenda-sub{color:var(--mu);font-weight:600;margin-top:5px}
    .agenda-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:20px}
    .agenda-kpi{position:relative;overflow:hidden;border:1px solid var(--bd);background:var(--sf);border-radius:16px;padding:18px;box-shadow:var(--sh)}
    .agenda-kpi:after{content:'';position:absolute;right:-22px;bottom:-22px;width:82px;height:82px;border-radius:50%;background:currentColor;opacity:.07}
    .agenda-kpi i{position:absolute;right:16px;top:16px;color:currentColor;opacity:.72}
    .agenda-kpi strong{display:block;font-size:2rem;line-height:1;color:currentColor}
    .agenda-kpi span{display:block;color:var(--mu);font-weight:700;margin-top:6px}
    .tone-in{color:var(--in);background:var(--is);border-color:var(--id)}
    .tone-gr{color:var(--gr);background:var(--gb);border-color:var(--gbd)}
    .tone-wn{color:var(--wn);background:var(--wb);border-color:var(--wbd)}
    .tone-rd{color:var(--rd);background:var(--rb);border-color:var(--rbd)}
    .agenda-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(330px,.95fr);gap:18px;align-items:start}
    .agenda-card{background:var(--sf);border:1px solid var(--bd);border-radius:16px;box-shadow:var(--sh);padding:20px}
    .agenda-card-h{display:flex;align-items:center;justify-content:space-between;gap:12px;padding-bottom:13px;border-bottom:1px solid var(--bd);margin-bottom:14px}
    .agenda-card-title{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--in);font-weight:800;display:flex;align-items:center;gap:8px}
    .doctor-row,.request-row{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding:14px;border:1px solid var(--bd);border-radius:12px;background:var(--sf2);margin-bottom:10px}
    .doctor-row:last-child,.request-row:last-child{margin-bottom:0}
    .avatar-soft{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:var(--is);border:1px solid var(--id);color:var(--in);font-weight:900;flex-shrink:0}
    .row-main{display:flex;align-items:center;gap:12px;min-width:0}
    .row-main strong{display:block;color:var(--tx);font-size:.96rem}
    .row-main p,.request-meta{color:var(--mu);font-size:.8rem;font-weight:600;margin-top:2px}
    .agenda-mini{display:grid;grid-template-columns:repeat(7,1fr);gap:4px}
    .agenda-day{min-height:44px;border:1px solid var(--bd);border-radius:9px;background:var(--sf2);display:flex;align-items:center;justify-content:center;position:relative;font-weight:800;color:var(--mu)}
    .agenda-day.has-event{background:var(--is);color:var(--in);border-color:var(--id)}
    .agenda-day.has-event:after{content:'';position:absolute;bottom:7px;width:5px;height:5px;border-radius:50%;background:var(--gr)}
    .agenda-flow{position:relative;padding-left:18px}
    .agenda-flow:before{content:'';position:absolute;left:6px;top:8px;bottom:8px;width:2px;background:linear-gradient(var(--in),var(--gr));border-radius:10px}
    .flow-item{position:relative;margin-bottom:12px;padding:13px 14px;background:var(--sf2);border:1px solid var(--bd);border-radius:12px}
    .flow-item:before{content:'';position:absolute;left:-17px;top:18px;width:10px;height:10px;border-radius:50%;background:var(--in);box-shadow:0 0 0 4px color-mix(in srgb,var(--in) 18%,transparent)}
    .flow-item.accepted:before{background:var(--gr)}
    .flow-item.rejected:before{background:var(--rd)}
    .flow-top{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
    .flow-title{font-weight:800;color:var(--tx)}
    .flow-time{font-family:'Space Mono',monospace;font-size:.58rem;color:var(--in);font-weight:800;text-transform:uppercase}
    .appointment-actions{width:min(420px,100%);display:grid;grid-template-columns:1fr;gap:10px;flex:0 0 min(420px,100%)}
    .appointment-action-card{background:var(--sf);border:1px solid var(--bd);border-radius:12px;padding:11px}
    .appointment-action-title{display:flex;align-items:center;gap:7px;margin-bottom:8px;font:800 .55rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;color:var(--mu)}
    .appointment-action-row{display:flex;gap:8px;align-items:center}
    .appointment-note{flex:1;min-width:0;border:1px solid var(--bd);border-radius:10px;background:var(--sf2);color:var(--tx);padding:9px 11px;font-weight:700}
    .action-line{display:flex;gap:8px;align-items:center;justify-content:flex-end;flex-wrap:wrap;margin-top:10px}
    .reject-box{width:100%;border:1px solid var(--bd);border-radius:10px;padding:10px 12px;background:var(--sf2);color:var(--tx);resize:vertical;min-height:58px}
    .schedule-modal{position:fixed;inset:0;z-index:210;background:rgba(4,3,10,.62);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;padding:18px}
    .schedule-box{width:min(620px,100%);background:var(--sf);border:1px solid var(--bd);border-radius:18px;box-shadow:var(--sh3);overflow:hidden}
    .schedule-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 20px;border-bottom:1px solid var(--bd)}
    .schedule-body{padding:20px;display:grid;gap:14px}
    .modal-close{width:34px;height:34px;border-radius:10px;border:1px solid var(--bd);background:var(--sf2);color:var(--mu);display:inline-flex;align-items:center;justify-content:center;cursor:pointer}
    .modal-close:hover{background:var(--rb);border-color:var(--rbd);color:var(--rd)}
    .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
    .field label{display:block;font-family:'Space Mono',monospace;font-size:.54rem;text-transform:uppercase;letter-spacing:.1em;color:var(--mu);font-weight:800;margin-bottom:6px}
    .field input,.field select,.field textarea{width:100%;border:1px solid var(--bd);border-radius:12px;background:var(--sf2);color:var(--tx);padding:11px 12px;font:700 .9rem 'Dosis',sans-serif}
    .field textarea{min-height:92px;resize:vertical}
    .empty-agenda{text-align:center;color:var(--mu);padding:28px 16px;font-weight:700}
    @media(max-width:920px){.agenda-grid{grid-template-columns:1fr}.form-grid{grid-template-columns:1fr}.doctor-row,.request-row{align-items:flex-start;flex-direction:column}.appointment-actions{flex-basis:auto;width:100%}.appointment-action-row{flex-wrap:wrap}.appointment-note{min-width:190px}.action-line{justify-content:flex-start}}
</style>

<section class="agenda-shell" x-data="{ modalOpen:false, selectedDoctor:null, doctors:@js($doctorModalOptions), openSchedule(id){ this.selectedDoctor = this.doctors.find(item => item.id === id); this.modalOpen = true; } }">
    @if(session('success') || session('info') || session('error'))
        <div class="alert {{ session('success') ? 'success' : (session('error') ? 'danger' : 'warning') }}" style="margin-bottom:16px;">
            <i class="fa-solid {{ session('success') ? 'fa-circle-check' : (session('error') ? 'fa-triangle-exclamation' : 'fa-circle-info') }}"></i>
            <span>{{ session('success') ?? session('info') ?? session('error') }}</span>
        </div>
    @endif

    <header class="agenda-head">
        <div>
            <div class="agenda-eyebrow">{{ $isDoctor ? 'Área médica' : 'Área do paciente' }} · Agendamento</div>
            <h1 class="agenda-title"><span>Consultas</span> de rotina e acompanhamento</h1>
            <p class="agenda-sub">{{ $isDoctor ? 'Analise propostas enviadas pelos pacientes e responda sem sair da agenda.' : 'Agende com médicos que já emitiram prescrições para você.' }}</p>
        </div>
        @unless($isDoctor)
            <button type="button" class="btn btn-primary" @click="doctors.length && openSchedule(doctors[0].id)" :disabled="!doctors.length">
                <i class="fa-solid fa-calendar-plus"></i> Nova solicitação
            </button>
        @endunless
    </header>

    <section class="agenda-kpis" aria-label="Indicadores de agendamento">
        <article class="agenda-kpi tone-in"><i class="fa-solid fa-hourglass-half"></i><strong>{{ $agendaStats['pending'] }}</strong><span>Pendentes</span></article>
        <article class="agenda-kpi tone-gr"><i class="fa-solid fa-circle-check"></i><strong>{{ $agendaStats['accepted'] }}</strong><span>Confirmadas</span></article>
        <article class="agenda-kpi tone-rd"><i class="fa-solid fa-circle-xmark"></i><strong>{{ $agendaStats['rejected'] }}</strong><span>Recusadas</span></article>
        <article class="agenda-kpi tone-wn"><i class="fa-solid {{ $isDoctor ? 'fa-inbox' : 'fa-user-doctor' }}"></i><strong>{{ $isDoctor ? $pendingAppointments->count() : $agendaStats['available_doctors'] }}</strong><span>{{ $isDoctor ? 'Propostas novas' : 'Médicos disponíveis' }}</span></article>
    </section>

    <div class="agenda-grid">
        <div style="display:grid;gap:18px;">
            @if($isDoctor)
                <article class="agenda-card">
                    <div class="agenda-card-h">
                        <h2 class="agenda-card-title"><i class="fa-solid fa-inbox"></i> Propostas dos pacientes</h2>
                        <span class="tag warn">{{ $pendingAppointments->count() }} pendente(s)</span>
                    </div>

                    @forelse($pendingAppointments as $appointment)
                        <section class="request-row" id="appointment-{{ $appointment->id }}">
                            <div class="row-main">
                                <span class="avatar-soft">{{ mb_substr($appointment->patient?->name ?? 'P', 0, 2) }}</span>
                                <div>
                                    <strong>{{ $appointment->patient?->name ?? 'Paciente' }}</strong>
                                    <p>{{ $appointment->consultation_type === 'routine' ? 'Rotina' : 'Acompanhamento' }} · {{ $appointment->mode }} · {{ $appointment->scheduled_for->format('d/m/Y H:i') }}</p>
                                    @if($appointment->reason)
                                        <p>{{ $appointment->reason }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="appointment-actions">
                                <form method="POST" action="{{ route('appointments.accept', $appointment) }}" class="appointment-action-card">
                                    @csrf
                                    @method('PATCH')
                                    <div class="appointment-action-title"><i class="fa-solid fa-check"></i> Confirmar horário</div>
                                    <div class="appointment-action-row">
                                        <input class="appointment-note" type="text" name="doctor_response" placeholder="Nota opcional ao paciente">
                                        <button class="btn btn-green btn-sm" type="submit"><i class="fa-solid fa-check"></i>Aceitar</button>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('appointments.reject', $appointment) }}" class="appointment-action-card">
                                    @csrf
                                    @method('PATCH')
                                    <div class="appointment-action-title"><i class="fa-solid fa-xmark"></i> Recusar ou negociar</div>
                                    <textarea class="reject-box" name="doctor_response" placeholder="Motivo ou sugestão de novo horário"></textarea>
                                    <div class="action-line">
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i>Recusar</button>
                                        <a class="btn btn-ghost btn-sm" href="{{ route('appointments.chat', $appointment) }}"><i class="fa-solid fa-comment-dots"></i>Chat</a>
                                    </div>
                                </form>
                            </div>
                        </section>
                    @empty
                        <div class="empty-agenda">Nenhuma proposta pendente.</div>
                    @endforelse
                </article>
            @else
                <article class="agenda-card">
                    <div class="agenda-card-h">
                        <h2 class="agenda-card-title"><i class="fa-solid fa-user-doctor"></i> Médicos com prescrição</h2>
                        <span class="tag info">{{ $doctorOptions->count() }} disponível(is)</span>
                    </div>

                    @forelse($doctorOptions as $option)
                        <section class="doctor-row">
                            <div class="row-main">
                                <span class="avatar-soft">{{ collect(explode(' ', $option['doctor']->name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->join('') }}</span>
                                <div>
                                    <strong>Dr(a). {{ $option['doctor']->name }}</strong>
                                    <p>{{ $option['diagnosis'] ?? 'Acompanhamento clínico' }} · {{ $option['prescriptions_count'] }} prescrição(ões)</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" @click="openSchedule({{ $option['doctor']->id }})">
                                <i class="fa-solid fa-calendar-plus"></i> Agendar
                            </button>
                        </section>
                    @empty
                        <div class="empty-agenda">
                            Você ainda não possui prescrições emitidas por médicos. Assim que uma prescrição for criada, o agendamento ficará disponível aqui.
                        </div>
                    @endforelse
                </article>
            @endif

            <article class="agenda-card">
                <div class="agenda-card-h">
                    <h2 class="agenda-card-title"><i class="fa-solid fa-calendar-days"></i> Maio 2026</h2>
                    <span class="tag success">{{ $acceptedAppointments->count() }} confirmada(s)</span>
                </div>
                <div class="agenda-mini" aria-label="Resumo mensal">
                    @for($day = 1; $day <= 31; $day++)
                        @php
                            $hasEvent = $acceptedAppointments->contains(fn ($item) => (int) $item->scheduled_for->format('d') === $day);
                        @endphp
                        <span class="agenda-day {{ $hasEvent ? 'has-event' : '' }}">{{ $day }}</span>
                    @endfor
                </div>
            </article>
        </div>

        <aside style="display:grid;gap:18px;">
            <article class="agenda-card">
                <div class="agenda-card-h">
                    <h2 class="agenda-card-title"><i class="fa-solid fa-clock"></i> Linha do tempo</h2>
                    <span class="tag">{{ $appointments->count() }} registo(s)</span>
                </div>
                <div class="agenda-flow">
                    @forelse($appointments->take(8) as $appointment)
                        <section class="flow-item {{ $appointment->status }}" id="appointment-{{ $appointment->id }}">
                            <div class="flow-top">
                                <span class="flow-title">{{ $isDoctor ? ($appointment->patient?->name ?? 'Paciente') : 'Dr(a). ' . ($appointment->doctor?->name ?? 'Médico') }}</span>
                                <span class="flow-time">{{ $appointment->scheduled_for->format('d/m · H:i') }}</span>
                            </div>
                            <p class="request-meta">{{ $appointment->consultation_type === 'routine' ? 'Consulta de rotina' : 'Acompanhamento' }} · {{ $appointment->mode }}</p>
                            <div style="margin-top:9px;display:flex;gap:8px;flex-wrap:wrap;">
                                <span class="tag {{ $appointment->status === 'accepted' ? 'success' : ($appointment->status === 'rejected' ? 'danger' : 'warn') }}">
                                    {{ ['pending' => 'Pendente', 'accepted' => 'Aceite', 'rejected' => 'Recusada', 'cancelled' => 'Cancelada'][$appointment->status] ?? $appointment->status }}
                                </span>
                                @if($appointment->status === 'rejected' && ! $isDoctor)
                                    <a class="btn btn-ghost btn-xs" href="{{ route('appointments.chat', $appointment) }}"><i class="fa-solid fa-comment-dots"></i>Conversar</a>
                                @endif
                            </div>
                            @if($appointment->doctor_response)
                                <p class="request-meta" style="margin-top:8px;">Resposta: {{ $appointment->doctor_response }}</p>
                            @endif
                        </section>
                    @empty
                        <div class="empty-agenda">Nenhum agendamento registado.</div>
                    @endforelse
                </div>
            </article>

            <article class="agenda-card">
                <div class="agenda-card-h">
                    <h2 class="agenda-card-title"><i class="fa-solid fa-envelope-open-text"></i> Notificações</h2>
                </div>
                <div class="flow-item accepted" style="margin-bottom:10px;">
                    <div class="flow-title">Email automático</div>
                    <p class="request-meta">O médico recebe email quando o paciente envia uma proposta.</p>
                </div>
                <div class="flow-item {{ $isDoctor ? 'accepted' : 'rejected' }}">
                    <div class="flow-title">Resposta ao paciente</div>
                    <p class="request-meta">Ao aceitar ou recusar, o paciente recebe email e notificação interna; em recusa, o chat fica disponível.</p>
                </div>
            </article>
        </aside>
    </div>

    @unless($isDoctor)
        <div class="schedule-modal" x-show="modalOpen" x-cloak x-transition.opacity>
            <form method="POST" action="{{ route('appointments.store') }}" class="schedule-box" @click.outside="modalOpen=false">
                @csrf
                <input type="hidden" name="doctor_id" :value="selectedDoctor?.id">
                <input type="hidden" name="prescription_id" :value="selectedDoctor?.prescription_id">

                <div class="schedule-head">
                    <div>
                        <h2 class="agenda-card-title" style="font-size:.68rem;"><i class="fa-solid fa-calendar-plus"></i> Nova proposta</h2>
                        <p class="agenda-sub" x-text="selectedDoctor ? `Dr(a). ${selectedDoctor.name} · ${selectedDoctor.diagnosis}` : ''"></p>
                    </div>
                    <button type="button" class="modal-close" @click="modalOpen=false" aria-label="Fechar"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <div class="schedule-body">
                    <div class="form-grid">
                        <div class="field">
                            <label for="scheduled_date">Data</label>
                            <input id="scheduled_date" name="scheduled_date" type="date" min="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="field">
                            <label for="scheduled_time">Hora</label>
                            <input id="scheduled_time" name="scheduled_time" type="time" required>
                        </div>
                        <div class="field">
                            <label for="consultation_type">Tipo</label>
                            <select id="consultation_type" name="consultation_type" required>
                                <option value="follow_up">Acompanhamento</option>
                                <option value="routine">Rotina</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="mode">Formato</label>
                            <select id="mode" name="mode" required>
                                <option value="presencial">Presencial</option>
                                <option value="telemedicina">Telemedicina</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label for="reason">Mensagem para o médico</label>
                        <textarea id="reason" name="reason" placeholder="Ex.: gostaria de rever a resposta ao tratamento, sintomas persistentes ou consulta de rotina."></textarea>
                    </div>
                    <div class="action-line">
                        <button type="button" class="btn btn-ghost" @click="modalOpen=false">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i>Enviar proposta</button>
                    </div>
                </div>
            </form>
        </div>
    @endunless
</section>
