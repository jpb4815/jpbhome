<?php

namespace {
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;

    class JobPage extends Page
    {
        private static $db = [
            'ShortDescription' => 'Text',
            'ButtonContent' => 'Varchar(255)',
        ];

        private static $allowed_children = [];

        private static $can_be_root = false;

        private static $show_in_sitetree = false;

        private static $many_many = [
            'Images' => Image::class,
        ];

        private static $owns = [
            'Images',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->addFieldsToTab(
                'Root.Main',
                [
                    TextareaField::create('ShortDescription',_t(__CLASS__ . '.SHORTDESCRIPTION', 'Short Description')),
                    TextField::create('ButtonContent',_t(__CLASS__ . '.BUTTONCONTENT', 'Button Content')),
                ],
                'Content'
            );

            $fields->addFieldToTab(
                'Root.Images',
                UploadField::create('Images', _t(__CLASS__ . '.Images' , 'Images'))
            );

            return $fields;
        }
    }
}
