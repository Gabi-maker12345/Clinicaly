<?php
    $authUser = auth()->user();
    $role = $authUser?->role ?? 'pacient';
    $normalizedRole = mb_strtolower(trim((string) $role));
    $isDoctor = $authUser?->isDoctor() ?? false;
    $isClinic = ($authUser?->isClinic() ?? false)
        || in_array($normalizedRole, ['clinica', 'clínica', 'clinic'], true)
        || request()->routeIs('clinic.*');
    $roleLabel = $authUser?->roleLabel() ?? 'Paciente';
    $pendingCount = 0;
    $notificationCount = 0;

    try {
        $pendingCount = $isDoctor && class_exists(\App\Models\Diagnostico::class)
            ? \App\Models\Diagnostico::where('status', 'pendente')->count()
            : 0;

        $messageCount = class_exists(\App\Models\Message::class)
            ? \App\Models\Message::where('user_id', '!=', $authUser?->id)
                ->where('read', false)
                ->whereHas('conversation', function ($query) use ($authUser) {
                    $query->where('sender_id', $authUser?->id)
                        ->orWhere('receiver_id', $authUser?->id);
                })
                ->count()
            : 0;

        $notificationCount = (int) ($authUser?->unreadNotifications()?->count() ?? 0) + $messageCount;
    } catch (\Throwable $e) {
        $pendingCount = 0;
        $notificationCount = 0;
    }
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Clinicaly')); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['class', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Dosis', 'ui-sans-serif', 'system-ui'],
                        dosis: ['Dosis', 'sans-serif'],
                        mono: ['Space Mono', 'monospace'],
                    }
                }
            }
        };
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="<?php echo e(asset('logo-clin.ico')); ?>" type="image/x-icon">
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
        :root{--in:#6d55b1;--il:#8b72cc;--is:#f5f3fd;--id:#ede9f8;--bg:#f2f0fa;--sf:#ffffff;--sf2:#f8f6fd;--sf3:#ede9f8;--bd:#ddd8f0;--bd2:#ccc4e8;--tx:#1a1530;--mu:#7c72a0;--fa:#b0a8cc;--gr:#059669;--gb:#ecfdf5;--gbd:#a7f3d0;--bl:#3b82f6;--bb:#eff6ff;--bbd:#bfdbfe;--wn:#d97706;--wb:#fffbeb;--wbd:#fcd34d;--rd:#dc2626;--rb:#fef2f2;--rbd:#fca5a5;--sh:0 1px 3px rgba(109,85,177,.08);--sh2:0 4px 12px rgba(109,85,177,.10);--sh3:0 8px 32px rgba(109,85,177,.14);--r:16px;--rs:8px;--sidebar-w:248px;--topbar-h:64px;}
        [data-theme="dark"]{--in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--bd2:#352b58;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);--sh3:0 8px 32px rgba(0,0,0,.7);}
        *{box-sizing:border-box} html{background:var(--bg);color:var(--tx)} body{margin:0;min-height:100vh;background:var(--bg);color:var(--tx);font-family:'Dosis',sans-serif;letter-spacing:0} a{color:inherit} [x-cloak]{display:none!important}
        .app-topbar{position:fixed;top:0;left:0;right:0;z-index:100;height:64px;background:var(--sf);border-bottom:1px solid var(--bd);box-shadow:var(--sh);display:flex;align-items:center;gap:12px;padding:0 20px}
        .app-logo{display:flex;align-items:center;gap:8px;min-width:180px;text-decoration:none}.app-logo img{width:158px;max-height:44px;object-fit:contain}.hamb{display:none;background:var(--sf2);border:1px solid var(--bd);border-radius:10px;width:38px;height:38px;color:var(--mu)}
        .app-search{flex:1;max-width:520px;position:relative}.app-search i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--mu);font-size:.85rem}.app-search input{width:100%;height:38px;border-radius:30px;border:1px solid var(--bd);background:var(--sf2);color:var(--tx);padding:0 16px 0 36px;font:600 .88rem 'Dosis',sans-serif;outline:none}.app-search input:focus{border-color:var(--in);box-shadow:0 0 0 3px rgba(109,85,177,.12);background:var(--sf)}
        .top-actions{margin-left:auto;display:flex;align-items:center;gap:8px}.icon-btn,.theme-ui{width:38px;height:38px;border-radius:10px;border:1px solid var(--bd);background:var(--sf2);color:var(--mu);display:inline-flex;align-items:center;justify-content:center;position:relative;cursor:pointer;text-decoration:none}.icon-btn:hover,.theme-ui:hover{border-color:var(--bd2,var(--bd));color:var(--in);background:var(--is)}.notify-dot{position:absolute;top:6px;right:6px;width:8px;height:8px;border-radius:50%;background:var(--rd);border:2px solid var(--sf);animation:pulse 2s infinite}@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
        #theme-toggle{position:absolute;opacity:0;pointer-events:none}.theme-ui .sun{display:none}[data-theme="dark"] .theme-ui .sun{display:block}[data-theme="dark"] .theme-ui .moon{display:none}
        .user-chip{display:flex;align-items:center;gap:10px;padding:5px 8px;border:1px solid var(--bd);border-radius:999px;background:var(--sf2);min-width:0}.user-chip img{width:34px;height:34px;border-radius:50%;object-fit:cover}.user-meta{line-height:1.05}.user-meta strong{display:block;color:var(--tx);font-size:.86rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.user-meta span{display:block;margin-top:3px;color:var(--in);font:700 .58rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.08em}
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(10,8,20,.4);backdrop-filter:blur(2px);z-index:89}.app-sidebar{position:fixed;top:var(--topbar-h);left:0;bottom:0;width:var(--sidebar-w);background:var(--sf);border-right:1px solid var(--bd);z-index:90;display:flex!important;flex-direction:column;overflow-y:auto;padding:20px 14px 24px;transition:transform .28s;scrollbar-width:none;-ms-overflow-style:none}.app-sidebar::-webkit-scrollbar{width:0;height:0;display:none}.side-profile{display:flex;align-items:center;gap:10px;padding:12px;background:var(--sf2);border:1px solid var(--bd);border-radius:12px;margin-bottom:16px}.side-profile img{width:38px;height:38px;border-radius:50%;object-fit:cover}.side-profile strong{display:block;font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.side-profile span{color:var(--mu);font:700 .48rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em}
        .sn{padding:0 0 8px}.sn-title{display:block;padding:14px 12px 5px;color:var(--fa);font:700 .48rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.14em}.sn a{display:flex;align-items:center;gap:10px;min-height:40px;padding:9px 12px;border-radius:10px;color:var(--mu);text-decoration:none;font-size:.88rem;font-weight:600}.sn a i{width:30px;height:30px;border-radius:8px;background:var(--sf2);border:1px solid var(--bd);display:flex;align-items:center;justify-content:center;font-size:.82rem}.sn a:hover,.sn a.active{background:var(--is);color:var(--in);font-weight:700}.sn a:hover i,.sn a.active i{background:var(--id)}.nav-badge{margin-left:auto;color:#fff;background:var(--rd);font:800 .52rem 'Space Mono',monospace;padding:2px 8px;border-radius:20px}
        .app-main{padding-top:var(--topbar-h);margin-left:var(--sidebar-w);min-height:100vh}.content-wrap{width:100%;max-width:none;margin:0;padding:32px 36px 80px}
        body.clinic-layout .app-sidebar,body.clinic-layout .sidebar-overlay,body.clinic-layout .hamb{display:none!important}
        body.clinic-layout .app-main{margin-left:0!important}
        body.clinic-layout .content-wrap{padding-left:24px!important;padding-right:24px!important}
        body[data-user-role="clinica"] .app-sidebar,
        body[data-user-role="clínica"] .app-sidebar,
        body[data-user-role="clinic"] .app-sidebar,
        body[data-user-role="clinica"] .sidebar-overlay,
        body[data-user-role="clínica"] .sidebar-overlay,
        body[data-user-role="clinic"] .sidebar-overlay,
        body[data-user-role="clinica"] .hamb,
        body[data-user-role="clínica"] .hamb,
        body[data-user-role="clinic"] .hamb{display:none!important}
        body[data-user-role="clinica"] .app-main,
        body[data-user-role="clínica"] .app-main,
        body[data-user-role="clinic"] .app-main{margin-left:0!important}
        .card{background:var(--sf);border:1px solid var(--bd);border-radius:16px;box-shadow:var(--sh);padding:24px}.tag,.tg{display:inline-flex;align-items:center;gap:6px;border-radius:999px;border:1px solid var(--bd);background:var(--sf2);color:var(--mu);font:700 .68rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.08em;padding:5px 11px}.tag.success,.tag-green,.tg-gr{background:var(--gb);color:var(--gr);border-color:var(--gbd)}.tag.danger,.tag-red,.tg-rd{background:var(--rb);color:var(--rd);border-color:var(--rbd)}.tag.warn,.tag-amber,.tg-am{background:var(--wb);color:var(--wn);border-color:var(--wbd)}.tag.info,.tag-purple,.tg-pu{background:var(--is);color:var(--in);border-color:var(--id)}.tag-blue{background:var(--bb);color:var(--bl);border-color:var(--bbd)}.tag-grey{background:var(--sf2);color:var(--mu);border-color:var(--bd)}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:10px;border:1.5px solid transparent;padding:10px 18px;font:800 .9rem 'Dosis',sans-serif;text-decoration:none;cursor:pointer;transition:.18s;white-space:nowrap}.btn-primary,.b-pr{background:var(--in);color:#fff}.btn-primary:hover,.b-pr:hover{background:var(--il)}.btn-ghost,.b-gh{background:var(--sf2);color:var(--mu);border-color:var(--bd)}.btn-ghost:hover,.b-gh:hover{background:var(--is);color:var(--in);border-color:var(--in)}.btn-danger,.btn-red,.b-rd{background:var(--rb);color:var(--rd);border-color:var(--rbd)}.btn-danger:hover,.btn-red:hover,.b-rd:hover{background:var(--rd);color:#fff}.btn-green{background:var(--gb);color:var(--gr);border-color:var(--gbd)}.btn-green:hover{background:var(--gr);color:#fff}.btn-blue{background:var(--bb);color:var(--bl);border-color:var(--bbd)}.btn-sm,.bsm{padding:7px 13px;font-size:.8rem}.btn-xs{padding:5px 11px;font-size:.74rem}
        .kpi-card{background:var(--sf);border:1px solid var(--bd);border-radius:16px;padding:22px;box-shadow:var(--sh)}.kpi-card .value{font-size:2rem;font-weight:800;line-height:1;color:var(--in)}.kpi-card .label{margin-top:8px;color:var(--mu);font:700 .68rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em}
        .patient-row{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:16px 0;border-bottom:1px solid var(--bd)}.patient-row:last-child{border-bottom:0}.data-table{width:100%;border-collapse:separate;border-spacing:0}.data-table th{color:var(--mu);font:700 .65rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;text-align:left;padding:12px;border-bottom:1px solid var(--bd)}.data-table td{padding:14px 12px;border-bottom:1px solid var(--bd);color:var(--tx)}.feed-item{display:flex;gap:12px;padding:14px 0;border-bottom:1px solid var(--bd)}.progress-bar{height:8px;background:var(--id);border-radius:999px;overflow:hidden}.progress-bar span,.progress-bar div{display:block;height:100%;background:linear-gradient(90deg,var(--in),var(--il));border-radius:inherit}.alert{display:flex;align-items:center;gap:12px;border-radius:16px;padding:15px 18px;border:1px solid var(--bd);background:var(--sf2);color:var(--tx)}.alert.success{background:var(--gb);border-color:var(--gbd);color:var(--gr)}.alert.warning{background:var(--wb);border-color:var(--wbd);color:var(--wn)}.alert.danger{background:var(--rb);border-color:var(--rbd);color:var(--rd)}
        input,textarea,select{background:var(--sf2);color:var(--tx);border-color:var(--bd)}::placeholder{color:var(--mu);opacity:.75}
        @media(min-width:768px){.app-topbar{padding-left:20px}.app-sidebar{transform:none!important}.sidebar-overlay{display:none!important}}
        @media(max-width:767px){.hamb{display:inline-flex;align-items:center;justify-content:center}.app-logo{min-width:auto}.app-logo img{width:128px}.app-search{display:none}.user-meta{display:none}.app-sidebar{transform:translateX(-100%)}.app-main{margin-left:0}.content-wrap{padding:24px 16px 56px}body.sidebar-open .app-sidebar{transform:translateX(0);box-shadow:var(--sh3)}body.sidebar-open .sidebar-overlay{display:block}.top-actions{gap:6px}}
    </style>
</head>
<body class="<?php echo e($isClinic ? 'clinic-layout' : ''); ?>" data-user-role="<?php echo e($normalizedRole); ?>">
    <?php if (isset($component)) { $__componentOriginalff9615640ecc9fe720b9f7641382872b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff9615640ecc9fe720b9f7641382872b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $attributes = $__attributesOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__attributesOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff9615640ecc9fe720b9f7641382872b)): ?>
<?php $component = $__componentOriginalff9615640ecc9fe720b9f7641382872b; ?>
<?php unset($__componentOriginalff9615640ecc9fe720b9f7641382872b); ?>
<?php endif; ?>

    <header class="app-topbar">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($isClinic)): ?>
            <button type="button" class="hamb" id="sidebar-open" aria-label="Abrir menu"><i class="fa-solid fa-bars"></i></button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <a href="<?php echo e($isClinic ? route('clinic.index') : route('profile.show') . '#dashboard'); ?>" class="app-logo" title="Clinicaly">
            <img src="<?php echo e(asset('Proosta-logo4.png')); ?>" alt="Clinicaly">
        </a>
        <form class="app-search" role="search" action="<?php echo e(route('messages.index')); ?>" method="GET">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input type="search" name="q" placeholder="Buscar pacientes, conversas e registros">
        </form>
        <nav class="top-actions" aria-label="Ações globais">
            <a href="<?php echo e(route('messages.index')); ?>" class="icon-btn" aria-label="Conversas"><i class="fa-solid fa-comment-dots"></i></a>
            <a href="<?php echo e(route('multi-accounts.index')); ?>" class="icon-btn <?php echo e(request()->routeIs('multi-accounts.*') ? 'active' : ''); ?>" aria-label="Contas conectadas"><i class="fa-solid fa-user-group"></i></a>
            <a href="<?php echo e(route('notifications.index')); ?>" class="icon-btn <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>" aria-label="Notificações"><i class="fa-solid fa-bell"></i><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notificationCount): ?><span class="notify-dot"></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></a>
            <input type="checkbox" id="theme-toggle" aria-label="Alternar tema">
            <label for="theme-toggle" class="theme-ui" title="Alternar tema"><i class="fa-solid fa-moon moon"></i><i class="fa-solid fa-sun sun"></i></label>
            <a href="<?php echo e($isClinic ? route('clinic.index') : route('profile.show') . '#profile'); ?>" class="user-chip">
                <img src="<?php echo e($authUser?->profile_photo_url); ?>" alt="<?php echo e($authUser?->name); ?>">
                <span class="user-meta"><strong><?php echo e($authUser?->name); ?></strong><span><?php echo e($roleLabel); ?></span></span>
            </a>
        </nav>
    </header>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($isClinic)): ?>
    <button class="sidebar-overlay" id="sidebar-close" type="button" aria-label="Fechar menu"></button>
    <aside class="app-sidebar" aria-label="Navegação principal">
        <div class="side-profile">
            <img src="<?php echo e($authUser?->profile_photo_url); ?>" alt="<?php echo e($authUser?->name); ?>">
            <div><strong><?php echo e($authUser?->name); ?></strong><span><?php echo e($roleLabel); ?></span></div>
        </div>
        <nav class="sn">
            <span class="sn-title">Principal</span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isClinic): ?>
                <a href="<?php echo e(route('clinic.index', ['tab' => 'employees'])); ?>"><i class="fa-solid fa-user-doctor"></i>Meus Funcionários</a>
                <a href="<?php echo e(route('clinic.index', ['tab' => 'patients'])); ?>"><i class="fa-solid fa-hospital-user"></i>Pacientes</a>
                <a href="<?php echo e(route('clinic.index', ['tab' => 'stock'])); ?>"><i class="fa-solid fa-boxes-stacked"></i>Estoque</a>
                <a href="<?php echo e(route('clinic.index', ['tab' => 'appointments'])); ?>"><i class="fa-solid fa-calendar-days"></i>Agendamentos</a>
            <?php else: ?>
            <a href="<?php echo e(route('profile.show')); ?>#dashboard"><i class="fa-solid fa-table-cells-large"></i>Dashboard</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDoctor): ?>
                <a href="<?php echo e(route('profile.show')); ?>#fila"><i class="fa-solid fa-list-check"></i>Fila de Validação <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingCount): ?><span class="nav-badge"><?php echo e($pendingCount); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('profile.show')); ?>#agenda"><i class="fa-solid fa-calendar-days"></i>Agenda</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDoctor): ?>
                <a href="<?php echo e(route('profile.show')); ?>#patients"><i class="fa-solid fa-users"></i>Pacientes</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('profile.show')); ?>#prescricoes"><i class="fa-solid fa-file-prescription"></i>Prescrições</a>
            <a href="<?php echo e(route('messages.index')); ?>" class="<?php echo e(request()->routeIs('messages.*') ? 'active' : ''); ?>"><i class="fa-solid fa-comments"></i>Minhas Conversas</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </nav>
        <nav class="sn">
            <span class="sn-title">Análise</span>
            <a href="<?php echo e(route('profile.show')); ?>#analise1"><i class="fa-solid fa-chart-simple"></i>Estatísticas</a>
            <a href="<?php echo e(route('profile.show')); ?>#analise2"><i class="fa-solid fa-chart-pie"></i>Relatórios</a>
            <a href="<?php echo e(route('notifications.index')); ?>" class="<?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>"><i class="fa-solid fa-bell"></i>Notificações <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notificationCount): ?><span class="nav-badge"><?php echo e($notificationCount); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></a>
        </nav>
        <nav class="sn">
            <span class="sn-title">Perfil</span>
            <a href="<?php echo e($isClinic ? route('clinic.index') : route('profile.show') . '#profile'); ?>"><i class="fa-solid fa-user"></i>Meu Perfil</a>
            <a href="<?php echo e($isClinic ? route('clinic.index') : route('profile.show') . '#settings'); ?>"><i class="fa-solid fa-gear"></i>Configurações</a>
        </nav>
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="padding:16px 12px;margin-top:auto;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-danger" style="width:100%;"><i class="fa-solid fa-right-from-bracket"></i>Sair</button>
        </form>
    </aside>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <main class="app-main">
        <div class="content-wrap">
            <?php echo $__env->yieldContent('content'); ?>
            <?php echo e($slot ?? ''); ?>

        </div>
    </main>

    <?php echo $__env->yieldPushContent('modals'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (function () {
            const key = 'cl-theme';
            const root = document.documentElement;
            const toggle = document.getElementById('theme-toggle');
            const setTheme = (theme) => {
                root.setAttribute('data-theme', theme);
                localStorage.setItem(key, theme);
                if (toggle) toggle.checked = theme === 'dark';
            };
            setTheme(localStorage.getItem(key) || 'light');
            toggle?.addEventListener('change', () => setTheme(toggle.checked ? 'dark' : 'light'));
            document.getElementById('sidebar-open')?.addEventListener('click', () => document.body.classList.add('sidebar-open'));
            document.getElementById('sidebar-close')?.addEventListener('click', () => document.body.classList.remove('sidebar-open'));
            window.addEventListener('pageshow', () => setTheme(localStorage.getItem(key) || 'light'));

            const scopedRole = new URLSearchParams(window.location.search).get('as_role');

            if (scopedRole) {
                const applyRoleToUrl = (value) => {
                    const url = new URL(value, window.location.origin);

                    if (url.origin !== window.location.origin) {
                        return value;
                    }

                    if (['/logout', '/login', '/register'].includes(url.pathname)) {
                        return value;
                    }

                    url.searchParams.set('as_role', scopedRole);

                    return url.toString();
                };

                const applyScopedRoleToLinks = (rootNode = document) => {
                    rootNode.querySelectorAll?.('a[href]').forEach((link) => {
                        const href = link.getAttribute('href');

                        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
                            return;
                        }

                        link.setAttribute('href', applyRoleToUrl(href));
                    });
                };

                const applyScopedRoleToForm = (form) => {
                    const action = form.getAttribute('action') || window.location.href;
                    const url = new URL(action, window.location.origin);

                    if (url.origin !== window.location.origin || url.pathname === '/logout') {
                        return;
                    }

                    if (!form.querySelector('input[name="as_role"]')) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'as_role';
                        input.value = scopedRole;
                        form.appendChild(input);
                    }
                };

                applyScopedRoleToLinks();
                document.querySelectorAll('form').forEach(applyScopedRoleToForm);

                document.addEventListener('click', (event) => {
                    const link = event.target.closest?.('a[href]');
                    if (!link) return;

                    const href = link.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
                        return;
                    }

                    link.setAttribute('href', applyRoleToUrl(href));
                }, true);

                document.addEventListener('submit', (event) => {
                    if (event.target instanceof HTMLFormElement) {
                        applyScopedRoleToForm(event.target);
                    }
                }, true);

                if (window.MutationObserver) {
                    new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            mutation.addedNodes.forEach((node) => {
                                if (node.nodeType === Node.ELEMENT_NODE) {
                                    applyScopedRoleToLinks(node);
                                    if (node instanceof HTMLFormElement) {
                                        applyScopedRoleToForm(node);
                                    }
                                    node.querySelectorAll?.('form').forEach(applyScopedRoleToForm);
                                }
                            });
                        });
                    }).observe(document.body, {childList: true, subtree: true});
                }

                const originalFetch = window.fetch;

                window.fetch = (input, init = {}) => {
                    if (typeof input === 'string' || input instanceof URL) {
                        input = applyRoleToUrl(input.toString());
                    }

                    return originalFetch(input, init);
                };
            }
        })();
    </script>
</body>
</html>
<?php /**PATH C:\Users\gcris\OneDrive\Documentos\Escola\Projetos Pessoais\Clinicaly\resources\views/layouts/app.blade.php ENDPATH**/ ?>