/* ============================================================
   1) ANTI-FLASH — à placer dans le <head>, AVANT tout le reste.
   Applique la classe "dark" sur <html> avant le premier rendu.
   Défaut = light : dark uniquement si l'utilisateur l'a choisi.
   ============================================================ */
(function () {
    let saved = null;
    try { saved = localStorage.getItem('theme'); } catch (e) {}

    if (saved === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();


/* ============================================================
   2) STORES ALPINE — thème + sidebar
   ============================================================ */
document.addEventListener('alpine:init', () => {

    Alpine.store('theme', {
        theme: 'light',

        // Appelé automatiquement par Alpine à l'enregistrement du store
        init() {
            let saved = null;
            try { saved = localStorage.getItem('theme'); } catch (e) {}

            // Défaut : light (le thème du système est ignoré)
            this.theme = saved === 'dark' ? 'dark' : 'light';
            this.updateTheme();
        },

        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            try { localStorage.setItem('theme', this.theme); } catch (e) {}
            this.updateTheme();
        },

        updateTheme() {
            const html = document.documentElement;
            const body = document.body;
            const isDark = this.theme === 'dark';

            html.classList.toggle('dark', isDark);
            if (body) {
                body.classList.toggle('dark', isDark);
                body.classList.toggle('bg-gray-900', isDark);
            }
        }
    });

    Alpine.store('sidebar', {
        // true sur desktop, false sur mobile
        isExpanded: window.innerWidth >= 1280,
        isMobileOpen: false,
        isHovered: false,

        toggleExpanded() {
            this.isExpanded = !this.isExpanded;
            // Ferme le menu mobile quand on bascule la sidebar desktop
            this.isMobileOpen = false;
        },

        toggleMobileOpen() {
            this.isMobileOpen = !this.isMobileOpen;
        },

        setMobileOpen(val) {
            this.isMobileOpen = val;
        },

        setHovered(val) {
            // Le survol ne joue que sur desktop, sidebar réduite
            if (window.innerWidth >= 1280 && !this.isExpanded) {
                this.isHovered = val;
            }
        }
    });
});


/* ============================================================
   3) BOUTON DE THÈME
   Utilise le store : une seule source de vérité, plus de
   désynchronisation possible entre le bouton et Alpine.
   Dans le HTML : @click="$store.theme.toggle()"
   ou l'ancien appel : onclick="toggleDarkMode()"
   ============================================================ */
function toggleDarkMode() {
    if (window.Alpine && Alpine.store('theme')) {
        Alpine.store('theme').toggle();
        return;
    }

    // Secours si Alpine n'est pas encore chargé
    const isDark = document.documentElement.classList.toggle('dark');
    try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch (e) {}
}


/* ============================================================
   4) Correctif du décalage <body> (ex. barre Google Translate)
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    const observer = new MutationObserver(() => {
        if (document.body.style.top && document.body.style.top !== '0px') {
            document.body.style.top = '0px';
        }
    });
    observer.observe(document.body, { attributes: true, attributeFilter: ['style'] });
});