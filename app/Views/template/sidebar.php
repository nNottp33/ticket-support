        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <?php  // if (session()->get('class') == 'user') :?>

                            <a class="sidebar-link sidebar-link"
                                href="<?php echo base_url();?>"
                                aria-expanded="false">
                                <i data-feather="home" class="feather-icon"></i>
                                <span class="hide-menu">Dashboard
                                    <div class="sub-title">แดชบอร์ด</div>
                                </span>

                            </a>

                            <?php // endif;?>

                            <?php  // if (session()->get('class') == 'user') :?>
                            <!-- <a class="sidebar-link sidebar-link"
                                href="<?php echo base_url('user/home');?>"
                            aria-expanded="false">
                            <i data-feather="home" class="feather-icon"></i>
                            <span class="hide-menu">Home
                                <div class="sub-title">หน้าแรก</div>
                            </span>
                            </a> -->

                            <?php // endif;?>
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
                                href="<?= base_url('admin/users/list'); ?>"
                                aria-expanded="false">
                                <i class="far fa-user"></i>
                                <span class="hide-menu">User
                                    <div class="sub-title">ข้อมูลผู้ใช้</div>
                                </span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link"
                                href="<?= base_url('admin/ticket/catagories') ?>"
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
                                    <a href="form-inputs.html" class="sidebar-link">
                                        <span class="hide-menu"> Report type1
                                            <div class="sub-title">รายงาน ???</div>
                                        </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="form-input-grid.html" class="sidebar-link">
                                        <span class="hide-menu"> Report type2
                                            <div class="sub-title">รายงาน ???</div>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>