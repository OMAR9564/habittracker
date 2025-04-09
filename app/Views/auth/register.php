<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Kayıt Ol</h2>
        
        <form action="<?= site_url('auth/register') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-medium mb-2">Kullanıcı Adı</label>
                <input type="text" name="username" id="username" 
                       class="w-full border <?= (isset($validation) && $validation->hasError('username')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       value="<?= old('username') ?>" required autofocus>
                
                <?php if (isset($validation) && $validation->hasError('username')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('username') ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-gray-500">En az 3 karakter uzunluğunda olmalıdır.</p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="pin" class="block text-gray-700 font-medium mb-2">PIN Kodu (6 Haneli)</label>
                <input type="password" name="pin" id="pin" 
                       class="w-full border <?= (isset($validation) && $validation->hasError('pin')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       minlength="6" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                
                <?php if (isset($validation) && $validation->hasError('pin')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('pin') ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-gray-500">6 haneli PIN kodunuzu girin (Sadece rakam).</p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <label for="confirm_pin" class="block text-gray-700 font-medium mb-2">PIN Kodunu Onayla</label>
                <input type="password" name="confirm_pin" id="confirm_pin" 
                       class="w-full border <?= (isset($validation) && $validation->hasError('confirm_pin')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       minlength="6" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                
                <?php if (isset($validation) && $validation->hasError('confirm_pin')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('confirm_pin') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Kayıt Ol
                </button>
            </div>
            
            <p class="text-center text-gray-600">
                Zaten hesabınız var mı? 
                <a href="<?= site_url('auth') ?>" class="text-purple-600 hover:underline">Giriş Yap</a>
            </p>
        </form>
    </div>
    
    <div class="mt-6 bg-purple-100 border-l-4 border-purple-500 p-4 rounded-lg">
        <h3 class="font-bold mb-2">Güvenlik İpuçları</h3>
        <ul class="text-sm text-gray-700 list-disc list-inside">
            <li>6 haneli PIN kodunuzu unutmayın.</li>
            <li>PIN kodunuzu hiç kimseyle paylaşmayın.</li>
            <li>Kolay tahmin edilebilir kodlar kullanmayın (örn. 123456, 111111).</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?> 