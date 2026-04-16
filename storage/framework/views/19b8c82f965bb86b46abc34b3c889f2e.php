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
        section:nth-child(n+2) { animation-delay: 0.1s; }
        section:nth-child(n+3) { animation-delay: 0.2s; }
        div[style*="background: var(--sf)"] { animation: fadeInUp 0.45s ease-out; }
    </style>
    
    <main class="mt-12 max-w-5xl mx-auto px-6 pb-20">
        <section class="mb-10">
            <h1 style="color: var(--tx);" class="text-3xl font-light">Bom dia, Dr. <span class="font-black" style="color: var(--in);"><?php echo e(explode(' ', $user->name)[0]); ?></span></h1>
            <p style="color: var(--mu);" class="font-medium">Confira as atualizações da sua fila clínica hoje.</p>
        </section>

        
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-8 shadow-sm flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4">
                    <span class="flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                </div>
                <p class="text-4xl font-black text-amber-500 mb-2"><?php echo e($stats['pendentes']); ?></p>
                <p class="font-bold">Casos Pendentes</p>
                <p style="color: var(--mu);" class="text-xs mt-1">Aguardando validação</p>
            </div>

            
            <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-8 shadow-sm flex flex-col items-center text-center">
                <p class="text-4xl font-black text-emerald-500 mb-2"><?php echo e($stats['validados']); ?></p>
                <p class="font-bold">Validados este mês</p>
                <p style="color: var(--mu);" class="text-xs mt-1">Produtividade clínica</p>
            </div>

            
            <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-8 shadow-sm flex flex-col items-center text-center">
                <p class="text-4xl font-black text-blue-500 mb-2"><?php echo e($stats['pacientes']); ?></p>
                <p class="font-bold">Pacientes ativos</p>
                <p style="color: var(--mu);" class="text-xs mt-1">Base Clinicaly</p>
            </div>
        </section>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['pendentes'] > 0): ?>
        <section class="mb-8">
            <div style="background: var(--rb); border: 1px solid var(--rbd); color: var(--rd);" class="rounded-[30px] p-6 flex items-center gap-4">
                <div class="bg-red-500 text-white w-10 h-10 rounded-2xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <p class="font-medium text-sm">Atenção médico: Você possui diagnósticos que requerem revisão imediata.</p>
                <a href="<?php echo e(route('diagnostico.fila')); ?>" class="ml-auto bg-red-600 text-white px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest">Ver Fila</a>
            </div>
        </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <section style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-10 shadow-sm mb-8">
            <h2 style="color: var(--mu);" class="text-[10px] font-black uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i> Movimentações Recentes
            </h2>
            
            <div style="border-color: var(--bd);" class="divide-y">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="py-6 flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div style="background: var(--is); color: var(--in);" class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-lg">
                            <?php echo e(substr($diag->paciente->name ?? 'P', 0, 1)); ?>

                        </div>
                        <div>
                            <p class="font-bold"><?php echo e($diag->paciente->name ?? 'Paciente'); ?></p>
                            <p style="color: var(--mu);" class="text-sm"><?php echo e($diag->sintomas_preview); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div class="text-right hidden md:block">
                            <span class="text-[10px] font-black uppercase tracking-widest <?php echo e($diag->status == 'pendente' ? 'text-amber-500' : 'text-emerald-500'); ?>">
                                <?php echo e($diag->status); ?>

                            </span>
                            <p style="color: var(--mu);" class="text-[10px] font-bold uppercase mt-1"><?php echo e($diag->updated_at->diffForHumans()); ?></p>
                        </div>

                        <a href="<?php echo e($diag->status === 'pendente' ? route('diagnostico.validar', $diag->id) : route('patients.history', $diag->id_paciente)); ?>" class="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg shadow-indigo-200">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="color: var(--mu);" class="text-center py-10 italic">Nenhuma atividade recente encontrada.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>

        
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('discovery.index')); ?>" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i class="fa-solid fa-plus-circle text-indigo-600 text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Novo Exame</span>
            </a>
            <a href="<?php echo e(route('chat.index')); ?>" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i style="color: var(--mu);" class="fa-solid fa-robot text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Chat IA</span>
            </a>
            <a href="<?php echo e(route('messages.index')); ?>" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i style="color: var(--mu);" class="fa-solid fa-comment-dots text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Mensagens</span>
            </a>
            <a href="<?php echo e(route('profile.show')); ?>" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i style="color: var(--mu);" class="fa-solid fa-user-gear text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Perfil</span>
            </a>
        </section>
    </main>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\gcris\OneDrive\Documentos\Escola\Projetos Pessoais\Clinicaly\resources\views/dashboard_medico.blade.php ENDPATH**/ ?>