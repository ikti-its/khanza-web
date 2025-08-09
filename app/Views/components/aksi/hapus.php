<div class="px-3 py-1.5">
    <?php
        echo view('components/aksi/hapus_tombol', [
            'modul_path' => $modul_path 
        ]);
        echo view('components/aksi/hapus_form',[
            'modul_path' => $modul_path,
            'id'         => $id
        ]);
    ?>
</div>

<script>
    window.openModal = function(modalId) {
        document.getElementById(modalId).style.display = 'block'
        document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
    }

    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none'
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
    }
</script>