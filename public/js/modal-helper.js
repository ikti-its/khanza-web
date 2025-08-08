// public/js/modal-helper.js

const modalConfigs = {}; // store modal-specific configs to avoid closure bugs

function initModalList({
  modalId,
  tableId,
  url,
  fields,
  searchIds,
  rowsPerPage = 10,
  onSelect
}) {
  let data = [];
  let filtered = [];
  let currentPage = 1;

  const modal = document.getElementById(modalId);
  const tbody = document.getElementById(tableId);
  const pageInfo = document.getElementById(`pageInfo_${modalId}`);
  const prevBtn = document.getElementById(`prevPageBtn_${modalId}`);
  const nextBtn = document.getElementById(`nextPageBtn_${modalId}`);

  modalConfigs[modalId] = { searchIds, onSelect };

  window[`open_${modalId}`] = function () {
    modal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
    fetchData();
  };

  window[`close_${modalId}`] = function () {
    modal.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
    const config = modalConfigs[modalId];
    if (config?.searchIds) {
      Object.keys(config.searchIds).forEach(id => {
        const input = document.getElementById(id);
        if (input) input.value = "";
      });
    }
  };

  function fetchData() {
    tbody.innerHTML = `<tr><td colspan="${fields.length + 1}" class="text-center p-4 text-gray-500">Memuat data...</td></tr>`;
    fetch(url)
      .then(res => res.json())
      .then(result => {
        data = result.data || [];
        filtered = [...data];
        currentPage = 1;
        renderPage();
      })
      .catch(() => {
        tbody.innerHTML = `<tr><td colspan="${fields.length + 1}" class="text-center p-4 text-red-500">Gagal memuat data</td></tr>`;
      });
  }

  function renderPage() {
    tbody.innerHTML = "";
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = filtered.slice(start, end);

    if (pageData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="${fields.length + 1}" class="text-center p-4 text-red-500">Tidak ada hasil</td></tr>`;
      updatePagination();
      return;
    }

    pageData.forEach(item => {
      const cols = fields.map(field => `<td class="p-2 border text-center">${item[field]}</td>`).join("");
      const row = `
        <tr class="hover:bg-emerald-50">
          ${cols}
          <td class="p-2 border text-center">
            <button type="button" class="text-emerald-700 hover:underline"
              data-json='${JSON.stringify(item)}'
              onclick="selectRowFromBtn(this, '${modalId}')">
              Pilih
            </button>
          </td>
        </tr>`;
      tbody.insertAdjacentHTML("beforeend", row);
    });

    if (data.length > rowsPerPage && pageData.length < rowsPerPage) {
      for (let i = 0; i < rowsPerPage - pageData.length; i++) {
        tbody.insertAdjacentHTML("beforeend", `
          <tr class="h-10"><td colspan="${fields.length + 1}" class="p-0 border-0 invisible whitespace-nowrap">.</td></tr>`);
      }
    }

    updatePagination();
  }

  function updatePagination() {
    const totalPages = Math.ceil(filtered.length / rowsPerPage);
    pageInfo.innerText = `Halaman ${currentPage} dari ${totalPages || 1}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage >= totalPages;
  }

  function handleSearch() {
    const config = modalConfigs[modalId];
    filtered = data.filter(item => {
      return Object.entries(config.searchIds).every(([inputId, fieldKey]) => {
        const val = document.getElementById(inputId)?.value?.toLowerCase() || '';
        return item[fieldKey]?.toLowerCase().includes(val);
      });
    });

    currentPage = 1;
    renderPage();
  }

  Object.keys(searchIds).forEach(id => {
    const input = document.getElementById(id);
    if (input) input.addEventListener("input", handleSearch);
  });

  prevBtn.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderPage();
    }
  });

  nextBtn.addEventListener("click", () => {
    const totalPages = Math.ceil(filtered.length / rowsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      renderPage();
    }
  });
}

window.selectRowFromBtn = function (btn, modalId) {
  const item = JSON.parse(btn.dataset.json);
  const config = modalConfigs[modalId];
  if (typeof config?.onSelect === 'function') {
    config.onSelect(item);
  }
  window[`close_${modalId}`]();
}

// Autofill elemen form berdasarkan mapping key -> value
window.autofillFields = function (map = {}) {
  Object.entries(map).forEach(([id, value]) => {
    const el = document.getElementById(id);
    if (el) el.value = value || '';
  });
};

// Fungsi util global untuk format tanggal ke YYYY-MM-DD
function formatToDateInput(val) {
  if (!val) return '';

  let date;

  // Jika val sudah Date object
  if (val instanceof Date) {
    date = val;
  } else {
    // Coba parse dari string
    date = new Date(val);
    if (isNaN(date.getTime())) return ''; // invalid date
  }

  const year = date.getFullYear();
  const month = `${date.getMonth() + 1}`.padStart(2, '0');
  const day = `${date.getDate()}`.padStart(2, '0');
  return `${year}-${month}-${day}`;
}

window.initModalList = initModalList;
