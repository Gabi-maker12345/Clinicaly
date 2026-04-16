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
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        section { animation: fadeInUp 0.4s ease-out; }
        article { animation: fadeInUp 0.45s ease-out; }
    </style>
    <style>
        .page { max-width: 900px; margin: 0 auto; padding: 28px 20px; }
        .ph { display: flex; align-items: center; gap: 20px; padding: 24px; background: var(--sf); border: 1px solid var(--bd); border-radius: var(--r); box-shadow: var(--sh); margin-bottom: 14px; }
        .av-lg { width: 68px; height: 68px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.3rem; }
        .tg { display: inline-flex; align-items: center; gap: 4px; font-size: .7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; border: 1px solid; }
        .sc { border-radius: var(--r); padding: 18px 20px; background: var(--sf); border: 1px solid var(--bd); }
        .sc .v { font-size: 2rem; font-weight: 800; line-height: 1; }
        .tli { display: flex; gap: 16px; padding-bottom: 22px; position: relative; }
        .tli::before { content:''; position: absolute; left: 11px; top: 26px; bottom: 0; width: 1.5px; background: var(--bd); }
        .tli:last-child::before { display: none; }
        .tld { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .6rem; z-index: 1; border: 2px solid; background: #fff; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 30px; font-weight: 700; font-size: .85rem; transition: 0.2s; border: none; cursor: pointer; text-decoration: none; }
        .b-pr { background: var(--in); color: #fff; }
        .b-gr { background: var(--gb); color: var(--gr); border: 1.5px solid var(--gbd); }
        .b-gh { background: transparent; border: 1px solid var(--bd); color: var(--mu); }
        .bsm { padding: 4px 12px; font-size: .75rem; }
    </style>

    <div class="page">
        <section class="mb-6">
            <header class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold mt-2" style="color: var(--tx);">Histórico do Paciente</h1>
                </div>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn b-gh"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
            </header>
        </section>

        <article class="ph">
            <div class="av-lg bg-indigo-100 text-indigo-600 border-2 border-indigo-200">
                <?php echo e(substr($patient->name, 0, 2)); ?>

            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold" style="color: var(--tx);"><?php echo e($patient->name); ?></h2>
                <p class="text-xs font-mono uppercase tracking-tighter" style="color: var(--mu);">ID: <?php echo e($patient->id); ?> · <?php echo e($patient->email); ?></p>
                <div class="flex gap-2 mt-3">
                    <span class="tg border-blue-200 text-blue-600 bg-blue-50"><?php echo e($stats['total_diagnostics']); ?> Diagnósticos</span>
                    <span class="tg border-emerald-200 text-emerald-600 bg-emerald-50">Adesão <?php echo e($stats['adherence_rate']); ?>%</span>
                </div>
            </div>
            <nav class="flex flex-col gap-2 items-end">
                <form action="<?php echo e(route('messages.start', $patient->id)); ?>" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn b-gh bsm w-full">
                        <i class="fa-solid fa-message"></i> Chat
                    </button>
                </form>
            </nav>
        </article>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <article class="sc"><p class="v text-blue-600"><?php echo e($stats['total_diagnostics']); ?></p><p class="text-xs text-gray-500 font-bold uppercase">Diagnósticos</p></article>
            <article class="sc"><p class="v text-emerald-600"><?php echo e($stats['total_prescriptions']); ?></p><p class="text-xs text-gray-500 font-bold uppercase">Prescrições</p></article>
            <article class="sc"><p class="v text-purple-600"><?php echo e($stats['adherence_rate']); ?>%</p><p class="text-xs text-gray-500 font-bold uppercase">Adesão</p></article>
        </div>

        <article style="background: var(--sf); border: 1px solid var(--bd);" class="rounded-2xl p-6 shadow-sm">
            <h2 class="flex items-center gap-2 text-xs font-mono font-bold uppercase tracking-widest pb-4 mb-6" style="color: var(--mu); border-bottom: 1px solid var(--bd);">
                <i class="fa-solid fa-timeline"></i> Linha do Tempo Clínica
            </h2>

            <ol>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="tli">
                        <?php
                            $isPending = $item['status'] === 'pending' || $item['status'] === null;
                        ?>
                        
                        <span class="tld <?php echo e($isPending ? 'border-red-500 text-red-500' : 'border-emerald-500 text-emerald-500'); ?>">
                            <i class="fa-solid <?php echo e($isPending ? 'fa-triangle-exclamation' : 'fa-check'); ?>"></i>
                        </span>

                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[10px] font-mono uppercase" style="color: var(--mu);"><?php echo e($item['date']->translatedFormat('d M Y · H:i')); ?></p>
                                    <p class="font-bold" style="color: var(--tx);"><?php echo e($item['title']); ?></p>
                                    <p class="text-sm" style="color: var(--mu);">Score: <?php echo e($item['score']); ?>% · Gravidade: <?php echo e($item['severity']); ?></p>
                                </div>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isPending): ?>
                                    
                                    <a href="<?php echo e(route('prescriptions.create', $item['id'])); ?>" class="btn b-gh bsm">
                                        <i class="fa-solid fa-pills"></i> Prescrever
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPending): ?>
                                <div class="mt-3 flex gap-2">
                                    <a href="<?php echo e(route('diagnostico.validar', $item['id'])); ?>" class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold rounded-full uppercase">
                                        Validar Urgente
                                    </a>
                                </div>
                            <?php else: ?>
                                
                                <p class="text-[10px] text-emerald-600 font-bold mt-1 uppercase">
                                    Validado por: <?php echo e($item['doctor']); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-center py-12">
                        <p class="font-mono text-xs" style="color: var(--mu);">Nenhum registro clínico encontrado.</p>
                    </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ol>
        </article>
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
<?php endif; ?><?php /**PATH C:\Users\gcris\OneDrive\Documentos\Escola\Projetos Pessoais\Clinicaly\resources\views/pages/history.blade.php ENDPATH**/ ?>