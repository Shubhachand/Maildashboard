class EmailNotification {
    constructor() {
        this.checkInterval = 60000; // 1 minute
        this.lastChecked = 0;
        this.unreadCount = 0;
        this.init();
    }

    init() {
        // Only initialize if user is logged in
        if (document.querySelector('meta[name="user-id"]')) {
            this.userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            
            // Set initial count
            this.updateUnreadBadge();
            
            // Start checking for new emails
            this.startChecking();
            
            // Listen for focus to refresh immediately
            window.addEventListener('focus', () => {
                if (Date.now() - this.lastChecked > 10000) { // Only if last check was more than 10 seconds ago
                    this.checkNewEmails();
                }
            });
        }
    }

    startChecking() {
        // Check immediately
        this.checkNewEmails();
        
        // Then check periodically
        setInterval(() => {
            this.checkNewEmails();
        }, this.checkInterval);
    }

    checkNewEmails() {
        this.lastChecked = Date.now();
        
        fetch('/api/emails/unread-count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.count > this.unreadCount) {
                // We have new emails!
                this.showNotification(data.count - this.unreadCount);
            }
            
            // Update the badge
            this.unreadCount = data.count;
            this.updateUnreadBadge();
        })
        .catch(error => console.error('Error checking for new emails:', error));
    }

    showNotification(newCount) {
        // Check if browser supports notifications
        if (!("Notification" in window)) {
            console.log("This browser does not support desktop notifications");
            return;
        }
        
        // Check if permission is already granted
        if (Notification.permission === "granted") {
            this.createNotification(newCount);
        }
        // Otherwise, request permission
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    this.createNotification(newCount);
                }
            });
        }
    }

    createNotification(newCount) {
        const notification = new Notification("Mail Dashboard", {
            icon: "/favicon.ico",
            body: `You have ${newCount} new ${newCount === 1 ? 'email' : 'emails'}.`
        });
        
        notification.onclick = () => {
            window.focus();
            notification.close();
            window.location.href = '/dashboard';
        };
        
        // Auto close after 5 seconds
        setTimeout(() => {
            notification.close();
        }, 5000);
    }

    updateUnreadBadge() {
        const unreadBadges = document.querySelectorAll('.unread-badge');
        
        unreadBadges.forEach(badge => {
            if (this.unreadCount > 0) {
                badge.textContent = this.unreadCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }
}

export default EmailNotification;