/**
 * 🧠 Omar BYA - Advanced Toast Notification System
 * Code aligned with Omar BYA's Personal Style Sheet
 */

// ✅ Rule 1: Naming Style - camelCase functions
const OmarNotifications = {

   // 🤖 Optimized by O.B.
   container: null,
   defaultDuration: 5000,

   // NOTE: Initialize notification system
   init: function () {
      this.createContainer();
      console.log('[OMAR-TRACE] Notification system initialized');
   },

   // NOTE: Create notification container
   createContainer: function () {
      if (this.container) return;

      this.container = document.createElement('div');
      this.container.id = 'omar-notifications-container';
      this.container.className = 'fixed top-5 right-5 z-50 space-y-3 max-w-sm w-full';
      document.body.appendChild(this.container);
   },

   // NOTE: Show success notification
   success: function (message, duration = this.defaultDuration) {
      this.show(message, 'success', duration);
   },

   // NOTE: Show error notification
   error: function (message, duration = this.defaultDuration) {
      this.show(message, 'error', duration);
   },

   // NOTE: Show warning notification
   warning: function (message, duration = this.defaultDuration) {
      this.show(message, 'warning', duration);
   },

   // NOTE: Show info notification
   info: function (message, duration = this.defaultDuration) {
      this.show(message, 'info', duration);
   },

   // NOTE: Main show method
   show: function (message, type = 'info', duration = this.defaultDuration) {
      if (!this.container) this.init();

      const toast = this.createToast(message, type, duration);
      this.container.appendChild(toast);

      // Animate in
      setTimeout(() => {
         toast.classList.remove('translate-x-full');
         toast.classList.add('translate-x-0');
      }, 10);

      // Auto remove
      if (duration > 0) {
         setTimeout(() => {
            this.removeToast(toast);
         }, duration);
      }

      return toast;
   },

   // NOTE: Create individual toast element
   createToast: function (message, type, duration) {
      const toast = document.createElement('div');
      toast.className = `toast-notification bg-white rounded-lg shadow-lg p-4 min-w-80 max-w-sm transform translate-x-full transition-all duration-300 ease-in-out border-l-4 ${this.getTypeClass(type)}`;

      const icon = this.getIcon(type);
      const progressBar = this.createProgressBar(duration);

      toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button type="button" class="toast-close bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="OmarNotifications.removeToast(this.closest('.toast-notification'))">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            ${progressBar}
        `;

      return toast;
   },

   // NOTE: Get type-specific CSS classes - Updated for new palette
   getTypeClass: function (type) {
      const typeClasses = {
         success: 'border-emerald-500 bg-emerald-50',
         error: 'border-red-500 bg-red-50',
         warning: 'border-amber-500 bg-amber-50',
         info: 'border-blue-500 bg-blue-50'
      };

      return typeClasses[type] || typeClasses.info;
   },

   // NOTE: Get type-specific icons
   getIcon: function (type) {
      const icons = {
         success: `<div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>`,
         error: `<div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>`,
         warning: `<div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                  </div>`,
         info: `<div class="flex-shrink-0">
                   <svg class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                       <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                   </svg>
               </div>`
      };

      return icons[type] || icons.info;
   },

   // NOTE: Create progress bar
   createProgressBar: function (duration) {
      if (duration <= 0) return '';

      return `
            <div class="mt-3 w-full bg-gray-200 rounded-full h-1">
                <div class="bg-indigo-600 h-1 rounded-full progress-bar" style="animation: progress ${duration}ms linear;"></div>
            </div>
        `;
   },

   // NOTE: Remove toast notification
   removeToast: function (toast) {
      if (!toast) return;

      toast.classList.add('translate-x-full', 'opacity-0');

      setTimeout(() => {
         if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
         }
      }, 300);
   },

   // NOTE: Clear all notifications
   clearAll: function () {
      if (!this.container) return;

      const toasts = this.container.querySelectorAll('.toast-notification');
      toasts.forEach(toast => this.removeToast(toast));
   },

   // 🤖 Optimized by O.B. - Quick shortcuts
   habitAdded: function (habitName) {
      this.success(`Habit "${habitName}" added successfully!`);
   },

   habitLogged: function (habitName) {
      this.info(`Entry logged for "${habitName}"`);
   },

   goalCompleted: function () {
      this.success('🎉 Congratulations! Goal completed!');
   },

   levelUp: function (newLevel) {
      this.success(`🚀 Level up! You reached Level ${newLevel}`);
   },

   validationError: function (message) {
      this.error(`Validation Error: ${message}`);
   }
};

// 🤖 Optimized by O.B. - Auto-initialize
document.addEventListener('DOMContentLoaded', function () {
   OmarNotifications.init();

   // Add CSS for progress bar animation
   const style = document.createElement('style');
   style.textContent = `
        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        .toast-notification {
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .toast-notification:hover {
            transform: translateX(0) scale(1.02);
        }
    `;
   document.head.appendChild(style);

   console.log('[OMAR-TRACE] Toast notification system ready');
});

// ✅ Rule 4: Personal Touch - Global access
window.OmarNotifications = OmarNotifications; 