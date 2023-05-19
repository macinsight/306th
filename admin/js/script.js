// script.js

// Get the dropdown element
const dropdown = document.querySelector('.dropdown-menu');

// Get all the form containers
const formContainers = document.querySelectorAll('.form-container');

// Add event listener to the dropdown
dropdown.addEventListener('click', (event) => {
    // Prevent default link behavior
    event.preventDefault();

    // Get the selected form ID from the clicked dropdown item
    const selectedFormId = event.target.dataset.formId;

    // Hide all form containers
    formContainers.forEach(formContainer => {
        formContainer.style.display = 'none';
    });

    // Show the selected form container
    const selectedForm = document.getElementById(selectedFormId);
    if (selectedForm) {
        selectedForm.style.display = 'block';
    }
});