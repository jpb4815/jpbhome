<?php

namespace {
    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TreeDropdownField;
    use SilverStripe\ORM\DataObject;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TabSet;
    use SilverStripe\Security\Permission;

    class HomeContentBlock extends DataObject
    {
        private static $db = [
            'Title' => 'Text',
            'Content' => 'Text',
            'ButtonOneText' =>'Varchar(255)',
            'ButtonTwoText' =>'Varchar(255)',
        ];

        private static $has_one = [
            'Image' => Image::class,
            'Page' => HomePage::class,
            'LinkedPageOne' => SiteTree::class,
            'LinkedPageTwo' => SiteTree::class,
        ];

        private static $owns = [
            'Image',
        ];

        public function getCMSFields()
        {
            $fields = FieldList::create(TabSet::create('Root'));

            $fields->addFieldsToTab('Root.Main', [
                $image = UploadField::create('Image', _t(__CLASS__ . '.IMAGE', 'Image'))
                    ->setDescription('Image should be at least 315px wide and 405px tall. Larger images will be resized to fit. Allowed file types: JPG, JPEG, PNG'),
                TextField::create('Title', _t(__CLASS__ . '.TITLE', 'Title')),
                TextareaField::create('Content', _t(__CLASS__ . '.CONTENT', 'Content')),
                TextField::create('ButtonOneText', _t(__CLASS__ . 'BUTTONONETEXT', 'Button One Text')),
                TreeDropdownField::create('LinkedPageOneID', _t(__CLASS__ . '.LINKEDPAGEONEID', 'Linked Page One'), SiteTree::class),
                TextField::create('ButtonTwoText', _t(__CLASS__ . 'BUTTONTWOTEXT', 'Button Two Text')),
                TreeDropdownField::create('LinkedPageTwoID', _t(__CLASS__ . '.LINKEDPAGETWOID', 'Linked Page Two'), SiteTree::class),
            ]);

            $image->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
            $image->setFolderName('home-content');

            return $fields;
        }

        public function validate()
        {
            $result = parent::validate();

            if (!$this->exists() && !$this->Page()->canCreateContentBlock()) {
                $result->addError('You cannot create more than three content blocks.');
            }

            return $result;
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
