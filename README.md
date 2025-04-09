# Alışkanlık Takipçisi

Bu uygulama, kötü alışkanlıkları bırakmak isteyenler için bir PHP CodeIgniter tabanlı takip sistemidir. Alışkanlıkların düzenli şekilde izlenmesi, ilerlemenin grafiksel olarak sunulması ve kullanıcıyı motive eden bir seviye sistemi ile desteklenmektedir.

## Özellikler

### 🔄 Takip Sistemi
- Kullanıcılar sadece alışkanlığın yapıldığı günleri işaretleyebilir
- Her gün için alışkanlığın kaç kez yapıldığı kaydedilir
- Alışkanlığın yapılmadığı günler otomatik olarak boş geçilir
- Kullanıcıya başarı yüzdesi sunulur

### 🎯 Hedef Sistemi (Kademeli & Sonsuz)
- İlk hedefler kısa süreli olarak başlar (3 gün)
- Her hedef tamamlandığında bir sonraki daha uzun sürer
- Sistem sonsuz döngü şeklinde çalışır
- Her tamamlanan hedefte başarı yüzdesi dinamik olarak güncellenir

### 📈 Görsel Geri Bildirim
- Genel gelişim çizgi grafik (line chart) ile gösterilir
- Grafikler üzerinden düşüşler, yükselişler ve istikrar kolayca gözlemlenebilir

### 🧱 Seviye (Level) Sistemi
- Alışkanlık yapılmadıkça seviye artar
- Alışkanlık tekrarlandığında başarı yüzdesi azalır ve seviye belli oranda düşer
- Kullanıcı tamamen sıfıra dönmez, motivasyon korunur
- Her başarıyla tamamlanan hedefte kullanıcıya ödül olarak seviye artışı verilir

### 🔐 Güvenlik Özellikleri
- Giriş ekranında 6 haneli PIN kodu kullanılır
- Her IP adresine en fazla 3 giriş denemesi hakkı tanınır
- Brute-force saldırılarına karşı korumalıdır

## Teknolojik Altyapı

- **Backend**: PHP (CodeIgniter 4 Framework)
- **Veritabanı**: MySQL
- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Grafikler**: Chart.js
- **Güvenlik**: CodeIgniter'ın yerleşik oturum, throttle ve filtre sistemleri

## Kurulum

1. Repo'yu klonlayın:
```bash
git clone https://github.com/kullaniciadi/aliskanliktakipcisi.git
```

2. Gereksinimleri yükleyin:
```bash
composer install
```

3. `.env` dosyasını yapılandırın:
```bash
cp env .env
```
Daha sonra `.env` dosyasını açarak veritabanı bilgilerinizi ayarlayın.

4. Veritabanı tablolarını oluşturun:
```bash
php spark migrate
```

5. Uygulamayı çalıştırın:
```bash
php spark serve
```

## Kullanım

1. Yeni bir hesap oluşturun veya mevcut hesabınızla giriş yapın
2. "Yeni Alışkanlık Ekle" butonu ile bırakmak istediğiniz alışkanlığı tanımlayın
3. Alışkanlığı yaptığınız günleri kaydetmek için "Kayıt Ekle" butonunu kullanın
4. İlerlemenizi grafik ve istatistiklerle takip edin
5. Hedefleri tamamlayarak seviyenizi yükseltin

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için `LICENSE` dosyasına bakın.
