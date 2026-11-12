import './bootstrap';

/* ==========================================================================
   Thème : clair, sombre ou système
   La même logique est appliquée avant le premier rendu par le script inline
   du layout (voir resources/views/components/theme-script.blade.php).
   ========================================================================== */

const THEME_KEY = 'theme';
const THEME_MODES = ['light', 'dark', 'system'];
const systemDark = window.matchMedia('(prefers-color-scheme: dark)');

function readThemeMode() {
    const saved = localStorage.getItem(THEME_KEY);
    return THEME_MODES.includes(saved) ? saved : 'system';
}

function resolveTheme(mode) {
    if (mode === 'system') {
        return systemDark.matches ? 'dark' : 'light';
    }
    return mode;
}

function applyTheme(mode) {
    const resolved = resolveTheme(mode);
    document.documentElement.classList.toggle('dark', resolved === 'dark');
    document.documentElement.style.colorScheme = resolved;
}

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        mode: readThemeMode(),

        get resolved() {
            return resolveTheme(this.mode);
        },

        set(mode) {
            if (! THEME_MODES.includes(mode)) return;

            this.mode = mode;

            if (mode === 'system') {
                localStorage.removeItem(THEME_KEY);
            } else {
                localStorage.setItem(THEME_KEY, mode);
            }

            applyTheme(mode);
        },

        cycle() {
            const next = THEME_MODES[(THEME_MODES.indexOf(this.mode) + 1) % THEME_MODES.length];
            this.set(next);
        },
    });
});

// Le mode « système » suit les changements de l'OS
systemDark.addEventListener('change', () => {
    if (readThemeMode() === 'system') applyTheme('system');
});

// Synchronisation entre onglets
window.addEventListener('storage', (event) => {
    if (event.key !== THEME_KEY) return;

    const mode = readThemeMode();
    applyTheme(mode);

    if (window.Alpine && Alpine.store('theme')) {
        Alpine.store('theme').mode = mode;
    }
});

applyTheme(readThemeMode());

/* ==========================================================================
   Table du plan de salle (Livewire)
   Le déplacement ne fait que changer la position visuelle ; la position est
   envoyée au serveur uniquement à la fin du glissement.
   ========================================================================== */

document.addEventListener('alpine:init', () => {
    Alpine.data('seatTable', (id) => ({
        id,
        dragging: false,
        moved: false,
        px: 0,
        py: 0,
        ox: 0,
        oy: 0,

        start(e) {
            if (e.button !== 0) return;
            this.dragging = true;
            this.moved = false;
            this.px = e.clientX;
            this.py = e.clientY;
            this.ox = this.$el.offsetLeft;
            this.oy = this.$el.offsetTop;
        },

        move(e) {
            if (! this.dragging) return;

            const dx = e.clientX - this.px;
            const dy = e.clientY - this.py;

            if (! this.moved && Math.abs(dx) + Math.abs(dy) < 5) return;

            this.moved = true;
            this.$el.style.left = Math.max(0, Math.round((this.ox + dx) / 10) * 10) + 'px';
            this.$el.style.top = Math.max(0, Math.round((this.oy + dy) / 10) * 10) + 'px';
        },

        end() {
            if (! this.dragging) return;
            this.dragging = false;

            if (this.moved) {
                this.$wire.moveTable(
                    this.id,
                    parseInt(this.$el.style.left, 10),
                    parseInt(this.$el.style.top, 10),
                );
            }
        },

        select() {
            if (! this.moved) this.$wire.selectTable(this.id);
        },
    }));
});

/* ==========================================================================
   Service worker (mode hors ligne de l'entrée)
   ========================================================================== */

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((error) => {
            console.warn('Service worker non enregistré :', error);
        });
    });
}

/* ==========================================================================
   Installation de l'application (PWA)
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const installBtn = document.getElementById('installBtn');
    let deferredPrompt = null;

    if (! installBtn) return;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installBtn.classList.remove('hidden');
    });

    installBtn.addEventListener('click', async () => {
        if (! deferredPrompt) return;

        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;

        deferredPrompt = null;
        installBtn.classList.add('hidden');

        if (outcome === 'accepted') {
            console.info('Application installée.');
        }
    });
});