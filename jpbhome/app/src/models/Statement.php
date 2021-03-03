<?php

namespace {
    use SilverStripe\Assets\Image;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;

    class Statement extends DataObject
    {
        private static $db = [
            'Title' => 'Varchar(255)',
            'Content' => 'Text',
        ];

        private static $has_one = [
            'Image' => Image::class,
        ];

        private static $owns = [
            'Image',
        ];

        private static $summary_fields = [
            'Thumbnail' => 'Image',
            'Title',
            'Snippet' => 'Content',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            if ($title = $fields->fieldByName('Root.Main.Title')) {
                $title->setDescription('This field is for internal identification purposes only.');
            }

            if ($image = $fields->fieldByName('Root.Main.Image')) {
                $image->setDescription('Images should 850px by 600px.');
                $image->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
                $image->setFolderName('sidebar-images');
            }

            return $fields;
        }

        public function getThumbnail()
        {
            if ($this->ImageID) {
                return $this->Image()->FocusFillMax(120, 85);
            }

            return 'no image';
        }

        public function getSnippet()
        {
            if ($this->Content) {
                return strlen($this->Content) > 100 ? (substr($this->Content, 0, 100) . '...') : $this->Content;
            }

            return 'no content';
        }

        public function canView($member = null)
        {
            return Permission::check('CMS_ACCESS_SidebarItemAdmin', 'any', $member);
        }

        public function canEdit($member = null)
        {
            return Permission::check('CMS_ACCESS_SidebarItemAdmin', 'any', $member);
        }

        public function canDelete($member = null)
        {
            return Permission::check('CMS_ACCESS_SidebarItemAdmin', 'any', $member);
        }

        public function canCreate($member = null, $context = [])
        {
            return Permission::check('CMS_ACCESS_SidebarItemAdmin', 'any', $member);
        }
    }
}
