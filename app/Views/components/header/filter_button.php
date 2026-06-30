<?php
/**
 * @var string $modul_path
 * @var string|null $active_filter
 * @var array $filters
 */
?>

<div class="relative">
    <select 
        onchange="applyGlobalFilter(this.value)"
        class="border border-gray-300 text-sm rounded-lg p-2 dark:bg-slate-900 dark:text-white">

        <option value="">Semua</option>

        <?php foreach ($filters as $key => $label): ?>
            <option value="<?= $key ?>" 
                <?= ($active_filter === $key) ? 'selected' : '' ?>>
                <?= $label ?>
            </option>
        <?php endforeach; ?>

    </select>
</div>

<script>
function applyGlobalFilter(value) {
    const url = new URL(window.location.href);

    if (value) {
        url.searchParams.set('filter', value);
    } else {
        url.searchParams.delete('filter');
    }

    window.location.href = url.toString();
}
</script>