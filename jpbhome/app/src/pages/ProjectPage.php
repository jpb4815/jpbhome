<?php
namespace {
    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\File;
    use SilverStripe\Assets\Image;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\EmailField;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use UncleCheese\DisplayLogic\Forms\Wrapper as DisplayLogicWrapper;

    class ProjectPage extends Page
    {
        const INFO_TYPE_LINK = 'Link';

        const INFO_TYPE_FILE = 'File';

        private static $allowed_children = [];

        private static $can_be_root = false;

        private static $show_in_sitetree = false;

        private static $db = [
            'SystemSize' => 'Varchar(255)',
            'ManagementCompany' => 'Varchar(255)',
            'InformationType' => 'Enum("Link,File","Link")',
            'InformationLink' => 'Text',
            'Email' => 'Varchar(255)',
            'SignupLink' => 'Text',
            'AdditionalInformation' => 'Text',
            'Featured' => 'Boolean',
        ];

        private static $has_one = [
            'Type' => ProjectType::class,
            'Status' => ProjectStatus::class,
            'Municipality' => Municipality::class,
            'Logo' => Image::class,
            'InformationFile' => File::class,
        ];

        private static $owns = [
            'Logo',
            'InformationFile',
        ];

        private static $summary_fields = [
            'Title' => 'Page Name',
            'Featured.Nice' => 'Featured'
        ];

        private static $default_sort = [
            'Featured' => 'DESC',
            'Title' => 'ASC',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->addFieldsToTab('Root.Details', [
                DropdownField::create('TypeID', _t(__CLASS__ . '.TypeID', 'Type'), ProjectType::get()->map())
                    ->setHasEmptyDefault(true)
                    ->setEmptyString('Select type...'),
                DropdownField::create('StatusID', _t(__CLASS__ . '.StatusID', 'Status'), ProjectStatus::get()->map())
                    ->setHasEmptyDefault(true)
                    ->setEmptyString('Select status...'),
                DropdownField::create('MunicipalityID', _t(__CLASS__ . '.MunicipalityID', 'Municipality'), Municipality::get()->map())
                    ->setHasEmptyDefault(true)
                    ->setEmptyString('Select municipality...'),
                $logo = UploadField::create('Logo', _t(__CLASS__ . '.Logo', 'Developer Logo')),
                CheckboxField::create('Featured',  _t(__CLASS__ . '.Featured', 'Featured')),
                TextField::create('SystemSize', _t(__CLASS__ . '.SystemSize', 'System Size')),
                TextField::create('ManagementCompany', _t(__CLASS__ . '.ManagementCompany', 'Subscriber/Management Company')),
                EmailField::create('Email', _t(__CLASS__ . '.Email', 'Email')),
                DropdownField::create(
                    'InformationType',
                    _t(__CLASS__ . '.InformationType', 'Information Type'),
                    $this->dbObject('InformationType')->enumValues()
                ),
                $link = TextareaField::create('InformationLink', _t(__CLASS__ . '.InformationLink', 'Information Link'))
                    ->setDescription('Please make sure to include http(s):// in the link.'),
                $file_wrapper = DisplayLogicWrapper::create(
                    UploadField::create('InformationFile', _t(__CLASS__ . '.InformationFile', 'Information File'))
                ),
                TextareaField::create('SignupLink', _t(__CLASS__ . '.SignupLink', 'Signup Link'))
                    ->setDescription('Please make sure to include http(s):// in the link.'),
                TextareaField::create('AdditionalInformation', _t(__CLASS__ . '.AdditionalInformation', 'Additional Information'))
                    ->setDescription('This field is optional and used to specify the relationship between the subscriber and the management company, or specific requirements or expectations the user may encounter when they click to sign up or get more information.'),
            ]);

            $logo->setDescription('Logos should 625px by 110px.');
            $logo->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
            $logo->setFolderName('developer-logos');

            $link->displayIf('InformationType')->isEqualTo(self::INFO_TYPE_LINK);
            $file_wrapper->displayIf('InformationType')->isEqualTo(self::INFO_TYPE_FILE);

            return $fields;
        }

        public function getInformationLinkOrFileURL()
        {
            if ($this->InformationType === self::INFO_TYPE_LINK) {
                return $this->InformationLink;
            }

            return $this->InformationFile()->getURL();
        }
    }
}
