(function () {

    window.autoPlaySlideVideos = function () {
        document.addEventListener('pointerdown', () => {
            document.querySelectorAll('[is-current-slide] [data-autoplay-video]').forEach(v => {
                v.play();
            })
        }, {once: true});
    }
    window.autoPlaySlideVideos()


    const FADE_MS = 1000;

    function showSlide(nextIndex) {
        const stage = document.getElementById('stage');
        const next = stage.querySelector('[data-slide-index="' + nextIndex + '"]');
        const current = stage.querySelector('.ppt-slide.is-active') || stage.querySelector('.ppt-slide[style*="opacity:1"]');

        if (!next || next === current) {
            return;
        }

        stage.querySelectorAll('.ppt-slide').forEach(s => s.classList.remove('is-active'));
        next.classList.add('is-active');

        next.style.visibility = 'visible';
        next.style.zIndex = '2';
        next.style.opacity = '0';

        requestAnimationFrame(() => {
            next.style.opacity = '1';
        });

        if (current) {
            current.style.zIndex = '1';
            current.style.opacity = '0';
            window.setTimeout(() => {
                current.style.visibility = 'hidden';
            }, FADE_MS);
        }

        const v = next.querySelector('video');
        if (v) {
            try {
                v.currentTime = 0;
            } catch (e) {
            }
            v.play().catch(() => {
            });
        }

        if (current) {
            const cv = current.querySelector('video');
            if (cv) {
                cv.pause();
            }
        }
    }

    window.PowerPointShow = {
        showSlide,
    };

    let currentIndex = 0;

    function slides() {
        return document.querySelectorAll('#stage .ppt-slide');
    }

    function nextSlide() {
        const list = slides();
        if (list.length === 0) {
            return;
        }

        if (currentIndex + 1 >= list.length) {
            currentIndex = list.length;
        } else {
            currentIndex = currentIndex + 1;
        }
        window.PowerPointShow.showSlide(currentIndex);
    }

    function prevSlide() {
        const list = slides();
        if (list.length === 0) {
            return;
        }

        currentIndex = currentIndex - 1;
        if (currentIndex < 0) {
            currentIndex = 0;
        }
        window.PowerPointShow.showSlide(currentIndex);
    }

    function restart() {
        currentIndex = 0;
        window.PowerPointShow.showSlide(currentIndex);
    }

    window.PowerPointShow.restart = restart;
    window.PowerPointShow.next = nextSlide;
    window.PowerPointShow.prev = prevSlide;

    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') {
            PowerPointShow.next();
        }
        if (e.key === 'ArrowLeft') {
            PowerPointShow.prev();
        }
    });

    function setMessage(message) {
        const host = document.getElementById('topBannerHost') || document.body;
        const id = 'mdbTopBanner';
        const showMs = 10000;

        const existing = document.getElementById(id);
        if (existing) {
            existing.remove();
        }

        const banner = document.createElement('div');
        banner.id = id;
        banner.classList.add(
            'mdb-top-banner',
            'alert',
            'alert-info',
            'alert-dismissible',
            'fade',
            'show',
            'shadow'
        );
        banner.setAttribute('role', 'alert');

        const text = document.createElement('span');
        text.textContent = message;

        banner.appendChild(text);
        host.appendChild(banner);

        window.setTimeout(function () {
            if (!document.getElementById(id)) {
                return;
            }

            banner.classList.remove('show');
            window.setTimeout(function () {
                banner.remove();
            }, 200);
        }, showMs);
    }

    window.setInterval(() => {
        fetch('/Api/get-controls/', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        }).then(r => r.json())
            .then(json => {
                if (json.next) {
                    PowerPointShow.next();
                } else if (json.previous) {
                    PowerPointShow.prev();
                } else if (json.restart) {
                    PowerPointShow.restart();
                }

                if (json.message) {
                    setMessage(json.message)
                }
            });
    }, 500);


})();