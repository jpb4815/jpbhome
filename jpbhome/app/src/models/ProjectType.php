<?php
namespace {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;

    class ProjectType extends DataObject
    {
        private static $db = [
            'Title' => 'Varchar(255)',
            'SortOrder' => 'Int',
        ];

        private static $has_many = [
            'Pages' => ProjectPage::class,
        ];

        private static $default_sort = 'SortOrder';

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->removeByName('SortOrder');

            return $fields;
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
