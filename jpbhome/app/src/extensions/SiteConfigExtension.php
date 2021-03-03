<?php

namespace {
    use SilverStripe\Assets\Image;
    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\Forms\EmailField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\TreeDropdownField;

    class SiteConfigExtension extends DataExtension
    {
        private static $db = [
            'HeadCode'                    => 'Text',
            'BodyCode'                    => 'Text',
            'TwitterLink'                 => 'Text',
            'Address1'                    => 'Varchar(255)',
            'Address2'                    => 'Varchar(255)',
            'City'                        => 'Varchar(255)',
            'PostalCode'                  => 'Varchar(20)',
            'Email'                       => 'Varchar(50)',
            'Phone'                       => 'Varchar(20)',
            'UpdateButtonLink'            => 'Varchar(255)',
            'HeaderUpdateButtonContent'   => 'Varchar(255)',
            'OerLogoLink'                 => 'Varchar(255)',
        ];

        private static $has_one =[
            'Logo' => Image::class,
            'OerLogo' => Image::class,
            'OerFooterLogo' => Image::class,
            'FaqFooterLink' => SiteTree::class
        ];

        private static $owns = [
            'Logo',
            'OerLogo',
            'OerFooterLogo',
        ];

        public function updateCMSFields(FieldList $fields)
        {
            $fields->addFieldsToTab(
                'Root.TagManager',
                [
                    TextareaField::create('HeadCode', _t(__CLASS__ . '.HEADCODE', 'Head Code'))
                      ->setDescription('Tag Manager code to be inserted into the <head> tag on the page.'),
                    TextareaField::create('BodyCode', _t(__CLASS__ . '.BODYCODE', 'Body Code'))
                      ->setDescription('Tag Manager code to be inserted just after the opening <body> tag on the page.'),
                ]
            );
            $fields->addFieldsToTab('Root.Main', [
                UploadField::create('Logo', _t(__CLASS__ . '.Logo', 'Logo')),
            ]);

            $fields->addFieldsToTab('Root.Contact', [
                TextField::create('Address1', _t(__CLASS__ . '.Address1', 'Address (line 1)')),
                TextField::create('Address2', _t(__CLASS__ . '.Address2', 'Address (line 2)')),
                TextField::create('City', _t(__CLASS__ . '.City', 'City')),
                TextField::create('PostalCode', _t(__CLASS__ . '.PostalCode', 'Zip Code')),
                EmailField::create('Email', _t(__CLASS__ . '.Email', 'Email')),
                TextField::create('Phone', _t(__CLASS__ . '.Phone', 'Phone')),
            ]);

            $fields->addFieldsToTab('Root.Social', [
                TextareaField::create('TwitterLink', _t(__CLASS__ . '.TwitterLink', 'Twitter Link')),
            ]);

            $fields->addFieldsToTab('Root.Header / Footer', [
                TextField::create('HeaderUpdateButtonContent', _t(__CLASS__ . '.HEADERUPDATEBUTTONCONTENT', 'Header Update Button Content')),
                TextField::create('UpdateButtonLink', _t(__CLASS__ . '.UPDATEBUTTONLINK', 'Update Button Link'))
                  ->setDescription('Controls both header and footer update links'),
                $oer_logo = UploadField::create('OerLogo', _t(__CLASS__ . '.OERLOGO', 'OER Header Logo'))
                  ->setDescription('Image should be at least 215px wide and 70px tall. Larger images will be resized to fit. Allowed file types: JPG, JPEG, PNG'),
                $oer_footer_logo = UploadField::create('OerFooterLogo', _t(__CLASS__ . '.OERFOOTERLOGO', 'OER Footer Logo'))
                  ->setDescription('Image should be at least 215px wide and 70px tall. Larger images will be resized to fit. Allowed file types: JPG, JPEG, PNG'),
                TextField::create('OerLogoLink', _t(__CLASS__ . '.OERLOGOLINK', 'OER Logo Link')),
                TreeDropdownField::create(
                    'FaqFooterLinkID',
                    _t(__CLASS__ . '.FAQFOOTERLINK', 'FAQ Footer Link'),
                    SiteTree::class
                )->setDescription('Leave empty to hide the content button.'),
            ]);

            $oer_logo->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $oer_logo->setFolderName('Logos');

            $oer_footer_logo->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $oer_footer_logo->setFolderName('Logos');

            return $fields;
        }


    }
}
