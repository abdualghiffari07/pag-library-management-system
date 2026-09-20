@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb
    pageTitle="Profile Administrator"
/>

<div
    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6"
>

    <div
        class="mb-6 flex flex-col gap-1"
    >
        <h3
            class="text-lg font-semibold text-gray-800 dark:text-white/90"
        >
            Profile Administrator
        </h3>

        <p
            class="text-sm text-gray-500 dark:text-gray-400"
        >
            Kelola informasi akun dan keamanan Administrator PAG Library.
        </p>
    </div>

    {{-- Success --}}
    @if (session('success'))

        <div
            class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700 dark:border-green-900/50 dark:bg-green-500/10 dark:text-green-400"
        >
            {{ session('success') }}
        </div>

    @endif

    @if (session('password_success'))

        <div
            class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700 dark:border-green-900/50 dark:bg-green-500/10 dark:text-green-400"
        >
            {{ session('password_success') }}
        </div>

    @endif

    @include(
        'pages.profile.partials.profile-card',
        ['user' => $user]
    )

    @include(
        'pages.profile.partials.personal-info',
        ['user' => $user]
    )

    @include(
        'pages.profile.partials.security-card',
        ['user' => $user]
    )

</div>

@endsection