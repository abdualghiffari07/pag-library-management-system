@php
    // Endpoint
    $visitorWorkflowReady = true;

    $registerReady = $visitorWorkflowReady
        && \Illuminate\Support\Facades\Route::has('visitors.store');

    $checkInReady = $visitorWorkflowReady
        && \Illuminate\Support\Facades\Route::has('visitors.checkin');

    // Kategori
    $visitorCategories = [
        'pekerja' => 'Pekerja',
        'mahasiswa' => 'Mahasiswa',
        'tamu' => 'Tamu',
        'lainnya' => 'Lainnya',
    ];

    // Data visitor yang baru terdaftar
    $registered = session(
        'registered_visitor',
        session('visitor_registered', [])
    );

    $registered = is_array($registered)
        ? $registered
        : [];

    // Tab awal
    $initialTab = session(
        'active_form',
        $errors->register->any() ? 'register' : 'checkin'
    );

    if ($initialTab === 'visit') {
        $initialTab = 'checkin';
    }

    if (!in_array($initialTab, ['register', 'checkin'], true)) {
        $initialTab = 'checkin';
    }

    // Data check-in awal
    $initialCheckinCategory = old(
        'checkin_category',
        $registered['visitor_category'] ?? ''
    );

    $initialCheckinNumber = old(
        'checkin_number',
        $registered['employee_number'] ?? ''
    );

    // Step pendaftaran mobile
    $initialRegistrationStep =
        $errors->register->has('profile_photo')
        && !$errors->register->has('visitor_category')
        && !$errors->register->has('visitor_name')
        && !$errors->register->has('phone_number')
        && !$errors->register->has('employee_number')
            ? 2
            : 1;

    // Receipt check-in
    $checkinReceipt = session('checkin_receipt');

    $hasCheckinReceipt =
        is_array($checkinReceipt)
        && !empty($checkinReceipt['name']);
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#102A43">

    <title>Pengunjung - PAG Library</title>

    @vite([
        'resources/css/app.css',
        'resources/css/background-slider.css',
        'resources/css/visitor-register.css',
        'resources/css/visitor-welcome.css',
        'resources/js/app.js',
        'resources/js/background-slider.js',
        'resources/js/visitor-register.js'
    ])
</head>

<body class="pv-page">

    {{-- Background --}}
    <x-background-slider />

    <div class="pv-shell">

        {{-- Header --}}
        <header class="pv-header">
            <a href="{{ route('landing') }}" class="pv-brand">
                <img
                    src="{{ asset('images/landing/logo-pertamina.png') }}"
                    alt="Logo Pertamina"
                    class="pv-brand-logo"
                >

                <span>
                    <strong class="pv-brand-name">
                        PERTA ARUN GAS
                    </strong>

                    <small class="pv-brand-sub">
                        LIBRARY MANAGEMENT SYSTEM
                    </small>
                </span>
            </a>

            <a
                href="{{ route('landing') }}"
                class="pv-home"
                aria-label="Kembali ke beranda"
            >
                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path
                        d="M15 18l-6-6 6-6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

                Kembali
            </a>
        </header>

        {{-- Main --}}
        <main class="pv-main">
            <div class="pv-wrap">

                @if (!$hasCheckinReceipt)
                    {{-- Heading --}}
                    <div class="pv-heading">
                        <div class="pv-eyebrow">
                            PAG LIBRARY
                        </div>

                        <h1>
                            Selamat datang di perpustakaan
                        </h1>

                        <p>
                            Daftarkan diri sekali, lalu catat kunjungan Anda dengan cepat.
                        </p>
                    </div>
                @endif

                {{-- Success --}}
                @if ($hasCheckinReceipt)

                    <div class="pv-card pv-success-card">
                        <div class="pv-success-page">

        <div
            class="pv-success-icon"
            role="img"
            aria-label="Kunjungan berhasil"
        >
            <svg
                viewBox="0 0 52 52"
                fill="none"
                aria-hidden="true"
            >
                <circle
                    class="pv-check-circle"
                    cx="26"
                    cy="26"
                    r="24"
                    stroke="currentColor"
                    stroke-width="3"
                    stroke-linecap="round"
                />

                <path
                    class="pv-check-mark"
                    d="M15 27 L22 34 L37 19"
                    stroke="currentColor"
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </div>

                            <span class="pv-success-badge">
                                KUNJUNGAN BERHASIL
                            </span>

                            <p class="pv-success-greeting">
                                Selamat Datang, Saudara
                            </p>

                            <h1 class="pv-success-name">
                                {{ $checkinReceipt['name'] }}
                            </h1>

                            <div class="pv-success-accent">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>

                            <div class="pv-success-time">
                                <div class="pv-success-time-icon">
                                    <svg
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
                                        <circle cx="12" cy="12" r="9"/>

                                        <path
                                            d="M12 7v5l3 2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </div>

                                <div class="pv-success-time-content">
                                    <span>
                                        Waktu Berkunjung
                                    </span>

                                    <strong>
                                        {{ $checkinReceipt['date'] ?? '-' }}
                                    </strong>

                                    <small>
                                        Pukul {{ $checkinReceipt['time'] ?? '-' }}
                                    </small>
                                </div>
                            </div>

                            <p class="pv-success-message">
                                Terima kasih telah berkunjung ke PAG Library.
                                Semoga kunjungan Anda menyenangkan.
                            </p>

                            <a
                                href="{{ route('visitors.register') }}"
                                class="pv-success-button"
                            >
                                <svg
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M15 18l-6-6 6-6"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>

                                Kembali ke Form Pengunjung
                            </a>

                            <span class="pv-success-note">
                                Sistem siap digunakan untuk pengunjung berikutnya.
                            </span>

                        </div>
                    </div>

                @else

                    {{-- Form Card --}}
                    <div
                        class="pv-card"
                        id="pv-app"
                        data-initial-tab="{{ $initialTab }}"
                        data-initial-step="{{ $initialRegistrationStep }}"
                        data-register-ready="{{ $registerReady ? '1' : '0' }}"
                        data-checkin-ready="{{ $checkInReady ? '1' : '0' }}"
                    >

                        {{-- Tabs --}}
                        <div
                            class="pv-tabs"
                            role="tablist"
                            aria-label="Jenis formulir"
                        >
                            <button
                                type="button"
                                class="pv-tab"
                                id="pv-tab-visit"
                                role="tab"
                                aria-controls="pv-visit"
                                aria-selected="false"
                                data-tab="checkin"
                            >
                                Masuk Pengunjung
                            </button>

                            <button
                                type="button"
                                class="pv-tab"
                                id="pv-tab-register"
                                role="tab"
                                aria-controls="pv-register"
                                aria-selected="false"
                                data-tab="register"
                            >
                                Pendaftaran Baru
                            </button>
                        </div>

                        <div class="pv-body">

                            {{-- Register success --}}
                            @if (session('register_success'))
                                <div class="pv-alert" role="status">
                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M5 12l5 5L19 8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                    <span>
                                        {{ session('register_success') }}
                                    </span>
                                </div>
                            @endif

                            {{-- Check-in fallback --}}
                            @if (session('checkin_success'))
                                <div class="pv-alert" role="status">
                                    {{ session('checkin_success') }}
                                </div>
                            @endif

                            {{-- Error --}}
                            @if (session('error'))
                                <div class="pv-alert is-error" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Validation --}}
                            @if (
                                $errors->register->any()
                                || $errors->checkin->any()
                                || $errors->any()
                            )
                                <div class="pv-alert is-error" role="alert">
                                    {{
                                        $errors->register->first()
                                        ?: (
                                            $errors->checkin->first()
                                            ?: $errors->first()
                                        )
                                    }}
                                </div>
                            @endif

                            <div
                                class="pv-alert"
                                id="pv-feedback"
                                role="status"
                                tabindex="-1"
                                aria-live="polite"
                                hidden
                            ></div>

                            {{-- Pendaftaran --}}
                            <section
                                class="pv-screen"
                                id="pv-register"
                                role="tabpanel"
                                aria-labelledby="pv-tab-register"
                            >
                                <form
                                    id="pv-registration-form"
                                    class="pv-registration"
                                    data-step="1"
                                    action="{{ route('visitors.store') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    novalidate
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="form_type"
                                        value="register"
                                    >

                                    {{-- Mobile step --}}
                                    <div
                                        class="pv-steps"
                                        aria-label="Langkah pendaftaran"
                                    >
                                        <span
                                            class="pv-step-dot is-active"
                                            data-step-dot="1"
                                        ></span>

                                        <span
                                            class="pv-step-dot"
                                            data-step-dot="2"
                                        ></span>

                                        <strong id="pv-step-label">
                                            1 / 2 · Data diri
                                        </strong>
                                    </div>

                                    <div class="pv-grid">

                                        {{-- Data --}}
                                        <div class="pv-details-step">
                                            <h2 class="pv-section-title">
                                                Informasi pengunjung
                                            </h2>

                                            <p class="pv-section-sub">
                                                Lengkapi data sesuai identitas yang berlaku.
                                            </p>

                                            <div class="pv-fields">

                                                {{-- Category --}}
                                                <div class="pv-field pv-span-2">
                                                    <label
                                                        class="pv-label"
                                                        for="pv-r-category"
                                                    >
                                                        Kategori pengunjung
                                                        <span class="pv-required">*</span>
                                                    </label>

                                                    <select
                                                        class="pv-control"
                                                        id="pv-r-category"
                                                        name="visitor_category"
                                                        required
                                                    >
                                                        <option value="">
                                                            Pilih kategori
                                                        </option>

                                                        @foreach ($visitorCategories as $value => $label)
                                                            <option
                                                                value="{{ $value }}"
                                                                @selected(old('visitor_category') === $value)
                                                            >
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('visitor_category', 'register')
                                                        <p class="pv-error">
                                                            {{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>

                                                {{-- Name --}}
                                                <div class="pv-field pv-span-2">
                                                    <label
                                                        class="pv-label"
                                                        for="pv-r-name"
                                                    >
                                                        Nama lengkap
                                                        <span class="pv-required">*</span>
                                                    </label>

                                                    <input
                                                        class="pv-control"
                                                        id="pv-r-name"
                                                        name="visitor_name"
                                                        type="text"
                                                        value="{{ old('visitor_name') }}"
                                                        maxlength="255"
                                                        autocomplete="name"
                                                        placeholder="Nama sesuai identitas"
                                                        required
                                                    >

                                                    @error('visitor_name', 'register')
                                                        <p class="pv-error">
                                                            {{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>

                                                {{-- Phone --}}
                                                <div class="pv-field">
                                                    <label
                                                        class="pv-label"
                                                        for="pv-r-phone"
                                                    >
                                                        No HP
                                                        <span class="pv-required">*</span>
                                                    </label>

                                                    <input
                                                        class="pv-control"
                                                        id="pv-r-phone"
                                                        name="phone_number"
                                                        type="tel"
                                                        inputmode="tel"
                                                        value="{{ old('phone_number') }}"
                                                        maxlength="20"
                                                        autocomplete="tel"
                                                        placeholder="08xxxxxxxxxx"
                                                        required
                                                    >

                                                    @error('phone_number', 'register')
                                                        <p class="pv-error">
                                                            {{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>

                                                {{-- Identity --}}
                                                <div class="pv-field">
                                                    <label
                                                        class="pv-label"
                                                        for="pv-r-id"
                                                        id="pv-r-id-label"
                                                    >
                                                        No ID
                                                        <span class="pv-required">*</span>
                                                    </label>

                                                    <input
                                                        class="pv-control"
                                                        id="pv-r-id"
                                                        name="employee_number"
                                                        type="text"
                                                        value="{{ old('employee_number') }}"
                                                        maxlength="100"
                                                        autocomplete="off"
                                                        placeholder="Pilih kategori dahulu"
                                                        required
                                                    >

                                                    <p
                                                        class="pv-help"
                                                        id="pv-r-id-help"
                                                    >
                                                        Nomor identitas digunakan untuk kunjungan berikutnya.
                                                    </p>

                                                    @error('employee_number', 'register')
                                                        <p class="pv-error">
                                                            {{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>

                                        {{-- Profile --}}
                                        <div class="pv-photo-pane pv-photo-step">
                                            <h2 class="pv-section-title">
                                                Foto profil
                                            </h2>

                                            <p class="pv-section-sub">
                                                Foto yang jelas untuk data keanggotaan perpustakaan.
                                            </p>

                                            <div
                                                class="pv-photo-box"
                                                id="pv-profile-box"
                                            >
                                                <div class="pv-photo-placeholder">
                                                    <svg
                                                        width="34"
                                                        height="34"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="#9cadbd"
                                                        stroke-width="1.4"
                                                        aria-hidden="true"
                                                    >
                                                        <path d="M14 3h-4L8 6H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-4z"/>
                                                        <circle cx="12" cy="13" r="4"/>
                                                    </svg>

                                                    <strong>
                                                        Belum ada foto profil
                                                    </strong>

                                                    <small>
                                                        Wajah terlihat jelas dan menghadap kamera
                                                    </small>
                                                </div>

                                                <img
                                                    id="pv-profile-preview"
                                                    alt="Pratinjau foto profil"
                                                >
                                            </div>

                                            <div class="pv-photo-actions">
                                                <button
                                                    type="button"
                                                    class="pv-secondary"
                                                    data-upload="profile"
                                                >
                                                    Pilih foto
                                                </button>

                                                <button
                                                    type="button"
                                                    class="pv-secondary"
                                                    data-camera="profile"
                                                >
                                                    Ambil foto
                                                </button>
                                            </div>

                                            <input
                                                type="file"
                                                id="pv-profile-file"
                                                name="profile_photo"
                                                accept="image/jpeg,image/png,image/webp"
                                                required
                                                hidden
                                            >

                                            <input
                                                type="file"
                                                id="pv-profile-capture"
                                                accept="image/*"
                                                capture="user"
                                                hidden
                                            >

                                            <p class="pv-photo-hint">
                                                JPG, PNG, atau WebP, maksimal 5 MB.
                                                Foto ini disimpan sebagai foto profil.
                                            </p>

                                            @error('profile_photo', 'register')
                                                <p class="pv-error">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                    </div>

                                    @unless ($registerReady)
                                        <div class="pv-unavailable">
                                            Penyimpanan pendaftaran belum tersedia.
                                            Periksa route dan controller pendaftaran.
                                        </div>
                                    @endunless

                                    <div class="pv-foot">
                                        <span class="pv-footnote">
                                            Data digunakan untuk administrasi perpustakaan.
                                            Isi hanya informasi yang diperlukan.
                                        </span>

                                        <button
                                            type="button"
                                            class="pv-secondary pv-mobile-only pv-back-registration"
                                            id="pv-back-step"
                                            style="flex:none"
                                        >
                                            Kembali
                                        </button>

                                        <button
                                            type="button"
                                            class="pv-primary pv-mobile-only pv-next-registration"
                                            id="pv-next-step"
                                        >
                                            Lanjutkan
                                            <span aria-hidden="true">→</span>
                                        </button>

                                        <button
                                            type="submit"
                                            class="pv-primary is-red pv-save-registration"
                                            @disabled(!$registerReady)
                                        >
                                            Simpan Pendaftaran
                                        </button>
                                    </div>
                                </form>
                            </section>

                            {{-- Check-in --}}
                            <section
                                class="pv-screen is-active"
                                id="pv-visit"
                                role="tabpanel"
                                aria-labelledby="pv-tab-visit"
                            >
                                <form
                                    id="pv-visit-form"
                                    class="pv-visit"
                                    action="{{ route('visitors.checkin') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    novalidate
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="form_type"
                                        value="checkin"
                                    >

                                    <div class="pv-visit-grid">

                                        {{-- Visitor --}}
                                        <div>
                                            <h2 class="pv-section-title">
                                                Masuk Pengunjung
                                            </h2>

                                            <p class="pv-section-sub">
                                                Masukkan nomor identitas yang sudah terdaftar.
                                            </p>

                                            <div class="pv-fields">

                                                {{-- Category --}}
                                                <div class="pv-field">
                                                    <label
                                                        class="pv-label"
                                                        for="pv-v-category"
                                                    >
                                                        Kategori pengunjung
                                                        <span class="pv-required">*</span>
                                                    </label>

                                                    <select
                                                        class="pv-control"
                                                        id="pv-v-category"
                                                        name="checkin_category"
                                                        required
                                                    >
                                                        <option value="">
                                                            Pilih kategori
                                                        </option>

                                                        @foreach ($visitorCategories as $value => $label)
                                                            <option
                                                                value="{{ $value }}"
                                                                @selected($initialCheckinCategory === $value)
                                                            >
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('checkin_category', 'checkin')
                                                        <p class="pv-error">
                                                            {{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>

                                                {{-- Identity --}}
                                                <div class="pv-field">
                                                    <label
                                                        class="pv-label"
                                                        for="pv-v-id"
                                                        id="pv-v-id-label"
                                                    >
                                                        No ID
                                                        <span class="pv-required">*</span>
                                                    </label>

                                                    <input
                                                        class="pv-control"
                                                        id="pv-v-id"
                                                        name="checkin_number"
                                                        type="text"
                                                        value="{{ $initialCheckinNumber }}"
                                                        maxlength="100"
                                                        autocomplete="off"
                                                        placeholder="Masukkan nomor identitas"
                                                        required
                                                    >

                                                    @error('checkin_number', 'checkin')
                                                        <p class="pv-error">
                                                            {{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>

                                            </div>

                                            <div class="pv-notice">
                                                Belum terdaftar?

                                                <a
                                                    href="{{ route('visitors.register') }}"
                                                    data-switch-tab="register"
                                                >
                                                    Buka pendaftaran baru
                                                </a>.

                                                Nama, No HP, dan foto profil tidak perlu
                                                diisi kembali setiap kunjungan.
                                            </div>
                                        </div>

                                        {{-- Selfie --}}
                                        <div class="pv-photo-pane">
                                            <h2 class="pv-section-title">
                                                Foto selfie
                                            </h2>

                                            <p class="pv-section-sub">
                                                Ambil foto untuk kunjungan saat ini.
                                            </p>

                                            <div
                                                class="pv-photo-box"
                                                id="pv-selfie-box"
                                            >
                                                <div class="pv-photo-placeholder">
                                                    <svg
                                                        width="34"
                                                        height="34"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="#9cadbd"
                                                        stroke-width="1.4"
                                                        aria-hidden="true"
                                                    >
                                                        <path d="M14 3h-4L8 6H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-4z"/>
                                                        <circle cx="12" cy="13" r="4"/>
                                                    </svg>

                                                    <strong>
                                                        Selfie kunjungan
                                                    </strong>

                                                    <small>
                                                        Gunakan kamera depan perangkat
                                                    </small>
                                                </div>

                                                <img
                                                    id="pv-selfie-preview"
                                                    alt="Pratinjau selfie kunjungan"
                                                >
                                            </div>

                                            <div class="pv-photo-actions">
                                                <button
                                                    type="button"
                                                    class="pv-secondary"
                                                    data-camera="selfie"
                                                >
                                                    Buka kamera
                                                </button>

                                                <button
                                                    type="button"
                                                    class="pv-secondary"
                                                    data-upload="selfie"
                                                >
                                                    Unggah foto
                                                </button>
                                            </div>

                                            <input
                                                type="file"
                                                id="pv-selfie-file"
                                                name="selfie"
                                                accept="image/jpeg,image/png,image/webp"
                                                required
                                                hidden
                                            >

                                            <input
                                                type="file"
                                                id="pv-selfie-capture"
                                                accept="image/*"
                                                capture="user"
                                                hidden
                                            >

                                            <p class="pv-photo-hint">
                                                Selfie digunakan untuk catatan kunjungan,
                                                bukan pengenalan wajah otomatis.
                                            </p>

                                            @error('selfie', 'checkin')
                                                <p class="pv-error">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                    </div>

                                    @unless ($checkInReady)
                                        <div class="pv-unavailable">
                                            Penyimpanan kunjungan belum tersedia.
                                            Periksa route dan controller check-in.
                                        </div>
                                    @endunless

                                    <div class="pv-foot">
                                        <span class="pv-footnote">
                                            Setiap kunjungan dicatat terpisah dari data pendaftaran.
                                        </span>

                                        <button
                                            type="submit"
                                            class="pv-primary is-red"
                                            @disabled(!$checkInReady)
                                        >
                                            Simpan Kunjungan
                                            <span aria-hidden="true">→</span>
                                        </button>
                                    </div>
                                </form>
                            </section>

                        </div>
                    </div>

                @endif

            </div>
        </main>

        {{-- Footer --}}
        <footer class="pv-footer">
            © {{ date('Y') }} PAG Library · Perta Arun Gas
        </footer>

    </div>

    {{-- Camera --}}
    @if (!$hasCheckinReceipt)
        <div
            class="pv-camera"
            id="pv-camera"
            role="dialog"
            aria-modal="true"
            aria-labelledby="pv-camera-title"
            aria-hidden="true"
        >
            <div class="pv-camera-card">
                <h2 id="pv-camera-title">
                    Ambil foto
                </h2>

                <div class="pv-camera-view">
                    <video
                        id="pv-camera-video"
                        autoplay
                        muted
                        playsinline
                    ></video>
                </div>

                <div class="pv-camera-actions">
                    <button
                        type="button"
                        class="pv-secondary"
                        id="pv-camera-cancel"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        class="pv-primary"
                        id="pv-camera-capture"
                    >
                        Ambil Foto
                    </button>
                </div>
            </div>
        </div>
    @endif

</body>
</html>