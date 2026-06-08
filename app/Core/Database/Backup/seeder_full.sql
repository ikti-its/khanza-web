COPY sik.alasan_cuti (id, nama, created_at, updated_at) FROM stdin;
S	Sakit	2025-05-15 23:07:10.313595+07	2025-05-15 23:07:10.313595+07
I	Izin	2025-05-15 23:07:10.313595+07	2025-05-15 23:07:10.313595+07
CT	Cuti Tahunan	2025-05-15 23:07:10.313595+07	2025-05-15 23:07:10.313595+07
CB	Cuti Besar	2025-05-15 23:07:10.313595+07	2025-05-15 23:07:10.313595+07
CM	Cuti Melahirkan	2025-05-15 23:07:10.313595+07	2025-05-15 23:07:10.313595+07
CU	Cuti karena Alasan Penting	2025-05-15 23:07:10.313595+07	2025-05-15 23:07:10.313595+07
\.

COPY sik.departemen (id, nama, created_at, updated_at) FROM stdin;
2	Petugas	2025-05-15 18:33:07.921616+07	2025-05-15 18:33:07.921616+07
3	Dokter	2025-05-15 18:33:15.989844+07	2025-05-15 18:33:15.989844+07
1	Admin	2025-05-15 20:00:58.872706+07	2025-05-15 20:00:58.872706+07
1000	Testing	2025-05-20 15:23:03.025262+07	2025-05-20 15:23:03.025262+07
\.

COPY ref.hari (id, nama, created_at, updated_at) FROM stdin;
1	Senin	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
2	Selasa	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
3	Rabu	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
4	Kamis	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
5	Jumat	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
6	Sabtu	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
7	Minggu	2025-05-15 20:05:08.34462+07	2025-05-15 20:05:08.34462+07
\.

COPY ref.jabatan (id, nama, created_at, updated_at) FROM stdin;
2	Petugas	2025-05-15 18:31:32.497043+07	2025-05-15 18:31:32.497043+07
3	Dokter	2025-05-15 18:31:43.12515+07	2025-05-15 18:31:43.12515+07
1	Admin	2025-05-15 20:00:48.601557+07	2025-05-15 20:00:48.601557+07
1000	Testing	2025-05-20 15:23:30.917849+07	2025-05-20 15:23:30.917849+07
\.

COPY ref.organisasi (id, nama, alamat, latitude, longitude, radius, created_at, updated_at) FROM stdin;
2e8ecec0-5f4d-4dbe-a74d-0f014718b68d	Apartemen Puncak Kertajaya	apartemen puncak kertajaya, Kertajaya Indah Regency, Keputih, Kec. Sukolilo, Surabaya, Jawa Timur 60111	-7.288022143772514	112.78670386466561	50	2025-05-24 19:39:07.031719+07	2025-05-24 19:39:07.031719+07
\.

COPY sik.akun (id, email, password, foto, role, created_at, updated_at, deleted_at, updater) FROM stdin;
83e0f2ba-9b19-439e-a448-97338eea7ff8	admin123@fathoor.dev	$2a$10$LcvwwjdpGUz3LmKLOa9Yy.Oqs0DAsquurYYMAWA8n0eO3uPX0ibrW	/img/default.png	1	2025-03-17 20:35:20.555048+07	2025-03-17 20:35:20.555048+07	\N	\N
933568d5-982a-43c3-a4aa-3177bab10f07	eric@fathoor.dev	$2a$12$GmKnyhdJVTC424cvJFgiC.fiQjFm18IN587OIq/puCLg5ab1abnEm	/img/default.png	2	2025-05-15 18:28:32.325395+07	2025-05-15 18:28:32.325395+07	\N	933568d5-982a-43c3-a4aa-3177bab10f07
9de502cb-2cd5-46cb-a717-97f2bb1f85c5	fathoor@fathoor.dev	$2a$10$Yg78XjsfHtvpiZHzxjhtBeauNRpK928c1zKSXjPdV.jOE0q21qXgq	/img/default.png	1337	2025-03-17 20:07:40.473102+07	2025-03-17 20:07:40.473102+07	\N	\N
b9b1ad6c-c41b-446a-b00e-f56684663c56	aziz@fathoor.dev	$2a$12$GmKnyhdJVTC424cvJFgiC.fiQjFm18IN587OIq/puCLg5ab1abnEm	/img/default.png	3	2025-05-15 18:28:32.325395+07	2025-05-15 18:28:32.325395+07	\N	b9b1ad6c-c41b-446a-b00e-f56684663c56
bd0b4833-510c-4c29-a3a4-e08e9a0a5955	admin@fathoor.dev	$2a$10$8jI.qKrVbXQjNzYX6KOIvukkYkNcmfYyPWiv9tuHE8vdg5EhjQBzy	/img/default.png	1	2025-03-17 20:07:40.473102+07	2025-03-17 20:07:40.473102+07	\N	bd0b4833-510c-4c29-a3a4-e08e9a0a5955
\.

COPY sik.alamat (id_akun, alamat, alamat_lat, alamat_lon, created_at, updated_at, deleted_at, updater) FROM stdin;
bd0b4833-510c-4c29-a3a4-e08e9a0a5955	Jl. Mawar No. 123, Surabaya	-7.257472	112.752088	2025-05-15 20:12:31.655792+07	2025-05-15 20:12:31.655792+07	\N	\N
933568d5-982a-43c3-a4aa-3177bab10f07	Jl. Kertajaya No. 5	-7.257472	112.752088	2025-05-19 20:18:04.722652+07	2025-05-19 20:18:04.722652+07	\N	\N
b9b1ad6c-c41b-446a-b00e-f56684663c56	Jl. Keputih Tegal Timur II	-7.257472	112.752088	2025-05-19 20:18:43.649155+07	2025-05-19 20:18:43.649155+07	\N	\N
\.

COPY sik.ambulans (no_ambulans, status, supir) FROM stdin;
AMB-003	accepted	Supri
AMB-005	available	agus
AMB-001	accepted	Sopir Uji
AMB-004	accepted	Supri
AMB-002	accepted	Supri
AMB-010	accepted	SupriABC
ABC	available	999
ABCDE	available	EDCBA
AMB-999	pending	ABCDEFGH
AMB-0999	available	123
TESTING	available	Supri
\.

COPY sik.barang_medis (id, kode_barang, kandungan, id_industri, nama, id_satbesar, id_satuan, h_dasar, h_beli, h_ralan, h_kelas1, h_kelas2, h_kelas3, h_utama, h_vip, h_vvip, h_beliluar, h_jualbebas, h_karyawan, stok_minimum, id_jenis, isi, kapasitas, kadaluwarsa, id_kategori, id_golongan) FROM stdin;
\.

COPY sik.berkas_pegawai (id_pegawai, nik, tempat_lahir, tanggal_lahir, agama, pendidikan, ktp, kk, npwp, bpjs, ijazah, skck, str, serkom, created_at, updated_at, deleted_at, updater) FROM stdin;
bd0b4833-510c-4c29-a3a4-e08e9a0a5955	3210112345678901	Jakarta	1995-01-01	Islam	S1	ktp_admin.pdf	kk_admin.pdf	npwp_admin.pdf	bpjs_admin.pdf	ijazah_admin.pdf	skck_admin.pdf	str_admin.pdf	serkom_admin.pdf	2025-05-19 22:27:04.917087+07	2025-05-19 22:27:04.917087+07	\N	bd0b4833-510c-4c29-a3a4-e08e9a0a5955
b9b1ad6c-c41b-446a-b00e-f56684663c56	3210223456789012	Bandung	1994-02-02	Kristen	S2	ktp_aziz.pdf	kk_aziz.pdf	npwp_aziz.pdf	bpjs_aziz.pdf	ijazah_aziz.pdf	skck_aziz.pdf	str_aziz.pdf	serkom_aziz.pdf	2025-05-19 22:27:04.917087+07	2025-05-19 22:27:04.917087+07	\N	b9b1ad6c-c41b-446a-b00e-f56684663c56
933568d5-982a-43c3-a4aa-3177bab10f07	3210334567890123	Surabaya	1993-03-03	Hindu	D3	ktp_eric.pdf	kk_eric.pdf	npwp_eric.pdf	bpjs_eric.pdf	ijazah_eric.pdf	skck_eric.pdf	str_eric.pdf	serkom_eric.pdf	2025-05-19 22:27:04.917087+07	2025-05-19 22:27:04.917087+07	\N	933568d5-982a-43c3-a4aa-3177bab10f07
\.

COPY sik.cuti (id, id_pegawai, tanggal_mulai, tanggal_selesai, id_alasan_cuti, status, created_at, updated_at, deleted_at, updater) FROM stdin;
cb38e8cb-cbbe-4708-b9fe-8fb2cb06d7b0	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	2025-05-16	2025-05-17	S	Ditolak	2025-05-15 23:07:43.915708+07	2025-06-01 17:25:17.032598+07	\N	bd0b4833-510c-4c29-a3a4-e08e9a0a5955
fda80261-207d-4afc-bc9f-e8cd0d49771e	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	2025-06-02	2025-06-03	CT	Diproses	2025-06-01 17:26:20.331472+07	2025-06-01 17:26:20.331472+07	\N	\N
\.

COPY sik.data_batch (no_batch, no_faktur, id_barang_medis, tanggal_datang, kadaluwarsa, asal, h_dasar, h_beli, h_ralan, h_kelas1, h_kelas2, h_kelas3, h_utama, h_vip, h_vvip, h_beliluar, h_jualbebas, h_karyawan, jumlahbeli, sisa) FROM stdin;
\.

COPY sik.data_phk (no_pegawai, lama_bekerja, pesangon, upmk, nominal, status) FROM stdin;
\.

COPY sik.data_thr (no_pegawai, lama_bekerja, tahun, nominal, status) FROM stdin;
\.

COPY sik.databarang (kode_brng, nama_brng, kode_satbesar, kode_sat, letak_barang, dasar, h_beli, ralan, kelas1, kelas2, kelas3, utama, vip, vvip, beliluar, jualbebas, karyawan, stokminimal, kdjns, isi, kapasitas, expire, status, kode_industri, kode_kategori, kode_golongan) FROM stdin;

VAK001	Vaksin Hepatitis B Recombinant 20 ug/1 mL Suspensi Injeksi (Umum)	-   	-   	-	1	1	1	1	1	1	1	1	1	1	1	1	0	-   	1	1	2023-01-23	1	-    	-   	-   
\.

COPY sik.detail_penerimaan_barang_medis (id_penerimaan, id_barang_medis, id_satuan, ubah_master, jumlah, h_pesan, subtotal_per_item, diskon_persen, diskon_jumlah, total_per_item, jumlah_diterima, kadaluwarsa, no_batch) FROM stdin;
\.

COPY sik.foto_pegawai (id_pegawai, foto, created_at, updated_at, deleted_at, updater) FROM stdin;
933568d5-982a-43c3-a4aa-3177bab10f07	/img/default.png	2025-05-19 20:20:06.601437+07	2025-05-19 20:20:06.601437+07	\N	\N
b9b1ad6c-c41b-446a-b00e-f56684663c56	/img/default.png	2025-05-19 20:20:06.601437+07	2025-05-19 20:20:06.601437+07	\N	\N
bd0b4833-510c-4c29-a3a4-e08e9a0a5955	/img/default.png	2025-05-15 20:14:12.663592+07	2025-05-15 20:14:12.663592+07	\N	\N
\.

COPY sik.golongan_barang_medis (id, nama, created_at, updated_at) FROM stdin;
1000	Analgesik	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
2000	Antibiotik	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
3000	Antijamur	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
4000	Antivirus	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
5000	Antasida	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.


COPY sik.industri_farmasi (id, kode, nama, alamat, kota, telepon, created_at, updated_at) FROM stdin;
1000	KLBF	Kalbe Farma	Jln. jalan	Jakarta	0812312312	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.jadwal_pegawai (id, id_pegawai, id_hari, id_shift, created_at, updated_at, deleted_at, updater) FROM stdin;
c951ca8b-9879-4188-810c-36f9924fd7c5	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	1	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
ef4471c6-6254-4895-97c9-a931d784659c	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	1	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
89cedec6-deb1-4f75-86c3-71c59b9200d6	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	1	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
318024fd-6f56-4f00-ac01-a4f08155535e	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	2	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
5412e5f7-0794-49ec-aa33-44e70d216561	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	2	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
33538ea0-c91a-4cd6-8e91-c2be83ef46a3	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	2	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
70a445de-6bbf-4066-9296-9749d9e32a1a	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	3	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
8b0f9ef6-56c3-4400-aaee-f12e6781fd4d	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	3	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
664c4863-22ce-495d-928a-87fe846937c2	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	3	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
9a845721-1247-4c6a-8c54-0c879a42c1db	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	4	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
fc6f1316-4288-4c3a-8023-25bfe6da15db	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	4	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
9e179bd9-58f5-40e6-b5d0-d850be2b7e6b	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	4	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
95a2604e-0f4e-4272-bea3-6d4c9f35dbd1	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	5	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
b8864871-ee39-463a-8d2f-0ba0ab69ecb9	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	5	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
18e1d494-956c-4064-8ee7-03068771eba1	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	5	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
6981e4a9-3fb9-4733-ae55-6527f167f6c2	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	6	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
17c55650-03ae-46c1-82d1-634c4c6a7ef8	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	6	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
ae26c35d-1e64-4680-9b28-9b71fc449a23	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	6	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
25a9fd65-d6ba-430d-9631-e1d924e8a10f	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	7	1	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
eadce2bb-55ae-45ff-884a-2b374c0e2dd6	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	7	2	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
1500dfea-e163-4c7e-85bb-165cdc9acefd	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	7	3	2025-05-15 20:08:17.888268+07	2025-05-15 20:08:17.888268+07	\N	\N
0cb4936d-3fcd-4f1c-8d80-63af9dbfdfa6	933568d5-982a-43c3-a4aa-3177bab10f07	1	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
568f5083-e014-4753-90b6-ae80364fcdef	933568d5-982a-43c3-a4aa-3177bab10f07	1	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
f10ffc0d-df32-46ee-a43b-2455783c4745	933568d5-982a-43c3-a4aa-3177bab10f07	1	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
3d7d30a8-712b-42b9-a1d2-681801e5e27a	933568d5-982a-43c3-a4aa-3177bab10f07	2	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
50451e8c-7562-4c35-bc6d-4153f4cf5cce	933568d5-982a-43c3-a4aa-3177bab10f07	2	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
5ff66544-a3bb-48e7-849f-f79626a1bbc6	933568d5-982a-43c3-a4aa-3177bab10f07	2	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
1532fe23-e7f6-4347-bd3a-cdfbf85aa0c4	933568d5-982a-43c3-a4aa-3177bab10f07	3	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
9051ce37-a6cd-47ed-8a44-ec0d78bcc438	933568d5-982a-43c3-a4aa-3177bab10f07	3	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
8c0ce7bd-344c-48ef-809f-78ffbc93d988	933568d5-982a-43c3-a4aa-3177bab10f07	3	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
670a4ef3-b98b-413b-a2b9-72d6b66a28b2	933568d5-982a-43c3-a4aa-3177bab10f07	4	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
dbb02638-0baf-44f8-b31f-eb9693087de6	933568d5-982a-43c3-a4aa-3177bab10f07	4	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
811b81da-4ddc-4de1-ba03-84a395377b87	933568d5-982a-43c3-a4aa-3177bab10f07	4	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
968a994d-dee6-4507-8b3c-4f1769cc0bfa	933568d5-982a-43c3-a4aa-3177bab10f07	5	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
dd939ae2-4e69-4df5-b974-0030c00477ae	933568d5-982a-43c3-a4aa-3177bab10f07	5	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
9242cb6b-0d7f-4215-9aef-33d7d896da23	933568d5-982a-43c3-a4aa-3177bab10f07	5	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
5dad8350-069a-4c88-ac86-6f740077fbe9	933568d5-982a-43c3-a4aa-3177bab10f07	6	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
2fdbedf3-ddc9-4136-a231-7e783421e0af	933568d5-982a-43c3-a4aa-3177bab10f07	6	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
9f868531-ff95-4146-a209-7a514dd6e72c	933568d5-982a-43c3-a4aa-3177bab10f07	6	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
9a731dc7-2241-4729-b829-c01b932f3fbd	933568d5-982a-43c3-a4aa-3177bab10f07	7	1	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
3868eac7-ee78-4c32-8d54-f2900cc9db8b	933568d5-982a-43c3-a4aa-3177bab10f07	7	2	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
f89e083a-b8a9-46c4-a100-6411232028e9	933568d5-982a-43c3-a4aa-3177bab10f07	7	3	2025-05-19 20:04:33.69813+07	2025-05-19 20:04:33.69813+07	\N	\N
0242f326-8e1f-4d5b-a80a-20f80bccfe26	b9b1ad6c-c41b-446a-b00e-f56684663c56	1	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
41b383c8-1383-45ea-8c96-9555d1410578	b9b1ad6c-c41b-446a-b00e-f56684663c56	1	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
7cf1515b-e8ce-4a10-884c-c5a5a9aac89b	b9b1ad6c-c41b-446a-b00e-f56684663c56	1	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
35c4d40f-8f90-42d8-b561-ed180c1b799d	b9b1ad6c-c41b-446a-b00e-f56684663c56	2	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
55db7ec7-3bf3-4b60-83d4-df0fbc37a8e9	b9b1ad6c-c41b-446a-b00e-f56684663c56	2	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
4534cd4e-6fb0-4e2e-82b1-99e72fc4db85	b9b1ad6c-c41b-446a-b00e-f56684663c56	2	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
3556f6bd-6d17-46c0-80aa-b89cb8f71b4d	b9b1ad6c-c41b-446a-b00e-f56684663c56	3	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
82f01a64-ca02-4803-ab6b-7971062c7469	b9b1ad6c-c41b-446a-b00e-f56684663c56	3	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
b7ac550b-b15e-4ffe-a4f3-7937d08ace70	b9b1ad6c-c41b-446a-b00e-f56684663c56	3	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
e62cf464-dc04-46b4-9ad5-ce67df369b66	b9b1ad6c-c41b-446a-b00e-f56684663c56	4	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
6feabee8-0382-4ed0-b089-f6c1e4275015	b9b1ad6c-c41b-446a-b00e-f56684663c56	4	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
da6b5f99-5ec9-4d24-9801-cefe94249da6	b9b1ad6c-c41b-446a-b00e-f56684663c56	4	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
eff4712b-c275-46f4-b3f7-8f7f6a63c2ac	b9b1ad6c-c41b-446a-b00e-f56684663c56	5	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
387a9eaf-fb04-4a9f-b77c-a7c39241e388	b9b1ad6c-c41b-446a-b00e-f56684663c56	5	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
6d703c28-0f32-4abe-aa54-357b8f707d43	b9b1ad6c-c41b-446a-b00e-f56684663c56	5	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
39538e05-2ee0-4a9b-b6c9-19c2c6142755	b9b1ad6c-c41b-446a-b00e-f56684663c56	6	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
62be9241-5617-4be8-9706-db606b4c37ed	b9b1ad6c-c41b-446a-b00e-f56684663c56	6	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
1fb69c05-2446-455e-9213-90a8bda545d8	b9b1ad6c-c41b-446a-b00e-f56684663c56	6	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
701e4777-92e4-4585-8eb9-2d4ea2f52360	b9b1ad6c-c41b-446a-b00e-f56684663c56	7	1	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
ca8eb8e3-9461-4e76-81c6-5fb00ef28d4c	b9b1ad6c-c41b-446a-b00e-f56684663c56	7	2	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
f819d5b5-cca3-446c-aab8-e13fa828ed00	b9b1ad6c-c41b-446a-b00e-f56684663c56	7	3	2025-05-19 20:06:58.501942+07	2025-05-19 20:06:58.501942+07	\N	\N
\.

COPY sik.jenis_barang_medis (id, nama, created_at, updated_at) FROM stdin;
1000	Obat Oral	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
2000	Obat Topikal	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
3000	Obat Injeksi	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
4000	Obat Sublingual	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
5000	Obat Infus	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.kamar (nomor_bed, kode_kamar, nama_kamar, kelas, tarif_kamar, status_kamar) FROM stdin;
1	bcd	anggrek	1	50000	available
2	1	anggrek	2	50000	penuh
3	bcd	anggrek	VIP	100000	penuh
Gudang	Gudang	Gudang	Gudang	0	penuh
ICU-01	ICU	ICU A	ICU	800000	available
ICU-02	ICU	ICU B	ICU	800000	available
K1-01	K1	anggrek	1	200000	available
K1-02	K1	anggrek 2	1	200000	available
K1-03	K1	anggrek 3	1	200000	available
K2-01	K2	tulip	2	150000	available
K2-02	K2	dahlia	2	150000	available
K3-01	K3	teratai	3	100000	available
K3-02	K3	lili	3	100000	available
VUP-01	VIP	mawar	VIP	500000	available
VUP-02	VIP	melati	VIP	500000	available
VUP-03	VIP	kenanga	VIP	500000	available
\.

COPY sik.kategori_barang_medis (id, nama, created_at, updated_at) FROM stdin;
1000	Obat Paten	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
2000	Obat Generik	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
3000	Obat Merek	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
4000	Obat Eksklusif	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
5000	Obat Bebas Paten	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.kepegawaian (no_pegawai, status, golongan, jabatan, jkn, jkk, jkm, jht, jp, jkp, ptkp, bank, rekening) FROM stdin;
\.

COPY sik.mutasi_barang (id, id_barang_medis, jumlah, harga, id_ruangandari, id_ruanganke, tanggal, keterangan, no_batch, no_faktur) FROM stdin;
\.

COPY sik.notifikasi (id, sender, recipient, tanggal, judul, pesan, read) FROM stdin;
2eb3e845-f0d2-4ab3-b599-dd91a6799438	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	933568d5-982a-43c3-a4aa-3177bab10f07	2025-06-01	INI JUDUL	INI PESAN	f
\.

COPY sik.opname (id, id_barang_medis, id_ruangan, h_beli, tanggal, "real", stok, keterangan, no_batch, no_faktur) FROM stdin;
\.

COPY sik.pegawai (id, id_akun, nip, nama, jenis_kelamin, id_jabatan, id_departemen, id_status_aktif, jenis_pegawai, telepon, tanggal_masuk, created_at, updated_at, deleted_at, updater) FROM stdin;
933568d5-982a-43c3-a4aa-3177bab10f07	933568d5-982a-43c3-a4aa-3177bab10f07	1987123456	Eric	L	2	2	1	Tetap	081234567890	2020-01-01	2025-05-15 18:35:47.617694+07	2025-05-15 18:35:47.617694+07	\N	933568d5-982a-43c3-a4aa-3177bab10f07
b9b1ad6c-c41b-446a-b00e-f56684663c56	b9b1ad6c-c41b-446a-b00e-f56684663c56	1987123457	Aziz	L	3	3	1	Kontrak	082345678901	2021-05-15	2025-05-15 18:35:47.617694+07	2025-05-15 18:35:47.617694+07	\N	b9b1ad6c-c41b-446a-b00e-f56684663c56
bd0b4833-510c-4c29-a3a4-e08e9a0a5955	bd0b4833-510c-4c29-a3a4-e08e9a0a5955	1987123455	Admin	L	1000	1000	A	Tetap	081234567890	2020-01-01	2025-05-15 20:01:08.381723+07	2025-05-20 15:24:14.18878+07	\N	bd0b4833-510c-4c29-a3a4-e08e9a0a5955
\.

COPY sik.penerimaan_barang_medis (id, no_faktur, no_pemesanan, id_supplier, tanggal_datang, tanggal_faktur, tanggal_jthtempo, id_pegawai, id_ruangan, pajak_persen, pajak_jumlah, tagihan, materai) FROM stdin;
\.

COPY sik.penggajian (no_pegawai, tahun, bulan, gaji_pokok, tunjangan, bpjs, pajak, nominal, status) FROM stdin;
\.

COPY sik.permintaan_resep_pulang (no_permintaan, tgl_permintaan, jam, no_rawat, kd_dokter, status, tgl_validasi, jam_validasi, kode_brng, jumlah, aturan_pakai) FROM stdin;
PRP202505071001	2025-05-07	10:00:00	RW20250427001	D001	Sudah	2025-05-07	10:05:00	OBT001	10	3x1 sesudah makan
PRP202505060901	2025-05-06	10:00:00	RW202505060001	D001	Belum	2025-05-06	10:30:00	OB001	5	3x1 sesudah makan
PRP202505060902	2025-05-06	10:10:00	RW202505060002	D002	Belum	2025-05-06	10:40:00	OB002	10	2x1 sebelum makan
PRP202505063041	2025-05-06	22:07:31	202504232512		Sudah	2025-05-06	22:07:31	B000000003	10	2x2
PRP202505065708	2025-05-06	22:06:12	202504232512	D005	Sudah	2025-05-06	22:06:12	2018001	30	3x1
PRP202505065709	2025-05-06	22:06:12	202504232512	D005	Sudah	2025-05-06	22:06:12	2018001	30	3x1
PRP202505068616	2025-05-06	21:57:12	202504232512		Sudah	2025-05-06	21:57:12	B000000552	15	3x1
PRP202505062704	2025-05-06	23:32:40	202504254997	D003	Sudah	2025-05-06	23:32:40	B000000396	15	3x1
PRP202505069177	2025-05-06	21:57:54	202504232512	D008	Sudah	2025-05-06	21:57:54	B000001294	100	2x1
PRP202505311745	2025-05-31	00:23:07	202504232661		Sudah	2025-05-31	00:23:07	2018003	1	3x1
\.

COPY sik.permintaan_stok_obat (no_permintaan, tgl_permintaan, jam, no_rawat, kd_dokter, status, tgl_validasi, jam_validasi) FROM stdin;
P001202504	2025-04-29	08:30:00	RW20250429001	D001	Belum	2025-04-29	09:00:00
P001202506	2025-04-29	10:00:00	RW20250429003	D003	Belum	2025-04-29	10:30:00
P001202507	2025-04-29	10:45:00	RW20250429004	D001	Sudah	2025-04-29	11:00:00
P001202508	2025-04-29	11:30:00	RW20250429005	D004	Belum	2025-04-29	12:00:00
P20250503001	2025-05-03	09:00:00	RW20250503001	D001	Belum	\N	\N
RSP202505021734	2025-05-02	22:26:49	202504254997	D004	Belum	2025-05-02	22:27:04
SOP202505025054	2025-05-02	22:32:33	202504254997	D004	Belum	2025-05-02	22:32:55
SOP202505025712	2025-05-02	22:36:22	202504254997	D004	Belum	2025-05-02	22:36:45
SOP202505028058	2025-05-02	22:40:02	202504254997	D004	Belum	2025-05-02	22:40:15
RSP202505021234	2025-05-02	22:45:00	202504254997	D004	Belum	\N	\N
RSP202505021235	2025-05-02	22:45:00	202504254997	D004	Belum	\N	\N
SOP202505029678	2025-05-02	22:56:38	202504232512	D004	Belum	2025-05-02	22:57:09
SOP202505025516	2025-05-02	23:00:37	202504254997	D004	Belum	2025-05-02	23:01:00
SOP202505027152	2025-05-02	23:18:44	202504254997	D004	Belum	\N	\N
SOP202505029321	2025-05-02	23:37:41	202504254997	D004	Belum	\N	\N
SOP202505029322	2025-05-02	23:37:41	202504254997	D004	Belum	\N	\N
SOP202505031426	2025-05-03	12:15:04	202504232512	D004	Belum	\N	\N
SOP202505057213	2025-05-05	14:23:38	202504254997	D004	Belum	\N	\N
SOP202505316071	2025-05-31	00:03:50	202505284371	D004	Belum	\N	\N
SOP202506011656	2025-06-01	15:21:57	202504164239	D001	Belum	2025-06-01	15:22:22
\.

COPY sik.presensi (id, id_pegawai, id_jadwal_pegawai, tanggal, jam_masuk, jam_pulang, keterangan, foto, created_at, updated_at, deleted_at, updater) FROM stdin;
\.

COPY sik.rawat_inap (nomor_rawat, nomor_rm, nama_pasien, alamat_pasien, penanggung_jawab, hubungan_pj, jenis_bayar, kamar, tarif_kamar, diagnosa_awal, diagnosa_akhir, tanggal_masuk, tanggal_keluar, jam_keluar, total_biaya, status_pulang, lama_ranap, dokter_pj, status_bayar, jam_masuk) FROM stdin;
202504254997	300	Mae	Jl. Merpati	Meri	Diri Sendiri	BPJS	VUP.01	500	Diare	\N	2025-04-06	0001-01-01	0001-01-01	0	\N	0	Dr. Elsa	\N	14:55:32
202504164239	123	Andi	Jl. Merpati	Eric	Diri Sendiri	BPJS	VUP.03	0	DBD	\N	2025-04-19	0001-01-01	0001-01-01 BC	0	Belum	0	Dr. Ahmad	Belum Bayar	15:10:48
202504232661	125	Don	Jl. Merpati	Budi	Diri Sendiri	BPJS	K1.02	200	Sakit	\N	2025-04-06	0001-01-01	0001-01-01 BC	0	Belum	0	Dr. Intan	Belum Bayar	15:13:07
202504232512	123	Andi	Jl. Merpati	Eric	Diri Sendiri	BPJS	VUP.02	500	Diare	\N	2025-04-06	0001-01-01	0001-01-01 BC	0	Belum	0	Dr. Fahmi	Belum Bayar	15:13:29
202504199396	123	Eric	kampung	Budi	Saudara	BPJS	K01	50000	Sakit	\N	2025-04-06	0001-01-01	0001-01-01 BC	0	Belum	0	Dr. Budi	Belum Bayar	15:13:45
202504193859	125	Don	Jl. Merpati	Budi	Diri Sendiri	BPJS	K01	50000	Sakit	\N	2025-04-06	0001-01-01	0001-01-01 BC	0	Belum	0	Dr. Gina	Belum Bayar	15:14:04
202505284545	202504164239	Dewi Lestari	Jl. Mawar No. 9	Surya Lesmana	IBU	BPJS	K3-02	100	Sakit	\N	2025-04-06	0001-01-01	0001-01-01	0	\N	0	Dr. Intan	\N	20:15:39
202505284371	RM2025052859642	Indira	21	Putri	SAUDARA	BPJS	K3-02	100	DBD	\N	2025-04-04	0001-01-01	0001-01-01	0	\N	0	Dr. Intan	\N	21:23:04
202504168143	123	Andi	Jl. Merpati	Eric	Diri Sendiri	BPJS	VUP.04	0	Influenza	\N	2025-04-19	0001-01-01	0001-01-01 BC	0	Belum	0	Dr. Ahmad	Belum Bayar	16:59:25
\.

COPY sik.resep_dokter_racikan_detail (no_resep, no_racik, kode_brng, p1, p2, kandungan, jml) FROM stdin;
RSP202505294923	RC01	2018001	1	2	250	3
RSP202505297596	1	2018003	1	2	200	4
RSP202505297596	1	B000000003	1	2	250	5
RSP202505292188	2	B000000003	1	2	500	5
RSP202505292188	2	B000008006	1	2	100	10
RSP202505297487	3	B000001798	1	2	100	10
RSP202505297487	3	B000000374	1	2	200	5
RSP202505304793	4	B000001729	1	2	400	4
RSP202505304793	4	B000000572	1	2	500	5
RSP202505316952	5	B000000003	1	2	100	10
RSP202505316952	5	B000001547	1	2	200	20
\.

COPY sik.resep_dokter_racikan (no_resep, no_racik, nama_racik, kd_racik, jml_dr, aturan_pakai, keterangan) FROM stdin;
RSP202505294923	RC01	Racikan Batuk	PCT	10	3x1 sesudah makan	Obat campuran batuk
RSP202505297596	1	Flu	PCT	10	3x1	Sesudah makan
RSP202505292188	2	Flu	PCT	10	3x1	Sesudah makan
RSP202505297487	3	Flu	PCT	10	3x1	Sesudah makan
RSP202505304793	4	Flu	PCT	10	3x1	Sesudah makan
RSP202505316952	5	Flu	PCT	10	3x1	Sesudah makan
\.

COPY sik.resep_obat (no_resep, tgl_perawatan, jam, no_rawat, kd_dokter, tgl_peresepan, jam_peresepan, status, tgl_penyerahan, jam_penyerahan, validasi) FROM stdin;
RSP202504229734	2025-04-22	15:08:55	RW202504169001	D004	2025-04-22	15:08:55	ranap	2025-04-22	15:08:55	t
RSP20250421002	2025-04-20	09:30:00	RW20250420002	D001	2025-04-20	09:35:00	ranap	2025-04-20	10:15:00	f
RSP20250421003	2025-04-19	13:00:00	RW20250419001	D001	2025-04-19	13:10:00	ralan	2025-04-19	14:00:00	f
RSP20250421004	2025-04-18	10:45:00	RW20250418001	D001	2025-04-18	10:50:00	ranap	2025-04-18	11:30:00	f
RSP20250421005	2025-04-17	11:15:00	RW20250417001	D001	2025-04-17	11:20:00	ralan	2025-04-17	12:00:00	t
RSP202504256269	2025-04-25	15:28:47	202504254997	D004	2025-04-25	15:28:47	ranap	2025-04-25	15:28:47	f
RSP202505027694	2025-05-02	15:20:52	202504254997	D004	2025-05-02	15:20:52	ranap	2025-05-02	15:20:52	f
RSP202505065592	2025-05-06	22:09:40	202504232512	D004	2025-05-06	22:09:40	ranap	2025-05-06	22:09:40	f
RSP202504255047	2025-04-25	15:20:42	202504254997	D004	2025-04-25	15:20:42	ranap	2025-04-25	15:20:42	t
RSP202505304793	2025-05-30	00:02:10	RW001	D001	2025-05-30	00:02:10	ranap	2025-05-30	00:02:10	f
RSP202505308319	2025-05-30	14:37:15	202505284371	D001	2025-05-30	14:37:15	ranap	2025-05-30	14:37:15	t
RSP202505316952	2025-05-31	13:51:06	202504168143	D003	2025-05-31	13:51:06	ranap	2025-05-31	13:51:06	f
RSP20250421001	2025-04-20	08:15:00	RW20250420001	D001	2025-04-20	08:20:00	ralan	2025-04-20	09:00:00	t
RSP202504238226	2025-04-23	16:26:25	202504199396	D004	2025-04-23	16:26:25	ranap	2025-04-23	16:26:25	t
RSP202504237598	2025-04-23	16:24:18	202504199396	D004	2025-04-23	16:24:18	ranap	2025-04-23	16:24:18	t
\.

COPY sik.resep_pulang (no_rawat, kode_brng, jml_barang, harga, total, dosis, tanggal, jam, kd_bangsal, no_batch, no_faktur) FROM stdin;
RW20250428001	OBT001	2	15000	30000	3x1 sesudah makan	2025-04-28	08:00:00	B001	BT001	FKT001
RW20250428002	OBT002	1	20000	20000	2x1 sebelum makan	2025-04-28	09:30:00	B002	BT002	FKT002
RW20250428004	OBT004	3	5000	15000	1x1 malam hari	2025-04-28	11:45:00	B004	BT004	FKT004
RW20250428005	OBT005	4	12000	48000	3x1 setelah makan	2025-04-28	13:00:00	B005	BT005	FKT005
202504254997	B000001294	6	10688	64128	3x1 	2025-04-28	11:35:18	VUP.01	1	1
202504232661	B000001294	6	10688	64128	3x1 	2025-04-28	11:36:04	K1.02	1	1
202504254997	B000001294	10	10688	106880	3x1 	2025-04-16	23:01:37	VUP.01	1	1
202504232512	2018001	30	21259	637770	3x1 	2025-04-16	23:01:37	VUP.02	1	1
202504232512	B000001294	100	10688	21965	3x1 	2025-04-16	23:01:37	VUP.02	1	1
202504254997	B000000396	15	0	0	3x1	2025-04-16	23:01:37	VUP.01	1	1
202504232661	2018003	1	0	0	3x1	2025-04-16	23:01:37	K1.02	1	1
\.

COPY sik.resume_pasien_ranap (no_rawat, kd_dokter, diagnosa_awal, alasan, keluhan_utama, pemeriksaan_fisik, jalannya_penyakit, pemeriksaan_penunjang, hasil_laborat, tindakan_dan_operasi, obat_di_rs, diagnosa_utama, kd_diagnosa_utama, diagnosa_sekunder, kd_diagnosa_sekunder, diagnosa_sekunder2, kd_diagnosa_sekunder2, diagnosa_sekunder3, kd_diagnosa_sekunder3, diagnosa_sekunder4, kd_diagnosa_sekunder4, prosedur_utama, kd_prosedur_utama, prosedur_sekunder, kd_prosedur_sekunder, prosedur_sekunder2, kd_prosedur_sekunder2, prosedur_sekunder3, kd_prosedur_sekunder3, alergi, diet, lab_belum, edukasi, cara_keluar, ket_keluar, keadaan, ket_keadaan, dilanjutkan, ket_dilanjutkan, kontrol, obat_pulang) FROM stdin;
RW001	D001	Demam Berdarah	Panas tinggi dan lemas	Demam selama 3 hari	TD: 120/80, N: 90x/mnt	Demam disertai nyeri kepala dan mual	USG Abdomen, Rontgen Thorax	Trombosit menurun	Transfusi cairan	Paracetamol, Ringer Laktat	Dengue Fever	A91	Dehidrasi Ringan	E86	Hipotensi	I95	Nyeri Perut	R10	Batuk Ringan	R05	Infus Cairan	99.15	Pemberian Obat	99.29	Pemantauan Vital Sign	89.52	Konsultasi Gizi	88.78	Tidak ada	Cair, rendah garam	Sedang diproses	Telah diberikan	Atas Izin Dokter	\N	Membaik	\N	Kembali Ke RS	\N	2024-08-01 10:00:00	Parasetamol 3x sehari
RW002	D002	Asma Akut	Sesak napas mendadak	Sesak berat	Wheezing terdengar jelas	Riwayat asma sejak kecil	Spirometri, Foto Thorax	Normal, eosinofil meningkat	Inhalasi bronkodilator	Salbutamol, Oksigen	Asma	J45	Infeksi Saluran Nafas Atas	J00	Alergi Debu	T78	Bronkospasme	J98	Hipoksia	R09	Nebulizer	93.93	Oksigenasi	93.94	Pemeriksaan Darah	90.59	Pendidikan Pasien	94.01	Alergi debu rumah	Tinggi kalori, rendah lemak	Sudah dilakukan	Edukasi penggunaan inhaler	Atas Izin Dokter	\N	Sembuh	\N	Kontrol di RS	Poli Paru	2024-08-03 14:00:00	Salbutamol 2x sehari
RW003	D003	Hipertensi Berat	Pusing dan mimisan	Tekanan darah tinggi	TD: 180/110, N: 88x/mnt	Hipertensi tidak terkontrol	EKG, Urinalisis	Proteinuria, hipertrofi ventrikel kiri	Pemberian antihipertensi	Captopril, Amlodipine	Hipertensi	I10	Nefropati	N08	Retinopati	H35	Gangguan Kognitif	F06	Hiperkolesterolemia	E78	Pemeriksaan EKG	89.52	CT Scan	87.03	Pemeriksaan Laboratorium	90.59	Psikologi	94.08	Tidak ada	Rendah garam	Pending	Konseling keluarga	Pulang Sendiri	Atas permintaan pasien	Membaik	Masih perlu kontrol	Puskesmas	Faskes terdekat	2024-08-07 08:00:00	Captopril 2x sehari
RW004	D004	Demensia	Penurunan daya ingat	Lupa nama anak sendiri	TD: 130/80, MMSE: 18/30	Progresif selama 1 tahun	CT Head, Pemeriksaan Neuro	Atrofi otak	Rawat suportif dan pengawasan	Vitamin B, Donepezil	Demensia Alzheimer	G30	Depresi	F32	Delirium	F05	Insomnia	G47	Nutrisi Buruk	E46	MRI Kepala	88.91	Tes Neurokognitif	94.10	Kunjungan Homecare	94.04	Psikoterapi	94.11	Tidak ada	Makanan lunak bergizi	Sudah lengkap	Keluarga diberi edukasi	Atas Izin Dokter	\N	Lain-lain	Stabil	Kontrol di RS	Poli Jiwa	\N	Donepezil malam hari
RW005	D005	TBC Paru	Batuk lama dan berat badan turun	Batuk >2 minggu, BB turun	Ronki basah bilateral	Didiagnosis TBC sejak 1 bulan lalu	Rontgen Thorax, Sputum BTA	BTA positif	Terapi OAT	HRZE, vitamin B6	Tuberculosis Paru	A15	Anemia Ringan	D50	Malnutrisi	E46	Gastritis	K29	Hipoglikemia	E16	Terapi OAT	99.24	Konseling	94.12	Tes HIV	90.61	Pendidikan TB	94.01	Tidak ada	Tinggi protein	Pending pengambilan ulang	Edukasi kepatuhan OAT	Atas Izin Dokter	\N	Membaik	\N	Kembali Ke RS	\N	2024-08-10 10:00:00	HRZE selama 2 bulan
\.

COPY sik.role (id, nama, created_at, updated_at) FROM stdin;
1337	Developer	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
1	Admin	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
2	Pegawai	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
3	Dokter	2025-05-15 18:24:16.375191+07	2025-05-15 18:24:16.375191+07
\.

COPY sik.ruangan (id, nama, created_at, updated_at) FROM stdin;
1000	Gudang	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
2000	Apotek	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
3000	LABORAT	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
4000	HCU	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
5000	ICU	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
6000	IGD	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
7000	Kelas 1	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
8000	Kelas 2	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
9000	Kelas 3	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
10000	Operasi	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
11000	NICU	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
12000	VIP	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
13000	VVIP	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.rujukan_keluar (nomor_rujuk, nomor_rawat, nomor_rm, nama_pasien, tempat_rujuk, tanggal_rujuk, jam_rujuk, keterangan_diagnosa, dokter_perujuk, kategori_rujuk, pengantaran, keterangan) FROM stdin;
1	2	3	eric	rsud	2025-05-05	2025-05-05	sakit	eric	bedah	ambulans	-
50	202504066965	789	Jaya	rsud	2025-04-06	0001-01-01 BC	sakit	ahmad	bedah	sendiri	
\.

COPY sik.rujukan_masuk (nomor_rujuk, perujuk, alamat_perujuk, nomor_rawat, nomor_rm, nama_pasien, alamat, umur, tanggal_masuk, tanggal_keluar, diagnosa_awal) FROM stdin;
1	RSUD	keputih	456	789	Eric	keputih	22	2025-04-04	2025-04-05	Sakit
50	RSUD	keputih	750	15615635	Jaya	keputih	23	2025-04-06	2025-04-10	Sakit
51	RSUD	keputih	202504066408	15615634	Jaya	keputih	23	2025-04-06	2025-04-05	Sakit
			202504148176	55	Don			2025-04-06	2025-04-10	Sakit
\.

COPY sik.satuan_barang_medis (id, nama, created_at, updated_at) FROM stdin;
1	-	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
2	pcs	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
3	tablet	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
4	kapsul	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
5	ampul	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
6	botol	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
7	tube	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
8	pasang	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
9	kotak	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
10	item	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.shift (id, nama, jam_masuk, jam_pulang, created_at, updated_at) FROM stdin;
NA	Belum Ditentukan	07:00:00+07	07:00:00+07	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
P	Pagi	07:00:00+07	15:00:00+07	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
S	Sore	15:00:00+07	23:00:00+07	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
M	Malam	23:00:00+07	07:00:00+07	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.status_aktif_pegawai (id, nama, created_at, updated_at) FROM stdin;
A	Aktif	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
BH	Berhenti dengan Hormat	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
C	Cuti	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
R	Resign	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
BT	Berhenti dengan Tidak Hormat	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
P	Pensiun	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
W	Wafat	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

COPY sik.stok_keluar_barang_medis (id, no_keluar, id_pegawai, tanggal_stok_keluar, id_ruangan, keterangan) FROM stdin;
\.

COPY sik.supplier_barang_medis (id, nama, alamat, no_telp, kota, nama_bank, no_rekening, created_at, updated_at) FROM stdin;
1	Mitra	Jln. Benar	08234234	Jakarta	BCA	8123123	2025-03-17 19:59:47.012224+07	2025-03-17 19:59:47.012224+07
\.

-- Akun Bayar
-- INSERT INTO akun_bayar (id, nama_akun, nomor_rekening, nama_rekening, ppn) VALUES ('1000', 'Cash', '-', '-', '0'), ('2000', 'Transfer lewat Virtual Mandiri', '12308123123', 'Bank Mandiri', '1');

COPY sik.tarif_tindakan (kode, nama_perawatan, kategori_perawatan, tarif, kelas) FROM stdin;
\.

COPY sik.transaksi_keluar_barang_medis (id, id_stok_keluar, id_barang_medis, no_batch, no_faktur, jumlah_keluar) FROM stdin;
\.

COPY sik.tukar_jadwal (id, id_sender, id_recipient, id_hari, id_shift_sender, id_shift_recipient, status) FROM stdin;
\.

COPY sik.ugd (nomor_reg, nomor_rawat, tanggal, jam, kode_dokter, dokter_dituju, nomor_rm, nama_pasien, jenis_kelamin, umur, poliklinik, penanggung_jawab, alamat_pj, hubungan_pj, biaya_registrasi, status, jenis_bayar, status_rawat, status_bayar) FROM stdin;
1	20250412256	2025-04-12	20:20:20	D001	Dr. Ahmad	1	Aziz	L	22	Poli Jantung	Jaya	Keputih	Diri Sendiri	100000	Lama	BPJS	Belum	Belum Bayar
UGD1001	RW1001	2025-04-12	14:00:00	D001	dr. Rina	RM1001	Andi	L	35	Poli Umum	Budi	Jl. Merpati	Suami	50000		Tunai	rawat	belum
\.

COPY sik.data_instansi (kode_instansi, nama_instansi, alamat_instansi, kota, no_telp) FROM stdin;
ITS	Institut Teknologi Sepuluh Nopember	Jl. Teknik Kimia, Keputih, Kec. Sukolilo, Surabaya, Jawa Timur 60111	Surabaya	(031) 5994251
PG	PT Petrokimia Gresik 	 Jl. Jend Ahmad Yani, Gresik 61119, Jawa Timur-Indonesia	Gresik	0811 9918 001
TELKOM	PT Telkom Indonesia (Persero) Tbk	Jl. Japati No.1, Bandung 40133, Jawa Barat, Indonesia	Bandung	(022) 4521451
PERTA	PT Pertamina (Persero)	Jl. Medan Merdeka Timur No.1A, Jakarta 10110	Jakarta	(021) 3815111
BRI	PT Bank Rakyat Indonesia (Persero) Tbk	Jl. Jend. Sudirman Kav. 44-46, Jakarta 10210	Jakarta	(021) 2510244
UNILEV	PT Unilever Indonesia Tbk	Jl. BSD Boulevard Barat, BSD Green Office Park, Tangerang 15345	Tangerang	(021) 80827000
ASTRA	PT Astra International Tbk	Jl. Gaya Motor Raya No.8, Sunter II, Jakarta Utara 14330	Jakarta Utara	(021) 65307000
\.

COPY sik.dokter_jaga (kode_dokter, nama_dokter, hari_kerja, jam_mulai, jam_selesai, poliklinik, status) FROM stdin;
D001	Dr. Andi Wijaya	2025-04-15	08:00:00	14:00:00	Poli Umum	
D003	Dr. Budi Santoso	2025-04-16	10:00:00	16:00:00	Poli Gigi	
D006	Dr. Hadi Permana	Senin	08:00:00	14:00:00	Poli Umum	
D007	Dr. Rina Lestari	Selasa	09:00:00	15:00:00	Poli Gigi	
D008	Dr. Joko Prabowo	Rabu	07:30:00	13:30:00	Poli Anak	
D009	Dr. Siti Aminah	Kamis	10:00:00	16:00:00	Poli Kandungan	
D010	Dr. Bambang Yuwono	Jumat	08:30:00	14:30:00	Poli Dalam	
D011	Dr. Maya Kusuma	Senin	07:45:00	13:45:00	Poli Saraf	
D012	Dr. Agus Riyanto	Selasa	08:15:00	14:15:00	Poli THT	
D013	Dr. Nina Fadhilah	Rabu	09:30:00	15:30:00	Poli Kulit	
D014	Dr. Wahyu Adi	Kamis	10:15:00	16:15:00	Poli Jantung	
D015	Dr. Lina Hartati	Jumat	07:00:00	13:00:00	Poli Gigi	
D016	Dr. Arif Setiawan	Senin	08:00:00	14:00:00	Poli Umum	aktif
D017	Dr. Bella Pratiwi	Senin	14:00:00	20:00:00	Poli Gigi	aktif
D018	Dr. Citra Ramadhan	Senin	20:00:00	08:00:00	Poli Anak	aktif
D019	Dr. Dimas Rasyid	Selasa	08:00:00	14:00:00	Poli Umum	aktif
D020	Dr. Erna Wahyuni	Selasa	14:00:00	20:00:00	Poli Kandungan	aktif
D021	Dr. Farhan Maulana	Selasa	20:00:00	08:00:00	Poli THT	aktif
D022	Dr. Gina Yuliana	Rabu	08:00:00	14:00:00	Poli Umum	aktif
D023	Dr. Hendra Saputra	Rabu	14:00:00	20:00:00	Poli Kulit	aktif
D024	Dr. Intan Saraswati	Rabu	20:00:00	08:00:00	Poli Gigi	aktif
D025	Dr. Joko Santosa	Kamis	08:00:00	14:00:00	Poli Umum	aktif
D026	Dr. Karina Dewi	Kamis	14:00:00	20:00:00	Poli Saraf	aktif
D027	Dr. Lukman Fadillah	Kamis	20:00:00	08:00:00	Poli Dalam	aktif
D028	Dr. Maya Rahmawati	Jumat	08:00:00	14:00:00	Poli Umum	aktif
D029	Dr. Niko Pradipta	Jumat	14:00:00	20:00:00	Poli THT	aktif
D030	Dr. Olivia Sari	Jumat	20:00:00	08:00:00	Poli Anak	aktif
D031	Dr. Putra Wijaya	Sabtu	08:00:00	14:00:00	Poli Umum	aktif
D032	Dr. Qory Nasution	Sabtu	14:00:00	20:00:00	Poli Gigi	aktif
D033	Dr. Rudi Hartanto	Sabtu	20:00:00	08:00:00	Poli Jantung	aktif
D034	Dr. Sinta Melati	Minggu	08:00:00	14:00:00	Poli Umum	aktif
D035	Dr. Taufik Hidayat	Minggu	14:00:00	20:00:00	Poli Dalam	aktif
D036	Dr. Umi Zakiyah	Minggu	20:00:00	08:00:00	Poli Saraf	aktif
D005	Dr. Fahmi Rizal	Selasa	17:28:24	23:28:25	Poli Dalam	aktif
D002	Dr. Andi Wicaksana	Sabtu	13:30:13	19:30:16	Poli Umum	aktif
D004	Dr. Daniel	Selasa	21:17:21	03:17:23	Poli Umum	aktif
\.

COPY sik.dokter (kode_dokter, nama_dokter, jenis_kelamin, alamat_tinggal, no_telp, spesialis, izin_praktik) FROM stdin;
D011	Dr. Maya Kusuma	P	Jl. Bougenville No.4, Palembang	084812345678	Saraf	SIP-05724
D012	Dr. Agus Riyanto	L	Jl. Kamboja No.6, Balikpapan	085345678901	THT	SIP-18586
D004	Dr. Daniel	L	Jl. Anggrek No.33, Bandung	082112345678	Anak	SIP-90407
D005	Dr. Fahmi Rizal	L	Jl. Melati No.7, Yogyakarta	083812345678	Penyakit Dalam	SIP-18895
D006	Dr. Hadi Permana	L	Jl. Cemara No.91, Semarang	085212345678	Saraf	SIP-76972
D007	Dr. Rina Lestari	P	Jl. Mawar No.3A, Malang	085612345432	Mata	SIP-36832
D008	Dr. Joko Prabowo	L	Jl. Flamboyan No.17B, Medan	081345678901	THT	SIP-39631
D009	Dr. Siti Aminah	P	Jl. Teratai No.29, Denpasar	082245678901	Kandungan	SIP-88406
D010	Dr. Bambang Yuwono	L	Jl. Dahlia No.11, Makassar	083145678901	Kulit	SIP-55215
D013	Dr. Nina Fadhilah	P	Jl. Wijaya Kusuma No.22, Pontianak	081876543210	Kulit	SIP-92379
D014	Dr. Wahyu Adi	L	Jl. Kemuning No.15, Banjarmasin	082334455667	Jantung	SIP-61586
D015	Dr. Lina Hartati	P	Jl. Sawo No.88, Serang	083812349876	Gigi	SIP-50643
D016	Dr. Arif Setiawan	L	Jl. Durian No.5, Solo	085698765432	Umum	SIP-92510
D017	Dr. Bella Pratiwi	P	Jl. Rambutan No.9, Batam	081367890123	Gigi	SIP-38525
D018	Dr. Citra Ramadhan	P	Jl. Alpukat No.18, Cirebon	082289765432	Anak	SIP-73606
D019	Dr. Dimas Rasyid	L	Jl. Nangka No.30, Pekanbaru	083199887766	Umum	SIP-87714
D020	Dr. Erna Wahyuni	P	Jl. Jambu No.101, Padang	084733344455	Kandungan	SIP-07141
D021	Dr. Farhan Maulana	L	Jl. Pisang No.202, Bandar Lampung	085223344556	THT	SIP-96975
D022	Dr. Gina Yuliana	P	Jl. Pepaya No.12, Palu	081223344556	Umum	SIP-75293
D023	Dr. Hendra Saputra	L	Jl. Apel No.66, Manado	082244556677	Kulit	SIP-63906
D024	Dr. Intan Saraswati	P	Jl. Manggis No.44, Jayapura	083866778899	Gigi	SIP-28565
D025	Dr. Joko Santosa	L	Jl. Cempaka No.14, Mataram	084877788899	Umum	SIP-30063
D026	Dr. Karina Dewi	P	Jl. Beringin No.77, Kupang	085288899900	Saraf	SIP-47565
D027	Dr. Lukman Fadillah	L	Jl. Sengon No.55, Ternate	081399988877	Penyakit Dalam	SIP-05522
D028	Dr. Maya Rahmawati	P	Jl. Ketapang No.23, Ambon	082311223344	Umum	SIP-53369
D029	Dr. Niko Pradipta	L	Jl. Jati No.9, Cimahi	083822334455	THT	SIP-46937
D030	Dr. Olivia Sari	P	Jl. Akasia No.3, Tasikmalaya	084833445566	Anak	SIP-54591
D031	Dr. Putra Wijaya	L	Jl. Kayu Manis No.7, Depok	085244556688	Umum	SIP-76626
D032	Dr. Qory Nasution	P	Jl. Pinus No.100, Bogor	081255667788	Gigi	SIP-24809
D033	Dr. Rudi Hartanto	L	Jl. Palem No.88, Bekasi	082266778899	Jantung	SIP-93916
D034	Dr. Sinta Melati	P	Jl. Waru No.61, Tangerang	083277889900	Umum	SIP-23813
D035	Dr. Taufik Hidayat	L	Jl. Gandaria No.50, Jakarta Timur	084288990011	Penyakit Dalam	SIP-25794
D036	Dr. Umi Zakiyah	P	Jl. Seruni No.28, Jakarta Utara	085299001122	Saraf	SIP-10147
D002	Dr. Andi Wicaksana	L	JL. Sistem Informasi	082337441736	Umum	SIP-63691
D001	Dr. Andi Wijaya	L	Jl. Merpati No.12, Surabaya	081234567890	Umum	SIP-63670
D003	Dr. Budi Santoso	L	Jl. Kenanga No.45, Jakarta Selatan	081298765432	Gigi	SIP-63430
\.

COPY sik.kelahiran_bayi (no_rkm_medis, nm_pasien, jk, tmp_lahir, tgl_lahir, jam, umur, tgl_daftar, nm_ibu, umur_ibu, nm_ayah, umur_ayah, alamat, bb, pb, proses_lahir, kelahiran_ke, keterangan, diagnosa, penyulit_kehamilan, ketuban, lk_perut, lk_kepala, lk_dada, penolong, no_skl, gravida, para, abortus, f1, u1, t1, r1, w1, n1, f5, u5, t5, r5, w5, n5, f10, u10, t10, r10, w10, n10, resusitas, obat, mikasi, mikonium, no_rm_ibu) FROM stdin;
\.

COPY sik.pasien_meninggal (no_rkm_medis, nm_pasien, jk, tgl_lahir, umur, gol_darah, stts_nikah, agama, tanggal, jam, icdx, icdx_antara1, icdx_antara2, icdx_langsung, keterangan, nama_dokter, kode_dokter) FROM stdin;
001234	Budi Santoso	L	1970-05-12	53 Tahun	O	Menikah	Islam	2025-07-03	13:45:00	I21.9	I20.0	I50.1	I46.1	Pasien meninggal karena gagal jantung setelah serangan jantung.	dr. Andi Subagio	D001
001999	Ani Wijaya	P	1980-10-15	43 Tahun	B	Menikah	Islam	2025-07-05	14:30:00	C34.1	J18.9	I50.0	R99	Meninggal karena komplikasi infeksi paru-paru.	dr. Budi Hermanto	D002
\.

COPY sik.pasien (no_rkm_medis, nm_pasien, no_ktp, jk, tmp_lahir, tgl_lahir, nm_ibu, alamat, gol_darah, pekerjaan, stts_nikah, agama, tgl_daftar, no_tlp, umur, pnd, asuransi, no_asuransi, suku_bangsa, bahasa_pasien, perusahaan_pasien, nip, email, cacat_fisik, kd_kel, kd_kec, kd_kab, kd_prop, stts_pasien) FROM stdin;
RM000001	Fitria Nur Azzahra	3512051806990003	P	Surabaya	1999-06-18	Sulastri	Jl. Kenanga Raya No.12, Surabaya	A	Pegawai Negeri Sipil	MENIKAH	ISLAM	2025-07-13	082145673829	26 Th 0 Bl 25 Hr	S2	UMUM		Jawa	Indonesia			fitria.azzahra99@gmail.com	Tidak Ada	Wonokromo	Wonokromo	Surabaya	Jawa Timur	Aktif
RM000002	I Putu Adhitya Pratama Mangku Purnama	5108061602030006	L	Singaraja	2003-02-16	Evi Tri Kustinawati	JL. Gebang Wetan 5b	O	Mahasiswa	BELUM MENIKAH	HINDU	2025-07-14	083192925747	22 Th 4 Bl 28 Hr	S1	UMUM		Bali	Indonesia	I003	5026211037	portodit@gmail.com	Tidak Ada	Gebang Putih	Sukolilo	Surabaya	Jawa Timur	Aktif
RM000003	Rifqi Naufal Luthfyardy	3578032804030003	L	Surabaya	2003-04-28	Ningsih	Wisma Medokan, G/13	A	Mahasiswa	BELUM MENIKAH	ISLAM	2025-07-14	087857097780	22 Th 2 Bl 16 Hr	S1	UMUM		Jawa	Indonesia	I003	5026211189	fyarrifqi5@gmail.com	Tidak Ada	Medokan Ayu	Rungkut	Surabaya	Jawa Timur	Aktif
RM000004	Zizi Wulandari	6485946319523453	P	Mojokerto	1995-11-25	Bahuwirya Nasyidah	 Gang Joyoboyo No. 799, Lamongan, Jawa Timur 80368	B	Notaris	MENIKAH	ISLAM	2025-07-14	08788860561	29 Th 7 Bl 19 Hr	S1	BPJS	44574832397951	Jawa	Indonesia	I001	19931003092	warsariyanti@gmail.com	Tidak Ada	Paciran	Sukorejo	Lamongan	Jawa TImur	Aktif
RM000005	Muhammad Fatchu Rozaq	3525160611030002	L	Gresik	2003-11-05	Ernita	Perum Banjarsari Permai Blok C	A	Mahasiswa	BELUM MENIKAH	ISLAM	2025-07-14	0895385132323	21 Th 8 Bl 9 Hr	S1	UMUM		Jawa	Indonesia			fatchu2003@gmail.com	Tidak Ada	Banjarsari	Manyar	Gresik	Jawa Timur	Aktif
RM000006	Faris Santoso	2406888874359694	L	Kediri	2003-06-29	Yani Wasita	Jl. M.H Thamrin, Kel. Pare, Kabupaten Kediri, Jawa Timur	AB	Tidak Bekerja	BELUM MENIKAH	ISLAM	2025-07-14	08647855645	22 Th 0 Bl 15 Hr	SMA	BPJS	57234694305207	Jawa	Indonesia			santosofaris25@gmail.com	Tidak Ada	Pare	Kediri	Kediri	Jawa Timur	Aktif
RM000007	Sri Wahyuningsih	8886210761972132	P	Malang	1975-02-16	Sarinah	Gg. Monginsidi, Kel. Manyar, Kabupaten Gresik, Jawa Timur	B	Ibu Rumah Tangga	MENIKAH	ISLAM	2025-07-14	085681331532	50 Th 4 Bl 28 Hr	D3	BPJS	23895314422088	Jawa	Indonesia			wahyuningsih75@gmail.com	Tidak Ada	Sidomukti	Manyar	Gresik	Jawa Timur	Aktif
RM000008	Rayyan Maulana	9986806623215593	L	Surabaya	2000-02-11	Nanik Partiningrum	JL. Ruby No.5 PPS Gresik	A	Karyawan	BELUM MENIKAH	ISLAM	2025-07-14	086526746354	25 Th 5 Bl 3 Hr	D4	BPJS	02119375947252	Jawa	Indonesia			rayyganss@gmail.com	Tidak Ada	Suci	Manyar	Gresik	Jawa Timur	Aktif
RM000009	Ucok Subejo	3525141404030001	L	Gresik	2003-04-14	Nurhayati	Perumahan Petrokimia Gresik	A	Mahasiswa	BELUM MENIKAH	ISLAM	2025-07-20	082337441736	22 Th 3 Bl 6 Hr	S1	UMUM		Jawa	Indonesia	PG	199310030924	didin6428@gmail.com	Tidak Ada	Sukodono	Gresik	Gresik	East Java	Aktif
\.

COPY sik.registrasi (nomor_reg, nomor_rawat, tanggal, jam, kode_dokter, nama_dokter, nomor_rm, nama_pasien, jenis_kelamin, umur, poliklinik, jenis_bayar, penanggung_jawab, alamat_pj, hubungan_pj, biaya_registrasi, status_registrasi, no_telepon, status_rawat, status_poli, status_bayar, status_kamar, nomor_bed, pekerjaanpj, kelurahanpj, kecamatanpj, kabupatenpj, propinsipj, notelp_pj, no_asuransi) FROM stdin;
REG202507147677	202507146641	2025-07-14	11:21:15	D1001	Dr. Ahmad Fauzi	RM000002	I Putu Adhitya Pratama Mangku Purnama	L	22	Poli Umum	BPJS	I Putu Adhitya Pratama Mangku Purnama	JL. Gebang Wetan 5b	DIRI SENDIRI	0	Lama	083192925747	Belum	Baru	Belum Bayar		\N	Mahasiswa	Gebang Putih	Sukolilo	Surabaya	Jawa Timur	083192925747	-
REG202507140503	202507140039	2025-07-14	11:22:22	D006	Dr. Farhan	RM000005	Muhammad Fatchu Rozaq	L	21	Poli Umum	BPJS	Rifqi Naufal Luthfyardy	Wisma Medokan, G/13	LAIN-LAIN	0	Lama	0895385132323	Belum	Baru	Belum Bayar		\N	Mahasiswa	Medokan Ayu	Rungkut	Surabaya	Jawa Timur	082764536475	-
REG202507143351	202507143148	2025-07-14	11:20:47	D006	Dr. Farhan	RM000001	Fitria Nur Azzahra	P	26	Poli Umum	BPJS	Ahmad Zulfikar	Jl. Kenanga Raya No.12, Surabaya	SUAMI	0	Lama	082145673829	Belum	Baru	Belum Bayar		\N	Dosen	Wonokromo	Wonokromo	Surabaya	Jawa Timur	087664758475	-
REG202507143821	202507145994	2025-07-14	11:23:32	D1001	Dr. Ahmad Fauzi	RM000008	Rayyan Maulana	L	25	Poli Umum	BPJS	Nanik Partiningrum	JL. Ruby No.5 PPS Gresik	IBU	0	Lama	086526746354	Belum	Baru	Belum Bayar		\N	Ibu Rumah Tangga	Suci	Manyar	Gresik	Jawa Timur	082374657465	-
REG202507144262	202507149548	2025-07-14	11:22:04	D1001	Dr. Ahmad Fauzi	RM000004	Zizi Wulandari	P	29	Poli Umum	BPJS	Maman Saptono	Gang Joyoboyo No. 799, Lamongan, Jawa Timur 80368	SUAMI	0	Lama	08788860561	Belum	Baru	Belum Bayar		\N	Karyawan Swasta	Paciran	Sukorejo	Lamongan	Jawa Timur	083764756453	-
REG202507147930	202507140838	2025-07-14	11:22:41	D006	Dr. Farhan	RM000006	Faris Santoso	L	22	Poli Umum	BPJS	Ade Maheswara	Jl. M.H Thamrin, Kel. Pare, Kabupaten Kediri, Jawa Timur	SAUDARA	0	Lama	08647855645	Belum	Baru	Belum Bayar		\N	Pedagang	Pare	Kediri	Kediri	Jawa Timur	082746354019	-
REG202507149203	202507144162	2025-07-14	11:21:42	D006	Dr. Farhan	RM000003	Rifqi Naufal Luthfyardy	L	22	Poli Umum	BPJS	Edi Hartono	Wisma Medokan, G/13	AYAH	0	Lama	087857097780	Belum	Baru	Belum Bayar		\N	Pengusaha	Medokan Ayu	Rungkut	Surabaya	Jawa Timur	082647163542	-
REG202507149717	202507143783	2025-07-14	11:23:09	D006	Dr. Farhan	RM000007	Sri Wahyuningsih	P	50	Poli Umum	BPJS	Rayyan Pangestu	Gg. Monginsidi, Kel. Manyar, Kabupaten Gresik, Jawa Timur	ANAK	0	Lama	085681331532	Belum	Baru	Belum Bayar		\N	Karyawan Swasta	Sidomukti	Manyar	Gresik	Jawa Timur	082746354675	-
\.

COPY sik.catatan_observasi_ranap_kebidanan (no_rawat, tgl_perawatan, jam_rawat, gcs, td, hr, rr, suhu, spo2, kontraksi, bjj, ppv, vt, nip) FROM stdin;
20240501000001	2025-05-26	08:00:00	15-15-15	120/80	80	18	36.5	98	3x/10mnt	140	tidak ada	5cm	1234567890
20240501000002	2025-05-26	09:30:00	14-14-14	110/70	78	20	36.8	97	2x/10mnt	135	manual	4cm	1987654321
20240501000003	2025-05-26	10:45:00	13-13-13	130/85	85	22	37.1	99	4x/10mnt	150	ventilasi	6cm	2020011122
20240501000004	2025-05-26	11:15:00	15-14-13	115/75	76	19	36.7	96	2x/10mnt	138	tidak ada	3cm	6677889900
20240501000005	2025-05-26	12:00:00	14-13-15	118/76	82	21	37.0	95	1x/10mnt	132	manual	4.5cm	4455667788
202505284371	2025-05-31	17:26:19	1	2	3	4	5	6	7	8	9	10	1987123457
\.

COPY sik.catatan_observasi_ranap_postpartum (no_rawat, tgl_perawatan, jam_rawat, gcs, td, hr, rr, suhu, spo2, tfu, kontraksi, perdarahan, keterangan, nip) FROM stdin;
20240501000011	2025-05-25	06:30:00	15-15-15	120/80	78	20	36.8	98	2j bawah pusat	baik	sedikit	Kondisi stabil	10000000001
20240501000012	2025-05-25	09:00:00	14-14-14	110/70	82	22	37.1	97	1j bawah pusat	sedang	normal	Tidak ada keluhan	10000000002
20240501000013	2025-05-26	11:15:00	13-13-13	118/76	76	19	36.5	99	setinggi pusat	lemah	banyak	Observasi ketat	10000000003
20240501000014	2025-05-26	15:00:00	12-12-12	130/85	88	24	37.5	96	3j bawah pusat	baik	normal	Perlu edukasi ASI	10000000004
20240501000015	2025-05-27	08:45:00	14-13-15	125/80	80	21	37.0	95	setinggi pusat	kuat	sedikit	Kontrol tekanan	10000000005
202505284371	2025-05-31	18:10:46	1	2	3	4	5	6	7	8	9	10	1987123456
\.

COPY sik.catatan_observasi_ranap (no_rawat, tgl_perawatan, jam_rawat, gcs, td, hr, rr, suhu, spo2, nip) FROM stdin;
20240501000001	2025-05-25	08:00:00	15-15-15	120/80	78	20	36.8	98	1234567890
20240501000002	2025-05-25	12:00:00	14-14-14	110/70	82	22	37.2	97	2234567890
20240501000003	2025-05-26	07:45:00	13-13-13	118/76	76	19	36.5	99	3234567890
20240501000004	2025-05-26	13:30:00	12-12-12	130/85	88	24	37.5	96	4234567890
20240501000005	2025-05-27	09:15:00	14-13-15	125/80	80	21	37.0	95	5234567890
202505284371	2025-05-31	16:49:27	1	2	3	4	5	6	1987123456
\.

COPY sik.pemberian_obat (tanggal_beri, jam_beri, nomor_rawat, nama_pasien, kode_obat, nama_obat, embalase, tuslah, jumlah, biaya_obat, total, gudang, no_batch, no_faktur, kelas) FROM stdin;
2025-04-16	09:15:00	RW002	Siti Aminah	OB002	Amoxicillin 250mg	1500	2500	1	7000.00	11000.00	Gudang B	BATCH002	FAKTUR002	kelas1
2025-04-16	10:00:00	RW003	Ahmad Fauzi	OB003	Ibuprofen 200mg	500	1000	3	4000.00	13500.00	Gudang C	BATCH003	FAKTUR003	kelas1
2025-04-19	15:57:46	RW202504169001	Eric	2018001	AB-Vask 10mg (Otsus)	\N	\N	5	25511	127555	Gudang A	1	1	\N
2025-04-19	18:28:05	RW001	Budi Santoso	B000002026	ADONA TAB (BELI NIRWANA)	\N	\N	5	4393	21965	Gudang A	1	1	\N
2025-04-21	19:07:52	RW202504169001	Eric	2018003	AB-Vask 10mg (APBK)	\N	\N	2	191871	383742	Gudang A	1	1	\N
2025-04-26	09:14:59	202504254997	Mae	B000001295	ALBUMINAR 20% x 50ML	\N	\N	5	952875	4764375	Gudang A	1	1	\N
2025-04-27	11:58:25	2025-04-27	11:56:48			\N	\N	\N	\N	\N	\N	\N	\N	\N
2025-05-28	19:38:57	202504164239	Andi			\N	\N	\N	\N	\N	1	\N	\N	\N
2025-05-31	16:24:17		Indira			\N	\N	\N	\N	\N	\N	\N	\N	\N
2025-05-31	18:10:03		Indira			\N	\N	\N	\N	\N	\N	\N	\N	\N
\.

COPY sik.pemeriksaan_ranap (no_rawat, tgl_perawatan, jam_rawat, suhu_tubuh, tensi, nadi, respirasi, tinggi, berat, spo2, gcs, kesadaran, keluhan, pemeriksaan, alergi, penilaian, rtl, instruksi, evaluasi, nip) FROM stdin;
202504244462	2025-05-12	08:30:00	36.5	120/80	75	16	170	65	98	15	Compos Mentis	Headache	Normal	None	Stable	Follow up in 2 weeks	Take paracetamol	Satisfactory	1987123456
202504167258	2025-05-12	09:00:00	37.2	130/85	80	18	165	70	97	15	Compos Mentis	Fever and cough	Inflamed throat	Penicillin	Needs rest	Monitor symptoms	Take rest and fluids	Improvement expected	1987123456
456	2025-05-13	11:30:00	36.9	120/90	78	16	175	75	98	15	Compos Mentis	Fatigue	Normal	Aspirin	Stable	Normal follow up	Rest and hydration	Satisfactory	1987123456
RW001	2025-05-14	14:00:00	37.0	140/90	85	19	160	60	96	15	Compos Mentis	Abdominal pain	Gastritis	None	Needs treatment	Follow up with specialist	Take antacids	Relief expected	1987123456
202505284545	2025-05-28	20:15:59	5	2	3	4	7	8	6	1	9			10			13	14	1987123456
202505284371	2025-05-29	19:39:12	37.0	2	3	4	7	8	6	1	11	9	10	12	13	14	15	16	1987123456
202507140039	2025-07-15	18:40:34	37	120/80	75	18	170	65	98	1	Compos Mentis	Tes	Tes	Tidak Ada	Tes	Tes	Tes	Tes	1987123456
202507140039	2025-07-15	19:12:02	37	120/80	75	18	170	56	98	2	Compos Mentis	Tes	Tes	Tidak Ada	Tes	Tes	Tes	Tes	1987123457
\.

COPY sik.resep_dokter (no_resep, kode_barang, jumlah, aturan_pakai, embalase, tuslah) FROM stdin;
RSP20250421001	OBT001	22.17	3x1 sesudah makan	0	0
RSP20250421002	OBT002	29.33	2x1 sebelum makan	0	0
RSP20250421003	OBT003	6.70	1x1 malam hari	0	0
RSP20250421004	OBT004	22.69	3x1 pagi, siang, malam	0	0
RSP20250421005	OBT005	20.70	2x2 pagi dan malam	0	0
RSP20250421006	OBT006	13.18	1x3 setiap 8 jam	0	0
RSP20250421007	OBT007	15.87	1x2 pagi dan sore	0	0
RSP20250421008	OBT008	7.28	3x1 sebelum makan	0	0
RSP20250421009	OBT009	25.97	1x1 sesudah makan	0	0
RSP20250421010	OBT010	24.62	2x1 setiap 12 jam	0	0
RSP20250421001	2018001	10	1x sehari sebelum makan	0	0
RSP20250421001	2018003	5	2x sehari setelah makan	0	0
RSP20250421002	A000000001	2	1x sehari	0	0
RSP20250421002	A000000002	3	2x pagi dan malam	0	0
RSP20250421003	A000000003	1	sehabis makan	0	0
RSP20250421003	A000000004	7	3x sebelum tidur	0	0
RSP20250421004	A000000005	12	setiap pagi	0	0
RSP20250421005	A000000006	20	pagi dan sore	0	0
	2018001	2	2	0	0
	B000002033	10	10	0	0
	B000000003	4	2x2	0	0
	B000000552	5	2x2	0	0
RSP202504221998	B000000552	16	2x2	0	0
RSP202504229734	B000000552	5	3x1	0	0
RSP202504237598	B000000003	10	2x2	0	0
RSP202504238226	B000000552	5	3x1	0	0
RSP202504255047	B000000552	5	2x2	0	0
RSP202504256269	2018003	10	3x1	0	0
RSP202505027694	2018003	10	3x1	0	0
RSP202505065592	B000000562	25	2x2	0	0
RSP202505065592	B000001729	40	4x1	0	0
RSP202505297487	B000001798	10	3x1	0	0
RSP202505297487	B000000374	5	3x1	0	0
RSP202505304793	B000001729	4	3x1	0	0
RSP202505304793	B000000572	5	3x1	0	0
RSP202505308319	2018003	1	2x1	0	0
RSP202505316952	B000000003	10	3x1	0	0
RSP202505316952	B000001547	20	3x1	0	0
\.

COPY sik.tindakan (nomor_rawat, nomor_rm, nama_pasien, tindakan, kode_dokter, nama_dokter, nip, nama_petugas, tanggal_rawat, jam_rawat, biaya) FROM stdin;
40	2	Aziz	suntik	D001	Dr. Ahmad	75	Agus	2025-06-01	20:20:00	100000
41	3	Don	cuci darah	D001	Dr. Ahmad	55	Mae	2025-05-08	18:00:00	500000
40	2	Aziz	OG.I.102	D001	Dr. Rina Kusuma	\N	Acil	2025-04-16	20:06:47	5000
40	2	Aziz	BU.VIP.4	D001	Dr. Rina Kusuma	\N	Mae	2025-04-18	20:55:10	\N
RSP202504223984	123	Eric	\N	D001	Dr. Rina Kusuma	\N	\N	2025-04-22	11:24:58	\N
202504244462	300	Mae	THT.II.12	D001	D008	\N	Acil	2025-04-25	15:03:07	148750
RW001	RM001	John Doe	PD.VVIP.29	D001	D001	\N	Acil	2025-04-25	20:56:41	198375
20250412256	1	Aziz	PD.VVIP.29	D001	D001	\N	Acil	2025-04-25	21:08:40	198375
RW202504169001	123	Eric	OG.I.102	D001	D001	\N	Acil	2025-05-18	14:04:27	4500000
RW202504169001	123	Eric	OG.I.102	D001	D001	\N	\N	2025-05-26	18:50:23	4500000
202505284371	RM2025052859642	Indira	PD.III.29	D001	D001	\N	\N	2025-05-29	18:43:49	108375
202504254997	300	Mae	UM.HT.041	\N	D001	\N	Acil	2025-05-31	12:39:29	\N
\.

COPY sik.diagnosa_pasien (no_rawat, kd_penyakit, status, prioritas, status_penyakit) FROM stdin;
\.

COPY sik.jns_perawatan_inap  FROM stdin;
\.

COPY sik.stok_obat_pasien (no_permintaan, tanggal, jam, no_rawat, kode_brng, jumlah, kd_bangsal, no_batch, no_faktur, aturan_pakai, jam00, jam01, jam02, jam03, jam04, jam05, jam06, jam07, jam08, jam09, jam10, jam11, jam12, jam13, jam14, jam15, jam16, jam17, jam18, jam19, jam20, jam21, jam22, jam23) FROM stdin;
SP202504290001	2025-04-29	08:00:00	2025/04/29/000001	B000000556	3	101  	B123	F001	3x1	f	f	f	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f
SP202504290003	2025-04-29	08:00:00	2025/04/29/000003	B000000556	2	103  	B125	F003	2x1	f	f	f	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f	f	f	f	f	f	f
SP202504290004	2025-04-29	07:30:00	2025/04/29/000004	2018001	1	104  	B126	F004	1x1	f	f	f	f	f	f	f	f	t	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f
SP202504290005	2025-04-29	16:30:00	2025/04/29/000005	B000001207	2	105  	B127	F005	2x1	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	t	f	f	f
SP202504290002	2025-04-29	21:00:00	202504069396	B000002030	1	102  	B124	F002	1x1 ml	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	t	f	f	f
P20250503001	2025-05-03	09:00:00	RW20250503001	2018001	2	B001 	B123	F001	3x1	t	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f
RSP202505021235	2025-05-02	22:45:00	202504254997	OBT001	2	B001 	BTCH001	FKT20250502	3x sehari	f	f	f	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f
RSP202505021235	2025-05-02	22:45:00	202504254997	OBT002	1	B001 	BTCH002	FKT20250502	2x sehari	f	f	f	f	f	f	f	f	f	t	f	f	f	f	f	f	f	f	t	f	f	f	f	f
SOP202505029321	2025-05-02	23:37:41	202504254997	B000000003	0	B001 	BTCH001	FKT20250502	2x2	f	f	f	f	f	f	f	t	t	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f
SOP202505029322	2025-05-02	23:37:41	202504254997	B000000003	10	B001 	BTCH001	FKT20250502	2x2	f	f	f	f	f	f	f	t	t	f	f	f	f	f	f	f	f	f	f	f	f	f	f	f
SOP202505031426	2025-05-03	12:15:04	202504232512	B000002033	10	B001 	BTCH001	FKT20250502	2x2	f	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f	f	f	f	f	f	f	f	f
SOP202505057213	2025-05-05	14:23:38	202504254997	B000002026	50	B001 	BTCH001	FKT20250502	2x2	f	f	f	f	f	f	f	f	f	f	f	f	t	f	f	f	f	f	t	f	f	f	f	f
SOP202505316071	2025-05-31	00:03:50	202505284371	2018003	10	B001 	BTCH001	FKT20250502	3x1	f	f	f	f	f	f	f	f	f	f	t	f	t	f	f	f	f	f	f	f	f	f	f	f
\.
