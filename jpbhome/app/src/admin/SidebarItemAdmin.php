<?php

namespace {
    use SilverStripe\Admin\ModelAdmin;

    class SidebarItemAdmin extends ModelAdmin
    {
        private static $managed_models = [
            Testimonial::class,
            Statement::class,
        ];

        private static $menu_title = 'Sidebar Items';

        private static $url_segment = 'sidebar-items';
    }
}
