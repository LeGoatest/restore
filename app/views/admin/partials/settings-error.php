<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="icon-[heroicons--x-circle-20-solid] text-red-400 text-xl"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-red-800">
                <?= htmlspecialchars($message ?? 'An error occurred while saving settings.') ?>
            </p>
        </div>
    </div>
</div>