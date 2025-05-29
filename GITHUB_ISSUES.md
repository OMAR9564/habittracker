# 🐛 GitHub Issues - Alışkanlık Takipçisi

## 🚨 **Yüksek Öncelikli Issues**

### Issue #1: 📊 Streak Mantığı Eksik
**Öncelik**: Yüksek  
**Etiketler**: `enhancement`, `feature`, `ui/ux`

**Açıklama:**
- Kullanıcı için streak (süreklilik) göstergesi eksik
- Kaç gün üst üste alışkanlık yapmadığı bilgisi yok
- Streak günü görsel olarak gösterilmeli

**Gereksinimler:**
- [ ] Streak hesaplama algoritması
- [ ] Dashboard'da streak göstergesi
- [ ] Habit detay sayfasında streak bilgisi
- [ ] Streak kırıldığında uyarı

---

### Issue #2: 📈 Grafik Mantığı Düzeltme
**Öncelik**: Yüksek  
**Etiketler**: `bug`, `ui/ux`, `charts`

**Açıklama:**
- Tüm grafikler bir yerde toplanmış durumda
- Kaydırmalı (scrollable) grafik sistemi gerekli
- Grafik verilerinin daha iyi organize edilmesi

**Gereksinimler:**
- [ ] Horizontal scroll özelliği
- [ ] Grafik container'ları düzenleme
- [ ] Mobile responsive grafik tasarımı
- [ ] Chart.js konfigürasyonu güncelleme

---

### Issue #3: 🔢 Sayaçlar Bozuk
**Öncelik**: Yüksek  
**Etiketler**: `bug`, `calculation`

**Açıklama:**
- Dashboard'daki sayaçlar doğru hesaplanmıyor
- Başarı oranı hesaplamaları hatalı
- Level sistemi tutarsız

**Gereksinimler:**
- [ ] Sayaç hesaplama algoritmalarını düzelt
- [ ] Başarı oranı formülünü gözden geçir
- [ ] Level hesaplama mantığını kontrol et
- [ ] Test case'leri ekle

---

## 🔄 **Orta Öncelikli Issues**

### Issue #4: 🎨 UI/UX İyileştirmeleri
**Öncelik**: Orta  
**Etiketler**: `enhancement`, `ui/ux`

**Açıklama:**
- Omar BYA standartlarına göre gelişmiş UI
- Toast notification sistemi entegrasyonu
- Responsive tasarım iyileştirmeleri

---

### Issue #5: 📊 Gelişmiş Raporlama
**Öncelik**: Orta  
**Etiketler**: `feature`, `reports`

**Açıklama:**
- CSV export özelliği
- Tarih aralığı filtreleme
- Karşılaştırmalı analiz

---

## 🚀 **Düşük Öncelikli Issues**

### Issue #6: 🔒 Güvenlik İyileştirmeleri
**Öncelik**: Düşük  
**Etiketler**: `security`, `enhancement`

### Issue #7: 📱 PWA Desteği
**Öncelik**: Düşük  
**Etiketler**: `feature`, `pwa`

---

## 📋 **Çözüm Planı**

### 1. **Streak Mantığı (Issue #1)**
```php
// Helper function eklenecek
function calculateCurrentStreak($logs) {
    // Süreklilik hesaplama algoritması
}
```

### 2. **Grafik Düzenleme (Issue #2)**
```css
.charts-container {
    overflow-x: auto;
    white-space: nowrap;
}
```

### 3. **Sayaç Düzeltme (Issue #3)**
```php
// Doğru hesaplama formülleri
$successRate = ($completedDays / $totalDays) * 100;
```

---

**🧠 Omar BYA Notları:**
Bu issue'lar projenin kalitesini artırmak ve kullanıcı deneyimini geliştirmek için belirlendi. 