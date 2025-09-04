<?php
use App\Core\Security;
?>

<!-- Hero Section Management -->
<div class="px-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div id="save-result"></div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form class="space-y-8 p-6" hx-post="/admin/hero/save" hx-target="#save-result" enctype="multipart/form-data">
                <?= Security::getCsrfField() ?>
                
                <!-- Content Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Content</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hero Title
                            </label>
                            <input type="text" 
                                   name="hero[title]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($hero['title'] ?? '') ?>"
                                   placeholder="Enter hero title">
                            <p class="text-sm text-gray-500 mt-1">Main heading that appears in the hero section</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Subtitle
                            </label>
                            <input type="text" 
                                   name="hero[subtitle]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($hero['subtitle'] ?? '') ?>"
                                   placeholder="Enter subtitle">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="hero[description]" 
                                    class="form-textarea" 
                                    rows="3"
                                    placeholder="Enter hero description"><?= htmlspecialchars($hero['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="space-y-6 pt-6 border-t">
                    <h3 class="text-lg font-medium text-gray-900">Call to Action</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                CTA Button Text
                            </label>
                            <input type="text" 
                                   name="hero[cta_text]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($hero['cta_text'] ?? '') ?>"
                                   placeholder="Enter button text">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                CTA Button URL
                            </label>
                            <input type="text" 
                                   name="hero[cta_url]"
                                   class="form-input" 
                                   value="<?= htmlspecialchars($hero['cta_url'] ?? '') ?>"
                                   placeholder="Enter button URL">
                        </div>
                    </div>
                </div>

                <!-- Background Settings -->
                <div class="space-y-6 pt-6 border-t">
                    <h3 class="text-lg font-medium text-gray-900">Background</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Background Type
                            </label>
                            <select name="hero[background_type]" class="form-select">
                                <option value="image" <?= ($hero['background_type'] ?? 'image') === 'image' ? 'selected' : '' ?>>Image</option>
                                <option value="video" <?= ($hero['background_type'] ?? '') === 'video' ? 'selected' : '' ?>>Video</option>
                                <option value="color" <?= ($hero['background_type'] ?? '') === 'color' ? 'selected' : '' ?>>Solid Color</option>
                                <option value="gradient" <?= ($hero['background_type'] ?? '') === 'gradient' ? 'selected' : '' ?>>Gradient</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Background
                            </label>
                            <?php if (($hero['background_type'] ?? '') === 'image' && !empty($hero['background_value'])): ?>
                                <div class="w-full h-32 bg-gray-100 rounded-lg mb-4">
                                    <img src="<?= htmlspecialchars($hero['background_value']) ?>" 
                                         alt="Current hero background" 
                                         class="w-full h-full object-cover rounded-lg">
                                </div>
                            <?php endif; ?>
                            
                            <!-- File Upload -->
                            <div class="flex items-center justify-center px-6 py-4 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="text-center">
                                    <i class="icon-[heroicons--cloud-arrow-up-20-solid] text-3xl text-gray-400"></i>
                                    <p class="mt-1 text-sm text-gray-500">
                                        <label for="file-upload" class="font-medium text-yellow-600 hover:text-yellow-500 cursor-pointer">
                                            Upload a file
                                        </label>
                                        or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                                <input id="file-upload" 
                                       name="hero[background_file]" 
                                       type="file" 
                                       class="hidden"
                                       accept="image/*,video/*">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Overlay Opacity
                                </label>
                                <div class="flex items-center space-x-4">
                                    <input type="range" 
                                           name="hero[overlay_opacity]"
                                           class="form-range" 
                                           min="0"
                                           max="100"
                                           value="<?= $hero['overlay_opacity'] ?? 50 ?>">
                                    <span class="text-sm text-gray-500"><?= $hero['overlay_opacity'] ?? 50 ?>%</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Text Alignment
                                </label>
                                <select name="hero[text_alignment]" class="form-select">
                                    <option value="left" <?= ($hero['text_alignment'] ?? '') === 'left' ? 'selected' : '' ?>>Left</option>
                                    <option value="center" <?= ($hero['text_alignment'] ?? 'center') === 'center' ? 'selected' : '' ?>>Center</option>
                                    <option value="right" <?= ($hero['text_alignment'] ?? '') === 'right' ? 'selected' : '' ?>>Right</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animation Settings -->
                <div class="space-y-6 pt-6 border-t">
                    <h3 class="text-lg font-medium text-gray-900">Animation</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Animation Style
                        </label>
                        <select name="hero[animation_style]" class="form-select">
                            <option value="none" <?= ($hero['animation_style'] ?? '') === 'none' ? 'selected' : '' ?>>None</option>
                            <option value="fade" <?= ($hero['animation_style'] ?? 'fade') === 'fade' ? 'selected' : '' ?>>Fade</option>
                            <option value="slide" <?= ($hero['animation_style'] ?? '') === 'slide' ? 'selected' : '' ?>>Slide</option>
                            <option value="zoom" <?= ($hero['animation_style'] ?? '') === 'zoom' ? 'selected' : '' ?>>Zoom</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Hero Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Live preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fileInput = document.getElementById('file-upload');
    const dropZone = document.querySelector('.border-dashed');
    
    // Handle file uploads
    fileInput.addEventListener('change', handleFileSelect);
    
    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    // Functions
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        dropZone.classList.add('border-yellow-400', 'bg-yellow-50');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-yellow-400', 'bg-yellow-50');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        
        if (file && (file.type.startsWith('image/') || file.type.startsWith('video/'))) {
            fileInput.files = dt.files;
            handleFileSelect({ target: fileInput });
        }
    }
    
    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = dropZone.previousElementSibling.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                    } else {
                        const newImg = document.createElement('div');
                        newImg.className = 'w-full h-32 bg-gray-100 rounded-lg mb-4';
                        newImg.innerHTML = `<img src="${e.target.result}" alt="Selected background" class="w-full h-full object-cover rounded-lg">`;
                        dropZone.parentElement.insertBefore(newImg, dropZone);
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    }
});
</script>