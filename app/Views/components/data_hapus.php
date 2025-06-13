<div class="px-3 py-1.5">
    <?php
        echo view('components/data_hapus_tombol', [
            'api_url' => $api_url 
        ]);
        echo view('components/data_hapus_form',[
            'row_id'  => $row_id,
            'api_url' => $api_url   
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