          <?php if (session()->get('logged_in')) :?>

          <aside class="left-sidebar" data-sidebarbg="skin6">
              <div class="scroll-sidebar" data-sidebarbg="skin6">
                  <nav class="sidebar-nav">
                      <ul id="sidebarnav">
                          <li class="sidebar-item">

                              <?php if (session()->get('class') == 'user') :?>
                              <a class="sidebar-link sidebar-link"
                                  href="<?php echo base_url('user/home');?>"
                                  aria-expanded="false">
                                  <i data-feather="home" class="feather-icon"></i>
                                  <span class="hide-menu">Home
                                      <div class="sub-title">หน้าแรก</div>
                                  </span>
                              </a>


                          <li class="list-divider"></li>
                          <li class="sidebar-item">
                              <a class="sidebar-link"
                                  href="<?php echo base_url('user/history/ticket');?>"
                                  aria-expanded="false">
                                  <i class="fas fa-history feather-icon"></i>
                                  <span class="hide-menu">History Ticket
                                      <div class="sub-title">ประวัติการแจ้งปัญหา</div>
                                  </span>
                              </a>
                          </li>


                          <li class="list-divider"></li>
                          <li class="nav-small-cap">
                              <span class="hide-menu">Report</span>
                          </li>

                          <li class="sidebar-item">
                              <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                  <i class="far fa-file-pdf"></i>
                                  <span class="hide-menu">Report
                                      <div class="sub-title">รายงาน</div>
                                  </span>
                              </a>
                              <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                  <li class="sidebar-item">
                                      <a href="<?php echo base_url('user/report/ticket/all'); ?>"
                                          class="sidebar-link">
                                          <span class="hide-menu"> Report All ticket
                                              <div class="sub-title">รายงานปัญหาทั้งหมด</div>
                                          </span>
                                      </a>
                                  </li>
                                  <li class="sidebar-item">
                                      <a href="<?php echo base_url('user/report/ticket/status'); ?>"
                                          class="sidebar-link">
                                          <span class="hide-menu"> Report Ticket by status
                                              <div class="sub-title">รายงานปัญหาตามสถานะ</div>
                                          </span>
                                      </a>
                                  </li>
                              </ul>
                          </li>

                          <?php endif;?>

                          <?php if (session()->get('class') == 'admin') :?>

                          <a class="sidebar-link sidebar-link"
                              href="<?php echo base_url('admin/');?>"
                              aria-expanded="false">
                              <i data-feather="home" class="feather-icon"></i>
                              <span class="hide-menu">Dashboard
                                  <div class="sub-title">แดชบอร์ด</div>
                              </span>

                          </a>
                          </li>

                          <li class="list-divider"></li>
                          <li class="nav-small-cap">
                              <span class="hide-menu">Data Management</span>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link"
                                  href="<?php echo base_url('admin/ticket/list');?>"
                                  aria-expanded="false">
                                  <i data-feather="tag" class="feather-icon"></i>
                                  <span class="hide-menu">Ticket List
                                      <div class="sub-title">รายการแจ้งปัญหา</div>
                                  </span>
                              </a>
                          </li>
                          <li class="sidebar-item">
                              <a class="sidebar-link sidebar-link"
                                  href="<?= base_url('admin/users'); ?>"
                                  aria-expanded="false">
                                  <i class="far fa-user"></i>
                                  <span class="hide-menu">User
                                      <div class="sub-title">ข้อมูลผู้ใช้</div>
                                  </span>
                              </a>
                          </li>

                          <li class="sidebar-item">
                              <a class="sidebar-link sidebar-link"
                                  href="<?= base_url('admin/catagories') ?>"
                                  aria-expanded="false">
                                  <i class="far fa-bookmark"></i>
                                  <span class="hide-menu">Catagories
                                      <div class="sub-title">ข้อมูลปัญหา</div>
                                  </span>
                              </a>
                          </li>

                          <li class="list-divider"></li>
                          <li class="nav-small-cap">
                              <span class="hide-menu">Report</span>
                          </li>

                          <li class="sidebar-item">
                              <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                  <i class="far fa-file-pdf"></i>
                                  <span class="hide-menu">Report
                                      <div class="sub-title">รายงาน</div>
                                  </span>
                              </a>
                              <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                  <li class="sidebar-item">
                                      <a href="<?= base_url('admin/report/performance'); ?>"
                                          class="sidebar-link">
                                          <span class="hide-menu"> Performance Report
                                              <div class="sub-title">รายงานประสิทธิภาพ</div>
                                          </span>
                                      </a>
                                  </li>
                                  <li class="sidebar-item">
                                      <a href="<?= base_url('admin/report/dashboard'); ?>"
                                          class="sidebar-link">
                                          <span class="hide-menu"> Dashboard Report
                                              <div class="sub-title">รายงานสรุปผล</div>
                                          </span>
                                      </a>
                                  </li>
                              </ul>
                          </li>

                          <?php  endif;?>
                      </ul>
                  </nav>
              </div>
          </aside>

          <?php  endif;
