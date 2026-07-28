// Global array to hold active Swiper instances so we can destroy them during dynamic updates
window.activeSwipers = [];

window.initAllSliders = function() {
    // 1. Cleanly destroy any existing active swipers to prevent memory leaks or duplicate instances
    if (window.activeSwipers && window.activeSwipers.length > 0) {
        window.activeSwipers.forEach(swiper => {
            try {
                swiper.destroy(true, true);
            } catch (e) {
                console.warn('Failed to destroy swiper instance:', e);
            }
        });
        window.activeSwipers = [];
    }

    // 2. Swiper configuration matrix
    const configs = [
        { selector: ".performer-slider", direction: "vertical", loop: true, reverse: false },
        { selector: ".performer-slider2", direction: "vertical", loop: true, reverse: false },
        { selector: ".performer-slider3", direction: "horizontal", loop: true, reverse: true },
        { selector: ".performer-slider4", direction: "horizontal", loop: true, reverse: true },
        { selector: ".performer-slider5", direction: "horizontal", loop: true, reverse: true },
        { selector: ".performer-slider6", direction: "horizontal", loop: true, reverse: true },
        { selector: ".performer-slider7", direction: "vertical", loop: true, reverse: false }
    ];

    // 3. Instantiate Swiper for each matching element in the DOM
    configs.forEach(cfg => {
        document.querySelectorAll(cfg.selector).forEach(el => {
            try {
                const instance = new Swiper(el, {
                    direction: cfg.direction,
                    loop: cfg.loop,
                    speed: 3000,
                    slidesPerView: 1,
                    spaceBetween: 0,
                    autoplay: {
                        delay: 10000,
                        disableOnInteraction: false,
                        reverseDirection: cfg.reverse
                    },
                    allowTouchMove: false
                });
                window.activeSwipers.push(instance);
            } catch (err) {
                console.error('Failed to initialize swiper for selector ' + cfg.selector, err);
            }
        });
    });
};

// Auto-run when the page DOM is fully parsed
document.addEventListener("DOMContentLoaded", function() {
    window.initAllSliders();
});





