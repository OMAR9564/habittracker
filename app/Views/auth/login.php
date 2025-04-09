<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Giriş Yap</h2>
        
        <form action="<?= site_url('auth/login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-medium mb-2">Kullanıcı Adı</label>
                <input type="text" name="username" id="username" 
                       class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       required autofocus>
            </div>
            
            <div class="mb-6">
                <label for="pin" class="block text-gray-700 font-medium mb-2">PIN Kodu (6 Haneli)</label>
                <input type="password" name="pin" id="pin" 
                       class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       minlength="6" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                <p class="mt-1 text-sm text-gray-500">6 haneli PIN kodunuzu girin.</p>
            </div>
            
            <div class="mb-6">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Giriş Yap
                </button>
            </div>
            
            <p class="text-center text-gray-600">
                Hesabınız yok mu? 
                <a href="<?= site_url('auth/register') ?>" class="text-purple-600 hover:underline">Kayıt Ol</a>
            </p>
        </form>
    </div>
    
    <div class="mt-6 bg-purple-100 border-l-4 border-purple-500 p-4 rounded-lg">
        <h3 class="font-bold mb-2">Güvenlik Bilgisi</h3>
        <p class="text-sm text-gray-700">
            Güvenliğiniz için 3 başarısız giriş denemesinden sonra hesabınız geçici olarak kilitlenecektir.
            PIN kodunuzu hiç kimseyle paylaşmayın.
        </p>
    </div>
</div>

<?= $this->endSection() ?> 