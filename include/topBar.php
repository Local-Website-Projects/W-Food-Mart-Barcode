<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-end mb-0">
            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-bs-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <span class="ms-1 nav-user-name hidden-sm">
                        <?php
                        $fetch_admin = $db_handle->runQuery("select * from admin where admin_id = {$_SESSION['admin']}");
                        echo $fetch_admin[0]["name"];
                        ?>

                    </span>
                    <img src="assets/images/users/user-5.jpg" alt="profile-user" class="rounded-circle thumb-xs" />
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class='dropdown-item' href='Profile'><i data-feather="user" class="align-self-center icon-xs icon-dual me-1"></i> Profile</a>
                    <div class="dropdown-divider mb-0"></div>
                    <a class='dropdown-item' href='Logout'><i data-feather="power" class="align-self-center icon-xs icon-dual me-1"></i> Logout</a>
                </div>
            </li>
        </ul><!--end topbar-nav-->

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile">
                    <i data-feather="menu" class="align-self-center topbar-icon"></i>
                </button>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>