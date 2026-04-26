  <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>
            <li>
              <form class="form-inline mr-auto">
                <div class="search-element">
                  <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                  <button class="btn" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </form>
            </li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">

          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
              class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Notifications
                <div class="float-right">
                  <a href="#">Mark All As Read</a>
                </div>
              </div>
              <div class="dropdown-list-content dropdown-list-icons">
                <a href="#" class="dropdown-item dropdown-item-unread"> <span
                    class="dropdown-item-icon bg-primary text-white"> <i class="fas
												fa-code"></i>
                  </span> <span class="dropdown-item-desc"> Template update is
                    available now! <span class="time">2 Min
                      Ago</span>
                  </span>
                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-info text-white"> <i class="far
												fa-user"></i>
                  </span> <span class="dropdown-item-desc"> <b>You</b> and <b>Dedik
                      Sugiharto</b> are now friends <span class="time">10 Hours
                      Ago</span>
                  </span>
                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-success text-white"> <i
                      class="fas
												fa-check"></i>
                  </span> <span class="dropdown-item-desc"> <b>Kusnaedi</b> has
                    moved task <b>Fix bug header</b> to <b>Done</b> <span class="time">12
                      Hours
                      Ago</span>
                  </span>
                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-danger text-white"> <i
                      class="fas fa-exclamation-triangle"></i>
                  </span> <span class="dropdown-item-desc"> Low disk space. Let's
                    clean it! <span class="time">17 Hours Ago</span>
                  </span>
                </a> <a href="#" class="dropdown-item"> <span class="dropdown-item-icon bg-info text-white"> <i class="fas
												fa-bell"></i>
                  </span> <span class="dropdown-item-desc"> Welcome to Otika
                    template! <span class="time">Yesterday</span>
                  </span>
                </a>
              </div>
              <div class="dropdown-footer text-center">
                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>
           <li class="dropdown">
    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img src="<?php echo !empty($authUser['profile_image']) ? url($authUser['profile_image']) : asset('admin/img/user.png'); ?>" 
             class="user-img-radious-style" width="35">
             
        <span class="d-sm-none d-lg-inline-block">
            <?php 
                // Prioritize the actual username, fallback to session role
                echo e($authUser['username'] ?? ucfirst($_SESSION['user_role'] ?? 'Guest')); 
            ?>
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right pullDown">
        <div class="dropdown-title">
            Hello, <?php echo e($authUser['username'] ?? 'User'); ?>
        </div>

        <a href="<?php echo url('admin/profile/view'); ?>" class="dropdown-item has-icon">
            <i class="far fa-user"></i> Profile
        </a>
        
        <a href="<?php echo url('admin/profile/view?id=' . $authUser['id']); ?>#settings" class="dropdown-item has-icon">
            <i class="fas fa-cog"></i> Settings
        </a>

        <div class="dropdown-divider"></div>

        <a href="<?php echo url('admin/auth/logout'); ?>" class="dropdown-item has-icon text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</li>
        </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo url('/'); ?>">  <span
                class="logo-name">BlogNest</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="dropdown active">
              <a href="<?php echo url('admin/dashboard') ?>" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>

            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i  data-feather="shopping-bag"></i><span>Pages</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo url('admin/pages/index') ?>">All Pages</a></li>
                <li><a class="nav-link" href="<?php echo url('admin/pages/create') ?>">Create Page</a></li>

              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i  data-feather="shopping-bag"></i><span>Profile</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo url('admin/profile/index') ?>">All Profile</a></li>
                <li><a class="nav-link" href="<?php echo url('admin/profile/view') ?>">View Profile</a></li>

              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="copy"></i><span>Post
                  </span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?php echo url('admin/posts') ?>">All Post</a></li>
                <li><a class="nav-link" href="<?php echo url('admin/posts/create') ?>">Create Post</a></li>
                <li><a class="nav-link" href="<?php echo url('admin/taxonomy') ?>">Mange Category & Tag</a></li>
                <li><a class="nav-link" href="<?php echo url('admin/comments') ?>">Mange Comments</a></li>

              </ul>
            </li>








          </ul>
        </aside>
      </div>