<?php

namespace {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;

    class Faq extends DataObject
    {
        private static $db = [
            'Question' => 'Text',
            'Answer' => 'HTMLText',
            'SortOrder' => 'Int',
        ];

        private static $has_one = [
            'Group' => FaqGroup::class,
        ];

        private static $belongs_many_many = [
            'FeaturedOn' => ProjectHolder::class,
        ];

        private static $default_sort = 'SortOrder';

        private static $summary_fields = [
            'Question',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->removeByName('SortOrder');

            return $fields;
        }

        public function getTitle()
        {
            return $this->Question;
        }

        public function canView($member = null)
        {
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canEdit($member = null)
        {
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canDelete($member = null)
        {
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }

        public function canCreate($member = null, $context = [])
        {
            return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
        }
    }
}
