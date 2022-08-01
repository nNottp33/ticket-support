  <?php if (session()->get('logged_in')) :?>
  <header class="topbar" data-navbarbg="skin6">
      <nav class="navbar top-navbar navbar-expand-md">
          <div class="navbar-header" data-logobg="skin6">

              <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                      class="ti-menu ti-close"></i></a>

              <div class="navbar-brand">
                  <b class="logo-icon">
                      <img src="<?= base_url(); ?>/assets/images/logo.png"
                          alt="homepage" class="dark-logo" />

                      <img src="<?= base_url(); ?>/assets/images/logo.png"
                          alt="homepage" class="light-logo" />
                  </b>

                  <span class="logo-text mt-2">

                      <img src="<?= base_url(); ?>/assets/images/logo-text.png"
                          alt="homepage" class="dark-logo" />

                      <img src="<?= base_url(); ?>/assets/images/logo-light-text.png"
                          class="light-logo" alt="homepage" />
                  </span>

              </div>

              <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                  data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                  aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
          </div>

          <div class="navbar-collapse collapse" id="navbarSupportedContent">
              <ul class="navbar-nav float-left mr-auto ml-3 pl-1"></ul>
              <ul class="navbar-nav float-right">
                  <li class=" nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false">
                          <img src="<?= base_url() ?>/assets/images/users/profile-pic.jpg"
                              alt="user" class="rounded-circle" width="40">
                          <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span class="text-dark">
                                  <?= session()->get('nickname') == '-' ? 'Unknown' : session()->get('nickname') ?></span>
                              <i data-feather="chevron-down" class="svg-icon"></i></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                          <a class="dropdown-item"
                              href="<?= base_url('/profile') ?>">
                              <div class="row">
                                  <div class="col-md-2 col-2">
                                      <i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                                  </div>
                                  <div class="col-md-8 col-8">
                                      <span class=" hide-menu">My Profile
                                          <div class="sub-title"> ข้อมูลส่วนตัว </div>
                                      </span>
                                  </div>
                              </div>
                          </a>

                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="javascript:void(0)">
                              <div class="row">
                                  <div class="col-md-2 col-2">
                                      <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                  </div>
                                  <div class="col-md-8 col-8" onclick="logout()">
                                      <span class=" hide-menu">Logout
                                          <div class="sub-title"> ออกจากระบบ </div>
                                      </span>
                                  </div>
                              </div>

                          </a>
                      </div>
                  </li>
              </ul>
          </div>
      </nav>
  </header>

  <?php endif;
