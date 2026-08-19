<header class="header">

    <div class="header-left">

        <button
            class="sidebar-toggle"
            id="sidebar-toggle"
            type="button"
            aria-label="Buka menu"
        >
            ☰
        </button>

        <div>
            <h1 class="header-title">
                {{ $title ?? 'Dashboard' }}
            </h1>

            <div class="header-line"></div>
        </div>

    </div>


    <div class="header-right">

        <span class="message-icon">
            <img src="{{ asset('images/message.png') }}"
                alt="Notifications">
        </span>


        <div class="admin-profile">

            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="admin-info">

                <div class="admin-name">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </div>

                <div class="admin-role">
                    Administrator
                </div>

            </div>

        </div>

    </div>

</header>