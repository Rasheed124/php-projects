"use strict";

// Array to store selected tags
let selectedTags = [];


console.log("Good day");

// Function to handle adding new tags
function addTag(tag) {
    // Prevent adding empty tags or duplicate tags
    if (tag && !selectedTags.includes(tag)) {
        selectedTags.push(tag);
        updateTagsList();
        document.getElementById('tag-input').value = ''; // Clear the input field
    }
}

// Function to update the displayed tags
function updateTagsList() {
    const tagsList = document.getElementById('tags-list');
    tagsList.innerHTML = ''; // Clear current tags

    // If there are no tags, show the "No tags" message
    if (selectedTags.length === 0) {
        document.getElementById('no-tags-message').style.display = 'block'; // Show "No tags" message
    } else {
        document.getElementById('no-tags-message').style.display = 'none'; // Hide "No tags" message

        // Otherwise, display the tags as small text with delete icons
        selectedTags.forEach((tag, index) => {
            const listItem = document.createElement('li');
            listItem.classList.add('list-inline-item', 'badge', 'badge-secondary', 'mr-2');
            listItem.textContent = tag;

            // Add a delete icon
            const removeIcon = document.createElement('span');
            removeIcon.classList.add('badge', 'badge-light', 'ml-1', 'cursor-pointer');
            removeIcon.innerHTML = '&times;'; // Cross symbol to remove
            removeIcon.onclick = () => removeTag(index); // Remove tag on click

            listItem.appendChild(removeIcon);
            tagsList.appendChild(listItem);
        });
    }

    // Update the hidden input field with the selected tags
    document.getElementById('tags-field').value = JSON.stringify(selectedTags);
}

// Function to handle removing tags
function removeTag(index) {
    selectedTags.splice(index, 1);
    updateTagsList();
}

// Event listener to add tags when Enter key is pressed
document.getElementById('tag-input').addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        const tag = event.target.value.trim();
        addTag(tag);
    }
});