<?php

namespace {
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
    use SilverStripe\View\Parsers\URLSegmentFilter;

    class FaqGroup extends DataObject
    {
        private static $db = [
            'Title' => 'Varchar(255)',
            'SortOrder' => 'Int',
            'Slug' => 'Text'
        ];

        private static $has_many = [
            'Faqs' => Faq::class,
        ];

        private static $has_one = [
            'FaqPage' => 'FaqPage',
        ];

        private static $summary_fields = [
            'ID'    => 'ID',
            'Title' => 'Title'
        ];

        private static $default_sort = 'SortOrder';

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->removeByName('SortOrder');
            $fields->removeByName('Slug');

            if ($faqs = $fields->fieldByName('Root.Faqs.Faqs')) {
                $faqs->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
            }

            return $fields;
        }

        public function onBeforeWrite()
        {
            parent::onBeforeWrite();

            // Generate slug
            if (!$this->Slug) {
                $filter = URLSegmentFilter::create();
                $this->Slug = $filter->filter($this->Title);
            }
        }

        public function Link()
        {
            return $this->FaqPage()->Link('#' . $this->Slug);
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
