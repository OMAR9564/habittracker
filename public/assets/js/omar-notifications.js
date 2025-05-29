/**
 * 🧠 Omar BYA - Advanced Notification System
 * Code aligned with Omar BYA's Personal Style Sheet
 */

const OmarNotifications = {

   // ✅ Rule 1: Naming Style - camelCase functions
   showToast: function (message, type = 'info', duration = 4000) {
      const toastContainer = this.getOrCreateContainer();
      const toast = this.createToast(message, type, duration);

      toastContainer.appendChild(toast);

      // Animate in
      setTimeout(() => {
         toast.classList.add('show');
      }, 100);

      // Auto remove
      setTimeout(() => {
         this.removeToast(toast);
      }, duration);

      OmarUtils.logTrace('Toast notification shown', { message, type, duration });
   },

   // NOTE: Create toast container if not exists
   getOrCreateContainer: function () {
      let container = document.getElementById('omar-toast-container');

      if (!container) {
         container = document.createElement('div');
         container.id = 'omar-toast-container';
         container.className = 'fixed top-4 right-4 z-50 space-y-2';
         document.body.appendChild(container);
      }

      return container;
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
                        <span class="sr-only">Kapat</span>
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

   // NOTE: Get type-specific CSS classes
   getTypeClass: function (type) {
      const typeClasses = {
         success: 'border-green-400',
         error: 'border-red-400',
         warning: 'border-yellow-400',
         info: 'border-blue-400'
      };

      return typeClasses[type] || typeClasses.info;
   },

   // NOTE: Get type-specific icons
   getIcon: function (type) {
      const icons = {
         success: `<div class="bg-green-100 rounded-full p-1"><svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>`,
         error: `<div class="bg-red-100 rounded-full p-1"><svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>`,
         warning: `<div class="bg-yellow-100 rounded-full p-1"><svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></div>`,
         info: `<div class="bg-blue-100 rounded-full p-1"><svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg></div>`
      };

      return icons[type] || icons.info;
   },

   // NOTE: Create progress bar for toast
   createProgressBar: function (duration) {
      return `
            <div class="mt-3 w-full bg-gray-200 rounded-full h-1">
                <div class="toast-progress bg-purple-600 h-1 rounded-full" style="width: 100%; animation: shrink ${duration}ms linear;"></div>
            </div>
        `;
   },

   // NOTE: Remove toast with animation
   removeToast: function (toast) {
      if (toast) {
         toast.classList.remove('show');
         toast.classList.add('translate-x-full');

         setTimeout(() => {
            if (toast.parentNode) {
               toast.parentNode.removeChild(toast);
            }
         }, 300);
      }
   },

   // NOTE: Convenience methods
   success: function (message, duration = 4000) {
      this.showToast(message, 'success', duration);
   },

   error: function (message, duration = 5000) {
      this.showToast(message, 'error', duration);
   },

   warning: function (message, duration = 4500) {
      this.showToast(message, 'warning', duration);
   },

   info: function (message, duration = 4000) {
      this.showToast(message, 'info', duration);
   }
};

// ✅ Rule 4: Personal Touch - Global export
window.OmarNotifications = OmarNotifications;

// Add required CSS styles
const style = document.createElement('style');
style.textContent = `
    .toast-notification.show {
        transform: translateX(0);
    }
    
    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
    
    .toast-close:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
`;
document.head.appendChild(style);

// 🤖 Optimized by O.B. - Initialization log
OmarUtils.logTrace('Omar Notifications system loaded'); 