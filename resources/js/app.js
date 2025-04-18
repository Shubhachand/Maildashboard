import './bootstrap';
import Alpine from 'alpinejs';
import EmailNotification from './components/EmailNotification';

window.Alpine = Alpine;
Alpine.start();

// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference or user's OS preference
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark' || (!savedTheme && darkModeMediaQuery.matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    
    // Update theme toggle button appearance
    updateThemeToggleIcons();
    
    // Listen for dark mode OS changes
    darkModeMediaQuery.addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateThemeToggleIcons();
        }
    });
    
    // Theme toggle button
    const themeToggleBtn = document.getElementById('theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            
            // Save preference to localStorage
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
            
            updateThemeToggleIcons();
            
            // Save preference via AJAX if user is logged in
            if (document.querySelector('meta[name="user-id"]')) {
                const theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                
                fetch("/settings/theme", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        theme_preference: theme
                    })
                }).catch(error => console.error('Error saving theme preference:', error));
            }
        });
    }
});

// Update theme toggle icons based on current theme
function updateThemeToggleIcons() {
    const isDarkMode = document.documentElement.classList.contains('dark');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    
    if (darkIcon && lightIcon) {
        if (isDarkMode) {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        }
    }
}

// Global dropdown closing on outside click
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.dropdown-content');
    
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target) && !dropdown.previousElementSibling.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

// Enable folder sorting on the folders index page
document.addEventListener('DOMContentLoaded', function() {
    const foldersList = document.getElementById('sortable-folders');
    
    if (foldersList && typeof Sortable !== 'undefined') {
        new Sortable(foldersList, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                // Get the new order
                const folderIds = Array.from(document.querySelectorAll('.folder-item'))
                    .map(item => item.getAttribute('data-id'));
                
                // Send the new order to the server
                fetch('/folders/reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        folders: folderIds
                    })
                })
                .catch(error => console.error('Error reordering folders:', error));
            }
        });
    }
});

// Initialize email notifications
if (document.querySelector('meta[name="user-id"]')) {
    const emailNotification = new EmailNotification();
}

// Add confirmation for delete actions
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('form[data-confirm]');
    
    deleteButtons.forEach(form => {
        form.addEventListener('submit', function(event) {
            const confirmMessage = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            
            if (!confirm(confirmMessage)) {
                event.preventDefault();
            }
        });
    });
});

// Enable rich text editor for email composition if available
document.addEventListener('DOMContentLoaded', function() {
    const emailBody = document.getElementById('body');
    
    if (emailBody && typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(emailBody)
            .catch(error => {
                console.error('Error initializing rich text editor:', error);
            });
    }
});