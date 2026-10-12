import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import { gsap } from 'gsap';

window.Alpine = Alpine;
window.Chart = Chart;
window.gsap = gsap;

// HyperFrames timeline registry (seek-safe, deterministic)
window.__timelines = window.__timelines || [];

/**
 * HyperFrames Targeted Motion (Welcome / Hero / Explicit showcase only)
 * Does NOT hide or shift general page content to ensure zero FOUC and blazing fast load.
 */
function initTargetedHyperFramesMotion() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    // Only run choreographed stagger on explicit landing/showcase elements
    const heroShowcase = document.querySelectorAll('[data-animate-hero]');
    if (heroShowcase.length > 0) {
        const tl = gsap.timeline({ defaults: { ease: 'power3.out', duration: 0.5 } });
        tl.fromTo(
            heroShowcase,
            { opacity: 0, y: 16 },
            { opacity: 1, y: 0, stagger: 0.05, clearProps: 'transform,opacity' }
        );
        window.__timelines.push(tl);
    }
}

/**
 * Apple Design Tactile Direct Manipulation Engine (apple-design skill)
 * Instant, zero-latency micro-interaction feedback on active button presses.
 */
function initUniversalAppleTactileFeedback() {
    document.addEventListener('pointerdown', (e) => {
        const target = e.target.closest('button, .apple-button, .apple-pill-interactive, a.inline-flex, input[type="submit"], [role="button"]');
        if (!target || target.disabled || target.getAttribute('aria-disabled') === 'true') return;

        target.style.transition = 'transform 80ms cubic-bezier(0.25, 1, 0.5, 1)';
        target.style.transform = 'scale(0.97)';

        const reset = () => {
            target.style.transition = 'transform 180ms cubic-bezier(0.25, 1, 0.5, 1)';
            target.style.transform = '';
            cleanup();
        };

        const cleanup = () => {
            window.removeEventListener('pointerup', reset);
            window.removeEventListener('pointercancel', reset);
            target.removeEventListener('pointerleave', reset);
        };

        window.addEventListener('pointerup', reset, { once: true });
        window.addEventListener('pointercancel', reset, { once: true });
        target.addEventListener('pointerleave', reset, { once: true });
    }, { passive: true });
}

/**
 * Universal Modal Spring Animator (apple-design & hyperframes-animation)
 * Smooth spring transition when dialogs or modals open, with zero layout shift on background content.
 */
function initUniversalModalSprings() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && (mutation.attributeName === 'class' || mutation.attributeName === 'open')) {
                const target = mutation.target;
                if (target.matches && target.matches('dialog[open], [id*="Modal"]:not(.hidden), .edit-modal:not(.hidden)')) {
                    const content = target.querySelector('.bg-white, [class*="modal-content"], form') || target;
                    if (content && !content.dataset.springAnimated) {
                        content.dataset.springAnimated = 'true';
                        gsap.fromTo(content, 
                            { opacity: 0, scale: 0.96, y: 12 },
                            { 
                                opacity: 1, 
                                scale: 1, 
                                y: 0, 
                                duration: 0.3, 
                                ease: 'back.out(1.15)',
                                onComplete: () => { delete content.dataset.springAnimated; }
                            }
                        );
                    }
                }
            }
        });
    });

    observer.observe(document.body, {
        attributes: true,
        subtree: true,
        attributeFilter: ['class', 'open']
    });
}

// Bootstrap without delaying initial render
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initTargetedHyperFramesMotion();
        initUniversalAppleTactileFeedback();
        initUniversalModalSprings();
    });
} else {
    initTargetedHyperFramesMotion();
    initUniversalAppleTactileFeedback();
    initUniversalModalSprings();
}

Alpine.start();
