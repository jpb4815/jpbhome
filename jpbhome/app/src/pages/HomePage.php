<?php

namespace {

    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\HeaderField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\OptionsetField;
    use SilverStripe\Forms\TreeDropdownField;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\CMS\Model\RedirectorPage;

    class HomePage extends Page {

        private static $db = [];

        private static $has_one =[];

        private static $has_many = [];

        private static $defaults = [];

        public function canCreate($member = null, $context = array()) {
            $pages = HomePage::get();
            return ($pages && $pages->Count() >= 1) ? false : true;
        }

        public function getCMSFields() {
            $fields = parent::getCMSFields();

            $fields->addFieldsToTab("Root.BottomCallouts", array(
                // 1st Callout
                HeaderField::create('FirstCallout', _t('HomePage.FirstCallout', 'First Callout')),
                TextField::create('Callout1Heading',_t('HomePage.Callout1Heading','First Callout Heading')),
                OptionsetField::create(
                    "Callout1LinkType",
                    _t('HomePage.REDIRECTTO', "Link to"),
                    array(
                        "Internal" => _t('HomePage.RedirectToPage', "A page on your website"),
                        "External" => _t('HomePage.RedirectToExternal', "A specific URL"),
                    ),
                    "Internal"
                ),

                // 2nd Callout
                HeaderField::create('SecondCallout', _t('HomePage.SecondCallout', 'Second Callout')),
                TextField::create('Callout2Heading',_t('HomePage.Callout2Heading','Second Callout Heading')),
                OptionsetField::create(
                    "Callout2LinkType",
                    _t('HomePage.REDIRECTTO', "Link to"),
                    array(
                        "Internal" => _t('HomePage.RedirectToPage', "A page on your website"),
                        "External" => _t('HomePage.RedirectToExternal', "A specific URL"),
                    ),
                    "Internal"
                ),

                // 3rd Callout
                HeaderField::create('ThirdCallout', _t('HomePage.ThirdCallout', 'Third Callout')),
                TextField::create('Callout3Heading',_t('HomePage.Callout3Heading','Third Callout Heading')),
                OptionsetField::create(
                    "Callout3LinkType",
                    _t('HomePage.REDIRECTTO', "Link to"),
                    array(
                        "Internal" => _t('HomePage.RedirectToPage', "A page on your website"),
                        "External" => _t('HomePage.RedirectToExternal', "A specific URL"),
                    ),
                    "Internal"
                ),

                // 4th Callout
                HeaderField::create('FourthCallout', _t('HomePage.FourthCallout', 'Fourth Callout')),
                TextField::create('Callout4Heading',_t('HomePage.Callout4Heading','Fourth Callout Heading')),
                OptionsetField::create(
                    "Callout4LinkType",
                    _t('HomePage.REDIRECTTO', "Link to"),
                    array(
                        "Internal" => _t('HomePage.RedirectToPage', "A page on your website"),
                        "External" => _t('HomePage.RedirectToExternal', "A specific URL"),
                    ),
                    "Internal"
                ),
            ));

            return $fields;
        }

    }

}
