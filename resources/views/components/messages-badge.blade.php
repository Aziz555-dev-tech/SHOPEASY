<a href="{{ route('conversations.index') }}"
   class="nav-link-admin nav-link-client position-relative {{ request()->routeIs('conversations.*') ? 'active' : '' }}">

    <i class="bi bi-chat-dots"></i>
    <span class="nav-label">Messages</span>

    <span id="messagesBadge"
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
        0
    </span>
</a>
