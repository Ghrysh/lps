
<?php $__env->startSection('title', 'Master Data Kuis'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Tambah Soal Baru</h3>
                <form action="<?php echo e(route('tools.quiz_store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-600 mb-2">Pertanyaan</label>
                        <textarea name="question_text" rows="3" class="w-full rounded-xl border-slate-200" required
                            placeholder="Contoh: Apa kepanjangan LPS?"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-600 mb-2">Durasi Waktu (Detik)</label>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-stopwatch text-orange-600 text-xl"></i>
                            <input type="number" name="duration" value="20" min="5" max="300"
                                class="w-full rounded-xl border-slate-200" required>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">*Semakin sulit soal, berikan waktu lebih lama.</p>
                    </div>

                    <div class="mb-4 space-y-3">
                        <label class="block text-sm font-bold text-slate-600">Pilihan Jawaban</label>

                        <?php $__currentLoopData = range(0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-2">
                                <input type="radio" name="correct_index" value="<?php echo e($i); ?>" required
                                    title="Pilih ini sebagai kunci jawaban">
                                <input type="text" name="answers[]" class="w-full rounded-lg border-slate-200 text-sm"
                                    placeholder="Pilihan <?php echo e(chr(65 + $i)); ?>" required>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <p class="text-xs text-slate-400">*Klik radio button bulat untuk menandai kunci jawaban.</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-600 text-white py-2 rounded-xl font-bold hover:bg-orange-700">Simpan
                        Soal</button>
                </form>
            </div>
        </div>

        
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Daftar Soal (<?php echo e($questions->count()); ?>)</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border p-4 rounded-xl hover:bg-slate-50">
                            <div class="flex justify-between items-start">
                                <p class="font-bold text-slate-700"><?php echo e($q->question_text); ?></p>
                                <form action="<?php echo e(route('tools.quiz_delete', $q->id)); ?>" method="POST"
                                    onsubmit="return confirm('Hapus soal ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <ul class="mt-2 text-sm grid grid-cols-2 gap-2">
                                <?php $__currentLoopData = $q->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="<?php echo e($ans->is_correct ? 'text-green-600 font-bold' : 'text-slate-500'); ?>">
                                        <?php echo e($ans->is_correct ? '✓' : '•'); ?> <?php echo e($ans->answer_text); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/tools/quiz_manager.blade.php ENDPATH**/ ?>