<?php

namespace {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;

    class Municipality extends DataObject
    {
        private static $db = [
            'Name' => 'Varchar(255)',
        ];

        private static $default_sort = 'Name';

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
