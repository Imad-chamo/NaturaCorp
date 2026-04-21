export default function initNotifications(initialUnread = 0) {
    return {
        open: false,
        notifications: [],
        unreadCount: initialUnread,

        toggle() {
            this.open = !this.open;
            if (this.open && this.notifications.length === 0) {
                this.fetchNotifications();
            }
        },

        fetchNotifications() {
            fetch('/notifications/fetch')
                .then(res => res.json())
                .then(data => {
                    this.notifications = data;
                    this.unreadCount = data.filter(n => !n.est_lu).length;
                });
        },

        markAsRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            }).then(() => {
                const notif = this.notifications.find(n => n.id === id);
                if (notif) {
                    notif.est_lu = true;
                    this.unreadCount = this.notifications.filter(n => !n.est_lu).length;
                }
            });
        },

        markAllAsRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            }).then(() => {
                this.notifications.forEach(n => n.est_lu = true);
                this.unreadCount = 0;
            });
        }
    };
}
