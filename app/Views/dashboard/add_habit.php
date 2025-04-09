<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="<?= site_url('dashboard') ?>" class="text-purple-600 hover:text-purple-800 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Yeni Alışkanlık Ekle</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?= site_url('dashboard/habit/add') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Alışkanlık Adı</label>
                <input type="text" name="name" id="name" 
                       class="w-full border <?= (isset($validation) && $validation->hasError('name')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       value="<?= old('name') ?>" placeholder="Örn: Sigara İçmek, Aşırı Yemek, vb." required autofocus>
                
                <?php if (isset($validation) && $validation->hasError('name')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('name') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-medium mb-2">Açıklama (İsteğe Bağlı)</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full border <?= (isset($validation) && $validation->hasError('description')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                          placeholder="Bu alışkanlığı neden bırakmak istiyorsunuz?"><?= old('description') ?></textarea>
                
                <?php if (isset($validation) && $validation->hasError('description')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('description') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-purple-800 mb-2">Başlangıç Bilgileri</h3>
                <p class="text-sm text-gray-700 mb-4">Yeni alışkanlık takibi aşağıdaki değerlerle başlayacaktır:</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Başlangıç Seviyesi</p>
                        <p class="font-bold">0</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">İlk Hedef Süresi</p>
                        <p class="font-bold">3 gün</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Başlangıç Başarı Oranı</p>
                        <p class="font-bold">100%</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Başlangıç Tarihi</p>
                        <p class="font-bold"><?= date('d/m/Y') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="<?= site_url('dashboard') ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                    İptal
                </a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Alışkanlık Ekle
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
        <h3 class="font-bold text-blue-800 mb-2">İpucu</h3>
        <p class="text-sm text-blue-800">
            Sadece alışkanlığı yaptığınız günleri kaydedin. Yapmadığınız günler için kayıt eklemenize gerek yok. 
            Sistem, kayıt eklediğiniz günleri sayarak ilerlemenizi hesaplayacaktır.
        </p>
    </div>
</div>

<?= $this->endSection() ?> 