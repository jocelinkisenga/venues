import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                // Initialize based on screen size
                isExpanded: window.innerWidth >= 1280, // true for desktop, false for mobile
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });


        $nextTick(() => {
    const chat = document.querySelector('#chat');
    chat.scrollTop = chat.scrollHeight;
});


// Alpine.start();
// dark mode

   
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        })();
    
// end of darkmode
    // Vérifie la préférence enregistrée ou celle du système
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }


// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Map imports
    if (document.querySelector('#mapOne')) {
        import('./components/map').then(module => module.initMap());
    }

    // Chart imports
    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1').then(module => module.initChartOne());
    }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3').then(module => module.initChartThree());
    }
    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6').then(module => module.initChartSix());
    }
    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8').then(module => module.initChartEight());
    }
    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13').then(module => module.initChartThirteen());
    }

    // Calendar init
    if (document.querySelector('#calendar')) {
        import('./components/calendar-init').then(module => module.calendarInit());
    }
});

//service worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js');
  });
}

let deferredPrompt;

// 1. Capture l'événement d'installation
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault(); // empêche popup automatique
  deferredPrompt = e;

  // optionnel: afficher ton bouton "Installer"
  document.getElementById('installBtn')?.classList.remove('hidden');
});

// 2. Clic sur bouton installation
const installBtn = document.getElementById('installBtn');

installBtn?.addEventListener('click', async () => {
  if (!deferredPrompt) return;

  deferredPrompt.prompt(); // ouvre popup installation

  const { outcome } = await deferredPrompt.userChoice;

  console.log('User choice:', outcome);

  deferredPrompt = null;
  installBtn.style.display = 'none';
});


const el = document.querySelector('.sm\\:w-\\[390px\\]');
if (el) {
  // Simulate the sm: breakpoint styles manually
  await setElementStyles(el, {
    display: 'flex',
    position: 'absolute',
    top: 'auto',
    left: 'auto',
    right: '0',
    bottom: '80px', // sm:bottom-20 (20 * 4px)
    width: '390px',
    height: '560px',
    maxHeight: 'calc(100vh - 8rem)',
    zIndex: '51'
  });
}

const data = {
  applied: !!el,
  newComputedStyles: el ? {
    position: getComputedStyle(el).position,
    width: getComputedStyle(el).width,
    height: getComputedStyle(el).height,
    right: getComputedStyle(el).right,
    bottom: getComputedStyle(el).bottom
  } : null
};