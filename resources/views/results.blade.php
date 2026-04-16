<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicaly — Resultado da Análise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
:root{--in:#6d55b1;--il:#8b72cc;--is:#f5f3fd;--id:#ede9f8;--bg:#f4f2fb;--sf:#fff;--sf2:#f8f6fd;--sf3:#ede9f8;--bd:#ddd8f0;--tx:#1a1530;--mu:#7c72a0;--fa:#b0a8cc;--gr:#059669;--gb:#ecfdf5;--gbd:#a7f3d0;--bl:#3b82f6;--bb:#eff6ff;--bbd:#bfdbfe;--wn:#d97706;--wb:#fffbeb;--wbd:#fcd34d;--rd:#dc2626;--rb:#fef2f2;--rbd:#fca5a5;--sh:0 1px 3px rgba(109,85,177,.08);--sh2:0 4px 12px rgba(109,85,177,.10);--r:14px;--rs:8px;}
[data-theme=dark]{--in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html,body{font-family:'Dosis',sans-serif !important;}
body{background:var(--bg);color:var(--tx);min-height:100vh;transition:background .3s,color .3s;}
a{text-decoration:none;color:inherit;}
.topbar{position:sticky;top:0;z-index:50;background:var(--sf);border-bottom:1px solid var(--bd);box-shadow:var(--sh);display:flex;align-items:center;gap:12px;padding:0 24px;height:60px;}
.logo{font-size:1.05rem;font-weight:800;letter-spacing:.1em;background:linear-gradient(135deg,var(--in),var(--il));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
@keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.fi{animation:fi .3s ease both;}.fi1{animation-delay:.05s;}.fi2{animation-delay:.1s;}.fi3{animation-delay:.15s;}
.card{background:var(--sf);border:1px solid var(--bd);border-radius:var(--r);padding:20px;box-shadow:var(--sh);}
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:30px;border:none;font-family:'Dosis',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .2s;white-space:nowrap;justify-content:center;}
.b-pr{background:var(--in);color:#fff;}
.b-pr:hover{background:var(--il);}
.b-gh{background:transparent;color:var(--mu);border:1.5px solid var(--bd);}
.b-gh:hover{border-color:var(--in);color:var(--in);background:var(--is);}
.ib{background:transparent;border:1.5px solid var(--bd);border-radius:50%;width:36px;height:36px;cursor:pointer;color:var(--mu);display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .2s;position:relative;}
.ib:hover{border-color:var(--in);color:var(--in);background:var(--is);}
.nd{position:absolute;top:-1px;right:-1px;width:8px;height:8px;border-radius:50%;background:var(--rd);border:2px solid var(--sf);}
.tb{background:var(--sf2);border:1.5px solid var(--bd);border-radius:50%;width:34px;height:34px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:.85rem;transition:all .2s;}
.tb:hover{border-color:var(--in);color:var(--in);}
.al{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:var(--rs);font-size:.84rem;font-weight:500;border:1px solid;}
.al-rd{background:var(--rb);border-color:var(--rbd);color:var(--rd);}
.al-wn{background:var(--wb);border-color:var(--wbd);color:var(--wn);}
.tg{display:inline-flex;align-items:center;gap:4px;font-size:.7rem;font-weight:600;padding:3px 10px;border-radius:20px;border:1px solid;white-space:nowrap;}
.tg-gr{border-color:var(--gbd);color:var(--gr);background:var(--gb);}
.tg-pu{border-color:var(--bd);color:var(--in);background:var(--is);}
.b-ac{background:var(--in);color:#fff;border:none;padding:14px 32px;border-radius:12px;font-size:0.95rem;font-weight:700;cursor:pointer;transition:all .2s;}
.b-ac:hover{background:var(--il);transform:translateY(-2px);box-shadow:0 4px 12px rgba(109,85,177,.2);}
.b-sc{background:transparent;border:1.5px solid var(--bd);color:var(--tx);padding:14px 32px;border-radius:12px;font-size:0.95rem;font-weight:700;cursor:pointer;transition:all .2s;}
.b-sc:hover{border-color:var(--in);color:var(--in);background:var(--is);}
    </style>
</head>
<body>
    <div style="max-width:1000px;margin:0 auto;padding:32px 20px 80px;">
        
        <!-- Header with session info -->
        <div class="fi fi1" style="margin-bottom:32px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <div>
                <h1 style="font-size:1.5rem;font-weight:800;color:var(--tx);font-family:'Dosis',sans-serif;">Resultado da Análise Clínica</h1>
                <p style="color:var(--mu);font-size:0.9rem;margin-top:4px;">Diagnóstico diferencial baseado em IA</p>
            </div>
            <div style="text-align:right;background:var(--is);border:1px solid var(--bd);border-radius:20px;padding:12px 20px;">
                <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:var(--in);font-weight:700;text-transform:uppercase;">ID da Sessão: #{{ date('YmdHis') }}</p>
                <p style="font-size:0.85rem;color:var(--tx);font-weight:600;margin-top:4px;">{{ $perfil['idade'] }} anos | {{ $perfil['genero'] === 'm' ? '♂ Masculino' : '♀ Feminino' }}</p>
            </div>
        </div>

        <!-- Critical alerts if present -->
        @if(!empty($alertas_criticos) && count($alertas_criticos) > 0)
        <div class="fi fi2" style="margin-bottom:24px;">
            @foreach($alertas_criticos as $alerta)
            <div class="al {{ $alerta['cor'] === 'red' ? 'al-rd' : 'al-wn' }}">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <strong>{{ $alerta['descricao'] }}</strong>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if(count($results) > 0)
        @php 
            $topMatch = $results->first(); 
            $match_pct = isset($topMatch->probability) ? $topMatch->probability : 0;
        @endphp
            
            <!-- Main diagnosis card -->
            <div class="card fi fi2" style="margin-bottom:32px;overflow:hidden;position:relative;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--in),var(--il));"></div>
                
                <div style="display:grid;grid-template-columns:1fr auto;gap:32px;align-items:start;">
                    <div>
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                            <span class="tg tg-pu">
                                <i class="fa-solid fa-check-circle"></i> DIAGNÓSTICO PRINCIPAL
                            </span>
                        </div>
                        <h2 style="font-size:2rem;font-weight:800;color:var(--tx);margin-bottom:8px;">{{ $topMatch->name }}</h2>
                        <p style="color:var(--mu);font-size:0.95rem;line-height:1.6;margin-bottom:20px;">{{ $topMatch->description ?? 'Análise em desenvolvimento' }}</p>
                        
                        <!-- Key metrics -->
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
                            <div style="background:var(--is);border:1px solid var(--bd);border-radius:var(--rs);padding:12px;text-align:center;">
                                <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:var(--mu);text-transform:uppercase;font-weight:700;">Cobertura Sintomática</p>
                                <p style="font-size:1.5rem;font-weight:800;color:var(--in);margin-top:4px;">{{ $topMatch->cobertura }}%</p>
                            </div>
                            <div style="background:var(--is);border:1px solid var(--bd);border-radius:var(--rs);padding:12px;text-align:center;">
                                <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:var(--mu);text-transform:uppercase;font-weight:700;">Sintomas Detectados</p>
                                <p style="font-size:1.5rem;font-weight:800;color:var(--in);margin-top:4px;">{{ $topMatch->match_count }}/{{ count($topMatch->symptoms) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Probability circle -->
                    <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                        <svg width="160" height="160" viewBox="0 0 160 160">
                            <circle cx="80" cy="80" r="70" fill="none" stroke="var(--bd)" stroke-width="12"/>
                            <circle cx="80" cy="80" r="70" fill="none" stroke="var(--in)" stroke-width="12" 
                                    stroke-dasharray="{{ 440 * ($match_pct / 100) }},440" 
                                    stroke-linecap="round" style="transform:rotate(-90deg);transform-origin:80px 80px;transition:stroke-dasharray 1s ease;"/>
                            <text x="80" y="75" font-size="36" font-weight="800" text-anchor="middle" fill="var(--in)">{{ $match_pct }}%</text>
                            <text x="80" y="95" font-family="'Space Mono',monospace" font-size="10" font-weight="700" text-anchor="middle" fill="var(--mu)">PROBABILIDADE</text>
                        </svg>
                        <div style="text-align:center;">
                            <p style="font-size:0.8rem;color:var(--mu);">
                                @if($match_pct >= 80)
                                    <i class="fa-solid fa-circle" style="color:#059669;"></i> Altamente Provável
                                @elseif($match_pct >= 50)
                                    <i class="fa-solid fa-circle" style="color:#d97706;"></i> Moderadamente Provável
                                @else
                                    <i class="fa-solid fa-circle" style="color:#3b82f6;"></i> Possível
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biometric data and modifiers -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:32px;">
                <div class="card fi fi2">
                    <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:var(--in);font-weight:700;text-transform:uppercase;margin-bottom:12px;">Dados Biométricos</p>
                    <div style="space-y:8px;">
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">Idade</span>
                            <strong>{{ $perfil['idade'] }} anos</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">Peso</span>
                            <strong>{{ $perfil['peso'] }} kg</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">Altura</span>
                            <strong>{{ $perfil['altura'] }} cm</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;">
                            <span style="color:var(--mu);">IMC</span>
                            <strong>{{ $perfil['imc'] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card fi fi2">
                    <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:var(--in);font-weight:700;text-transform:uppercase;margin-bottom:12px;">Fatores Clínicos</p>
                    <div style="space-y:8px;">
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">Mod. Idade</span>
                            <strong style="color:var(--in);">×{{ number_format($topMatch->mod_idade, 2) }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">Mod. Género</span>
                            <strong style="color:var(--in);">×{{ number_format($topMatch->mod_genero, 2) }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;">
                            <span style="color:var(--mu);">Mod. IMC</span>
                            <strong style="color:var(--in);">×{{ number_format($topMatch->mod_imc, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card fi fi2">
                    <p style="font-family:'Space Mono',monospace;font-size:0.65rem;color:var(--in);font-weight:700;text-transform:uppercase;margin-bottom:12px;">Classificação</p>
                    <div style="space-y:8px;">
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">IMC</span>
                            <strong>{{ ucfirst(str_replace('_', ' ', $perfil['imc_classificacao'])) }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--bd);">
                            <span style="color:var(--mu);">Severidade</span>
                            <span class="tg tg-pu">{{ ucfirst($topMatch->severity ?? 'Média') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:8px 0;">
                            <span style="color:var(--mu);">Código ICD</span>
                            <strong>{{ $topMatch->icd_code ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Differential diagnoses -->
            <div class="fi fi3" style="margin-bottom:32px;">
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--tx);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-list-check"></i> Diagnósticos Diferenciais
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:12px;">
                    @foreach($results->skip(1)->take(3) as $disease)
                    <div class="card" style="border-left:4px solid var(--in);display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <h4 style="font-weight:700;color:var(--tx);margin-bottom:4px;">{{ $disease->name }}</h4>
                            <p style="font-size:0.8rem;color:var(--mu);">{{ $disease->match_count }} sintomas encontrados</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:1.5rem;font-weight:800;color:var(--in);">{{ $disease->probability }}%</p>
                            <p style="font-size:0.65rem;color:var(--mu);text-transform:uppercase;">Cobertura: {{ $disease->cobertura }}%</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Reference links if available -->
            @if($diagnostico && !empty($diagnostico->links_referencia) && count($diagnostico->links_referencia) > 0)
            <div class="card fi fi3" style="margin-bottom:32px;">
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--tx);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-book-medical"></i> Referências Clínicas
                </h3>
                <div style="space-y:12px;">
                    @foreach($diagnostico->links_referencia as $link)
                    <a href="{{ $link['url'] ?? '#' }}" target="_blank" style="display:block;background:var(--is);border:1px solid var(--bd);border-radius:var(--rs);padding:12px;text-decoration:none;transition:all .2s;border-left:3px solid var(--in);" onmouseover="this.style.background='var(--id)'" onmouseout="this.style.background='var(--is)'">
                        <p style="font-weight:700;color:var(--tx);font-size:0.9rem;">{{ $link['fonte'] ?? 'Fonte' }}</p>
                        <p style="color:var(--mu);font-size:0.8rem;margin-top:4px;">{{ $link['resumo'] ?? 'Referência clínica' }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action buttons -->
            <div class="fi fi3" style="margin-bottom:32px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <a href="{{ route('dashboard') }}" class="b-sc" style="text-align:center;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
                    <i class="fa-solid fa-hourglass-end"></i> Analisar Depois
                </a>
                <a href="{{ route('diagnostico.validar', $diagnostico->id) }}" class="b-ac" style="text-align:center;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
                    <i class="fa-solid fa-check-circle"></i> Validar Diagnóstico
                </a>
            </div>

            <!-- Disclaimer -->
            <div class="al al-rd fi fi3" style="margin-bottom:32px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Aviso Legal:</strong> Clinicaly fornece probabilidades estatísticas como suporte à decisão clínica. Não substitui diagnóstico profissional. Sempre consulte um médico qualificado.
                </div>
            </div>

        @else
            <!-- No results state -->
            <div class="card fi fi2" style="text-align:center;padding:60px 40px;">
                <i class="fa-solid fa-database" style="font-size:4rem;color:var(--id);margin-bottom:16px;display:block;"></i>
                <h2 style="font-size:1.5rem;font-weight:800;color:var(--tx);margin-bottom:8px;">Análise Inconclusiva</h2>
                <p style="color:var(--mu);font-size:0.95rem;line-height:1.6;">Os sintomas e dados biométricos informados não apresentam correlação segura com o banco de patologias disponível.</p>
                <a href="{{ route('discovery.index') }}" class="btn b-pr" style="margin-top:24px;"><i class="fa-solid fa-arrow-left"></i> Tentar Novamente</a>
            </div>
        @endif

    </div>

    <script>
        // Theme management
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();

        // Apply Dosis font globally
        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.style.fontFamily = "'Dosis', sans-serif";
        });
    </script>
</body>
</html>