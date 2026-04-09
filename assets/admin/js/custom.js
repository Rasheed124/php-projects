// assets/js/custom-post.js

console.log("Custom Post JS Loaded!");

document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Choices.js Initialization ---
  const tagElement = document.getElementById("post-create-tag-select");
  if (tagElement) {
    // Initialize Choices.js
    new Choices(tagElement, {
      removeItemButton: true,
      placeholderValue: "Select tags",
      allowHTML: true,
      shouldSort: false,
    });
  }

  // --- 2. Auto-Slug Generation ---
  const titleInput = document.getElementById("post-create-title");
  const slugInput = document.getElementById("post-create-slug");

  if (titleInput && slugInput) {
    titleInput.addEventListener("input", function () {
      // Generate slug format
      const slug = titleInput.value
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, "") // Remove special chars
        .replace(/[\s_-]+/g, "-") // Replace spaces/underscores with -
        .replace(/^-+|-+$/g, ""); // Remove leading/trailing -

      slugInput.value = slug;
    });
  }
// ===========================================================================================
// CATEGORY JS
function editCategory(id, name) {
    document.getElementById('cat-form-title').innerText = 'Edit Category: ' + name;
    document.getElementById('cat-id').value = id;
    document.getElementById('cat-name').value = name;
    document.getElementById('cat-submit-btn').innerText = 'Update Category';
}
function resetCatForm() {
    document.getElementById('cat-form-title').innerText = 'Create Category';
    document.getElementById('cat-id').value = '0';
    document.getElementById('cat-name').value = '';
    document.getElementById('cat-submit-btn').innerText = 'Save Category';
}

// TAG JS
function editTag(id, name) {
    document.getElementById('tag-form-title').innerText = 'Edit Tag: ' + name;
    document.getElementById('tag-id').value = id;
    document.getElementById('tag-name').value = name;
    document.getElementById('tag-submit-btn').innerText = 'Update Tag';
}
function resetTagForm() {
    document.getElementById('tag-form-title').innerText = 'Create Tag';
    document.getElementById('tag-id').value = '0';
    document.getElementById('tag-name').value = '';
    document.getElementById('tag-submit-btn').innerText = 'Save Tag';
}
});
