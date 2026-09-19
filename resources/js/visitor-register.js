(() => {
    'use strict';

    const $ = id => document.getElementById(id);

    const app = document.getElementById('pv-app');

    if (!app) return;

    const config = {
        initialTab: app.dataset.initialTab || 'checkin',
        initialStep: Number(app.dataset.initialStep || 1),
        registerReady: app.dataset.registerReady === '1',
        checkInReady: app.dataset.checkinReady === '1'
    };

    const identity = {
        pekerja: [
            'No. Pekerja',
            'Contoh: 007',
            'Gunakan nomor pekerja yang terdaftar.'
        ],

        mahasiswa: [
            'NIM / NPM',
            'Masukkan NIM / NPM',
            'Gunakan nomor induk mahasiswa.'
        ],

        tamu: [
            'No KTP',
            '16 digit NIK',
            'Gunakan 16 digit NIK pada KTP.'
        ],

        lainnya: [
            'No Identitas',
            'Masukkan nomor identitas',
            'Gunakan identitas resmi yang berlaku.'
        ]
    };

    const photos = {
        profile: {
            file: null,
            url: null
        },

        selfie: {
            file: null,
            url: null
        }
    };

    let activeTab = 'checkin';
    let step = 1;
    let stream = null;
    let cameraTarget = null;
    let cameraRequest = 0;
    let captureBusy = false;
    let busy = false;
    let lastFocus = null;

    function feedback(message, error = false) {
        const el = $('pv-feedback');

        el.textContent = message;
        el.classList.toggle('is-error', error);
        el.hidden = false;
    }

    function clearFeedback() {
        $('pv-feedback').hidden = true;
        $('pv-feedback').textContent = '';
    }

    function setStep(value) {
        step = value;

        $('pv-registration-form').dataset.step = String(value);

        $('pv-step-label').textContent =
            value === 1
                ? '1 / 2 · Data diri'
                : '2 / 2 · Foto profil';

        document
            .querySelectorAll('[data-step-dot]')
            .forEach(dot => {
                dot.classList.toggle(
                    'is-active',
                    Number(dot.dataset.stepDot) === value
                );
            });
    }

    function setTab(name) {
        if (
            name !== 'register'
            && name !== 'checkin'
        ) {
            return;
        }

        activeTab = name;

        clearFeedback();
        closeCamera();

        document
            .querySelectorAll('[data-tab]')
            .forEach(tab => {
                const selected = tab.dataset.tab === name;

                tab.setAttribute(
                    'aria-selected',
                    String(selected)
                );

                tab.tabIndex = selected ? 0 : -1;
            });

        $('pv-register')
            .classList
            .toggle(
                'is-active',
                name === 'register'
            );

        $('pv-visit')
            .classList
            .toggle(
                'is-active',
                name === 'checkin'
            );

        if (name === 'register') {
            setStep(1);
        }
    }

    function updateIdentity(
        prefix,
        clear = false
    ) {
        const category =
            $(prefix + '-category').value;

        const item =
            identity[category]
            || [
                'No ID',
                'Pilih kategori dahulu',
                'Pilih kategori pengunjung terlebih dahulu.'
            ];

        $(prefix + '-id-label').innerHTML =
            item[0]
            + ' <span class="pv-required">*</span>';

        $(prefix + '-id').placeholder =
            item[1];

        $(prefix + '-id').inputMode =
            category === 'tamu'
                ? 'numeric'
                : 'text';

        $(prefix + '-id').maxLength =
            category === 'tamu'
                ? 16
                : 100;

        if (prefix === 'pv-r') {
            $('pv-r-id-help').textContent =
                item[2];
        }

        if (clear) {
            $(prefix + '-id').value = '';
        }

        $(prefix + '-id')
            .setCustomValidity('');
    }

    function validateIdentity(prefix) {
        const field =
            $(prefix + '-id');

        const category =
            $(prefix + '-category').value;

        const value =
            field.value.trim();

        field.setCustomValidity(
            category === 'tamu'
            && !/^\d{16}$/.test(value)
                ? 'No KTP harus terdiri dari 16 digit.'
                : ''
        );

        const valid =
            field.reportValidity();

        field.setCustomValidity('');

        return valid;
    }

    function validateRegistrationDetails() {
        const fields = [
            'pv-r-category',
            'pv-r-name',
            'pv-r-phone'
        ];

        for (const id of fields) {
            if (!$(id).reportValidity()) {
                return false;
            }
        }

        const phone =
            $('pv-r-phone');

        const value =
            phone.value.trim();

        const digits =
            value.replace(/\D/g, '');

        phone.setCustomValidity(
            /^\+?[0-9\s-]+$/.test(value)
            && digits.length >= 8
            && digits.length <= 15
                ? ''
                : 'Masukkan nomor HP yang valid (8–15 digit).'
        );

        const validPhone =
            phone.reportValidity();

        phone.setCustomValidity('');

        return validPhone
            && $('pv-r-id').reportValidity()
            && validateIdentity('pv-r');
    }

    function assignFile(kind, file) {
        const input =
            $('pv-' + kind + '-file');

        if (!file) {
            input.value = '';
            return true;
        }

        if (input.files[0] === file) {
            return true;
        }

        try {
            const transfer =
                new DataTransfer();

            transfer.items.add(file);

            input.files =
                transfer.files;

            return input.files[0] === file;

        } catch (error) {
            return false;
        }
    }

    function validatePhoto(file) {
        if (!file) {
            feedback(
                'Tambahkan foto terlebih dahulu.',
                true
            );

            return false;
        }

        if (
            ![
                'image/jpeg',
                'image/png',
                'image/webp'
            ].includes(file.type)
        ) {
            feedback(
                'Gunakan foto JPG, PNG, atau WebP.',
                true
            );

            return false;
        }

        if (
            file.size
            > 5 * 1024 * 1024
        ) {
            feedback(
                'Ukuran foto maksimal 5 MB.',
                true
            );

            return false;
        }

        return true;
    }

    function showPhoto(kind, file) {
        if (!validatePhoto(file)) {
            assignFile(
                kind,
                photos[kind].file
            );

            return false;
        }

        if (!assignFile(kind, file)) {
            feedback(
                'Perangkat tidak mendukung pengiriman foto kamera ini. Gunakan tombol unggah foto.',
                true
            );

            return false;
        }

        if (photos[kind].url) {
            URL.revokeObjectURL(
                photos[kind].url
            );
        }

        photos[kind].file =
            file;

        photos[kind].url =
            URL.createObjectURL(file);

        const image =
            $('pv-' + kind + '-preview');

        image.src =
            photos[kind].url;

        image.classList.add(
            'is-visible'
        );

        $('pv-' + kind + '-box')
            .querySelector(
                '.pv-photo-placeholder'
            )
            .hidden = true;

        clearFeedback();

        return true;
    }

    function closeCamera() {
        cameraRequest++;

        if (stream) {
            stream
                .getTracks()
                .forEach(track => {
                    track.stop();
                });

            stream = null;
        }

        const video =
            $('pv-camera-video');

        video.pause();
        video.srcObject = null;

        $('pv-camera')
            .classList
            .remove('is-open');

        $('pv-camera')
            .setAttribute(
                'aria-hidden',
                'true'
            );

        $('pv-camera-capture')
            .disabled = false;

        captureBusy = false;
        cameraTarget = null;

        if (
            lastFocus
            && typeof lastFocus.focus === 'function'
        ) {
            lastFocus.focus();
        }

        lastFocus = null;
    }

    async function openCamera(kind) {
    clearFeedback();

    const captureInput =
        $('pv-' + kind + '-capture');

    const isSecure =
        window.isSecureContext;

    // Jika dibuka dari HP melalui HTTP/LAN,
    // gunakan kamera native perangkat.
    if (!isSecure) {
        captureInput.click();
        return;
    }

    // Fallback jika browser tidak mendukung getUserMedia.
    if (
        !navigator.mediaDevices
        || !navigator.mediaDevices.getUserMedia
    ) {
        captureInput.click();
        return;
    }

    closeCamera();

    lastFocus =
        document.activeElement;

    const request =
        ++cameraRequest;

    try {
        const nextStream =
            await navigator.mediaDevices
                .getUserMedia({
                    video: {
                        facingMode: 'user'
                    },
                    audio: false
                });

        if (
            request
            !== cameraRequest
        ) {
            nextStream
                .getTracks()
                .forEach(track => {
                    track.stop();
                });

            return;
        }

        stream =
            nextStream;

        cameraTarget =
            kind;

        $('pv-camera-video')
            .srcObject = stream;

        $('pv-camera-title')
            .textContent =
                kind === 'profile'
                    ? 'Ambil foto profil'
                    : 'Ambil selfie kunjungan';

        $('pv-camera')
            .classList
            .add('is-open');

        $('pv-camera')
            .setAttribute(
                'aria-hidden',
                'false'
            );

        $('pv-camera-cancel')
            .focus();

    } catch (error) {
        closeCamera();

        // Jika kamera web ditolak,
        // langsung gunakan kamera native HP.
        captureInput.click();
    }
}

    function capturePhoto() {
        const video =
            $('pv-camera-video');

        if (
            !stream
            || !cameraTarget
            || !video.videoWidth
            || captureBusy
        ) {
            return;
        }

        captureBusy = true;

        $('pv-camera-capture')
            .disabled = true;

        const target =
            cameraTarget;

        const request =
            cameraRequest;

        const canvas =
            document.createElement('canvas');

        const scale =
            Math.min(
                1,
                960 / video.videoWidth
            );

        canvas.width =
            Math.max(
                1,
                Math.round(
                    video.videoWidth
                    * scale
                )
            );

        canvas.height =
            Math.max(
                1,
                Math.round(
                    video.videoHeight
                    * scale
                )
            );

        const ctx =
            canvas.getContext('2d');

        // Mirror hasil foto
        ctx.translate(
            canvas.width,
            0
        );

        ctx.scale(
            -1,
            1
        );

        ctx.drawImage(
            video,
            0,
            0,
            canvas.width,
            canvas.height
        );

        canvas.toBlob(
            blob => {
                if (
                    request
                    !== cameraRequest
                ) {
                    return;
                }

                if (blob) {
                    showPhoto(
                        target,
                        new File(
                            [blob],
                            target
                            + '-'
                            + Date.now()
                            + '.jpg',
                            {
                                type: 'image/jpeg'
                            }
                        )
                    );
                } else {
                    feedback(
                        'Foto gagal diambil. Silakan coba kembali.',
                        true
                    );
                }

                closeCamera();
            },
            'image/jpeg',
            0.85
        );
    }

    function submitRegistration(event) {
        if (
            !config.registerReady
            || busy
        ) {
            event.preventDefault();
            return;
        }

        if (
            window.matchMedia(
                '(max-width:700px)'
            ).matches
        ) {
            setStep(1);
        }

        if (
            !validateRegistrationDetails()
        ) {
            event.preventDefault();
            return;
        }

        const profileInput =
            $('pv-profile-file');

        const profileFile =
            profileInput.files[0];

        if (
            !validatePhoto(profileFile)
        ) {
            event.preventDefault();

            setStep(2);

            return;
        }

        busy = true;

        document
            .querySelectorAll(
                '#pv-registration-form button[type="submit"]'
            )
            .forEach(button => {
                button.disabled = true;
            });
    }

    function submitCheckin(event) {
        if (
            !config.checkInReady
            || busy
        ) {
            event.preventDefault();
            return;
        }

        if (
            !$('pv-v-category')
                .reportValidity()
            || !validateIdentity('pv-v')
        ) {
            event.preventDefault();
            return;
        }

        const selfieFile =
            $('pv-selfie-file')
                .files[0];

        if (
            !validatePhoto(selfieFile)
        ) {
            event.preventDefault();
            return;
        }

        busy = true;

        document
            .querySelectorAll(
                '#pv-visit-form button[type="submit"]'
            )
            .forEach(button => {
                button.disabled = true;
            });
    }

    // Tab
    document
        .querySelectorAll('[data-tab]')
        .forEach(tab => {
            tab.addEventListener(
                'click',
                () => {
                    setTab(
                        tab.dataset.tab
                    );
                }
            );

            tab.addEventListener(
                'keydown',
                event => {
                    if (
                        event.key !== 'ArrowLeft'
                        && event.key !== 'ArrowRight'
                    ) {
                        return;
                    }

                    event.preventDefault();

                    const next =
                        tab.dataset.tab === 'register'
                            ? 'checkin'
                            : 'register';

                    setTab(next);

                    $(
                        'pv-tab-'
                        + (
                            next === 'register'
                                ? 'register'
                                : 'visit'
                        )
                    ).focus();
                }
            );
        });

    document
        .querySelectorAll('[data-switch-tab]')
        .forEach(link => {
            link.addEventListener(
                'click',
                event => {
                    event.preventDefault();

                    setTab(
                        link.dataset.switchTab
                    );
                }
            );
        });

    // Identitas
    ['pv-r', 'pv-v']
        .forEach(prefix => {
            $(prefix + '-category')
                .addEventListener(
                    'change',
                    () => {
                        updateIdentity(
                            prefix,
                            true
                        );
                    }
                );

            $(prefix + '-id')
                .addEventListener(
                    'input',
                    event => {
                        event.target
                            .setCustomValidity('');
                    }
                );

            updateIdentity(prefix);
        });

    // Step pendaftaran
    $('pv-next-step')
        .addEventListener(
            'click',
            () => {
                if (
                    validateRegistrationDetails()
                ) {
                    clearFeedback();
                    setStep(2);
                }
            }
        );

    $('pv-back-step')
        .addEventListener(
            'click',
            () => {
                clearFeedback();
                setStep(1);
            }
        );

    // Upload
    document
        .querySelectorAll('[data-upload]')
        .forEach(button => {
            button.addEventListener(
                'click',
                () => {
                    $(
                        'pv-'
                        + button.dataset.upload
                        + '-file'
                    ).click();
                }
            );
        });

    ['profile', 'selfie']
        .forEach(kind => {
            $('pv-' + kind + '-file')
                .addEventListener(
                    'change',
                    event => {
                        if (
                            event.target.files[0]
                        ) {
                            showPhoto(
                                kind,
                                event.target.files[0]
                            );
                        }
                    }
                );

            $('pv-' + kind + '-capture')
                .addEventListener(
                    'change',
                    event => {
                        if (
                            event.target.files[0]
                        ) {
                            showPhoto(
                                kind,
                                event.target.files[0]
                            );
                        }

                        event.target.value = '';
                    }
                );
        });

    // Kamera
    document
        .querySelectorAll('[data-camera]')
        .forEach(button => {
            button.addEventListener(
                'click',
                () => {
                    openCamera(
                        button.dataset.camera
                    );
                }
            );
        });

    $('pv-camera-cancel')
        .addEventListener(
            'click',
            closeCamera
        );

    $('pv-camera-capture')
        .addEventListener(
            'click',
            capturePhoto
        );

    $('pv-camera')
        .addEventListener(
            'click',
            event => {
                if (
                    event.target
                    === $('pv-camera')
                ) {
                    closeCamera();
                }
            }
        );

    document
        .addEventListener(
            'keydown',
            event => {
                if (
                    event.key === 'Escape'
                    && $('pv-camera')
                        .classList
                        .contains('is-open')
                ) {
                    closeCamera();
                }
            }
        );

    // Submit
    $('pv-registration-form')
        .addEventListener(
            'submit',
            submitRegistration
        );

    $('pv-visit-form')
        .addEventListener(
            'submit',
            submitCheckin
        );

    // Bersihkan kamera dan preview
    window.addEventListener(
        'pagehide',
        () => {
            closeCamera();

            Object
                .values(photos)
                .forEach(photo => {
                    if (photo.url) {
                        URL.revokeObjectURL(
                            photo.url
                        );
                    }
                });
        }
    );

    window.addEventListener(
        'pageshow',
        () => {
            busy = false;

            document
                .querySelectorAll(
                    '.pv-save-registration, #pv-visit-form button[type="submit"]'
                )
                .forEach(button => {
                    button.disabled =
                        button
                            .closest('form')
                            .id
                        === 'pv-registration-form'
                            ? !config.registerReady
                            : !config.checkInReady;
                });
        }
    );

    // Inisialisasi form
    setTab(config.initialTab);

    if (
        config.initialTab
        === 'register'
    ) {
        setStep(
            config.initialStep
        );
    }
})();