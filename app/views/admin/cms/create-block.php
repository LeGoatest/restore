<!-- Clean Prismic Modal -->
<div class="fixed inset-0 flex items-center justify-center p-4 z-50" id="modal-overlay">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full relative">
        
        <!-- Close Button -->
        <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M12.854 3.146a.5.5 0 0 0-.708 0L8 7.293 3.854 3.146a.5.5 0 1 0-.708.708L7.293 8l-4.147 4.146a.5.5 0 0 0 .708.708L8 8.707l4.146 4.147a.5.5 0 0 0 .708-.708L8.707 8l4.147-4.146a.5.5 0 0 0 0-.708z"/>
            </svg>
        </button>

        <div class="p-8">
            <!-- Title -->
            <h1 class="text-2xl font-normal text-gray-700 text-center mb-8">Create new custom type</h1>

            <!-- Type Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                <!-- Repeatable Type -->
                <div class="cursor-pointer border border-gray-200 rounded-lg p-6 hover:border-gray-300 transition-all duration-200 bg-white text-center" 
                     data-type="repeatable" onclick="selectBlockType('repeatable')">
                    
                    <div class="mb-4 flex justify-center">
                        <!-- Simple Repeatable Icon -->
                        <svg width="60" height="45" viewBox="0 0 60 45" fill="none">
                            <!-- Back document -->
                            <rect x="4" y="4" width="32" height="24" rx="2" stroke="#9CA3AF" stroke-width="1.5" fill="white"/>
                            <!-- Front document -->
                            <rect x="8" y="8" width="32" height="24" rx="2" stroke="#6B7280" stroke-width="1.5" fill="white"/>
                            <!-- Green squares -->
                            <rect x="12" y="12" width="6" height="6" fill="#10B981" rx="1"/>
                            <rect x="20" y="12" width="6" height="6" fill="#10B981" rx="1"/>
                            <!-- Content lines -->
                            <rect x="12" y="20" width="12" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="23" width="16" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="26" width="10" height="1.5" fill="#D1D5DB"/>
                        </svg>
                    </div>

                    <h2 class="text-lg font-medium text-gray-700 mb-2">Repeatable Type</h2>
                    <p class="text-sm text-gray-500">Best for multiple instances<br>like blog posts, authors,<br>products...</p>
                </div>

                <!-- Single Type -->
                <div class="cursor-pointer border border-gray-200 rounded-lg p-6 hover:border-gray-300 transition-all duration-200 bg-white text-center" 
                     data-type="single" onclick="selectBlockType('single')">
                    
                    <div class="mb-4 flex justify-center">
                        <!-- Simple Single Icon -->
                        <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
                            <!-- Document with dashed border -->
                            <rect x="8" y="8" width="28" height="28" rx="2" stroke="#9CA3AF" stroke-width="1.5" stroke-dasharray="3,3" fill="white"/>
                            <!-- Small icon -->
                            <rect x="12" y="12" width="4" height="4" fill="#9CA3AF" rx="0.5"/>
                            <!-- Content lines -->
                            <rect x="18" y="12" width="12" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="18" width="20" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="21" width="18" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="24" width="16" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="27" width="19" height="1.5" fill="#D1D5DB"/>
                            <rect x="12" y="30" width="14" height="1.5" fill="#D1D5DB"/>
                        </svg>
                    </div>

                    <h2 class="text-lg font-medium text-gray-700 mb-2">Single Type</h2>
                    <p class="text-sm text-gray-500">Best for a unique page, like<br>the homepage or privacy<br>policy page...</p>
                </div>
            </div>

            <!-- Input Section -->
            <div class="text-center border-t pt-6">
                <input id="mask-name" type="text" placeholder="Enter your type name" 
                       class="w-full text-center text-lg py-3 border-0 border-b border-gray-300 focus:outline-none focus:border-blue-500 bg-transparent mb-4">
                
                <div class="relative mb-6">
                    <input id="mask-id" type="text" readonly
                           class="w-full text-center text-sm py-2 border-0 border-b border-gray-200 focus:outline-none bg-transparent text-gray-500">
                    <span class="placeholder text-xs text-gray-400 absolute left-1/2 transform -translate-x-1/2 -top-4">e.g., Blog post, Product...</span>
                </div>

                <form method="POST" id="block-form">
                    <input type="hidden" id="block-type" name="block_type" value="">
                    <input type="hidden" name="name" id="form-name" value="">
                    <input type="hidden" name="schema" id="form-schema" value="">
                    <input type="hidden" name="template" id="form-template" value="">
                    
                    <button type="submit" disabled id="submit-btn"
                            class="bg-gray-300 text-white px-8 py-3 rounded-md font-medium transition-colors">
                        Create new custom type
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let selectedBlockType = '';

function closeModal() {
    window.location.href = '/admin/cms/blocks';
}

function updateButtonState() {
    const maskNameInput = document.getElementById('mask-name');
    const submitBtn = document.getElementById('submit-btn');
    
    if (!maskNameInput || !submitBtn) return;
    
    const hasName = maskNameInput.value.trim().length > 0;
    const hasType = selectedBlockType.length > 0;
    
    console.log('Button state check:', { hasName, hasType, selectedBlockType }); // Debug log
    
    if (hasName && hasType) {
        submitBtn.disabled = false;
        submitBtn.className = 'bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-md font-medium transition-colors';
    } else {
        submitBtn.disabled = true;
        submitBtn.className = 'bg-gray-300 text-white px-8 py-3 rounded-md font-medium transition-colors';
    }
}

function selectBlockType(type) {
    selectedBlockType = type;
    
    // Update visual selection - remove active from all
    document.querySelectorAll('[data-type]').forEach(card => {
        card.classList.remove('border-blue-500', 'shadow-lg', 'bg-blue-50');
        card.classList.add('border-gray-200', 'bg-white');
    });
    
    // Add active to selected
    const selectedCard = document.querySelector(`[data-type="${type}"]`);
    selectedCard.classList.add('border-blue-500', 'shadow-lg', 'bg-blue-50');
    selectedCard.classList.remove('border-gray-200', 'bg-white');
    
    // Set hidden input
    document.getElementById('block-type').value = type;
    
    // Update button state
    updateButtonState();
    
    // Focus on input after selection
    setTimeout(() => {
        document.getElementById('mask-name').focus();
    }, 100);
}

// Handle ESC key and modal overlay clicks
document.addEventListener('DOMContentLoaded', function() {
    // ESC key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    
    // Click outside modal to close
    document.getElementById('modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Handle input changes
    const maskNameInput = document.getElementById('mask-name');
    const maskIdInput = document.getElementById('mask-id');
    
    maskNameInput.addEventListener('input', function() {
        const value = this.value.trim();
        if (value) {
            // Generate slug from name
            const slug = value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
            maskIdInput.value = slug;
        } else {
            maskIdInput.value = '';
        }
        updateButtonState();
    });
    
    // Handle form submission
    document.getElementById('block-form').addEventListener('submit', function(e) {
        const blockType = document.getElementById('block-type').value;
        const blockName = maskNameInput.value.trim();
        
        if (!blockName || !blockType) {
            e.preventDefault();
            alert('Please select a type and enter a name');
            return;
        }
        
        // Set form values
        document.getElementById('form-name').value = blockName;
        
        // Create default schema based on block type
        let defaultSchema = {};
        let defaultTemplate = '';
        
        if (blockType === 'repeatable') {
            defaultSchema = {
                title: { type: 'title', label: 'Title' },
                description: { type: 'rich_text', label: 'Description' },
                image: { type: 'image', label: 'Featured Image' }
            };
            defaultTemplate = `<div class="bg-white rounded-lg p-6 shadow-sm">
    <h2 class="text-xl font-semibold mb-3">{{title}}</h2>
    <div class="mb-4">{{image}}</div>
    <div class="text-gray-600">{{description}}</div>
</div>`;
        } else if (blockType === 'single') {
            defaultSchema = {
                title: { type: 'title', label: 'Page Title' },
                content: { type: 'rich_text', label: 'Page Content' }
            };
            defaultTemplate = `<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">{{title}}</h1>
    <div class="prose max-w-none">{{content}}</div>
</div>`;
        }
        
        document.getElementById('form-schema').value = JSON.stringify(defaultSchema);
        document.getElementById('form-template').value = defaultTemplate;
    });
});
</script>