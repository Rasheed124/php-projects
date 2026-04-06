// assets/js/custom-post.js

console.log("Custom Post JS Loaded!"); 

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Choices.js Initialization ---
    const tagElement = document.getElementById('post-create-tag-select');
    if (tagElement) {
        // Initialize Choices.js
        new Choices(tagElement, {
            removeItemButton: true,
            placeholderValue: 'Select tags',
            allowHTML: true,
            shouldSort: false
        });
    }

    // --- 2. Auto-Slug Generation ---
    const titleInput = document.getElementById('post-create-title');
    const slugInput = document.getElementById('post-create-slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            // Generate slug format
            const slug = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '') // Remove special chars
                .replace(/[\s_-]+/g, '-') // Replace spaces/underscores with -
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing -

            slugInput.value = slug;
        });
    }
});