console.log("SCRIPT JS STARTED");
/**
 * Sales Performance Ranking System — Modular Dashboard JavaScript
 * Architecture: Revealing Module Pattern with isolated IIFE namespaces.
 *
 * Modules:
 *   AnimationManager  — batched CSS animation triggers
 *   SwiperManager     — lifecycle-safe Swiper instances (Map-based, per selector)
 *   ParticlesManager  — localized canvas cleanup & reinitialisation
 *   StatsManager      — single Stats.js panel & single rAF loop
 *   AchievementManager — debounced queued popup system with session tracking
 *   RealtimeManager   — Pusher connection with debounced event handling
 *   DashboardUpdater  — refresh-locked, slide-level DOM diffing
 */

// ------------------------------------------------------------------
// 1. AnimationManager
// ------------------------------------------------------------------
const AnimationManager = (() => {
    'use strict';

    /**
     * Stagger the slideUpFade animation over a container's key elements.
     * Forces a reflow on each element individually (no batch thrashing).
     */
    const apply = (container) => {
        if (!container) return;
        const els = container.querySelectorAll(
            '.team-box, .performer-box, .goft-box, .sales-table tbody tr'
        );
        els.forEach((el, i) => {
            el.style.opacity = '0';
            el.classList.remove('animate-update');
            void el.offsetWidth; // force reflow to restart animation
            setTimeout(() => el.classList.add('animate-update'), i * 50);
        });
    };

    return { apply };
})();


// ------------------------------------------------------------------
// 2. SwiperManager
// ------------------------------------------------------------------
const SwiperManager = (() => {
    'use strict';

    // Map: CSS selector string → Swiper instance
    const _instances = new Map();

    const CONFIGS = {
        '.performer-slider': { direction: 'vertical', loop: true, reverse: false },
        '.performer-slider2': { direction: 'vertical', loop: true, reverse: false },
        '.performer-slider3': { direction: 'horizontal', loop: true, reverse: true },
        '.performer-slider4': { direction: 'horizontal', loop: true, reverse: true },
        '.performer-slider5': { direction: 'horizontal', loop: true, reverse: true },
        '.performer-slider6': { direction: 'horizontal', loop: true, reverse: true },
        '.performer-slider7': { direction: 'vertical', loop: true, reverse: false },
    };

    /**
     * Create one Swiper on `el` using the config registered for `selector`.
     * Any pre-existing instance for that selector is destroyed first.
     */
    const create = (selector, el, startSlide = 0) => {
        const cfg = CONFIGS[selector];
        if (!cfg || !el) return null;

        // Destroy old instance for this selector if present
        _destroyOne(selector);

        try {
            const sw = new Swiper(el, {
                direction: cfg.direction,
                loop: cfg.loop,
                speed: 3000,
                slidesPerView: 1,
                spaceBetween: 0,
                initialSlide: startSlide,
                autoplay: {
                    delay: 10000,
                    disableOnInteraction: false,
                    reverseDirection: cfg.reverse,
                },
                allowTouchMove: false,
            });
            _instances.set(selector, sw);
            return sw;
        } catch (err) {
            console.error(`SwiperManager: failed to init "${selector}"`, err);
            return null;
        }
    };

    /** Initialise every configured selector found in `root` (default: document). */
    const initAll = (root = document) => {
        Object.keys(CONFIGS).forEach(sel => {
            root.querySelectorAll(sel).forEach(el => {
                if (!_instances.has(sel)) create(sel, el, 0);
            });
        });
    };

    const _destroyOne = (selector) => {
        const sw = _instances.get(selector);
        if (!sw) return;
        try { sw.destroy(true, true); } catch (_) { /* ignore */ }
        _instances.delete(selector);
    };

    const destroy = (sel) => _destroyOne(sel);
    const get = (sel) => _instances.get(sel);
    const destroyAll = () => [..._instances.keys()].forEach(_destroyOne);

    return { create, initAll, get, destroy, destroyAll };
})();


// ------------------------------------------------------------------
// 3. ParticlesManager
// ------------------------------------------------------------------
const ParticlesManager = (() => {
    'use strict';

    const CFG = {
        particles: {
            number: { value: 180, density: { enable: true, value_area: 552 } },
            color: { value: '#ffffff' },
            shape: {
                type: 'circle', stroke: { width: 0, color: '#000000' },
                polygon: { nb_sides: 5 }, image: { src: '', width: 100, height: 100 }
            },
            opacity: {
                value: 1, random: true,
                anim: { enable: true, speed: 1, opacity_min: 0, sync: false }
            },
            size: {
                value: 3.95, random: true,
                anim: { enable: false, speed: 4, size_min: 0.3, sync: false }
            },
            line_linked: { enable: false, distance: 150, color: '#ffffff', opacity: 0.4, width: 1 },
            move: {
                enable: true, speed: 1, direction: 'none', random: true,
                straight: false, out_mode: 'out', bounce: false,
                attract: { enable: false, rotateX: 600, rotateY: 600 }
            },
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: { enable: true, mode: 'bubble' },
                onclick: { enable: true, mode: 'repulse' }, resize: true
            },
            modes: {
                grab: { distance: 400, line_linked: { opacity: 1 } },
                bubble: { distance: 250, size: 0, duration: 2, opacity: 0, speed: 3 },
                repulse: { distance: 400, duration: 0.4 },
                push: { particles_nb: 4 }, remove: { particles_nb: 2 }
            },
        },
        retina_detect: true,
    };

    /** Start particles on every `[id^="particles-js"]` inside `root`. */
    const init = (root = document) => {
        if (typeof particlesJS === 'undefined') return;

        // Defensive fix: particlesJS destroypJS sometimes leaves window.pJSDom as null or undefined.
        if (!window.pJSDom) {
            window.pJSDom = [];
        }

        root.querySelectorAll('[id^="particles-js"]').forEach(el => {
            if (!el.querySelector('.particles-js-canvas-el')) {
                try { particlesJS(el.id, CFG); }
                catch (e) { console.error('ParticlesManager: init failed for #' + el.id, e); }
            }
        });
    };

    /**
     * Destroy only instances whose canvas is a descendant of `container`.
     * Leaves all other instances alive (no global nuke on partial updates).
     */
    const destroyIn = (container) => {
        if (typeof particlesJS === 'undefined' || !window.pJSDom || !container) return;
        for (let i = window.pJSDom.length - 1; i >= 0; i--) {
            const pjs = window.pJSDom[i];
            const canvas = pjs?.pJS?.canvas?.el;
            if (canvas && container.contains(canvas)) {
                try { pjs.pJS.fn.vendors.destroypJS(); } catch (_) { /* ignore */ }
                if (window.pJSDom) window.pJSDom.splice(i, 1);
            }
        }
    };

    /** Nuke every active instance (used only on full-page teardown). */
    const destroyAll = () => {
        if (!window.pJSDom) return;
        for (let i = window.pJSDom.length - 1; i >= 0; i--) {
            try { window.pJSDom[i].pJS.fn.vendors.destroypJS(); } catch (_) { /* ignore */ }
        }
        window.pJSDom = [];
    };

    return { init, destroyIn, destroyAll };
})();


// ------------------------------------------------------------------
// 4. StatsManager  (singleton — one panel, one rAF loop)
// ------------------------------------------------------------------
const StatsManager = (() => {
    'use strict';

    let _stats = null;
    let _raf = null;

    const init = () => {
        if (typeof Stats === 'undefined' || _stats) return;
        try {
            _stats = new Stats();
            _stats.setMode(0);
            Object.assign(_stats.domElement.style, {
                position: 'absolute', left: '0px', top: '0px', zIndex: '99999',
            });
            document.body.appendChild(_stats.domElement);

            const loop = () => {
                _stats.begin();
                _stats.end();
                _raf = requestAnimationFrame(loop);
            };
            _raf = requestAnimationFrame(loop);
        } catch (e) {
            console.warn('StatsManager: Stats.js not available.', e);
        }
    };

    const destroy = () => {
        if (_raf) { cancelAnimationFrame(_raf); _raf = null; }
        if (_stats?.domElement?.parentNode) {
            _stats.domElement.parentNode.removeChild(_stats.domElement);
        }
        _stats = null;
    };

    return { init, destroy };
})();


// ------------------------------------------------------------------
// 5. AchievementManager
// ------------------------------------------------------------------
const AchievementManager = (() => {
    'use strict';

    const _prev = new Map();   // id → last known percent
    const _fired = new Set();   // ids that have already triggered a popup this session
    const _queue = [];
    let _showing = false;

    /**
     * Seed _prev from the current live DOM so we never fire on initial load.
     * Also mark anyone already at 100% so they don't trigger after the first refresh.
     */
    const init = (root = document) => {
        root.querySelectorAll('tbody tr[data-id]').forEach(row => {
            const id = row.dataset.id;
            const pct = parseFloat(row.dataset.percent || '0');
            _prev.set(id, pct);
            if (pct >= 100) _fired.add(id);
        });
    };

    /**
     * Compare freshly-fetched rows against stored baselines.
     * Call this BEFORE updating the live DOM.
     *
     * @param {Document|Element} newRoot  — the parsed fetched document
     */
    const check = (newRoot) => {
        newRoot.querySelectorAll('tbody tr[data-id]').forEach(row => {
            const id = row.dataset.id;
            const pct = parseFloat(row.dataset.percent || '0');

            if (!_prev.has(id)) {
                // New salesperson not seen before — just baseline, no popup
                _prev.set(id, pct);
                if (pct >= 100) _fired.add(id);
                return;
            }

            const was = _prev.get(id);

            // 100% threshold crossed AND not yet celebrated this session
            if (was < 100 && pct >= 100 && !_fired.has(id)) {
                _fired.add(id);
                console.log("Image from row:", row.dataset.image);
                _enqueue(
                    row.dataset.name,
                    row.dataset.league,
                    row.dataset.contest,
                    row.dataset.image
                );
            }

            _prev.set(id, pct);   // always update baseline after evaluation
        });
    };

    const _enqueue = (name, league, contest, image) => {

        console.log("Enqueue Image:", image);

        _queue.push({
            name,
            league,
            contest,
            image
        });

        if (!_showing) {
            _runNext();
        }
    };

    const _runNext = () => {
        if (_queue.length === 0) { _showing = false; return; }
        _showing = true;

        const { name, league, contest, image } = _queue.shift();

        console.log("Popup Image:", image);

        const img = document.getElementById("achievement-image");

        if (img) {
            img.src = image || "/images/default.jpg";
        }

        // Play Sound
        const sound = document.getElementById("achievement-sound");

        if (sound) {
            sound.currentTime = 0;
            sound.volume = 0.8;
            sound.play().catch(() => { });
        }

        // Confetti burst
        if (typeof confetti === 'function') {
            confetti({ particleCount: 250, spread: 120, origin: { y: 0.6 } });
            setTimeout(() => {
                confetti({ particleCount: 150, angle: 60, spread: 80, origin: { x: 0 } });
                confetti({ particleCount: 150, angle: 120, spread: 80, origin: { x: 1 } });
            }, 300);
        }

        const popup = document.getElementById('achievement-popup');
        const nameEl = document.getElementById('achievement-name');
        const imgEl = document.getElementById('achievement-image');
        // const img = document.getElementById("achievement-image");

        if (img) {
            console.log("Popup Image:", image);

            img.src = image && image !== "undefined"
                ? image
                : "/images/default.jpg";
        }

        if (popup && nameEl) {

            if (imgEl) {
                imgEl.src = image;
            }

            nameEl.innerHTML = `
        <strong>${name}</strong>
        <small>${league} - ${contest}</small>
    `;
            // document.getElementById("achievement-role").innerHTML =
            // `${league} • ${contest}`;

            popup.style.display = 'flex';

            setTimeout(() => {
                popup.style.display = 'none';
                setTimeout(_runNext, 500);
            }, 4000);

        } else {
            _showing = false;
        }
    };

    /** Manually enqueue a celebration — exposed for debugging / testing */
    const celebrate = (name, league, contest, image) => _enqueue(name, league, contest, image);

    return { init, check, celebrate };
})();


// ------------------------------------------------------------------
// 6. RankTracker (Tracks rank changes and plays sounds)
// ------------------------------------------------------------------
const RankTracker = (() => {
    'use strict';

    let _oldLeaderboard = new Map();
    let _oldDepartments = new Map();
    let _oldStarPerformers = new Map();
    let _marqueeMessages = [];

    const _renderMarquee = () => {
        const marqueeEl = document.getElementById('dynamic-marquee');
        if (marqueeEl) {
            const combined = _marqueeMessages.join(' &nbsp;&nbsp; • &nbsp;&nbsp; ');
            marqueeEl.innerHTML = `${combined} &nbsp;&nbsp; • &nbsp;&nbsp; ${combined} &nbsp;&nbsp; • &nbsp;&nbsp; ${combined}`;
        }
    };

    const _addMarqueeMessage = (msg) => {
        _marqueeMessages.unshift(msg);
        if (_marqueeMessages.length > 8) _marqueeMessages.pop();
        _renderMarquee();
    };

    const _getLeaderboardRanks = (doc) => {
        const ranks = new Map();
        doc.querySelectorAll('.performer-slider2 tbody tr[data-id]').forEach(row => {
            const tbody = row.closest('tbody');
            const index = Array.from(tbody.children).indexOf(row);
            const listId = tbody.closest('.leaderboard')?.querySelector('h4')?.textContent?.trim() || 'default';
            const name = row.dataset.name || 'Salesperson';
            const sales = parseFloat(row.dataset.sales?.replace(/,/g, '') || '0');
            const percent = parseFloat(row.dataset.percent || '0');

            ranks.set(`${listId}-${row.dataset.id}`, { rank: index, name, sales, percent });
        });
        return ranks;
    };

    const _getDepartmentRanks = (doc) => {
        const ranks = new Map();
        doc.querySelectorAll('.team-box-main .team-box').forEach((box, index) => {
            const deptName = box.querySelector('h3')?.textContent?.trim() || `dept-${index}`;
            ranks.set(deptName, index);
        });
        return ranks;
    };

    const _getStarPerformers = (doc) => {
        const performers = new Map();
        doc.querySelectorAll('.performer-slider .swiper-slide').forEach((slide) => {
            const h2 = slide.querySelector('.perform-con h2');
            const h3 = slide.querySelector('.perform-con h3');
            if (h2 && h3) {
                const category = h2.childNodes[0]?.nodeValue?.trim();
                const name = h3.childNodes[0]?.nodeValue?.trim();
                if (category && name) {
                    performers.set(category, name);
                }
            }
        });
        return performers;
    };

    const init = (root = document) => {
        _oldLeaderboard = _getLeaderboardRanks(root);
        _oldDepartments = _getDepartmentRanks(root);
        _oldStarPerformers = _getStarPerformers(root);

        if (Array.isArray(window.InitialMarqueeMessages) && window.InitialMarqueeMessages.length) {
            _marqueeMessages = [...window.InitialMarqueeMessages];
            _renderMarquee();
        }
    };

    const check = (newDoc) => {
        let leaderboardImproved = false;
        let departmentImproved = false;
        let starPerformerImproved = false;
        let newSaleMade = false;

        const newLeaderboard = _getLeaderboardRanks(newDoc);
        newLeaderboard.forEach((newData, key) => {
            if (_oldLeaderboard.has(key)) {
                const oldData = _oldLeaderboard.get(key);

                if (newData.sales > oldData.sales) {
                    newSaleMade = true;
                    _addMarqueeMessage(`💰 New Sale by ${newData.name}! ($${newData.sales - oldData.sales})`);
                }

                if (newData.percent >= 100 && oldData.percent < 100) {
                    _addMarqueeMessage(`🎯 Target Completed by ${newData.name}!`);
                }

                if (newData.rank < oldData.rank) {
                    leaderboardImproved = true;
                    _addMarqueeMessage(`📈 ${newData.name} moved UP to rank #${newData.rank + 1}!`);
                }
            } else {
                if (newData.sales > 0) {
                    newSaleMade = true;
                    _addMarqueeMessage(`💰 New Entry by ${newData.name}! ($${newData.sales})`);
                }
            }
        });

        const newDepartments = _getDepartmentRanks(newDoc);
        newDepartments.forEach((newRank, key) => {
            if (_oldDepartments.has(key)) {
                const oldRank = _oldDepartments.get(key);
                if (newRank < oldRank) {
                    departmentImproved = true;
                    _addMarqueeMessage(`🏢 Department ${key} overtook others!`);
                }
            }
        });

        const newStarPerformers = _getStarPerformers(newDoc);
        newStarPerformers.forEach((newName, category) => {
            if (_oldStarPerformers.has(category)) {
                const oldName = _oldStarPerformers.get(category);
                if (newName !== oldName && newName && !newName.includes("NO RECORD")) {
                    starPerformerImproved = true;
                    _addMarqueeMessage(`🌟 New Top Performer in ${category}: ${newName}!`);
                }
            } else {
                if (newName && !newName.includes("NO RECORD")) {
                    starPerformerImproved = true;
                }
            }
        });

        if (leaderboardImproved) {
            playSound(window.SoundPaths.milestone);
        }

        if (departmentImproved || starPerformerImproved) {
            setTimeout(() => {
                playSound(window.SoundPaths.milestone);
            }, (leaderboardImproved) ? 1500 : 0);
        }

        _oldLeaderboard = newLeaderboard;
        _oldDepartments = newDepartments;
        _oldStarPerformers = newStarPerformers;
    };

    const playSound = (src) => {
        const audio = new Audio(src);
        audio.volume = 0.8;
        audio.play().catch(e => console.log('RankTracker Audio play failed:', e));
    };

    return { init, check };
})();


// ------------------------------------------------------------------
// 7. RealtimeManager  (Pusher — debounced 1 s)
// ------------------------------------------------------------------
const RealtimeManager = (() => {
    'use strict';

    let _debounce = null;

    const _playNewSaleSound = () => {
        if (!window.SoundPaths?.newSale) return;
        const audio = new Audio(window.SoundPaths.newSale);
        audio.volume = 0.8;
        audio.play().catch(e => console.log('New Sale Audio play failed:', e));
    };

    const init = () => {

        if (typeof Pusher === 'undefined') {
            console.error("Pusher not loaded");
            return;
        }

        const pusher = new Pusher(window.AppConfig.pusherKey, {
            cluster: window.AppConfig.pusherCluster,
            forceTLS: true
        });

        pusher.connection.bind('connected', () => {
            console.log("✅ Pusher Connected");
        });

        const channel = pusher.subscribe('ranking-updates');

        channel.bind('pusher:subscription_succeeded', () => {
            console.log("✅ Subscribed");
        });

        channel.bind('ranking.updated', function (data) {

            console.log("🔥 ranking.updated received", data);

            // 👇 Guaranteed sale event — bajao turant, DOM diff ka wait mat karo
            _playNewSaleSound();

            clearTimeout(_debounce);

            _debounce = setTimeout(() => {
                console.log("Refreshing dashboard...");
                DashboardUpdater.refresh();
            }, 1000);

        });

    };

    return { init };

})();

// ------------------------------------------------------------------
// 8. DashboardUpdater  (refresh-locked, slide-level DOM diffing)
// ------------------------------------------------------------------
const DashboardUpdater = (() => {
    'use strict';

    let _busy = false;

    // ---- private helpers ----------------------------------------

    /** Full replace: destroy old Swiper, clone new element, reinit. */
    const _recreate = (selector, oldEl, newEl, hasParticles) => {
        if (hasParticles) ParticlesManager.destroyIn(oldEl);

        const saved = SwiperManager.get(selector)?.realIndex ?? 0;
        SwiperManager.destroy(selector);

        const clone = newEl.cloneNode(true);
        oldEl.parentNode.replaceChild(clone, oldEl);

        SwiperManager.create(selector, clone, saved);
        AnimationManager.apply(clone);
        if (hasParticles) ParticlesManager.init(clone);
    };

    /** Slide-level diff: only touch slides whose HTML changed. */
    const _diff = (selector, newEl, hasParticles) => {
        const oldEl = document.querySelector(selector);
        if (!oldEl || !newEl) return;
        if (oldEl.innerHTML === newEl.innerHTML) return;   // nothing changed

        const oldSlides = oldEl.querySelectorAll('.swiper-slide');
        const newSlides = newEl.querySelectorAll('.swiper-slide');

        if (oldSlides.length !== newSlides.length) {
            _recreate(selector, oldEl, newEl, hasParticles);
            return;
        }

        let changed = false;
        oldSlides.forEach((oldSlide, i) => {
            const newSlide = newSlides[i];
            if (oldSlide.innerHTML === newSlide.innerHTML) return;

            changed = true;
            if (hasParticles) ParticlesManager.destroyIn(oldSlide);
            oldSlide.innerHTML = newSlide.innerHTML;
            if (hasParticles) ParticlesManager.init(oldSlide);
            AnimationManager.apply(oldSlide);
        });

        if (changed) {
            SwiperManager.get(selector)?.update();
        }
    };

    // ---- public -------------------------------------------------

    const refresh = async () => {
        if (_busy) { console.log('DashboardUpdater: refresh locked, skipping.'); return; }
        _busy = true;

        try {
            const url = new URL(window.location.href);
            url.searchParams.set('_t', Date.now());
            const res = await fetch(url.toString(), { cache: 'no-store' });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');

            // ★ Check achievements BEFORE touching the DOM ★
            AchievementManager.check(doc);

            // ★ Check ranks for sounds BEFORE touching the DOM ★
            RankTracker.check(doc);

            // Team boxes (no Swiper)
            const oldTeam = document.querySelector('.team-box-main');
            const newTeam = doc.querySelector('.team-box-main');
            if (oldTeam && newTeam && oldTeam.innerHTML !== newTeam.innerHTML) {
                oldTeam.innerHTML = newTeam.innerHTML;
                AnimationManager.apply(oldTeam);
            }

            // Sliders
            _diff('.performer-slider2', doc.querySelector('.performer-slider2'), false);
            _diff('.performer-slider', doc.querySelector('.performer-slider'), true);
            _diff('.performer-slider7', doc.querySelector('.performer-slider7'), false);

        } catch (err) {
            console.error('DashboardUpdater: refresh failed —', err);
        } finally {
            _busy = false;
        }
    };

    return { refresh };
})();


// ------------------------------------------------------------------
// 9. Boot
// ------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    try { AudioUnlocker.init(); } catch (e) { console.error('Boot: AudioUnlocker failed', e); }
    try { SwiperManager.initAll(); } catch (e) { console.error('Boot: SwiperManager failed', e); }
    try { ParticlesManager.init(); } catch (e) { console.error('Boot: ParticlesManager failed', e); }
    try { AchievementManager.init(); } catch (e) { console.error('Boot: AchievementManager failed', e); }
    try { RankTracker.init(); } catch (e) { console.error('Boot: RankTracker failed', e); }
    try { StatsManager.init(); } catch (e) { console.error('Boot: StatsManager failed', e); }
    try { RealtimeManager.init(); } catch (e) { console.error('Boot: RealtimeManager failed', e); }
});


// ------------------------------------------------------------------
// 10. Global debug/test namespace (available immediately after script loads)
// ------------------------------------------------------------------
window.dashboardApp = {
    SwiperManager,
    ParticlesManager,
    AchievementManager,
    RankTracker,
    DashboardUpdater,
    StatsManager,
    RealtimeManager,
    AnimationManager,
};

window.dashboardDebug = {

    triggerMockUpdate() {
        DashboardUpdater.refresh();
    },

    triggerMockCelebration(
        name = 'JOHN DOE',
        league = 'TITAN',
        contest = 'FRONT SALE'
    ) {
        AchievementManager.celebrate(name, league, contest);
    }

};
console.log("SCRIPT JS FINISHED");

// ------------------------------------------------------------------
// Audio Unlock — attempt silent unlock on load (kiosk fallback)
// ------------------------------------------------------------------
const AudioUnlocker = (() => {
    'use strict';
    let _attempted = false;

    const attempt = () => {
        if (_attempted) return;
        _attempted = true;

        [window.SoundPaths?.newSale, window.SoundPaths?.leaderboard, window.SoundPaths?.milestone]
            .filter(Boolean)
            .forEach(src => {
                const a = new Audio(src);
                a.muted = true;
                a.play().then(() => {
                    a.pause();
                    a.currentTime = 0;
                }).catch(() => {});
            });
    };

    const init = () => {
        attempt(); // try immediately on load

        // Also retry on ANY interaction, in case it's ever available
        ['click', 'touchstart', 'keydown'].forEach(evt =>
            document.addEventListener(evt, attempt, { once: true })
        );
    };

    return { init };
})();
