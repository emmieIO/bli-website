document.addEventListener('DOMContentLoaded', () => {
    // Event deletion modal
    const eventDeleteForm = document.getElementById('delete-event-form');

    window.confirmEventDelete = function (button, eventName) {
        const actionRoute = button.getAttribute('data-delete-route');
        eventDeleteForm.action = actionRoute;
        document.getElementById('event-name').textContent = eventName;
    }

    const tabButtons = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");
    const tabSwitchButtons = document.querySelectorAll(".tab-switch");

    // Get last active tab from localStorage or default to 'happening'
    let activeTab = localStorage.getItem('activeEventTab') || 'happening';

    function setActiveTab(tabName) {
        // Update tab buttons
        tabButtons.forEach(btn => {
            if (btn.dataset.tab === tabName) {
                btn.classList.add('active', 'text-primary', 'border-primary/40', 'shadow-lg');
                btn.classList.remove('text-gray-600', 'border-gray-200');
            } else {
                btn.classList.remove('active', 'text-primary', 'border-primary/40', 'shadow-lg');
                btn.classList.add('text-gray-600', 'border-gray-200');
            }
        });

        // Update tab contents
        tabContents.forEach(content => {
            if (content.id === tabName) {
                content.classList.remove('hidden');
                content.classList.add('active');

                // Add fade-in animation
                content.style.opacity = '0';
                content.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    content.style.transition = 'all 0.5s ease';
                    content.style.opacity = '1';
                    content.style.transform = 'translateY(0)';
                }, 50);
            } else {
                content.classList.add('hidden');
                content.classList.remove('active');
            }
        });

        localStorage.setItem('activeEventTab', tabName);
    }

    // Initial activation
    setActiveTab(activeTab);

    // Tab button click events
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            setActiveTab(this.dataset.tab);

            // Smooth scroll to top of events section
            document.getElementById('events-grid').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Tab switch buttons (from empty states)
    tabSwitchButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            setActiveTab(this.dataset.tab);

            // Smooth scroll to top of events section
            document.getElementById('events-grid').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all tab contents for animation
    tabContents.forEach(content => {
        content.style.opacity = '0';
        content.style.transform = 'translateY(20px)';
        content.style.transition = 'all 0.5s ease';
        observer.observe(content);
    });
});
// Countdown Timer
window.startCountdown = function (targetStartDateTime, targetId, targetEndDateTime) {
    const countdownElement = document.getElementById(targetId);
    let timer; // declare early
    function updateCountdown() {
        const target = new Date(targetStartDateTime).getTime();
        const now = new Date().getTime();
        let diff = target - now;

        if (now > new Date(targetEndDateTime).getTime()) {
            countdownElement.textContent = "Event has ended!";
            clearInterval(timer);
            return;
        }

        if (diff <= 0) {
            countdownElement.textContent = "Event has started!";
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        diff %= (1000 * 60 * 60 * 24);

        const hours = Math.floor(diff / (1000 * 60 * 60));
        diff %= (1000 * 60 * 60);

        const minutes = Math.floor(diff / (1000 * 60));
        diff %= (1000 * 60);

        const seconds = Math.floor(diff / 1000);

        countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    timer = setInterval(updateCountdown, 1000);
};

// expertise logic
const expertiseContainer = document.getElementById('expertise-container')
const expertiseInput = document.getElementById('expertise-input')

expertiseInput?.addEventListener('keypress', function (e) {
    if (e.key === ',') {
        e.preventDefault();
        if (e.target.value.trim() !== "") {
            const pill = document.createElement('span');
            pill.className = 'pill';
            pill.textContent = e.target.value.trim();
            expertiseContainer.appendChild(pill);
            e.target.value = ''; 
        }
    }
})