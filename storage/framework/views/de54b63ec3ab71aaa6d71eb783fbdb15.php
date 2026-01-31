

<?php $__env->startSection('title', 'Dashboard LPS'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Monitoring</h2>
        <p class="text-sm text-slate-500">Ringkasan data operasional Lembaga Penjamin Simpanan (LPS)</p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php
            // Data Dummy Khusus LPS
            $stats = [
                'bank_peserta' => 1045,
                'total_simpanan' => '5.840', // Dalam Triliun
                'klaim_proses' => 12,
                'pegawai_lps' => 742
            ];

            $cards = [
                ['label' => 'Bank Peserta', 'value' => $stats['bank_peserta'], 'sub' => 'Bank Umum & BPR aktif', 'color' => 'border-orange-500', 'icon' => 'fa-building-columns'],
                ['label' => 'Total Simpanan', 'value' => 'Rp' . $stats['total_simpanan'] . 'T', 'sub' => 'Estimasi dana dijamin', 'color' => 'border-orange-400', 'icon' => 'fa-vault'],
                ['label' => 'Klaim Berjalan', 'value' => $stats['klaim_proses'], 'sub' => 'Proses rekonsiliasi/verifikasi', 'color' => 'border-green-500', 'icon' => 'fa-file-invoice-dollar'],
                ['label' => 'Total Pegawai', 'value' => $stats['pegawai_lps'], 'sub' => 'Seluruh kantor wilayah', 'color' => 'border-blue-900', 'icon' => 'fa-users-gear']
            ];
        ?>

        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 <?php echo e($item['color']); ?> flex justify-between items-start hover:shadow-md transition-shadow">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider"><?php echo e($item['label']); ?></p>
                    <h3 class="text-3xl font-bold text-slate-800 my-1"><?php echo e($item['value']); ?></h3>
                    <p class="text-[10px] text-slate-400"><?php echo e($item['sub']); ?></p>
                </div>
                <div class="bg-slate-100 p-3 rounded-lg text-orange-600">
                    <i class="fas <?php echo e($item['icon']); ?> text-lg"></i>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php $laporanBelumVerifikasi = 3; ?>
    <?php if($laporanBelumVerifikasi > 0): ?>
        <div class="bg-orange-50 border border-orange-200 p-5 rounded-xl flex items-center space-x-4 shadow-sm">
            <div class="bg-orange-500 text-white w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                <i class="fas fa-shield-check text-xl"></i>
            </div>
            <div>
                <p class="text-base font-bold text-orange-900 leading-tight">Notifikasi Penjaminan</p>
                <p class="text-sm text-orange-700 mt-1">
                    Terdapat <span class="font-extrabold underline"><?php echo e($laporanBelumVerifikasi); ?></span> laporan premi berkala bank peserta yang memerlukan verifikasi manual oleh <span class="font-semibold">Biro SDM & Administrasi</span>.
                </p>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h4 class="font-bold text-slate-800 mb-4 uppercase text-xs tracking-widest">Aktivitas Terkini Penugasan</h4>
            <div class="space-y-4">
                <?php $__currentLoopData = ['Audit Kepatuhan Bank - Wilayah I', 'Rekrutmen Tenaga Ahli Likuidasi', 'Pembaruan Sertifikat Penjaminan BPR']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                        <span class="text-sm text-slate-700 font-medium"><?php echo e($task); ?></span>
                    </div>
                    <span class="text-[10px] bg-white px-2 py-1 rounded border text-slate-400">Baru saja</span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="bg-orange-600 p-6 rounded-xl shadow-sm text-white flex flex-col justify-between">
            <div>
                <i class="fas fa-info-circle text-2xl mb-4"></i>
                <h4 class="font-bold text-lg mb-2">Status Sistem LPS</h4>
                <p class="text-xs text-orange-100 leading-relaxed">
                    Seluruh modul pelaporan (SCV), sistem premi, dan pendaftaran penugasan pejabat dalam kondisi stabil.
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <p class="text-[10px] uppercase tracking-tighter opacity-70">Terakhir diperbarui: <?php echo e(date('H:i')); ?> WIB</p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>