import './bootstrap';
import { createIcons, icons } from 'lucide';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';
import Alpine from 'alpinejs';
import 'flowbite';

createIcons({ icons });

// Create an instance of Notyf
window.notyf = new Notyf({
    duration: 4000,
    ripple: true,
    position: { x: 'right', y: 'bottom' },
    className: 'bg-red-200'
});
window.Alpine = Alpine;

// Start Alpine when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    Alpine.start();

    // Handle menu toggle
    const dashboardLayout = document.querySelector(".dashboard_layout");
    const menuToggleBtn = document.querySelector('.toggle-btn');

    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', function () {
            dashboardLayout.classList.toggle("toggle-sidebar");
        });
    }
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
