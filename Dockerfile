FROM php:8.2-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev \
    default-mysql-client zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip mbstring xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --no-dev --no-interaction --no-progress --no-scripts

COPY . .

RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

RUN echo '#!/bin/sh\n\
echo "🚀 Starting Siakadku..."\n\
\n\
echo "⏳ Waiting for MySQL..."\n\
until mysqladmin ping -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent; do\n\
  sleep 2\n\
done\n\
echo "✅ DB ready!"\n\
\n\
echo "📝 Setting up migrations table..."\n\
mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" -e "\n\
CREATE TABLE IF NOT EXISTS migrations (\n\
    id int unsigned primary key auto_increment,\n\
    migration varchar(255) not null,\n\
    batch int not null\n\
);\n\
\n\
-- Mark ALL migrations as completed (batch 1)\n\
INSERT IGNORE INTO migrations (migration, batch) VALUES\n\
('\''0001_01_01_000001_create_cache_table'\'', 1),\n\
('\''0001_01_01_000002_create_jobs_table'\'', 1),\n\
('\''2024_01_01_000001_create_users_table'\'', 1),\n\
('\''2024_01_01_000002_create_gurus_table'\'', 1),\n\
('\''2024_01_01_000003_create_kelas_table'\'', 1),\n\
('\''2024_01_01_000004_create_siswas_table'\'', 1),\n\
('\''2024_01_01_000005_create_mapels_table'\'', 1),\n\
('\''2024_01_01_000006_create_jadwals_table'\'', 1),\n\
('\''2024_01_01_000006_create_tahun_ajarans_table'\'', 1),\n\
('\''2024_01_01_000007_create_nilais_table'\'', 1),\n\
('\''2024_01_01_000008_create_absensis_table'\'', 1),\n\
('\''2025_11_04_114627_create_notifications_table'\'', 1),\n\
('\''2025_11_07_000005_add_default_value_to_jenis_kelamin_in_siswas_table'\'', 1),\n\
('\''2025_11_07_154328_add_status_to_nilais_table'\'', 1),\n\
('\''2025_11_07_172337_add_last_seen_to_users_table'\'', 1),\n\
('\''2025_11_07_195916_add_tahun_ajaran_foreign_to_siswas'\'', 1),\n\
('\''2025_11_07_224252_add_foto_profil_to_users_table'\'', 1),\n\
('\''2025_11_07_235904_remove_avatar_column_from_users_table'\'', 1),\n\
('\''2025_11_08_011252_create_semesters_table'\'', 1),\n\
('\''2025_11_08_011310_create_ruangans_table'\'', 1),\n\
('\''2025_11_08_011319_create_ekstrakurikulers_table'\'', 1),\n\
('\''2025_11_08_011331_create_pengumumen_table'\'', 1),\n\
('\''2025_11_08_021414_create_ujian_table'\'', 1),\n\
('\''2025_11_08_021416_create_forum_table'\'', 1),\n\
('\''2025_11_08_021418_create_komentar_forum_table'\'', 1),\n\
('\''2025_11_08_022803_create_rpp_table'\'', 1),\n\
('\''2025_11_08_022807_create_materi_table'\'', 1),\n\
('\''2025_11_08_022809_create_tugas_table'\'', 1),\n\
('\''2025_11_08_022812_create_pesan_table'\'', 1),\n\
('\''2025_11_08_032901_drop_komentar_forum_if_exists'\'', 1),\n\
('\''2025_11_08_155210_create_permission_tables'\'', 1),\n\
('\''2025_11_08_173854_create_settings_table'\'', 1),\n\
('\''2025_11_08_190659_add_nama_to_siswas_table'\'', 1),\n\
('\''2025_11_09_002934_add_subjek_to_pesan_table'\'', 1),\n\
('\''2025_11_09_003541_fix_pesan_table_add_subjek'\'', 1),\n\
('\''2025_11_09_072640_create_kategori_biaya_table'\'', 1),\n\
('\''2025_11_09_072646_create_biaya_spp_table'\'', 1),\n\
('\''2025_11_09_072648_create_tagihan_spp_table'\'', 1),\n\
('\''2025_11_09_072650_create_pembayaran_spp_table'\'', 1),\n\
('\''2025_11_09_072654_create_buku_table'\'', 1),\n\
('\''2025_11_09_072656_create_peminjaman_buku_table'\'', 1),\n\
('\''2025_11_09_072658_create_pengembalian_buku_table'\'', 1),\n\
('\''2025_11_09_072700_create_kategori_inventaris_table'\'', 1),\n\
('\''2025_11_09_072701_create_barang_inventaris_table'\'', 1),\n\
('\''2025_11_09_072703_create_pemeliharaan_table'\'', 1),\n\
('\''2025_11_09_072705_create_peminjaman_inventaris_table'\'', 1),\n\
('\''2025_11_09_072706_create_pendaftaran_ekskul_table'\'', 1),\n\
('\''2025_11_09_072708_create_kegiatan_ekskul_table'\'', 1),\n\
('\''2025_11_09_072710_create_nilai_ekskul_table'\'', 1),\n\
('\''2025_11_09_072712_create_prestasi_table'\'', 1),\n\
('\''2025_11_09_072714_create_layanan_bk_table'\'', 1),\n\
('\''2025_11_09_072716_create_konseling_table'\'', 1),\n\
('\''2025_11_09_072717_create_catatan_bk_table'\'', 1),\n\
('\''2025_11_09_072719_create_alumni_table'\'', 1),\n\
('\''2025_11_09_072721_create_tracer_study_table'\'', 1),\n\
('\''2025_11_09_072722_create_lowongan_kerja_table'\'', 1),\n\
('\''2025_11_09_072724_create_beasiswa_table'\'', 1),\n\
('\''2025_11_09_072726_create_agenda_sekolah_table'\'', 1),\n\
('\''2025_11_09_072728_create_surat_table'\'', 1),\n\
('\''2025_11_09_072730_create_arsip_table'\'', 1),\n\
('\''2025_11_09_072732_create_templates_table'\'', 1),\n\
('\''2025_11_09_072800_create_spp_table'\'', 1),\n\
('\''2025_11_09_202721_create_kategori_buku_table'\'', 1),\n\
('\''2025_11_09_202745_add_foreign_key_to_buku_table'\'', 1),\n\
('\''2025_11_09_202800_fix_nilais_indexes_safely'\'', 1),\n\
('\''2025_11_10_131115_add_foreign_keys_to_layanan_bk_relations'\'', 1),\n\
('\''2025_11_11_001735_create_log_activities_table'\'', 1),\n\
('\''2025_11_11_004234_create_api_quotas_table'\'', 1),\n\
('\''2025_11_11_005422_create_api_keys_table'\'', 1),\n\
('\''2025_11_11_041032_add_timestamps_to_api_quotas_table'\'', 1),\n\
('\''2025_11_11_045849_create_dapodik_sync_logs_table'\'', 1),\n\
('\''2025_11_11_062227_create_dapodik_sync_logs_table'\'', 1),\n\
('\''2025_11_11_063530_create_dapodik_sync_logs_table'\'', 1),\n\
('\''2025_11_11_070952_fix_layanan_bk_relations'\'', 1),\n\
('\''2025_11_11_071106_skip_problematic_migrations'\'', 1),\n\
('\''2025_11_11_135315_add_timestamps_to_api_quotas_table'\'', 1),\n\
('\''2025_11_12_000645_create_guru_mapel_table'\'', 1),\n\
('\''2025_11_12_003219_create_materi_table'\'', 1),\n\
('\''2025_11_12_040936_add_jumlah_tersedia_to_barang_inventaris_table'\'', 1),\n\
('\''2025_11_12_050830_add_kelas_id_to_pengumumen_table'\'', 1),\n\
('\''2025_11_12_141139_create_tugas_siswa_table'\'', 1),\n\
('\''2025_11_12_141939_create_tugas_siswas_table'\'', 1),\n\
('\''2025_11_12_155837_add_online_payment_fields_to_pembayaran_spp_table'\'', 1),\n\
('\''2025_11_12_164655_add_online_payment_fields_to_tagihan_spp_table'\'', 1),\n\
('\''2025_11_12_172807_add_bukti_upload_to_pembayaran_spp_table'\'', 1),\n\
('\''2025_11_13_202417_create_ekstrakurikuler_siswa_table'\'', 1),\n\
('\''2025_11_13_202434_add_kuota_to_ekstrakurikulers_table'\'', 1),\n\
('\''2025_11_14_001046_create_artikel_karir_table'\'', 1),\n\
('\''2025_11_14_011544_create_komentar_forum_table'\'', 1),\n\
('\''2025_11_14_012126_create_forum_likes_table'\'', 1),\n\
('\''2025_11_14_072933_fix_notifications_table'\'', 1),\n\
('\''2025_11_14_073140_recreate_notifications_table'\'', 1),\n\
('\''2025_11_14_073946_create_system_notifications_table'\'', 1),\n\
('\''2025_11_16_000646_add_soft_deletes_to_siswas_and_users_tables'\'', 1),\n\
('\''2025_11_16_174136_add_maintenance_mode_to_settings_table'\'', 1),\n\
('\''2025_11_16_190932_add_maintenance_schedule_to_settings_table'\'', 1),\n\
('\''2025_11_19_174951_add_deleted_at_to_users_table'\'', 1);\n\
" 2>/dev/null || echo "⚠️  Could not setup migrations table, but continuing..."\n\
\n\
php artisan config:cache\n\
php artisan route:cache\n\
\n\
echo "✅ Application ready!"\n\
exec apache2-foreground\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
