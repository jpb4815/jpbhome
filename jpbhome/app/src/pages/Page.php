<?php
namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\View\Requirements;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\Forms\FieldGroup;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\HiddenField;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\Form;
    use SilverStripe\Control\Session;
    use SilverStripe\Forms\LiteralField;
    use SilverStripe\ErrorPage\ErrorPage;
    use SilverStripe\Control\HTTPResponse_Exception;
    use SilverStripe\CMS\Controllers\ContentController;

    class Page extends SiteTree {

        private static $db = [];

        private static $has_one = [
            'HeaderImage' => \SilverStripe\Assets\Image::class,
        ];

        private static $owns = [
            'HeaderImage'
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            if ($this->ClassName != "SilverStripe\Blog\Model\BlogPost"){
                $fields->addFieldsToTab(
                    'Root.Banner',
                    [
                        $image = UploadField::create('HeaderImage', _t(__CLASS__ . 'HeaderImage', 'Banner Image'))
                            ->setDescription("Header images should be at least 1920 px wide by 480 px tall, larger images will be cropped to fit.")
                    ]
                );
                $image->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
                $image->setFolderName('header-images');
            }

            return $fields;
        }

        public function Link($action = null)
        {
            if($this->WorkID){
                $url_segments = [
                    Config::inst()->get('WorkPageController', 'url_segment'),
                    $this->Work()->URLSegment,
                    'resources',
                    $this->URLSegment
                ];

                if($action) {
                    $url_segments[] = $action;
                }

                return implode("/", $url_segments);
            }

            return parent::Link($action);
        }

        public function getBreadcrumbItems($maxDepth = 20, $stopAtPageType = false, $showHidden = false)
        {
            if ($this->WorkID) {
                return new ArrayList([
                    new ArrayData([
                        'MenuTitle' => $this->Work()->Title,
                        'Link' => $this->Work()->Link()
                    ]),
                    new ArrayData([
                        'MenuTitle' => 'resources',
                        'Link' => $this->Work()->Link()
                    ]),
                    $this
                ]);
            }

            return parent::getBreadcrumbItems($maxDepth, $stopAtPageType, $showHidden);
        }
    }
}
