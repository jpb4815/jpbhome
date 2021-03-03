<?php

namespace {
    use SilverStripe\Assets\Image;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Security\Permission;

    class Testimonial extends DataObject
    {
        private static $db = [
            'Name' => 'Varchar(255)',
            'Position' => 'Varchar(255)',
            'ProjectName' => 'Varchar(255)',
            'Quote' => 'HTMLText',
        ];

        private static $has_one = [
            'Image' => Image::class,
        ];

        private static $owns = [
            'Image',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            if ($image = $fields->fieldByName('Root.Main.Image')) {
                $image->setDescription('Image should be square with minimum width of 115px. Larger images will be resized to fit. Allowed file types: JPG/JPEG, PNG.');
                $image->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
                $image->setFolderName('testimonials');
            }

            return $fields;
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
