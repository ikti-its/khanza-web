<?php
declare(strict_types=1);

/**
 * Tracking Helper — Progress tracking end-to-end untuk Permintaan Barang.
 *
 * Alur Barang Baru (procurement):
 *   ① Permintaan → ② Persetujuan → ③ Pengajuan → ④ Pengadaan → ⑤ Penerimaan → ⑥ Pengeluaran
 *
 * Alur Barang Existing (direct):
 *   ① Permintaan → ② Persetujuan (langsung stok keluar)
 */

if (!function_exists('get_permintaan_tracking')) {
    /**
     * @return array{
     *   scenario: 'direct'|'procurement',
     *   steps: list<array{key:string, label:string, status:string, status_label:string, date:?string, pic:?string}>,
     *   progress_label: string,
     *   progress_color: string,
     * }
     */
    function get_permintaan_tracking(int $id_permintaan): array
    {
        $config             = (new \Config\Database())->default;
        $config['database'] = env('database.default.khanza_db');
        $db                 = \Config\Database::connect($config);

        // === Data Permintaan ===
        $permintaan = $db->table('inventori_non_medis.permintaan_barang pb')
            ->join('role.petugas pt', 'pb.petugas = pt.id_petugas', 'left')
            ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
            ->join('role.petugas pg', 'pb.petugas_gudang = pg.id_petugas', 'left')
            ->join('person.orang og', 'pg.id_orang = og.id_orang', 'left')
            ->select('pb.*, o.nama AS nama_pemohon, og.nama AS nama_pengelola')
            ->where('pb.id_permintaan', $id_permintaan)
            ->get()->getRowArray();

        if (empty($permintaan)) {
            return ['scenario' => 'direct', 'steps' => [], 'progress_label' => '-', 'progress_color' => 'gray'];
        }

        $st = (int) ($permintaan['id_status_permintaan_barang'] ?? 0);

        // === Cari Pengajuan linked ===
        $pengajuan = $db->table('inventori_non_medis.pengajuan_barang pj')
            ->join('role.petugas pt', 'pj.atasan_logistik = pt.id_petugas', 'left')
            ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
            ->select('pj.id_pengajuan, pj.no_pengajuan, pj.tanggal, pj.id_status_pengajuan_barang, pj.tanggal_diproses, o.nama AS nama_atasan')
            ->where('pj.id_permintaan', $id_permintaan)
            ->orderBy('pj.id_pengajuan', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        // === Cari Pengadaan dari Pengajuan ===
        $pengadaan = null;
        if (!empty($pengajuan)) {
            $pengadaan = $db->table('inventori_non_medis.pengadaan_barang pd')
                ->join('inventori_non_medis.suplier s', 'pd.id_suplier = s.id_suplier', 'left')
                ->select('pd.id_pengadaan, pd.no_pengadaan, pd.tanggal, pd.id_status_pengadaan_barang, s.nama_suplier')
                ->where('pd.id_pengajuan', (int) $pengajuan['id_pengajuan'])
                ->orderBy('pd.id_pengadaan', 'DESC')
                ->limit(1)
                ->get()->getRowArray();
        }

        // === Cari Penerimaan dari Pengadaan ===
        $penerimaan = null;
        $persen_terima = 0;
        if (!empty($pengadaan)) {
            $penerimaan = $db->table('inventori_non_medis.penerimaan_barang')
                ->select('id_penerimaan, no_penerimaan, tanggal, id_status_penerimaan_barang')
                ->where('id_pengadaan', (int) $pengadaan['id_pengadaan'])
                ->where('id_status_penerimaan_barang', 2)
                ->orderBy('id_penerimaan', 'DESC')
                ->limit(1)
                ->get()->getRowArray();

            // Jika belum ada yang Diterima, ambil yang terbaru
            if (empty($penerimaan)) {
                $penerimaan = $db->table('inventori_non_medis.penerimaan_barang')
                    ->select('id_penerimaan, no_penerimaan, tanggal, id_status_penerimaan_barang')
                    ->where('id_pengadaan', (int) $pengadaan['id_pengadaan'])
                    ->orderBy('id_penerimaan', 'DESC')
                    ->limit(1)
                    ->get()->getRowArray();
            }

            // Hitung % penerimaan
            $total_pesan = (int) ($db->table('inventori_non_medis.pengadaan_barang_detail')
                ->selectSum('qty', 'total')
                ->where('id_pengadaan', (int) $pengadaan['id_pengadaan'])
                ->where('id_barang >', 0)
                ->get()->getRowArray()['total'] ?? 0);

            $total_terima = (int) ($db->query("
                SELECT COALESCE(SUM(d.qty_diterima), 0) AS total
                FROM inventori_non_medis.penerimaan_barang_detail d
                JOIN inventori_non_medis.penerimaan_barang p ON d.id_penerimaan = p.id_penerimaan
                WHERE p.id_pengadaan = ? AND p.id_status_penerimaan_barang = 2
            ", [(int) $pengadaan['id_pengadaan']])->getRowArray()['total'] ?? 0);

            $persen_terima = $total_pesan > 0 ? min(100, (int) round(($total_terima / $total_pesan) * 100)) : 0;
        }

        // === Tentukan Skenario ===
        $is_procurement = !empty($pengajuan);
        $scenario = $is_procurement ? 'procurement' : 'direct';

        // === Build Steps ===
        $steps = [];

        if ($scenario === 'direct') {
            // ① Permintaan → ② Persetujuan (langsung selesai)
            $steps[] = _step_permintaan_direct($permintaan, $st);
            $steps[] = _step_persetujuan_direct($permintaan, $st);
        } else {
            // ① Permintaan → ② Persetujuan → ③ Pengajuan → ④ Pengadaan → ⑤ Penerimaan → ⑥ Pengeluaran
            $steps[] = _step_permintaan_proc($permintaan, $st);
            $steps[] = _step_persetujuan_proc($permintaan, $st);
            $steps[] = _step_pengajuan($pengajuan, $st);
            $steps[] = _step_pengadaan($pengadaan, $pengajuan, $st);
            $steps[] = _step_penerimaan($penerimaan, $persen_terima, $pengadaan, $st);
            $steps[] = _step_pengeluaran($permintaan, $st);
        }

        // === Progress label & color ===
        [$progress_label, $progress_color] = _determine_progress($st, $scenario, $pengajuan, $pengadaan, $penerimaan, $persen_terima);

        return compact('scenario', 'steps', 'progress_label', 'progress_color');
    }
}

// ===========================
// DIRECT SCENARIO STEPS
// ===========================

if (!function_exists('_step_permintaan_direct')) {
    function _step_permintaan_direct(array $p, int $st): array
    {
        if ($st === 1) return _s('Permintaan', 'active', 'Draft', $p['tanggal'], $p['nama_pemohon']);
        if ($st === 4) return _s('Permintaan', 'active', 'Menunggu Persetujuan', $p['tanggal'], $p['nama_pemohon']);
        if ($st === 3) return _s('Permintaan', 'failed', 'Ditolak', $p['tanggal'], $p['nama_pemohon']);
        return _s('Permintaan', 'done', 'Diajukan', $p['tanggal'], $p['nama_pemohon']);
    }
}

if (!function_exists('_step_persetujuan_direct')) {
    function _step_persetujuan_direct(array $p, int $st): array
    {
        if (in_array($st, [2, 6])) return _s('Persetujuan', 'done', 'Disetujui & Stok Keluar', $p['tanggal_diproses'], $p['nama_pengelola']);
        if ($st === 3) return _s('Persetujuan', 'failed', 'Ditolak', $p['tanggal_diproses'], $p['nama_pengelola']);
        if ($st === 4) return _s('Persetujuan', 'active', 'Menunggu', null, null);
        return _s('Persetujuan', 'waiting', 'Menunggu', null, null);
    }
}

// ===========================
// PROCUREMENT SCENARIO STEPS
// ===========================

if (!function_exists('_step_permintaan_proc')) {
    function _step_permintaan_proc(array $p, int $st): array
    {
        if ($st === 1) return _s('Permintaan', 'active', 'Draft', $p['tanggal'], $p['nama_pemohon']);
        if ($st === 4) return _s('Permintaan', 'active', 'Menunggu Persetujuan', $p['tanggal'], $p['nama_pemohon']);
        return _s('Permintaan', 'done', 'Diajukan', $p['tanggal'], $p['nama_pemohon']);
    }
}

if (!function_exists('_step_persetujuan_proc')) {
    function _step_persetujuan_proc(array $p, int $st): array
    {
        if (in_array($st, [5, 6])) return _s('Persetujuan', 'done', 'Disetujui', $p['tanggal_diproses'], $p['nama_pengelola']);
        if ($st === 3) return _s('Persetujuan', 'failed', 'Ditolak', $p['tanggal_diproses'], $p['nama_pengelola']);
        if ($st === 4) return _s('Persetujuan', 'active', 'Menunggu', null, null);
        return _s('Persetujuan', 'waiting', 'Menunggu', null, null);
    }
}

if (!function_exists('_step_pengajuan')) {
    function _step_pengajuan(?array $pj, int $st): array
    {
        if (empty($pj)) return _s('Pengajuan', 'waiting', 'Menunggu', null, null);

        $link = '/inventori-non-medis/pengajuan-barang/' . (int) $pj['id_pengajuan'];
        $s_pj = (int) ($pj['id_status_pengajuan_barang'] ?? 0);
        if ($s_pj === 2) return _s('Pengajuan', 'done', 'Disetujui Atasan', $pj['tanggal_diproses'] ?? $pj['tanggal'], $pj['nama_atasan'], $link);
        if ($s_pj === 3) return _s('Pengajuan', 'failed', 'Ditolak Atasan', $pj['tanggal_diproses'] ?? $pj['tanggal'], $pj['nama_atasan'], $link);
        if ($s_pj === 4) return _s('Pengajuan', 'active', 'Menunggu Persetujuan Atasan', $pj['tanggal'], null, $link);
        return _s('Pengajuan', 'active', 'Diproses', $pj['tanggal'], null, $link);
    }
}

if (!function_exists('_step_pengadaan')) {
    function _step_pengadaan(?array $pd, ?array $pj, int $st): array
    {
        if (empty($pd)) {
            // Pengajuan sudah disetujui tapi belum ada pengadaan
            $pj_done = !empty($pj) && (int) ($pj['id_status_pengajuan_barang'] ?? 0) === 2;
            if ($pj_done) return _s('Pengadaan', 'active', 'Menunggu PO Dibuat', null, null);
            return _s('Pengadaan', 'waiting', 'Menunggu', null, null);
        }

        $link = '/inventori-non-medis/pengadaan-barang/' . (int) $pd['id_pengadaan'];
        $s_pd = (int) ($pd['id_status_pengadaan_barang'] ?? 0);
        if ($s_pd === 2) return _s('Pengadaan', 'done', 'Selesai (Dipesan)', $pd['tanggal'], $pd['nama_suplier'], $link);
        if ($s_pd === 3) return _s('Pengadaan', 'failed', 'Dibatalkan', $pd['tanggal'], $pd['nama_suplier'], $link);
        return _s('Pengadaan', 'active', 'Pembelian ke Suplier', $pd['tanggal'], $pd['nama_suplier'], $link);
    }
}

if (!function_exists('_step_penerimaan')) {
    function _step_penerimaan(?array $pn, int $persen, ?array $pd, int $st): array
    {
        if (empty($pn)) {
            $pd_active = !empty($pd) && in_array((int) ($pd['id_status_pengadaan_barang'] ?? 0), [1, 2]);
            if ($pd_active) return _s('Penerimaan', 'active', 'Menunggu Kiriman', null, null);
            return _s('Penerimaan', 'waiting', 'Menunggu', null, null);
        }

        $link = '/inventori-non-medis/penerimaan-barang/' . (int) $pn['id_penerimaan'];
        $s_pn = (int) ($pn['id_status_penerimaan_barang'] ?? 0);
        if ($s_pn === 2) {
            if ($persen >= 100 || $st === 6) return _s('Penerimaan', 'done', 'Diterima Lengkap', $pn['tanggal'], null, $link);
            return _s('Penerimaan', 'active', "Diterima {$persen}%", $pn['tanggal'], null, $link);
        }
        if ($s_pn === 3) return _s('Penerimaan', 'failed', 'Ditolak', $pn['tanggal'], null, $link);
        return _s('Penerimaan', 'active', 'Sedang Diperiksa', $pn['tanggal'], null, $link);
    }
}

if (!function_exists('_step_pengeluaran')) {
    function _step_pengeluaran(array $p, int $st): array
    {
        if ($st === 6) return _s('Pengeluaran', 'done', 'Stok Keluar', $p['tanggal_diproses'], $p['nama_pengelola']);
        return _s('Pengeluaran', 'waiting', 'Menunggu', null, null);
    }
}

// ===========================
// HELPERS
// ===========================

if (!function_exists('_s')) {
    function _s(string $label, string $status, string $status_label, ?string $date, ?string $pic, ?string $link = null): array
    {
        return compact('label', 'status', 'status_label', 'date', 'pic', 'link');
    }
}

if (!function_exists('_determine_progress')) {
    /** @return array{0:string, 1:string} */
    function _determine_progress(int $st, string $scenario, ?array $pj, ?array $pd, ?array $pn, int $persen): array
    {
        if ($st === 6) return ['Selesai', 'green'];
        if ($st === 3) return ['Ditolak', 'red'];
        if ($st === 1) return ['Draft', 'gray'];
        if ($st === 4) return ['Menunggu Persetujuan', 'yellow'];
        if ($st === 2 && $scenario === 'direct') return ['Selesai', 'green'];

        // Status 5 — trace hilir
        if (!empty($pn) && (int) ($pn['id_status_penerimaan_barang'] ?? 0) === 2) {
            if ($persen >= 100) return ['Selesai', 'green'];
            return ["Diterima {$persen}%", 'blue'];
        }
        if (!empty($pn) && (int) ($pn['id_status_penerimaan_barang'] ?? 0) === 1) {
            return ['Barang Sedang Diperiksa', 'blue'];
        }
        if (!empty($pd)) {
            $s_pd = (int) ($pd['id_status_pengadaan_barang'] ?? 0);
            if ($s_pd === 3) return ['Pengadaan Dibatalkan', 'red'];
            if ($s_pd === 2) return ['Menunggu Kiriman', 'blue'];
            if ($s_pd === 1) return ['Pembelian Diproses', 'blue'];
        }
        if (!empty($pj)) {
            $s_pj = (int) ($pj['id_status_pengajuan_barang'] ?? 0);
            if ($s_pj === 3) return ['Pengajuan Ditolak', 'red'];
            if ($s_pj === 2) return ['Menunggu Pengadaan', 'yellow'];
            if ($s_pj === 4) return ['Pengajuan Diproses', 'yellow'];
        }

        return ['Menunggu Pengadaan', 'yellow'];
    }
}

if (!function_exists('get_progress_badge_html')) {
    function get_progress_badge_html(string $label, string $color): string
    {
        $colors = [
            'green'  => 'background-color:#D1FAE5; color:#065F46;',
            'red'    => 'background-color:#FEE2E2; color:#991B1B;',
            'yellow' => 'background-color:#FEF3C7; color:#92400E;',
            'blue'   => 'background-color:#DBEAFE; color:#1E40AF;',
            'gray'   => 'background-color:#F3F4F6; color:#374151;',
        ];
        $style = $colors[$color] ?? $colors['gray'];
        return '<span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:9999px; font-size:12px; font-weight:600; ' . $style . '">' . esc($label) . '</span>';
    }
}


// ===========================
// TRACKING PENGAJUAN BARANG
// ===========================

if (!function_exists('get_pengajuan_tracking')) {
    /**
     * Progress tracking untuk Pengajuan Barang.
     * Alur: ① Pengajuan → ② Pengadaan → ③ Penerimaan
     */
    function get_pengajuan_tracking(int $id_pengajuan): array
    {
        $config             = (new \Config\Database())->default;
        $config['database'] = env('database.default.khanza_db');
        $db                 = \Config\Database::connect($config);

        // Data pengajuan
        $pengajuan = $db->table('inventori_non_medis.pengajuan_barang pj')
            ->join('role.petugas pt', 'pj.atasan_logistik = pt.id_petugas', 'left')
            ->join('person.orang o', 'pt.id_orang = o.id_orang', 'left')
            ->join('role.petugas pg', 'pj.petugas_gudang = pg.id_petugas', 'left')
            ->join('person.orang og', 'pg.id_orang = og.id_orang', 'left')
            ->select('pj.*, o.nama AS nama_atasan, og.nama AS nama_pemohon')
            ->where('pj.id_pengajuan', $id_pengajuan)
            ->get()->getRowArray();

        if (empty($pengajuan)) {
            return ['scenario' => 'pengajuan', 'steps' => [], 'progress_label' => '-', 'progress_color' => 'gray'];
        }

        $st_pj = (int) ($pengajuan['id_status_pengajuan_barang'] ?? 0);

        // Cari pengadaan
        $pengadaan = $db->table('inventori_non_medis.pengadaan_barang pd')
            ->join('inventori_non_medis.suplier s', 'pd.id_suplier = s.id_suplier', 'left')
            ->select('pd.id_pengadaan, pd.no_pengadaan, pd.tanggal, pd.id_status_pengadaan_barang, s.nama_suplier')
            ->where('pd.id_pengajuan', $id_pengajuan)
            ->orderBy('pd.id_pengadaan', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        // Cari penerimaan
        $penerimaan = null;
        $persen_terima = 0;
        if (!empty($pengadaan)) {
            $penerimaan = $db->table('inventori_non_medis.penerimaan_barang')
                ->select('id_penerimaan, no_penerimaan, tanggal, id_status_penerimaan_barang')
                ->where('id_pengadaan', (int) $pengadaan['id_pengadaan'])
                ->where('id_status_penerimaan_barang', 2)
                ->orderBy('id_penerimaan', 'DESC')
                ->limit(1)
                ->get()->getRowArray();

            if (empty($penerimaan)) {
                $penerimaan = $db->table('inventori_non_medis.penerimaan_barang')
                    ->select('id_penerimaan, no_penerimaan, tanggal, id_status_penerimaan_barang')
                    ->where('id_pengadaan', (int) $pengadaan['id_pengadaan'])
                    ->orderBy('id_penerimaan', 'DESC')
                    ->limit(1)
                    ->get()->getRowArray();
            }

            $total_pesan = (int) ($db->table('inventori_non_medis.pengadaan_barang_detail')
                ->selectSum('qty', 'total')
                ->where('id_pengadaan', (int) $pengadaan['id_pengadaan'])
                ->where('id_barang >', 0)
                ->get()->getRowArray()['total'] ?? 0);

            $total_terima = (int) ($db->query("
                SELECT COALESCE(SUM(d.qty_diterima), 0) AS total
                FROM inventori_non_medis.penerimaan_barang_detail d
                JOIN inventori_non_medis.penerimaan_barang p ON d.id_penerimaan = p.id_penerimaan
                WHERE p.id_pengadaan = ? AND p.id_status_penerimaan_barang = 2
            ", [(int) $pengadaan['id_pengadaan']])->getRowArray()['total'] ?? 0);

            $persen_terima = $total_pesan > 0 ? min(100, (int) round(($total_terima / $total_pesan) * 100)) : 0;
        }

        // Build steps
        $steps = [];

        // ① Pengajuan
        if ($st_pj === 2) $steps[] = _s('Pengajuan', 'done', 'Disetujui', $pengajuan['tanggal_diproses'] ?? $pengajuan['tanggal'], $pengajuan['nama_atasan']);
        elseif ($st_pj === 3) $steps[] = _s('Pengajuan', 'failed', 'Ditolak', $pengajuan['tanggal_diproses'] ?? $pengajuan['tanggal'], $pengajuan['nama_atasan']);
        elseif ($st_pj === 4) $steps[] = _s('Pengajuan', 'active', 'Menunggu Persetujuan', $pengajuan['tanggal'], $pengajuan['nama_pemohon']);
        else $steps[] = _s('Pengajuan', 'active', 'Draft', $pengajuan['tanggal'], $pengajuan['nama_pemohon']);

        // ② Pengadaan
        if (empty($pengadaan)) {
            $steps[] = _s('Pengadaan', $st_pj === 2 ? 'active' : 'waiting', $st_pj === 2 ? 'Menunggu PO Dibuat' : 'Menunggu', null, null);
        } else {
            $link_pd = '/inventori-non-medis/pengadaan-barang/' . (int) $pengadaan['id_pengadaan'];
            $s_pd    = (int) ($pengadaan['id_status_pengadaan_barang'] ?? 0);
            if ($s_pd === 2) $steps[] = _s('Pengadaan', 'done', 'Selesai (Dipesan)', $pengadaan['tanggal'], $pengadaan['nama_suplier'], $link_pd);
            elseif ($s_pd === 3) $steps[] = _s('Pengadaan', 'failed', 'Dibatalkan', $pengadaan['tanggal'], $pengadaan['nama_suplier'], $link_pd);
            else $steps[] = _s('Pengadaan', 'active', 'Pembelian ke Suplier', $pengadaan['tanggal'], $pengadaan['nama_suplier'], $link_pd);
        }

        // ③ Penerimaan
        if (empty($penerimaan)) {
            $pd_active = !empty($pengadaan) && in_array((int) ($pengadaan['id_status_pengadaan_barang'] ?? 0), [1, 2]);
            $steps[] = _s('Penerimaan', $pd_active ? 'active' : 'waiting', $pd_active ? 'Menunggu Kiriman' : 'Menunggu', null, null);
        } else {
            $link_pn = '/inventori-non-medis/penerimaan-barang/' . (int) $penerimaan['id_penerimaan'];
            $s_pn    = (int) ($penerimaan['id_status_penerimaan_barang'] ?? 0);
            if ($s_pn === 2) {
                $steps[] = _s('Penerimaan', $persen_terima >= 100 ? 'done' : 'active', $persen_terima >= 100 ? 'Diterima Lengkap' : "Diterima {$persen_terima}%", $penerimaan['tanggal'], null, $link_pn);
            } elseif ($s_pn === 3) {
                $steps[] = _s('Penerimaan', 'failed', 'Ditolak', $penerimaan['tanggal'], null, $link_pn);
            } else {
                $steps[] = _s('Penerimaan', 'active', 'Sedang Diperiksa', $penerimaan['tanggal'], null, $link_pn);
            }
        }

        // Progress label
        if ($st_pj === 3) { $progress_label = 'Ditolak'; $progress_color = 'red'; }
        elseif ($st_pj === 4) { $progress_label = 'Menunggu Persetujuan'; $progress_color = 'yellow'; }
        elseif (!empty($penerimaan) && (int) ($penerimaan['id_status_penerimaan_barang'] ?? 0) === 2 && $persen_terima >= 100) { $progress_label = 'Selesai'; $progress_color = 'green'; }
        elseif (!empty($penerimaan) && (int) ($penerimaan['id_status_penerimaan_barang'] ?? 0) === 2) { $progress_label = "Diterima {$persen_terima}%"; $progress_color = 'blue'; }
        elseif (!empty($pengadaan) && (int) ($pengadaan['id_status_pengadaan_barang'] ?? 0) === 3) { $progress_label = 'Pengadaan Dibatalkan'; $progress_color = 'red'; }
        elseif (!empty($pengadaan)) { $progress_label = 'Pembelian Diproses'; $progress_color = 'blue'; }
        elseif ($st_pj === 2) { $progress_label = 'Menunggu Pengadaan'; $progress_color = 'yellow'; }
        else { $progress_label = 'Diproses'; $progress_color = 'yellow'; }

        return compact('steps', 'progress_label', 'progress_color') + ['scenario' => 'pengajuan'];
    }
}


// ===========================
// TRACKING PENERIMAAN BARANG
// ===========================

if (!function_exists('get_penerimaan_tracking')) {
    /**
     * Progress tracking untuk Penerimaan Barang.
     * Alur: ① Penerimaan → ② Stok Masuk
     */
    function get_penerimaan_tracking(int $id_penerimaan): array
    {
        $config             = (new \Config\Database())->default;
        $config['database'] = env('database.default.khanza_db');
        $db                 = \Config\Database::connect($config);

        $penerimaan = $db->table('inventori_non_medis.penerimaan_barang p')
            ->join('inventori_non_medis.pengadaan_barang pd', 'p.id_pengadaan = pd.id_pengadaan', 'left')
            ->join('inventori_non_medis.suplier s', 'pd.id_suplier = s.id_suplier', 'left')
            ->select('p.*, pd.no_pengadaan, s.nama_suplier')
            ->where('p.id_penerimaan', $id_penerimaan)
            ->get()->getRowArray();

        if (empty($penerimaan)) {
            return ['scenario' => 'penerimaan', 'steps' => [], 'progress_label' => '-', 'progress_color' => 'gray'];
        }

        $s_pn = (int) ($penerimaan['id_status_penerimaan_barang'] ?? 0);

        $steps = [];

        // ① Penerimaan
        if ($s_pn === 2) $steps[] = _s('Penerimaan', 'done', 'Diterima', $penerimaan['tanggal'], $penerimaan['nama_suplier']);
        elseif ($s_pn === 3) $steps[] = _s('Penerimaan', 'failed', 'Ditolak', $penerimaan['tanggal'], null);
        else $steps[] = _s('Penerimaan', 'active', 'Sedang Diperiksa', $penerimaan['tanggal'], $penerimaan['nama_suplier']);

        // ② Stok Masuk
        if ($s_pn === 2 && !empty($penerimaan['no_masuk'])) {
            $steps[] = _s('Stok Masuk', 'done', 'Tercatat', $penerimaan['tanggal'], $penerimaan['no_masuk']);
        } elseif ($s_pn === 3) {
            $steps[] = _s('Stok Masuk', 'failed', 'Dibatalkan', null, null);
        } else {
            $steps[] = _s('Stok Masuk', 'waiting', 'Menunggu', null, null);
        }

        // Progress
        if ($s_pn === 2) { $pl = 'Selesai'; $pc = 'green'; }
        elseif ($s_pn === 3) { $pl = 'Ditolak'; $pc = 'red'; }
        else { $pl = 'Sedang Diperiksa'; $pc = 'yellow'; }

        return ['scenario' => 'penerimaan', 'steps' => $steps, 'progress_label' => $pl, 'progress_color' => $pc];
    }
}
