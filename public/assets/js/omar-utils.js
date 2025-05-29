/**
 * 🧠 Omar BYA - Personal JavaScript Utilities
 * Code aligned with Omar BYA's Personal Style Sheet
 */

// ✅ Rule 1: Naming Style - camelCase for functions
const OmarUtils = {

   // 🤖 Optimized by O.B.
   logTrace: function (message, data = null) {
      console.log('[OMAR-TRACE]', message, data);
   },

   // NOTE: Habit tracking utilities
   calculateSuccessRate: function (completedDays, totalDays) {
      if (totalDays === 0) return 0;
      return Math.round((completedDays / totalDays) * 100);
   },

   // NOTE: Date manipulation helpers
   formatDateForDisplay: function (dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('tr-TR', {
         day: '2-digit',
         month: '2-digit',
         year: 'numeric'
      });
   },

   // NOTE: Animation helpers
   fadeInElement: function (element, duration = 500) {
      element.style.opacity = '0';
      element.style.transform = 'translateY(20px)';
      element.style.transition = `all ${duration}ms ease`;

      setTimeout(() => {
         element.style.opacity = '1';
         element.style.transform = 'translateY(0)';
      }, 10);
   },

   // NOTE: Chart.js configuration
   getDefaultChartConfig: function (type = 'line') {
      return {
         type: type,
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  display: false
               }
            },
            scales: {
               x: {
                  display: true,
                  grid: {
                     display: false
                  }
               },
               y: {
                  display: true,
                  beginAtZero: true,
                  grid: {
                     color: 'rgba(0,0,0,0.1)'
                  }
               }
            }
         }
      };
   },

   // NOTE: Success rate color helper
   getSuccessRateColor: function (percentage) {
      if (percentage >= 70) return 'success-high';
      if (percentage >= 40) return 'success-medium';
      return 'success-low';
   },

   // NOTE: Local storage helpers
   saveToStorage: function (key, data) {
      try {
         localStorage.setItem(`habitTracker_${key}`, JSON.stringify(data));
         this.logTrace('Data saved to storage', { key, data });
      } catch (error) {
         this.logTrace('Storage save failed', error);
      }
   },

   loadFromStorage: function (key) {
      try {
         const data = localStorage.getItem(`habitTracker_${key}`);
         return data ? JSON.parse(data) : null;
      } catch (error) {
         this.logTrace('Storage load failed', error);
         return null;
      }
   },

   // NOTE: Form validation helpers
   validateHabitForm: function (formData) {
      const errors = [];

      if (!formData.name || formData.name.trim().length < 3) {
         errors.push('Alışkanlık adı en az 3 karakter olmalıdır.');
      }

      if (formData.description && formData.description.length > 500) {
         errors.push('Açıklama 500 karakteri geçemez.');
      }

      return {
         isValid: errors.length === 0,
         errors: errors
      };
   },

   // NOTE: Notification helper
   showNotification: function (message, type = 'info', duration = 3000) {
      // TODO: Implement toast notification system
      this.logTrace('Notification', { message, type });

      // Temporary alert implementation
      if (type === 'success') {
         alert(`✅ ${message}`);
      } else if (type === 'error') {
         alert(`❌ ${message}`);
      } else {
         alert(`ℹ️ ${message}`);
      }
   },

   // 🤖 Optimized by O.B. - Enhanced Chart Management
   createScrollableChart: function (containerId, chartData, chartTitle = '') {
      const container = document.getElementById(containerId);
      if (!container) {
         this.logTrace('Container not found', containerId);
         return null;
      }

      // Create chart item wrapper
      const chartItem = document.createElement('div');
      chartItem.className = 'chart-item';

      const chartWrapper = document.createElement('div');
      chartWrapper.className = 'chart-wrapper';

      if (chartTitle) {
         const title = document.createElement('h4');
         title.className = 'text-sm font-semibold mb-2 text-gray-700';
         title.textContent = chartTitle;
         chartWrapper.appendChild(title);
      }

      const canvas = document.createElement('canvas');
      canvas.style.height = '160px';
      chartWrapper.appendChild(canvas);

      chartItem.appendChild(chartWrapper);
      container.appendChild(chartItem);

      // Create chart
      const ctx = canvas.getContext('2d');
      const config = this.getDefaultChartConfig('line');
      config.data = chartData;

      return new Chart(ctx, config);
   },

   // NOTE: Streak management utilities
   getStreakBadgeClass: function (streakType) {
      const streakClasses = {
         excellent: 'streak-badge streak-excellent',
         good: 'streak-badge streak-good',
         starting: 'streak-badge streak-starting',
         none: 'streak-badge streak-none'
      };

      return streakClasses[streakType] || streakClasses.none;
   },

   // NOTE: Format streak display
   formatStreakDisplay: function (streakData) {
      const { current_streak, streak_type, days_since_last } = streakData;

      let message = '';
      let icon = '';

      if (current_streak === 0) {
         message = 'Bugün alışkanlık yapıldı';
         icon = '😔';
      } else if (current_streak === 1) {
         message = '1 gün temiz!';
         icon = '🌱';
      } else if (current_streak < 7) {
         message = `${current_streak} gün temiz!`;
         icon = '💪';
      } else if (current_streak < 30) {
         message = `${current_streak} gün temiz!`;
         icon = '🔥';
      } else {
         message = `${current_streak} gün temiz!`;
         icon = '🏆';
      }

      return {
         message: message,
         icon: icon,
         badgeClass: this.getStreakBadgeClass(streak_type)
      };
   },

   // NOTE: Initialize scrollable charts container
   initializeChartsContainer: function (containerId) {
      const container = document.getElementById(containerId);
      if (container) {
         container.className = 'charts-container';
         this.logTrace('Charts container initialized', containerId);
      }
   }
};

// ✅ Rule 4: Personal Touch - Export for global usage
window.OmarUtils = OmarUtils;

// 🤖 Optimized by O.B. - Auto-initialization
document.addEventListener('DOMContentLoaded', function () {
   OmarUtils.logTrace('Omar Utils initialized successfully');

   // Apply fade-in animation to all cards
   const cards = document.querySelectorAll('.omar-card, .habit-card');
   cards.forEach((card, index) => {
      setTimeout(() => {
         OmarUtils.fadeInElement(card);
      }, index * 100);
   });
}); 