<div class="py-4 grid gap-3 md:items-start">
    <div class="sm:col-span-1">
        <label for="hs-as-table-product-review-search" class="sr-only">Cari</label>
        <div class="relative">
            <input type="text" id="myInput" onkeyup="myFunction()" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Cari">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
    function myFunction() {
        let table  = document.getElementById("myTable"); // Pastikan ini mengacu pada ID tabel yang benar
        if (!table) return; // Pastikan tabel ada sebelum melanjutkan

        let input  = document.getElementById("myInput");
        let filter = input.value.toUpperCase();
        let tr = table.getElementsByTagName("tr");
        if(!tr || tr.length <= 1) return;

        // Iterate over all table rows (excluding header row)
        for (let i = 1; i < tr.length; i++) {
            let found = false;

            let td_list = tr[i].getElementsByTagName("td");

            // Iterate over all td elements in the row
            for (let j = 0; j < td_list.length; j++) {
                let td = td_list[j];
                let nested = td.firstElementChild?.firstElementChild;
                let text = nested.textContent;

                // Reset cell text (remove any old highlights)
                
                nested.innerHTML = text;

                let index = text.toUpperCase().indexOf(filter);
                if (index == -1) {
                    continue;
                }
                nested.innerHTML =
                    text.substring(0, index) +
                    "<mark>" + text.substring(index, index + filter.length) + "</mark>" +
                    text.substring(index + filter.length);
                found = true;
            }
            // Show or hide row based on search result
            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>