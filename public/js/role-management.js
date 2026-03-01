/**
 * Role Management JavaScript
 * For Nepal Government Appointment System
 */

class RoleManager {
    constructor() {
      this.initEventListeners()
    }

    initEventListeners() {
      // Initialize event listeners when DOM is ready
      document.addEventListener("DOMContentLoaded", () => {
        this.setupSearchFunctionality()
        this.setupDeleteConfirmations()
        this.setupFormHandling()
        this.setupAnimations()
      })
    }

    setupSearchFunctionality() {
      const searchInput = document.getElementById("search-roles")
      if (!searchInput) return

      searchInput.addEventListener("keyup", () => {
        const value = searchInput.value.toLowerCase()
        const roleCards = document.querySelectorAll(".role-card")
        let visibleCards = 0

        roleCards.forEach((card) => {
          const name = card.dataset.name
          const matchFound = name.indexOf(value) > -1

          if (matchFound) {
            card.classList.remove("hidden")
            visibleCards++
          } else {
            card.classList.add("hidden")
          }
        })

        // Show or hide the "no results" message
        const noResults = document.getElementById("no-results")
        if (noResults) {
          if (visibleCards === 0 && value !== "") {
            noResults.classList.remove("hidden")
          } else {
            noResults.classList.add("hidden")
          }
        }
      })
    }

    setupDeleteConfirmations() {
      const deleteButtons = document.querySelectorAll(".delete-role-btn")

      deleteButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
          e.preventDefault()
          const form = button.closest("form")
          const roleName = button.closest(".role-card").querySelector("h3").textContent.trim()

          if (window.nepalConfirm) {
            window.nepalConfirm
              .show({
                title: "Delete Role",
                message: `Are you sure you want to delete <strong>${roleName}</strong>? This action cannot be undone.`,
                type: "danger",
                confirmText: "Delete Role",
                cancelText: "Cancel",
                confirmIcon: '<span class="iconify" data-icon="tabler:trash" data-width="18"></span>',
              })
              .then(() => {
                // Submit the form if confirmed
                form.submit()

                // Show a toast notification
                if (window.nepalToast) {
                  window.nepalToast.info("Processing", "Deleting role...")
                }
              })
              .catch(() => {
                // Show a toast notification if canceled
                if (window.nepalToast) {
                  window.nepalToast.nepal("Action Canceled", "Role deletion was canceled.")
                }
              })
          } else {
            // Fallback to standard confirmation if nepalConfirm is not available
            if (confirm(`Are you sure you want to delete ${roleName}? This action cannot be undone.`)) {
              form.submit()
            }
          }
        })
      })
    }

    setupFormHandling() {
      const roleForm = document.getElementById("role-form")
      if (!roleForm) return

      // Reset form button
      const resetButton = document.getElementById("reset-form")
      if (resetButton) {
        resetButton.addEventListener("click", () => {
          if (window.nepalConfirm) {
            window.nepalConfirm
              .show({
                title: "Reset Form",
                message: "Are you sure you want to reset the form? All entered data will be lost.",
                type: "warning",
                confirmText: "Yes, Reset",
                cancelText: "Cancel",
                confirmIcon: '<span class="iconify" data-icon="tabler:refresh" data-width="18"></span>',
              })
              .then(() => {
                // Reset the form
                roleForm.reset()

                // Show toast notification
                if (window.nepalToast) {
                  window.nepalToast.info("Form Reset", "The form has been reset.")
                }
              })
              .catch(() => {
                // Show a toast notification if canceled
                if (window.nepalToast) {
                  window.nepalToast.nepal("Action Canceled", "Form reset was canceled.")
                }
              })
          } else {
            // Fallback to standard confirmation
            if (confirm("Are you sure you want to reset the form? All entered data will be lost.")) {
              roleForm.reset()
            }
          }
        })
      }

      // Cancel button
      const cancelButton = document.getElementById("cancel-form")
      if (cancelButton) {
        cancelButton.addEventListener("click", () => {
          if (window.nepalConfirm) {
            window.nepalConfirm
              .show({
                title: "Cancel Form",
                message: "Are you sure you want to cancel? Any unsaved changes will be lost.",
                type: "warning",
                confirmText: "Yes, Cancel",
                cancelText: "No, Continue Editing",
                confirmIcon: '<span class="iconify" data-icon="tabler:x" data-width="18"></span>',
              })
              .then(() => {
                // Redirect back to roles index
                window.location.href = roleForm.dataset.cancelUrl || "/roles"
              })
              .catch(() => {
                // Show a toast notification if canceled
                if (window.nepalToast) {
                  window.nepalToast.nepal("Action Canceled", "You can continue editing the form.")
                }
              })
          } else {
            // Fallback to standard confirmation
            if (confirm("Are you sure you want to cancel? Any unsaved changes will be lost.")) {
              window.location.href = roleForm.dataset.cancelUrl || "/roles"
            }
          }
        })
      }

      // Handle form submission with stay on page logic
      roleForm.addEventListener("submit", (e) => {
        const stayOnPageCheckbox = document.getElementById("stay_on_page")
        if (!stayOnPageCheckbox) return

        if (stayOnPageCheckbox.checked) {
          e.preventDefault()

          const formData = new FormData(roleForm)
          const xhr = new XMLHttpRequest()

          xhr.open("POST", roleForm.action)
          xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")

          xhr.onload = () => {
            if (xhr.status === 200) {
              try {
                const response = JSON.parse(xhr.responseText)

                // Show success message
                if (window.nepalToast) {
                  window.nepalToast.success("Success", "Role created successfully!")
                }

                // Clear the form for a new entry
                document.getElementById("name").value = ""
                // Focus on the name field
                document.getElementById("name").focus()
              } catch (e) {
                // Redirect to the response URL if not valid JSON
                window.location.href = roleForm.action
              }
            } else if (xhr.status === 422) {
              // Handle validation errors
              try {
                const errors = JSON.parse(xhr.responseText).errors

                // Display the first error
                if (errors.name && window.nepalToast) {
                  window.nepalToast.error("Validation Error", errors.name[0])
                } else if (window.nepalToast) {
                  window.nepalToast.error("Error", "There was an error creating the role.")
                }
              } catch (e) {
                if (window.nepalToast) {
                  window.nepalToast.error("Error", "There was an error creating the role.")
                }
              }
            } else {
              if (window.nepalToast) {
                window.nepalToast.error("Error", "There was an error creating the role.")
              }
            }
          }

          xhr.onerror = () => {
            if (window.nepalToast) {
              window.nepalToast.error("Error", "There was an error creating the role.")
            }
          }

          xhr.send(formData)
        }
      })
    }

    setupAnimations() {
      // Add animation to cards
      const roleCards = document.querySelectorAll(".role-card")
      roleCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`
        card.classList.add("animate-fade-in")
      })

      // Animate progress bars on page load
      setTimeout(() => {
        const progressBars = document.querySelectorAll(".progress-value")
        progressBars.forEach((bar) => {
          const width = bar.style.width
          bar.style.width = "0"
          setTimeout(() => {
            bar.style.width = width
          }, 300)
        })
      }, 500)
    }
  }

  // Initialize the Role Manager
  const roleManager = new RoleManager()
