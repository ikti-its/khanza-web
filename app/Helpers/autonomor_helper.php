<?php

// AutoNomor untuk Nomor Rekam Medis (Saat ini dipake di masterpasien dan kelahiranbayi)
if (!function_exists('generateNextNoRM')) {
    /**
     * Generate nomor rekam medis berikutnya berdasarkan input terakhir.
     * Contoh: RM000137 → RM000138
     */
    function generateNextNoRM(?string $lastNo): string
    {
        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^RM(\d{6})$/', $lastNo, $match)) {
            return 'RM000001';
        }

        $lastNum = (int) $match[1];
        $nextNum = $lastNum + 1;

        return 'RM' . str_pad((string) $nextNum, 6, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No. Registrasi
if (!function_exists('generateNextNoRegistrasi')) {
    /**
     * Generate nomor registrasi berikutnya.
     * Format: REG-YYYYMMDDXXXX
     * Contoh: REG-202606210001
     *
     * @param string|null $lastNo  Nomor terakhir pada tanggal yang sama (dari DB)
     * @param string|null $tanggal Tanggal registrasi format 'Y-m-d', default hari ini
     */
    function generateNextNoRegistrasi(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'REG-' . date('Ymd', $tgl);

        /** @var list<bool> $match */
        $match = [];
        if (!$lastNo || !preg_match('/^REG-' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No. Rawat
if (!function_exists('generateNextNoRawat')) {
    /**
     * Generate nomor rawat berikutnya.
     * Format: RW-XXX
     * Contoh: RW-001
     *
     * @param string|null $lastNo Nomor rawat terakhir (dari DB)
     */
    function generateNextNoRawat(?string $lastNo): string
    {
        /** @var list<bool> $match */
        $match = [];
        if (!$lastNo || !preg_match('/^RW-(\d{3})$/', $lastNo, $match)) {
            return 'RW-001';
        }

        $nextNum = (int) $match[1] + 1;

        return 'RW-' . str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.Permintaan (Saat ini dipake di fitur Permintaan Barang)
if (!function_exists('generateNextNoPermintaanBarang')) {
    /** Generate nomor permintaan barang berikutnya.
     * Format: PRMYYYYMMDDXXXX
     * Contoh: PRM202506070001
     */
    function generateNextNoPermintaanBarang(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'PRM' . date('Ymd', $tgl);

        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^PRM' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.Pengajuan (Saat ini dipake di fitur Pengajuan Barang)
if (!function_exists('generateNextNoPengajuanBarang')) {
    /** Generate nomor pengajuan barang berikutnya.
     * Format: PGJYYYYMMDDXXXX
     * Contoh: PGJ202506070001
     */
    function generateNextNoPengajuanBarang(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'PGJ' . date('Ymd', $tgl);

        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^PGJ' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.Pengadaan (Saat ini dipake di fitur Pengadaan Barang)
if (!function_exists('generateNextNoPengadaanBarang')) {
    /** Generate nomor pengadaan barang berikutnya.
     * Format: PGDYYYYMMDDXXXX
     * Contoh: PGD202506070001
     */
    function generateNextNoPengadaanBarang(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'PGD' . date('Ymd', $tgl);

        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^PGD' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.Penerimaan (Saat ini dipake di fitur Penerimaan Barang)
if (!function_exists('generateNextNoPenerimaanBarang')) {
    /** Generate nomor penerimaan barang berikutnya.
     * Format: PNRYYYYMMDDXXXX
     * Contoh: PNR202506070001
     */
    function generateNextNoPenerimaanBarang(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'PNR' . date('Ymd', $tgl);

        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^PNR' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.Keluar (Saat ini dipake di fitur Ringkasan Permintaan Barang)
if (!function_exists('generateNextNoKeluarBarang')) {
    /** Generate nomor keluar stok non medis berikutnya.
     * Format: SKYYYYMMDDXXXX
     * Contoh: SK202506070001
     */
    function generateNextNoKeluarBarang(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'SK' . date('Ymd', $tgl);

        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^SK' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.Masuk (Saat ini dipake di fitur Penerimaan Barang)
if (!function_exists('generateNextNoMasukBarang')) {
    /** Generate nomor masuk stok non medis berikutnya.
     * Format: SMYYYYMMDDXXXX
     * Contoh: SM202506070001
     */
    function generateNextNoMasukBarang(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'SM' . date('Ymd', $tgl);

        /** @var list<bool> */
        $match = [];
        if (!$lastNo || !preg_match('/^SM' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No.SKL Kelahiran Bayi
if (!function_exists('generateNextSKL')) {
    /** Generate No. SKL dengan format: 0001/RM-SKL/07/2025 */
    function generateNextSKL(?string $lastSKL, string $tgl_lahir): string
    {
        $tanggal = strtotime($tgl_lahir);
        assert(is_int($tanggal));
        $bulan = date('m', $tanggal);
        $tahun = date('Y', $tanggal);

        /** @var list<bool> */
        $match = [];
        if (!$lastSKL || !preg_match('/^(\d{4})\/RM-SKL\/\d{2}\/\d{4}$/', $lastSKL, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        $nomorStr = str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
        return "{$nomorStr}/RM-SKL/{$bulan}/{$tahun}";
    }
}

// AutoNomor untuk No. Permintaan Radiologi
if (!function_exists('generateNextNoPermintaanRad')) {
    /**
     * Generate nomor permintaan radiologi berikutnya.
     * Format: RADYYYYMMDDXXXX
     * Contoh: RAD202606070001
     *
     * @param string|null $lastNo  Nomor terakhir pada tanggal yang sama (dari DB)
     * @param string|null $tanggal Tanggal permintaan format 'Y-m-d', default hari ini
     */
    function generateNextNoPermintaanRad(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'RAD' . date('Ymd', $tgl);

        /** @var list<bool> $match */
        $match = [];
        if (!$lastNo || !preg_match('/^RAD' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }

        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

// AutoNomor untuk No. Permintaan Laboratorium
// Laboratorium — Patologi Anatomi (PA)
if (!function_exists('generateNextNoPermintaanPk')) {
    /**
     * Generate nomor permintaan lab Patologi Klinik berikutnya.
     * Format: PKYYYYMMDDXXXX
     * Contoh: PK202606070001
     *
     * @param string|null $lastNo  Nomor terakhir pada tanggal yang sama (dari DB)
     * @param string|null $tanggal Tanggal permintaan format 'Y-m-d', default hari ini
     */
    function generateNextNoPermintaanPk(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'PK' . date('Ymd', $tgl);
 
        /** @var list<bool> $match */
        $match = [];
        if (!$lastNo || !preg_match('/^PK' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }
 
        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}
 
// Laboratorium — Patologi Anatomi (PA)
if (!function_exists('generateNextNoPermintaanPa')) {
    /**
     * Generate nomor permintaan lab Patologi Anatomi berikutnya.
     * Format: PAYYYYMMDDXXXX
     * Contoh: PA202606070001
     *
     * @param string|null $lastNo  Nomor terakhir pada tanggal yang sama (dari DB)
     * @param string|null $tanggal Tanggal permintaan format 'Y-m-d', default hari ini
     */
    function generateNextNoPermintaanPa(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'PA' . date('Ymd', $tgl);
 
        /** @var list<bool> $match */
        $match = [];
        if (!$lastNo || !preg_match('/^PA' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }
 
        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}
 
// Laboratorium — Mikrobiologi (MB)
if (!function_exists('generateNextNoPermintaanMb')) {
    /**
     * Generate nomor permintaan lab Mikrobiologi berikutnya.
     * Format: MBYYYYMMDDXXXX
     * Contoh: MB202606070001
     *
     * @param string|null $lastNo  Nomor terakhir pada tanggal yang sama (dari DB)
     * @param string|null $tanggal Tanggal permintaan format 'Y-m-d', default hari ini
     */
    function generateNextNoPermintaanMb(?string $lastNo, ?string $tanggal = null): string
    {
        $tgl    = $tanggal ? strtotime($tanggal) : time();
        assert(is_int($tgl));
        $prefix = 'MB' . date('Ymd', $tgl);
 
        /** @var list<bool> $match */
        $match = [];
        if (!$lastNo || !preg_match('/^MB' . date('Ymd', $tgl) . '(\d{4})$/', $lastNo, $match)) {
            $nomor = 1;
        } else {
            $nomor = (int) $match[1] + 1;
        }
 
        return $prefix . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}
