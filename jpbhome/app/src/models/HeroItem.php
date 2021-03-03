<?php

namespace {
    use SilverStripe\Assets\Image;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;

    class HeroItem extends DataObject
    {
        private static $db = [
            'Content' => 'Varchar(255)',
        ];

        private static $has_one = [
            'Image' => Image::class,
            'Page' => HomePage::class,
        ];

        private static $owns = [
            'Image',
        ];

        private static $summary_fields = [
            'Title' => 'Title'
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();
            $fields->removeByName('PageID');

            if ($image = $fields->fieldByName('Root.Main.Image')) {
                $image->setDescription('Images should be PNGs no larger than (200px wide by 115px tall).');
                $image->getValidator()->setAllowedExtensions(['png']);
                $image->setFolderName('hero-items');
            }

            return $fields;
        }

        public function validate()
        {
            $result = parent::validate();

            if (!$this->exists() && !$this->Page()->canCreateHeroItem()) {
                $result->addError('You cannot create more than three banner items.');
            }

            return $result;
        }

        public function getTitle()
        {
            return $this->Content;
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
