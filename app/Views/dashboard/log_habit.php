<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="text-purple-600 hover:text-purple-800 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold"><?= esc($habit['name']) ?> - Kayıt Ekle</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Seviye <?= $habit['level'] ?></span>
                <span class="text-sm font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Hedef: <?= $habit['current_goal'] ?> gün</span>
                <span class="text-sm font-medium bg-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-100 text-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-800 px-2 py-1 rounded-full">Başarı: <?= number_format($habit['success_percentage'], 1) ?>%</span>
            </div>
            
            <?php if (!empty($habit['description'])): ?>
                <p class="text-gray-600 text-sm mt-3"><?= esc($habit['description']) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg mb-6">
            <h3 class="font-bold text-yellow-800 mb-2">Önemli Bilgi</h3>
            <p class="text-sm text-yellow-800">
                Her alışkanlık kaydı, seviyenizi bir miktar düşürür ve başarı yüzdenizi etkiler. 
                Bu, alışkanlığı bırakma sürecinde ilerlemek için doğal bir mekanizmadır. 
                Endişelenmeyin, hedefinizi tamamladığınızda seviyeniz önemli miktarda artacaktır!
            </p>
        </div>
        
        <form action="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label for="date" class="block text-gray-700 font-medium mb-2">Tarih</label>
                <input type="date" name="date" id="date" 
                       class="w-full border <?= (isset($validation) && $validation->hasError('date')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       value="<?= old('date') ?? date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                
                <?php if (isset($validation) && $validation->hasError('date')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('date') ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-gray-500">Alışkanlığı yaptığınız tarihi seçin.</p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="count" class="block text-gray-700 font-medium mb-2">Sayı (Kaç kez yaptınız?)</label>
                <input type="number" name="count" id="count" 
                       class="w-full border <?= (isset($validation) && $validation->hasError('count')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                       value="<?= old('count') ?? 1 ?>" min="1" max="100" required>
                
                <?php if (isset($validation) && $validation->hasError('count')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('count') ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-gray-500">Seçtiğiniz günde alışkanlığı kaç kez yaptığınızı belirtin.</p>
                <?php endif; ?>
            </div>
            
            <div class="mb-6">
                <label for="notes" class="block text-gray-700 font-medium mb-2">Notlar (İsteğe Bağlı)</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full border <?= (isset($validation) && $validation->hasError('notes')) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-purple-600"
                          placeholder="Bu kayıtla ilgili notlarınız..."><?= old('notes') ?></textarea>
                
                <?php if (isset($validation) && $validation->hasError('notes')): ?>
                    <p class="mt-1 text-sm text-red-500"><?= $validation->getError('notes') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex justify-between">
                <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                    İptal
                </a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Kayıt Ekle
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
        <h3 class="font-bold text-blue-800 mb-2">İpucu</h3>
        <p class="text-sm text-blue-800">
            Sadece alışkanlığı yaptığınız günleri kaydedin. Yapmadığınız günler için kayıt eklemenize gerek yok.
            Sistemdeki "Alışkanlık Kaydı", aslında kaçındığınız günlerin takibi için değil, alışkanlığı yaptığınız günleri izlemek içindir.
        </p>
    </div>
</div>

<?= $this->endSection() ?> 