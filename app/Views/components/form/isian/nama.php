<input 
    id="<?= $id ?>"
    type="text"
    name="<?= $kolom ?>" 
    value="<?= $value ?>"
    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
    <?= $req  === 1   ? 'required' : ''; ?>
>