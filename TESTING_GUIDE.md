# 🍽️ FoodRescue Laravel - Panduan Testing Aplikasi

## 📋 Status Aplikasi
✅ **Server Running**: `http://127.0.0.1:8000`
✅ **Database**: Terkoneksi dan ter-seed
✅ **10 Donasi Sample**: Sudah dibuat dan siap digunakan
✅ **Routes**: Semua route sudah terdaftar dengan benar
✅ **Middleware**: Role-based access control aktif

## 👥 Akun Testing

### 🔧 **Admin Account**
- **Email**: `admin@foodrescue.com`
- **Password**: `password123`
- **Akses**: Semua fitur, kelola donasi, verifikasi user

### 🍽️ **Donor Account** 
- **Email**: `donor@sakura.com`
- **Password**: `password123`
- **Akses**: Buat donasi, kelola donasi sendiri

### 🤝 **Recipient Account**
- **Email**: `recipient@peduli.com`
- **Password**: `password123`
- **Akses**: Lihat donasi, buat permintaan donasi

## 🧪 Langkah Testing

### 1️⃣ **Test sebagai DONOR**
1. Login dengan `donor@sakura.com` / `password123`
2. Akses dashboard: `http://127.0.0.1:8000/dashboard`
3. **Test Create Donation**: 
   - Klik "Create Donation" atau akses: `http://127.0.0.1:8000/donations/create`
   - Isi form donasi baru
   - Submit dan cek apakah tersimpan

### 2️⃣ **Test sebagai RECIPIENT**
1. Login dengan `recipient@peduli.com` / `password123`
2. **Test Food Map**: Akses `http://127.0.0.1:8000/donations`
3. Lihat 10 donasi yang tersedia di peta
4. Klik donasi untuk detail dan buat permintaan

### 3️⃣ **Test sebagai ADMIN**
1. Login dengan `admin@foodrescue.com` / `password123`
2. **Test Admin Panel**: Akses `http://127.0.0.1:8000/admin`
3. Approve/reject donasi pending
4. Kelola users dan reports

## 🗺️ Donasi Sample yang Tersedia

1. **Nasi Kotak Sisa Acara Kantor** (25 kotak) - Jl. Veteran
2. **Roti Tawar dan Kue** (45 bungkus) - Jl. Kawi  
3. **Sayuran Segar** (20 kg) - Pasar Besar Malang
4. **Nasi Gudeg Jogja** (40 porsi) - Jl. Sumbersari
5. **Buah-buahan Segar** (15 kg) - Jl. Gajayana
6. **Mi Ayam dan Bakso** (20 porsi) - Jl. Dinoyo
7. **Nasi Padang Lengkap** (35 porsi) - Jl. Ijen
8. **Snack dan Kue Kering** (25 toples) - Jl. Bendungan Sutami
9. **Soto Ayam dan Lontong** (18 porsi) - Jl. Sukarno Hatta
10. **Paket Sembako dan Beras** (12 paket) - Jl. Raya Tlogomas

## 🔗 URL Testing Utama

- **Home**: `http://127.0.0.1:8000/`
- **Login**: `http://127.0.0.1:8000/login`
- **Dashboard**: `http://127.0.0.1:8000/dashboard`
- **Food Map**: `http://127.0.0.1:8000/donations`
- **Create Donation**: `http://127.0.0.1:8000/donations/create` *(Donor/Admin only)*
- **Admin Panel**: `http://127.0.0.1:8000/admin` *(Admin only)*

## ⚠️ Troubleshooting

### Jika `/donations/create` memberikan 404:
1. Pastikan login sebagai `donor` atau `admin`
2. Cek role user dengan mengakses `/dashboard`
3. Clear cache: `php artisan route:clear`

### Jika middleware error:
1. Pastikan user memiliki role yang benar
2. Logout dan login ulang
3. Cek database tabel `users` kolom `role`

## 🎯 Test Scenarios

1. **Create Donation Flow**: Login donor → Dashboard → Create Donation → Fill form → Submit
2. **View Donations Flow**: Login recipient → Food Map → View donations → Request donation
3. **Admin Approval Flow**: Login admin → Admin panel → Approve donations → Manage users

---
**Status**: Ready for Testing ✅
**Date**: July 6, 2025
