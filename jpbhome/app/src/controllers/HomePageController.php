<?php

namespace {
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\HeaderField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\OptionsetField;
    use SilverStripe\Forms\TreeDropdownField;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\CMS\Model\RedirectorPage;
    use SilverStripe\View\Requirements;

    class HomePageController extends PageController {

        public function init() {

            parent::init();

            Requirements::set_force_js_to_bottom(true);
            Requirements::css('css/owl.carousel.css');
        }

    }
}
