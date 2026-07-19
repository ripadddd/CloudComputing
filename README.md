# Tugas 2 Cloud — Docker Container PHP App

**Nama :** Muhammad Rizki Padillah  
**NPM  :** 2410010252  
**Mata Kuliah :** Cloud Computing  

---

## 📋 Deskripsi

Aplikasi **To-Do List** berbasis PHP yang berjalan di dalam **Docker Container**.  
Menggunakan PHP 8.2 + Apache Web Server sebagai base image.

---

## 📁 Struktur Folder

```
Tugas2_Cloud_Muhammad_Rizki_Padillah_2410010252/
├── app/
│   └── index.php          # Aplikasi PHP To-Do List
├── Dockerfile             # Konfigurasi build image Docker
├── docker-compose.yml     # Konfigurasi jalankan container
└── README.md              # Dokumentasi ini
```

---

## 🚀 Cara Menjalankan

### Prasyarat
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) sudah terinstall dan berjalan

### Langkah-langkah

**1. Clone atau download repository ini**
```bash
git clone https://github.com/USERNAME/Tugas2_Cloud_Muhammad_Rizki_Padillah_2410010252.git
cd Tugas2_Cloud_Muhammad_Rizki_Padillah_2410010252
```

**2. Build dan jalankan container**
```bash
docker-compose up -d
```
> Flag `-d` = detached mode (berjalan di background)

**3. Buka aplikasi di browser**
```
http://localhost:8080
```

**4. Untuk menghentikan container**
```bash
docker-compose down
```

---

## 🐳 Penjelasan Docker

### Dockerfile
| Instruksi | Fungsi |
|---|---|
| `FROM php:8.2-apache` | Base image PHP 8.2 + Apache |
| `WORKDIR` | Set folder kerja di dalam container |
| `COPY app/ /var/www/html/` | Copy file PHP ke dalam container |
| `RUN a2enmod rewrite` | Aktifkan modul Apache |
| `EXPOSE 80` | Buka port 80 di container |
| `CMD` | Jalankan Apache saat container start |

### docker-compose.yml
| Konfigurasi | Nilai | Keterangan |
|---|---|---|
| `container_name` | rizki-todo-app | Nama container |
| `ports` | 8080:80 | Port laptop → port container |
| `volumes` | ./app:/var/www/html | Sinkronisasi folder lokal |
| `restart` | unless-stopped | Auto restart jika crash |
| `networks` | rizki-network | Network bridge |

---

## ✨ Fitur Aplikasi

- ✅ Tambah tugas baru
- ✅ Tandai tugas selesai / belum selesai
- ✅ Hapus tugas
- ✅ Progress bar persentase tugas selesai
- ✅ Tampilan responsif dan modern

---

## 📸 Perintah Docker Berguna

```bash
# Lihat container yang sedang berjalan
docker ps

# Lihat log container
docker-compose logs -f

# Masuk ke dalam container
docker exec -it rizki-todo-app bash

# Rebuild image (jika ada perubahan Dockerfile)
docker-compose up -d --build
```
