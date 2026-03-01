/**
 * Nepal Toast Notification System
 * For Nepal Government Appointment System
 */

class NepalToast {
    constructor(options = {}) {
      this.defaults = {
        position: "top-right",
        duration: 5000, // ms
        maxToasts: 5,
        animationDuration: 300, // ms
        pauseOnHover: true,
        dismissible: true,
        progressBar: true,
        escapeHtml: true,
      }

      this.settings = { ...this.defaults, ...options }
      this.toasts = []
      this.containerEl = null

      this.init()
    }

    init() {
      // Create container if it doesn't exist
      if (!this.containerEl) {
        this.containerEl = document.createElement("div")
        this.containerEl.className = `nepal-toast-container ${this.settings.position}`
        document.body.appendChild(this.containerEl)
      }

      // Add styles
      this.addStyles()
    }

    addStyles() {
      // Check if styles already exist
      if (document.getElementById("nepal-toast-styles")) {
        return
      }

      const style = document.createElement("style")
      style.id = "nepal-toast-styles"
      style.textContent = `
              .nepal-toast-container {
                  position: fixed;
                  z-index: 9999;
                  display: flex;
                  flex-direction: column;
                  gap: 0.75rem;
                  max-width: 100%;
                  width: 24rem;
                  pointer-events: none;
                  padding: 1rem;
              }

              .nepal-toast-container.top-right {
                  top: 0;
                  right: 0;
                  align-items: flex-end;
              }

              .nepal-toast-container.top-left {
                  top: 0;
                  left: 0;
                  align-items: flex-start;
              }

              .nepal-toast-container.bottom-right {
                  bottom: 0;
                  right: 0;
                  align-items: flex-end;
                  flex-direction: column-reverse;
              }

              .nepal-toast-container.bottom-left {
                  bottom: 0;
                  left: 0;
                  align-items: flex-start;
                  flex-direction: column-reverse;
              }

              .nepal-toast-container.top-center {
                  top: 0;
                  left: 50%;
                  transform: translateX(-50%);
                  align-items: center;
              }

              .nepal-toast-container.bottom-center {
                  bottom: 0;
                  left: 50%;
                  transform: translateX(-50%);
                  align-items: center;
                  flex-direction: column-reverse;
              }

              .nepal-toast {
                  position: relative;
                  display: flex;
                  width: 100%;
                  max-width: 24rem;
                  pointer-events: auto;
                  padding: 1rem;
                  border-radius: 0.5rem;
                  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                  overflow: hidden;
                  transform-origin: top right;
                  animation: nepal-toast-enter ${this.settings.animationDuration}ms ease-out forwards;
                  opacity: 0;
                  transform: translateY(-12px) scale(0.95);
                  border-width: 1px;
                  border-style: solid;
              }

              .nepal-toast.nepal-toast-exiting {
                  animation: nepal-toast-exit ${this.settings.animationDuration}ms ease-in forwards;
              }

              .nepal-toast-icon {
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  flex-shrink: 0;
                  width: 1.5rem;
                  height: 1.5rem;
                  margin-right: 0.75rem;
              }

              .nepal-toast-content {
                  flex: 1;
                  min-width: 0;
              }

              .nepal-toast-title {
                  margin: 0;
                  font-size: 0.875rem;
                  font-weight: 600;
                  line-height: 1.25rem;
                  margin-bottom: 0.25rem;
              }

              .nepal-toast-message {
                  margin: 0;
                  font-size: 0.875rem;
                  line-height: 1.25rem;
                  color: inherit;
                  opacity: 0.9;
              }

              .nepal-toast-close {
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  flex-shrink: 0;
                  margin-left: 0.75rem;
                  width: 1.25rem;
                  height: 1.25rem;
                  border-radius: 0.25rem;
                  background: transparent;
                  border: none;
                  cursor: pointer;
                  opacity: 0.5;
                  transition: opacity 0.2s ease;
                  padding: 0;
              }

              .nepal-toast-close:hover {
                  opacity: 1;
              }

              .nepal-toast-progress {
                  position: absolute;
                  bottom: 0;
                  left: 0;
                  height: 3px;
                  background-color: rgba(255, 255, 255, 0.7);
                  transition-property: width;
                  transition-timing-function: linear;
              }

              /* Toast types */
              .nepal-toast-success {
                  background-color: #D1FAE5;
                  color: #065F46;
                  border-color: #A7F3D0;
              }

              .nepal-toast-error {
                  background-color: #FEE2E2;
                  color: #B91C1C;
                  border-color: #FECACA;
              }

              .nepal-toast-warning {
                  background-color: #FEF3C7;
                  color: #92400E;
                  border-color: #FDE68A;
              }

              .nepal-toast-info {
                  background-color: #DBEAFE;
                  color: #1E40AF;
                  border-color: #BFDBFE;
              }

              .nepal-toast-nepal {
                  background-color: #E0F2FE;
                  color: #003893;
                  border-color: #BAE6FD;
              }

              /* Dark mode */
              @media (prefers-color-scheme: dark) {
                  .nepal-toast-success {
                      background-color: #065F46;
                      color: #D1FAE5;
                      border-color: #047857;
                  }

                  .nepal-toast-error {
                      background-color: #B91C1C;
                      color: #FEE2E2;
                      border-color: #991B1B;
                  }

                  .nepal-toast-warning {
                      background-color: #92400E;
                      color: #FEF3C7;
                      border-color: #78350F;
                  }

                  .nepal-toast-info {
                      background-color: #1E40AF;
                      color: #DBEAFE;
                      border-color: #1E3A8A;
                  }

                  .nepal-toast-nepal {
                      background-color: #003893;
                      color: #E0F2FE;
                      border-color: #002D74;
                  }
              }

              /* Animations */
              @keyframes nepal-toast-enter {
                  0% {
                      opacity: 0;
                      transform: translateY(-12px) scale(0.95);
                  }
                  100% {
                      opacity: 1;
                      transform: translateY(0) scale(1);
                  }
              }

              @keyframes nepal-toast-exit {
                  0% {
                      opacity: 1;
                      transform: translateX(0);
                  }
                  100% {
                      opacity: 0;
                      transform: translateX(100%);
                  }
              }

              /* Mobile adjustments */
              @media (max-width: 640px) {
                  .nepal-toast-container {
                      width: 100%;
                      padding: 0.5rem;
                  }

                  .nepal-toast-container.top-center,
                  .nepal-toast-container.bottom-center {
                      left: 0;
                      transform: none;
                      align-items: stretch;
                      width: 100%;
                  }

                  .nepal-toast {
                      max-width: 100%;
                      border-radius: 0.375rem;
                  }
              }

              /* Dark mode progress bar */
              @media (prefers-color-scheme: dark) {
                  .nepal-toast-progress {
                      background-color: rgba(255, 255, 255, 0.7);
                  }
              }
          `

      document.head.appendChild(style)
    }

    /**
     * Show a toast notification
     * @param {Object} options - Toast options
     * @param {string} options.title - Toast title
     * @param {string} options.message - Toast message
     * @param {string} options.type - Toast type (success, error, warning, info, nepal)
     * @param {number} options.duration - Duration in ms
     * @param {boolean} options.dismissible - Whether the toast can be dismissed
     * @param {boolean} options.progressBar - Whether to show a progress bar
     * @param {string} options.icon - Custom icon HTML
     * @returns {Object} - Toast instance
     */
    show(options = {}) {
      const settings = { ...this.settings, ...options }

      // Limit the number of toasts
      if (this.toasts.length >= settings.maxToasts) {
        // Remove the oldest toast
        this.dismiss(this.toasts[0].id)
      }

      // Create toast element
      const toastEl = document.createElement("div")
      const toastId = "nepal-toast-" + Date.now() + "-" + Math.floor(Math.random() * 1000)
      toastEl.id = toastId
      toastEl.className = `nepal-toast nepal-toast-${settings.type || "info"}`

      // Escape HTML if needed
      const title = settings.escapeHtml ? this.escapeHtml(settings.title || "") : settings.title || ""
      const message = settings.escapeHtml ? this.escapeHtml(settings.message || "") : settings.message || ""

      // Create toast content
      toastEl.innerHTML = `
              <div class="nepal-toast-icon">
                  ${settings.icon || this.getIconForType(settings.type)}
              </div>
              <div class="nepal-toast-content">
                  ${title ? `<h4 class="nepal-toast-title">${title}</h4>` : ""}
                  ${message ? `<div class="nepal-toast-message">${message}</div>` : ""}
              </div>
              ${
                settings.dismissible
                  ? `
                  <button type="button" class="nepal-toast-close" aria-label="Close">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <line x1="18" y1="6" x2="6" y2="18"></line>
                          <line x1="6" y1="6" x2="18" y2="18"></line>
                      </svg>
                  </button>
              `
                  : ""
              }
              ${
                settings.progressBar && settings.duration
                  ? `
                  <div class="nepal-toast-progress"></div>
              `
                  : ""
              }
          `

      // Add to container
      this.containerEl.appendChild(toastEl)

      // Set up progress bar
      let progressBar = null
      if (settings.progressBar && settings.duration) {
        progressBar = toastEl.querySelector(".nepal-toast-progress")
        progressBar.style.width = "100%"
        progressBar.style.transitionDuration = `${settings.duration}ms`

        // Start the progress bar animation after a small delay
        setTimeout(() => {
          progressBar.style.width = "0%"
        }, 10)
      }

      // Set up event listeners
      if (settings.dismissible) {
        const closeBtn = toastEl.querySelector(".nepal-toast-close")
        closeBtn.addEventListener("click", () => {
          this.dismiss(toastId)
        })
      }

      // Set up hover pause
      let timeoutId = null
      if (settings.pauseOnHover) {
        toastEl.addEventListener("mouseenter", () => {
          if (timeoutId) {
            clearTimeout(timeoutId)
          }
          if (progressBar) {
            progressBar.style.transitionProperty = "none"
          }
        })

        toastEl.addEventListener("mouseleave", () => {
          if (progressBar) {
            const remaining = (Number.parseFloat(progressBar.style.width) / 100) * settings.duration
            progressBar.style.transitionProperty = "width"
            progressBar.style.transitionDuration = `${remaining}ms`
            progressBar.style.width = "0%"
          }

          timeoutId = setTimeout(() => {
            this.dismiss(toastId)
          }, settings.duration)
        })
      }

      // Auto dismiss
      if (settings.duration) {
        timeoutId = setTimeout(() => {
          this.dismiss(toastId)
        }, settings.duration)
      }

      // Store toast data
      const toastData = {
        id: toastId,
        element: toastEl,
        timeoutId,
        settings,
      }

      this.toasts.push(toastData)

      return toastData
    }

    /**
     * Dismiss a toast by ID
     * @param {string} id - Toast ID
     */
    dismiss(id) {
      const index = this.toasts.findIndex((toast) => toast.id === id)
      if (index === -1) return

      const toast = this.toasts[index]
      const { element, timeoutId } = toast

      // Clear timeout
      if (timeoutId) {
        clearTimeout(timeoutId)
      }

      // Add exit animation
      element.classList.add("nepal-toast-exiting")

      // Remove after animation
      setTimeout(() => {
        if (element.parentNode) {
          element.parentNode.removeChild(element)
        }
        this.toasts.splice(index, 1)
      }, this.settings.animationDuration)
    }

    /**
     * Dismiss all toasts
     */
    dismissAll() {
      ;[...this.toasts].forEach((toast) => {
        this.dismiss(toast.id)
      })
    }

    /**
     * Show a success toast
     * @param {string|Object} titleOrOptions - Title string or options object
     * @param {string} message - Message (if first param is title)
     * @returns {Object} - Toast instance
     */
    success(titleOrOptions, message) {
      const options =
        typeof titleOrOptions === "string"
          ? { title: titleOrOptions, message, type: "success" }
          : { ...titleOrOptions, type: "success" }

      return this.show(options)
    }

    /**
     * Show an error toast
     * @param {string|Object} titleOrOptions - Title string or options object
     * @param {string} message - Message (if first param is title)
     * @returns {Object} - Toast instance
     */
    error(titleOrOptions, message) {
      const options =
        typeof titleOrOptions === "string"
          ? { title: titleOrOptions, message, type: "error" }
          : { ...titleOrOptions, type: "error" }

      return this.show(options)
    }

    /**
     * Show a warning toast
     * @param {string|Object} titleOrOptions - Title string or options object
     * @param {string} message - Message (if first param is title)
     * @returns {Object} - Toast instance
     */
    warning(titleOrOptions, message) {
      const options =
        typeof titleOrOptions === "string"
          ? { title: titleOrOptions, message, type: "warning" }
          : { ...titleOrOptions, type: "warning" }

      return this.show(options)
    }

    /**
     * Show an info toast
     * @param {string|Object} titleOrOptions - Title string or options object
     * @param {string} message - Message (if first param is title)
     * @returns {Object} - Toast instance
     */
    info(titleOrOptions, message) {
      const options =
        typeof titleOrOptions === "string"
          ? { title: titleOrOptions, message, type: "info" }
          : { ...titleOrOptions, type: "info" }

      return this.show(options)
    }

    /**
     * Show a Nepal-branded toast
     * @param {string|Object} titleOrOptions - Title string or options object
     * @param {string} message - Message (if first param is title)
     * @returns {Object} - Toast instance
     */
    nepal(titleOrOptions, message) {
      const options =
        typeof titleOrOptions === "string"
          ? { title: titleOrOptions, message, type: "nepal" }
          : { ...titleOrOptions, type: "nepal" }

      return this.show(options)
    }

    /**
     * Get icon HTML for toast type
     * @param {string} type - Toast type
     * @returns {string} - Icon HTML
     */
    getIconForType(type) {
      switch (type) {
        case "success":
          return `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                      <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>`
        case "error":
          return `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="15" y1="9" x2="9" y2="15"></line>
                      <line x1="9" y1="9" x2="15" y2="15"></line>
                  </svg>`
        case "warning":
          return `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      <line x1="12" y1="9" x2="12" y2="13"></line>
                      <line x1="12" y1="17" x2="12.01" y2="17"></line>
                  </svg>`
        case "nepal":
          return `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                      <polyline points="9 22 9 12 15 12 15 22"></polyline>
                  </svg>`
        case "info":
        default:
          return `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="12" y1="16" x2="12" y2="12"></line>
                      <line x1="12" y1="8" x2="12.01" y2="8"></line>
                  </svg>`
      }
    }

    /**
     * Escape HTML to prevent XSS
     * @param {string} html - HTML string
     * @returns {string} - Escaped HTML
     */
    escapeHtml(html) {
      const div = document.createElement("div")
      div.textContent = html
      return div.innerHTML
    }
  }

  // Initialize the toast notification system
  const nepalToast = new NepalToast()

  // Make it globally available
  window.nepalToast = nepalToast
