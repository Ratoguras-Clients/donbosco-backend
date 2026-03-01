/**
 * User Management JavaScript
 * Handles user filtering, search, and other interactive features
 */

document.addEventListener("DOMContentLoaded", () => {
    // Initialize search functionality
    initSearch()

    // Initialize delete confirmation
    initDeleteConfirmation()

    // Initialize card animations
    initCardAnimations()

    // Initialize role filters if they exist
    initRoleFilters()

    // Initialize stats counters
    updateStatCounters()
  })

  /**
   * Initialize the search functionality
   */
  function initSearch() {
    const searchInput = document.getElementById("user-search")
    if (!searchInput) return

    searchInput.addEventListener("keyup", function () {
      const searchTerm = this.value.toLowerCase()
      filterUsers(searchTerm)
    })

    // Clear search button
    const clearButton = document.getElementById("clear-search")
    if (clearButton) {
      clearButton.addEventListener("click", function () {
        searchInput.value = ""
        filterUsers("")
        this.classList.add("hidden")
      })
    }
  }

  /**
   * Filter users based on search term
   */
  function filterUsers(searchTerm) {
    const userCards = document.querySelectorAll(".user-card")
    const clearButton = document.getElementById("clear-search")
    let visibleCount = 0

    userCards.forEach((card) => {
      const userName = card.querySelector(".user-name").textContent.toLowerCase()
      const userEmail = card.querySelector(".user-email").textContent.toLowerCase()
      const userMinistry = card.querySelector(".user-ministry")?.textContent.toLowerCase() || ""
      const userRoles = Array.from(card.querySelectorAll(".user-role")).map((role) => role.textContent.toLowerCase())

      if (
        userName.includes(searchTerm) ||
        userEmail.includes(searchTerm) ||
        userMinistry.includes(searchTerm) ||
        userRoles.some((role) => role.includes(searchTerm))
      ) {
        card.classList.remove("hidden")
        visibleCount++
      } else {
        card.classList.add("hidden")
      }
    })

    // Show/hide clear button
    if (clearButton) {
      if (searchTerm) {
        clearButton.classList.remove("hidden")
      } else {
        clearButton.classList.add("hidden")
      }
    }

    // Show/hide empty state
    const emptyState = document.getElementById("empty-search-results")
    if (emptyState) {
      if (visibleCount === 0 && searchTerm) {
        emptyState.classList.remove("hidden")
      } else {
        emptyState.classList.add("hidden")
      }
    }

    // Update stats counters based on visible cards
    updateStatCounters()
  }

  /**
   * Initialize role filters
   */
  function initRoleFilters() {
    const roleFilters = document.querySelectorAll(".role-filter")
    if (roleFilters.length === 0) return

    roleFilters.forEach((filter) => {
      filter.addEventListener("click", function () {
        const role = this.dataset.role

        // Toggle active state
        if (this.classList.contains("bg-[#003893]")) {
          // Deactivate
          this.classList.remove("bg-[#003893]", "text-white")
          this.classList.add("bg-gray-100", "text-gray-800")
        } else {
          // Activate and deactivate others
          roleFilters.forEach((f) => {
            f.classList.remove("bg-[#003893]", "text-white")
            f.classList.add("bg-gray-100", "text-gray-800")
          })
          this.classList.remove("bg-gray-100", "text-gray-800")
          this.classList.add("bg-[#003893]", "text-white")
        }

        filterByRole(role)
      })
    })

    // Add "All" filter
    const allFilter = document.querySelector(".role-filter-all")
    if (allFilter) {
      allFilter.addEventListener("click", function () {
        roleFilters.forEach((f) => {
          f.classList.remove("bg-[#003893]", "text-white")
          f.classList.add("bg-gray-100", "text-gray-800")
        })
        this.classList.remove("bg-gray-100", "text-gray-800")
        this.classList.add("bg-[#003893]", "text-white")

        // Show all cards
        const userCards = document.querySelectorAll(".user-card")
        userCards.forEach((card) => card.classList.remove("hidden"))

        // Update stats
        updateStatCounters()
      })
    }
  }

  /**
   * Filter users by role
   */
  function filterByRole(role) {
    if (!role) {
      // Show all if no role selected
      const userCards = document.querySelectorAll(".user-card")
      userCards.forEach((card) => card.classList.remove("hidden"))
      updateStatCounters()
      return
    }

    const userCards = document.querySelectorAll(".user-card")
    let visibleCount = 0

    userCards.forEach((card) => {
      const userRoles = Array.from(card.querySelectorAll(".user-role")).map((r) => r.dataset.role)

      if (userRoles.includes(role)) {
        card.classList.remove("hidden")
        visibleCount++
      } else {
        card.classList.add("hidden")
      }
    })

    // Show/hide empty state
    const emptyState = document.getElementById("empty-search-results")
    if (emptyState) {
      if (visibleCount === 0) {
        emptyState.classList.remove("hidden")
      } else {
        emptyState.classList.add("hidden")
      }
    }

    // Update stats counters
    updateStatCounters()
  }

  /**
   * Initialize delete confirmation
   */
  function initDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll(".confirm-delete")
    if (deleteButtons.length === 0) return

    deleteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault()
        const message = this.dataset.message || "Are you sure you want to delete this user?"

        // Check if customConfirm is defined
        if (typeof customConfirm === "function") {
          customConfirm(message, () => {
            this.closest("form").submit()
          })
        } else {
          // Fallback to browser confirm
          if (confirm(message)) {
            this.closest("form").submit()
          }
        }
      })
    })
  }

  /**
   * Initialize card animations
   */
  function initCardAnimations() {
    const userCards = document.querySelectorAll(".user-card")

    userCards.forEach((card, index) => {
      // Add staggered animation delay
      card.style.animationDelay = `${index * 0.05}s`

      // Add hover effect
      card.addEventListener("mouseenter", function () {
        this.classList.add("shadow-lg")
        this.classList.remove("shadow-md")
      })

      card.addEventListener("mouseleave", function () {
        this.classList.remove("shadow-lg")
        this.classList.add("shadow-md")
      })
    })
  }

  /**
   * Update stats counters based on visible cards
   */
  function updateStatCounters() {
    // Total users
    const totalCounter = document.getElementById("total-users-count")
    if (totalCounter) {
      const visibleCards = document.querySelectorAll(".user-card:not(.hidden)").length
      totalCounter.textContent = visibleCards
    }

    // Admin users
    const adminCounter = document.getElementById("admin-users-count")
    if (adminCounter) {
      const adminCards = document.querySelectorAll('.user-card:not(.hidden) .user-role[data-role="admin"]').length
      adminCounter.textContent = adminCards
    }

    // Active users (assuming all visible users are active)
    const activeCounter = document.getElementById("active-users-count")
    if (activeCounter) {
      const visibleCards = document.querySelectorAll(".user-card:not(.hidden)").length
      activeCounter.textContent = visibleCards
    }
  }

  /**
   * Initialize the index page
   */
  function initIndexPage() {
    // Any specific index page initialization
  }

  /**
   * Initialize the show page
   */
  function initShowPage() {
    // Any specific show page initialization
  }

  /**
   * Initialize the create/edit page
   */
  function initFormPage() {
    // Password validation
    const passwordInput = document.getElementById("password")
    const confirmPasswordInput = document.getElementById("password_confirmation")

    if (passwordInput && confirmPasswordInput) {
      function validatePassword() {
        if (passwordInput.value && confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
          confirmPasswordInput.setCustomValidity("Passwords don't match")
        } else {
          confirmPasswordInput.setCustomValidity("")
        }
      }

      passwordInput.addEventListener("change", validatePassword)
      confirmPasswordInput.addEventListener("keyup", validatePassword)
    }
  }
