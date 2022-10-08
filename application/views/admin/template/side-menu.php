<div class="vertical-menu">

    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>
                <li>
                    <a href="<?= base_url('admin/index'); ?>" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-image-add"></i>
                        <span key="t-tables">Slider</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/slider/add'); ?>" key="t-basic-tables">Add Slider</a></li>
                        <li><a href="<?= base_url('admin/slider/list'); ?>" key="t-basic-tables">Slider List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-calendar-event"></i>
                        <span key="t-tables">Event</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/event/add'); ?>" key="t-basic-tables">Add Event</a></li>
                        <li><a href="<?= base_url('admin/event/list'); ?>" key="t-basic-tables">Event List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-motorcycle"></i>
                        <span key="t-tables">Bike</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/bike/add'); ?>" key="t-basic-tables">Add Bike</a></li>
                        <li><a href="<?= base_url('admin/bike/list'); ?>" key="t-basic-tables">Bike List</a></li>
                    </ul>

                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-repost"></i>
                        <span key="t-tables">Post</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/post/list'); ?>" key="t-basic-tables">Post List</a></li>
                        <li><a href="<?= base_url('admin/user/list'); ?>" key="t-basic-tables">User List</a></li>
                    </ul>

                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-group"></i>
                        <span key="t-tables">Community</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/community/list'); ?>" key="t-basic-tables">Group List</a></li>
                    </ul>

                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-language"></i>
                        <span key="t-tables">Language</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/language/add'); ?>" key="t-basic-tables">Language Add</a></li>
                        <li><a href="<?= base_url('admin/language/list'); ?>" key="t-data-tables">Language List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-plus"></i>
                        <span key="t-tables">Authentication</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/authentication/accounts-add'); ?>" key="t-basic-tables">Accounts Add</a></li>
                        <li><a href="<?= base_url('admin/authentication/accounts-list'); ?>" key="t-data-tables">Accounts List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-cog"></i>
                        <span key="t-tables">Settings</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?= base_url('admin/smtp/list'); ?>" key="t-basic-tables">SMTP</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>