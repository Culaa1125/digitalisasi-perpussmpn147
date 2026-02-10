// public/js/session-manager.js

class SessionManager {
    constructor(config) {
        this.warningTime = (config.lifetime - 2) * 60 * 1000; // 2 menit sebelum expire
        this.sessionLifetime = config.lifetime * 60 * 1000;
        this.extendUrl = config.extendUrl;
        this.logoutUrl = config.logoutUrl;
        this.userType = config.userType; // 'siswa' atau 'admin'
        
        this.warningTimer = null;
        this.logoutTimer = null;
        
        this.init();
    }
    
    init() {
        this.resetTimers();
        this.setupActivityListeners();
    }
    
    resetTimers() {
        // Clear existing timers
        if (this.warningTimer) clearTimeout(this.warningTimer);
        if (this.logoutTimer) clearTimeout(this.logoutTimer);
        
        // Set warning timer
        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, this.warningTime);
        
        // Set logout timer
        this.logoutTimer = setTimeout(() => {
            this.autoLogout();
        }, this.sessionLifetime);
    }
    
    setupActivityListeners() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.resetTimers();
            }, { passive: true });
        });
    }
    
    showWarning() {
        const modal = document.createElement('div');
        modal.id = 'session-warning-modal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-xl p-8 max-w-md mx-4 shadow-2xl">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sesi Akan Berakhir</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Sesi Anda akan berakhir dalam 2 menit. Apakah Anda ingin melanjutkan?
                    </p>
                    <div class="flex gap-3">
                        <button id="session-extend-btn" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition">
                            Lanjutkan Sesi
                        </button>
                        <button id="session-end-btn" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                            Akhiri Sesi
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        document.getElementById('session-extend-btn').addEventListener('click', () => {
            this.extendSession();
            this.closeWarning();
        });
        
        document.getElementById('session-end-btn').addEventListener('click', () => {
            this.manualLogout();
        });
    }
    
    closeWarning() {
        const modal = document.getElementById('session-warning-modal');
        if (modal) modal.remove();
    }
    
    async extendSession() {
        try {
            const response = await fetch(this.extendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                this.resetTimers();
                this.showNotification('Sesi berhasil diperpanjang', 'success');
            }
        } catch (error) {
            console.error('Error extending session:', error);
        }
    }
    
    manualLogout() {
        this.closeWarning();
        window.location.href = this.logoutUrl;
    }
    
    autoLogout() {
        this.closeWarning();
        this.showNotification('Sesi Anda telah berakhir', 'error');
        
        setTimeout(() => {
            const loginRoute = this.userType === 'siswa' 
                ? '/siswa/login' 
                : '/admin/login';
            window.location.href = loginRoute;
        }, 2000);
    }
    
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (window.sessionConfig) {
        new SessionManager(window.sessionConfig);
    }
});