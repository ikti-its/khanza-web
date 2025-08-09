<?php 
    if($is_left){
        echo '<label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">' . $display;
    } else {
        echo '<label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">'. $display;
    }
    if($required === 1){
        echo '<span class="text-red-600">*</span>';
    }
    echo '</label>';
?>
