<?php
namespace {

    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\View\Requirements;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Forms\FieldGroup;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\HiddenField;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\Form;
    use SilverStripe\Control\Session;
    use SilverStripe\Forms\LiteralField;
    use SilverStripe\ErrorPage\ErrorPage;
    use SilverStripe\Control\HTTPResponse_Exception;
    use SilverStripe\CMS\Controllers\ContentController;

    class Page extends SiteTree {

        private static $db = [];

        private static $has_one = [];

        public function getCMSFields() {
            $fields = parent::getCMSFields();

            return $fields;
        }

    }
}
