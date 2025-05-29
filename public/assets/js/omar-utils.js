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
      return date.toLocaleDateString('en-US', {
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
         errors.push('Habit name must be at least 3 characters long.');
      }

      if (formData.description && formData.description.length > 500) {
         errors.push('Description cannot exceed 500 characters.');
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

   // NOTE: Level calculation helper
   calculateLevel: function (successRate, completedDays) {
      return Math.floor((successRate / 10) + (completedDays / 7));
   },

   // NOTE: Progress bar animation
   animateProgressBar: function (element, targetWidth, duration = 1000) {
      let width = 0;
      const increment = targetWidth / (duration / 16);

      const animate = () => {
         if (width < targetWidth) {
            width += increment;
            element.style.width = `${Math.min(width, targetWidth)}%`;
            requestAnimationFrame(animate);
         }
      };

      requestAnimationFrame(animate);
   },

   // NOTE: Chart data formatter
   formatChartData: function (logs, days = 7) {
      const data = [];
      const labels = [];
      const today = new Date();

      for (let i = days - 1; i >= 0; i--) {
         const date = new Date(today);
         date.setDate(date.getDate() - i);
         const dateStr = date.toISOString().split('T')[0];

         labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));

         const logForDate = logs.find(log => log.date === dateStr);
         data.push(logForDate ? logForDate.count : 0);
      }

      return { labels, data };
   },

   // 🤖 Optimized by O.B. - Revolutionary design helpers
   initGlassmorphismEffects: function () {
      const cards = document.querySelectorAll('.glass-container, .habit-card');
      cards.forEach((card, index) => {
         card.style.animationDelay = `${index * 0.1}s`;
         card.classList.add('fade-in');
      });
   },

   // NOTE: Streak calculation helper
   calculateStreak: function (logs) {
      if (!logs || logs.length === 0) return { current: 0, max: 0 };

      logs.sort((a, b) => new Date(b.date) - new Date(a.date));

      let currentStreak = 0;
      let maxStreak = 0;
      let tempStreak = 0;

      for (let i = 0; i < logs.length; i++) {
         const currentDate = new Date(logs[i].date);
         const nextDate = i < logs.length - 1 ? new Date(logs[i + 1].date) : null;

         tempStreak++;

         if (nextDate) {
            const diffTime = Math.abs(currentDate - nextDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 1) {
               maxStreak = Math.max(maxStreak, tempStreak);
               if (i === 0) currentStreak = tempStreak;
               tempStreak = 0;
            }
         } else {
            maxStreak = Math.max(maxStreak, tempStreak);
            if (i === 0) currentStreak = tempStreak;
         }
      }

      return { current: currentStreak, max: maxStreak };
   }
};

// 🤖 Optimized by O.B. - Global initialization
document.addEventListener('DOMContentLoaded', function () {
   OmarUtils.logTrace('Omar Utils initialized');
   OmarUtils.initGlassmorphismEffects();
}); 