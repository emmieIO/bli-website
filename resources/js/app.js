import './bootstrap';
import { createIcons, icons } from 'lucide';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import AOS from 'aos';
import 'aos/dist/aos.css';
import 'flowbite';
// import './anime.js'

createIcons({ icons });

// Create an instance of Notyf
window.notyf = new Notyf({
  duration: 7000,
  ripple: true,
  dismissible: true,
  position: { x: 'right', y: 'top' },
  types: [
    {
      type: 'warning',
      className:'text-sm',
      background: 'orange',
      icon: {
        className: 'icon-[mdi-light--alert]',
        tagName: 'span',
        // text: 'warning'
      }
    },
  ]
  // className: 'bg-red-200'
});
window.Alpine = Alpine;
Alpine.plugin(collapse)
AOS.init({
  startEvent: 'DOMContentLoaded'
});


// Start Alpine when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  Alpine.start();
  const modal = document.getElementById("action-modal");
  const titleEl = document.getElementById("action-modal-title");
  const messageEl = document.getElementById("action-modal-message");
  const iconEl = document.getElementById("action-modal-icon");
  const form = document.getElementById("action-modal-form");

  // Handle menu toggle
  const dashboardLayout = document.querySelector(".dashboard_layout");
  const menuToggleBtn = document.querySelector('.toggle-btn');

  if (menuToggleBtn) {
    menuToggleBtn.addEventListener('click', function () {
      dashboardLayout.classList.toggle("toggle-sidebar");
    });
  }
  document.querySelectorAll("[data-modal-target='action-modal']").forEach(button => {
    button.addEventListener('click', () => {
      form.action = button.getAttribute('data-action');
      form.querySelector("input[name='_method']").value = button.getAttribute('data-method') || 'POST'
      titleEl.textContent = button.getAttribute("data-title") || "Confirm Action";
      messageEl.textContent = button.getAttribute("data-message") || "Are you sure you want to proceed with this action?";
      const icon = button.getAttribute("data-icon");
      if (icon) {
        iconEl.innerHTML = icon;
        iconEl.classList.remove("hidden");
      } else {
        iconEl.classList.add("hidden");
      }
      if (window.lucide) lucide.createIcons();
    })
  })


  const feedbackModalTitle = document.getElementById("feedback-modal-title");
  const feedbackModalMessage = document.getElementById("feedback-modal-message");
  const feedbackModalConfirm = document.getElementById('feedback-modal-confirm');
  const feedbackInput = document.getElementById('feedback-modal-feedback');
  const feedbackForm = document.getElementById('feedback-modal-form')

  document.querySelectorAll("[data-modal-target='feedback-modal']").forEach(button => {

    button.addEventListener('click', function () {
      feedbackForm.action = button.getAttribute("data-action")
      feedbackForm.method = button.getAttribute("data-method")
      feedbackForm.querySelector("input[name='_method']").value = button.getAttribute("data-spoofMethod")
      feedbackModalTitle.textContent = button.getAttribute('data-title')
      feedbackModalMessage.textContent = button.getAttribute('data-message')
      feedbackModalConfirm.textContent = button.getAttribute('data-confirm-text')
      feedbackInput.name = button.getAttribute('data-input-name') || 'feedback';
    })
  })
});

// expertise-tags.js
export class ExpertiseTags {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    if (!this.container) return;

    this.tagsContainer = this.container.querySelector('#tags-container');
    this.textInput = this.container.querySelector('#expertise-text-input');
    this.hiddenInput = this.container.querySelector('#expertise-input');
    this.tags = [];

    this.init();
  }

  init() {
    // Initialize with existing tags from data attribute
    const initialTags = JSON.parse(this.container.dataset.tags || '[]');
    this.tags = Array.isArray(initialTags) ? initialTags : [];

    this.renderTags();
    this.setupEventListeners();
  }

  renderTags() {
    this.tagsContainer.innerHTML = '';
    this.tags.forEach((tag, index) => {
      const tagElement = document.createElement('div');
      tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full bg-teal-100 text-teal-800 text-sm';

      const tagText = document.createElement('span');
      tagText.textContent = tag;

      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'ml-1.5 text-teal-600 hover:text-teal-800';
      removeBtn.innerHTML = '<i data-lucide="x" class="w-4 h-4"></i>';

      removeBtn.addEventListener('click', () => this.removeTag(index));

      tagElement.appendChild(tagText);
      tagElement.appendChild(removeBtn);
      this.tagsContainer.appendChild(tagElement);
    });

    // Refresh Lucide icons
    if (window.lucide) {
      window.lucide.createIcons();
    }
  }

  updateHiddenInput() {
    this.hiddenInput.value = JSON.stringify(this.tags);
  }

  addTags() {
    const newTags = this.textInput.value.split(',')
      .map(tag => tag.trim())
      .filter(tag => tag !== '');

    newTags.forEach(tag => {
      if (tag && !this.tags.includes(tag)) {
        this.tags.push(tag);
      }
    });

    this.textInput.value = '';
    this.renderTags();
    this.updateHiddenInput();
  }

  removeTag(index) {
    this.tags.splice(index, 1);
    this.renderTags();
    this.updateHiddenInput();
  }

  setupEventListeners() {
    this.textInput.addEventListener('keydown', (e) => {
      if (['Enter', ','].includes(e.key)) {
        e.preventDefault();
        this.addTags();
      }
    });

    this.textInput.addEventListener('blur', () => this.addTags());
  }
}


