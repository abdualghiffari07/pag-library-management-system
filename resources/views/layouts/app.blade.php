<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>
        {{ $title ?? 'Dashboard' }} | PAG Library
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- Alpine Store --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                theme: 'light',

                init() {
                    const savedTheme = localStorage.getItem('theme');

                    const systemTheme = window.matchMedia(
                        '(prefers-color-scheme: dark)'
                    ).matches
                        ? 'dark'
                        : 'light';

                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },

                toggle() {
                    this.theme = this.theme === 'light'
                        ? 'dark'
                        : 'light';

                    localStorage.setItem(
                        'theme',
                        this.theme
                    );

                    this.updateTheme();
                },

                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;

                    if (this.theme === 'dark') {
                        html.classList.add('dark');

                        body.classList.add(
                            'dark',
                            'bg-gray-900'
                        );
                    } else {
                        html.classList.remove('dark');

                        body.classList.remove(
                            'dark',
                            'bg-gray-900'
                        );
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: false,
                isMobileOpen: false,
                isHovered: false,

                init() {
                    this.restoreDesktopState();

                    window.addEventListener(
                        'resize',
                        () => this.handleResize()
                    );
                },

                restoreDesktopState() {
                    if (window.innerWidth < 1280) {
                        this.isExpanded = false;
                        return;
                    }

                    const savedState = localStorage.getItem(
                        'pagSidebarExpanded'
                    );

                    this.isExpanded = savedState === null
                        ? true
                        : savedState === 'true';
                },

                toggleExpanded() {
                    if (window.innerWidth < 1280) {
                        this.toggleMobileOpen();
                        return;
                    }

                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                    this.isHovered = false;

                    localStorage.setItem(
                        'pagSidebarExpanded',
                        String(this.isExpanded)
                    );
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(value) {
                    this.isMobileOpen = value;
                },

                setHovered(value) {
                    if (
                        window.innerWidth >= 1280 &&
                        !this.isExpanded
                    ) {
                        this.isHovered = value;
                    } else {
                        this.isHovered = false;
                    }
                },

                handleResize() {
                    if (window.innerWidth < 1280) {
                        this.isExpanded = false;
                        this.isMobileOpen = false;
                        this.isHovered = false;

                        return;
                    }

                    this.isMobileOpen = false;
                    this.isHovered = false;

                    this.restoreDesktopState();
                }
            });
        });
    </script>

    {{-- Dark Mode --}}
    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');

            const systemTheme = window.matchMedia(
                '(prefers-color-scheme: dark)'
            ).matches
                ? 'dark'
                : 'light';

            const theme = savedTheme || systemTheme;

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body
    class="dashboard-page"
    x-data="{ loaded: true }"
>

    {{-- Preloader --}}
    <x-common.preloader />

    <div class="min-h-screen xl:flex">

        {{-- Backdrop --}}
        @include('layouts.backdrop')

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main --}}
        <div
            class="min-w-0 flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]':
                    $store.sidebar.isExpanded ||
                    $store.sidebar.isHovered,

                'xl:ml-[90px]':
                    !$store.sidebar.isExpanded &&
                    !$store.sidebar.isHovered,

                'ml-0':
                    $store.sidebar.isMobileOpen
            }"
        >

            {{-- Header --}}
            @include('layouts.app-header')

            {{-- Content --}}
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
            </div>

        </div>

    </div>

    @stack('scripts')

</body>

</html>