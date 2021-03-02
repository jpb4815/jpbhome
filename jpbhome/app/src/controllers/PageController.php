<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;

    class PageController extends ContentController
    {
        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
         */
        private static $allowed_actions = [];

        private static $url_handlers = [];

        protected function init()
        {
            parent::init();
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
            Requirements::set_force_js_to_bottom(true);

        // JS
        Requirements::javascript("javascript/jquery-1.11.1.min.js");
        Requirements::javascript("javascript/bootstrap/bootstrap.min.js");
        Requirements::javascript("javascript/datatable-defaults.js");
        Requirements::javascript("javascript/responsive-tabs.js");
        Requirements::javascript("javascript/nav.js");
        Requirements::javascript("javascript/behavior.js");

        // CSS
        Requirements::css('javascript/bootstrap-datatables/dataTables.bootstrap.css');
        Requirements::css('css/styles.css');
        Requirements::css('css/print.css','print');

        // IE CONDITIONALS
        $head = '<!--[if lt IE 9]>
        <script type="text/javascript" src="javascript/html5shiv.min.js"></script>
        <script type="text/javascript" src="javascript/respond.min.js"></script>
        <![endif]-->';
        Requirements::insertHeadTags($head);
        }
    }
}
