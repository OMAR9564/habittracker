<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto">
    <!-- Hero Section -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8 text-center">
        <h1 class="text-4xl font-bold text-purple-700 mb-4">Alışkanlık Takipçisi</h1>
        <p class="text-xl text-gray-600 mb-6">Kötü alışkanlıklarınızı bırakmak ve gelişiminizi takip etmek için mükemmel araç</p>
        
        <div class="mt-8">
            <?php if (!session()->get('isLoggedIn')): ?>
                <a href="<?= site_url('auth/register') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition inline-block mr-4">
                    Hemen Başla
                </a>
                <a href="<?= site_url('auth') ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition inline-block">
                    Giriş Yap
                </a>
            <?php else: ?>
                <a href="<?= site_url('dashboard') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition inline-block">
                    Kontrol Panelim
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-purple-100 text-purple-700 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-center mb-2">Basit Takip Sistemi</h2>
            <p class="text-gray-600 text-center">Alışkanlıklarınızı takip etmek için basit ve kolay bir arayüz. Sadece yaptığınız günleri işaretleyin.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-purple-100 text-purple-700 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-center mb-2">Görsel Geri Bildirim</h2>
            <p class="text-gray-600 text-center">İlerlemenizi çizgi grafiklerle görün. Düşüşleri, yükselişleri ve istikrarı kolayca gözlemleyin.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-purple-100 text-purple-700 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-center mb-2">Kademeli Hedefler</h2>
            <p class="text-gray-600 text-center">Kısa süreli hedeflerle başlayıp, kademeli olarak ilerleyin. Her başarı, bir sonraki hedef için motivasyon sağlar.</p>
        </div>
    </div>
    
    <!-- How It Works Section -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-2xl font-bold text-center mb-6">Nasıl Çalışır?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-purple-600 text-white rounded-full">1</div>
                <h3 class="font-semibold mb-2">Kayıt Ol</h3>
                <p class="text-gray-600 text-sm">6 haneli bir PIN oluşturarak hesabınızı güvence altına alın.</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-purple-600 text-white rounded-full">2</div>
                <h3 class="font-semibold mb-2">Alışkanlık Ekle</h3>
                <p class="text-gray-600 text-sm">Bırakmak istediğiniz alışkanlığı tanımlayın ve takip etmeye başlayın.</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-purple-600 text-white rounded-full">3</div>
                <h3 class="font-semibold mb-2">Günlük Giriş</h3>
                <p class="text-gray-600 text-sm">Alışkanlığınızı yaptığınız günleri işaretleyin ve istatistiklerinizi görün.</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center bg-purple-600 text-white rounded-full">4</div>
                <h3 class="font-semibold mb-2">Hedeflere Ulaşın</h3>
                <p class="text-gray-600 text-sm">Başarılarınızla seviyenizi yükseltin ve hedeflerinizi tamamlayın.</p>
            </div>
        </div>
    </div>
    
    <!-- Testimonial Section (Placeholder) -->
    <div class="bg-purple-700 text-white rounded-lg shadow-md p-8 mb-8 text-center">
        <h2 class="text-2xl font-bold mb-6">Kullanıcılarımız Ne Diyor?</h2>
        
        <blockquote class="max-w-2xl mx-auto">
            <p class="text-lg italic mb-4">"Bu uygulama sayesinde sigarayı bırakmak için 3 aydır mücadele ediyorum. Seviye sistemi ve görsel takip beni gerçekten motive ediyor!"</p>
            <cite class="font-semibold">- Ahmet Y., İstanbul</cite>
        </blockquote>
    </div>
    
    <!-- CTA Section -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <h2 class="text-2xl font-bold text-purple-700 mb-4">Hemen Başlayın!</h2>
        <p class="text-gray-600 mb-6">Kötü alışkanlıklarınızı bırakmak için ilk adımı atın ve izleyin.</p>
        
        <?php if (!session()->get('isLoggedIn')): ?>
            <a href="<?= site_url('auth/register') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition inline-block">
                Ücretsiz Hesap Oluştur
            </a>
        <?php else: ?>
            <a href="<?= site_url('dashboard') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition inline-block">
                Kontrol Paneline Git
            </a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?> 