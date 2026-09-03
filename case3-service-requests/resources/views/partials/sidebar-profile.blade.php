<div class="sidebar-profile">
    <span class="user-avatar" aria-hidden="true">{{ $user->initials() }}</span>
    <div class="sidebar-profile-text">
        <span class="sidebar-profile-name">{{ $user->displayName() }}</span>
        <span class="sidebar-profile-role">{{ $user->roleLabel() }}</span>
    </div>
</div>
