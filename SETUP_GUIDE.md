# 🚀 Alışkanlık Takipçisi - Kurulum Rehberi

## 📋 **Sistem Gereksinimleri**

- **PHP 8.1+** ✅ (Mevcut: 8.4.5)
- **MySQL 5.7+** or **MariaDB 10.3+**
- **Composer** (Dependency management)
- **Web Server** (Apache/Nginx) veya PHP built-in server

## 🛠️ **Kurulum Adımları**

### 1. **📁 Proje Klonlama**
```bash
git clone <repository-url>
cd habittracker
```

### 2. **📦 Bağımlılıkları Yükleme**
```bash
composer install
```

### 3. **⚙️ Environment Konfigürasyonu**
```bash
# .env dosyasını oluştur
cp env .env

# .env dosyasını düzenle:
# - Database bilgilerini gir
# - Encryption key'i ayarla
```

#### **.env Örnek Konfigürasyon:**
```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.forceGlobalSecureRequests = false

database.default.hostname = localhost
database.default.database = habit_tracker_db
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
database.default.port = 3306

encryption.key = hex2bin:2f2958c4f8c8b8c0e8f5d6a7b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8
```

### 4. **🗄️ Veritabanı Kurulumu**
```bash
# MySQL'de database oluştur
mysql -u root -p
CREATE DATABASE habit_tracker_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Migration'ları çalıştır
php spark migrate
```

### 5. **🎨 Statik Dosyalar**
Aşağıdaki dosyaların `public/assets/` dizininde olduğundan emin olun:
- ✅ `css/omar-style.css`
- ✅ `js/omar-utils.js`
- ✅ `js/omar-notifications.js`

### 6. **🚀 Uygulamayı Çalıştırma**
```bash
# Built-in PHP server ile
php spark serve

# Veya port belirterek
php spark serve --port=8080
```

## 🔧 **Opsiyonel Konfigürasyonlar**

### **📊 Database İndexleri (Performans için)**
```sql
USE habit_tracker_db;

CREATE INDEX idx_habits_user_id ON habits(user_id);
CREATE INDEX idx_habit_logs_habit_date ON habit_logs(habit_id, date);
CREATE INDEX idx_goals_habit_active ON goals_history(habit_id, is_active);
```

### **🔒 Production İçin Güvenlik**
```env
# Production .env ayarları
CI_ENVIRONMENT = production
app.forceGlobalSecureRequests = true
app.CSPEnabled = true
```

## 🧪 **Test ve Doğrulama**

### 1. **✅ Sistem Kontrolü**
- [ ] PHP versiyonu (8.1+)
- [ ] Composer çalışıyor
- [ ] MySQL bağlantısı
- [ ] Migration'lar tamamlandı

### 2. **🎯 Fonksiyon Testleri**
1. Kullanıcı kaydı oluştur
2. Giriş yap
3. Yeni alışkanlık ekle
4. Günlük kayıt ekle
5. Dashboard'ı kontrol et
6. Raporları görüntüle

### 3. **🎨 Frontend Testleri**
- [ ] Toast notification'lar çalışıyor
- [ ] Responsive tasarım
- [ ] Chart.js grafikleri
- [ ] Animasyonlar

## 🐛 **Sorun Giderme**

### **Database Bağlantı Hatası**
```bash
# MySQL servisi çalışıyor mu?
sudo systemctl status mysql

# Kullanıcı izinleri doğru mu?
mysql -u root -p
GRANT ALL PRIVILEGES ON habit_tracker_db.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
```

### **Composer Sorunları**
```bash
# Cache temizle
composer clear-cache

# Yeniden yükle
composer install --no-cache
```

### **Asset Dosyaları Yüklenmiyor**
- `public/assets/` dizin izinlerini kontrol edin
- `.htaccess` dosyası doğru mu?
- Web server konfigürasyonu

## 📊 **Performans Optimizasyonu**

### **Production İçin**
```bash
# Composer optimizasyonu
composer install --optimize-autoloader --no-dev

# Cache'leri temizle
php spark cache:clear
```

### **Apache .htaccess (Opsiyonel)**
```apache
# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
</IfModule>

# Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
</IfModule>
```

## 🎯 **Başlangıç Sonrası**

### **İlk Kullanım**
1. `http://localhost:8080` adresine git
2. Yeni kullanıcı hesabı oluştur
3. PIN kodu belirle (6 haneli)
4. İlk alışkanlığını ekle
5. Günlük kayıt yapmaya başla

### **Admin Paneli**
- Şu anda yok, gelecek sürümlerde eklenecek
- Veritabanı üzerinden manuel yönetim

---

## 🧠 **Omar BYA Notları**

Bu kurulum rehberi, alışkanlık takip uygulamasını hızlı ve güvenli bir şekilde çalıştırmanız için hazırlanmıştır. Tüm adımlar Omar BYA kişisel kodlama standartlarına uygun olarak yapılandırılmıştır.

**🎯 Hedef**: Sıfır konfigürasyon ile çalışan, modern ve güvenli bir uygulama. 