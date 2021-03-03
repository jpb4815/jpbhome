<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Blog\Model\BlogPost;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldAddNewButton;
    use SilverStripe\Forms\NumericField;
    use SilverStripe\Forms\TreeDropdownField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\Forms\LiteralField;
    use SilverStripe\Forms\TextField;
    class HomePage extends Page
    {
        private static $db = [
            'HeroTitle' => 'Varchar(255)',
            'HeroButtonText' => 'Varchar(50)',
            'MobileHeroTitle' => 'Varchar(255)',
            'SnapshotLabel' => 'Varchar(55)',
            'SnapshotNumberOne' => 'Int',
            'SnapshotNumberTwo' => 'Int',
            'SnapshotNumberThree' => 'Int',
            'SnapshotButtonText' => 'Varchar(50)',
        ];

        private static $has_one = [
            'HeroLinkedPage' => SiteTree::class,
            'MobileHeroImage' => Image::class,
            'SnapshotLinkedPage' => SiteTree::class,
            'SnapshotImage' => Image::class,
            'ContentBlockLinkedPage' => SiteTree::class,
        ];

        private static $owns = [
            'MobileHeroImage',
            'SnapshotImage',
        ];

        private static $has_many = [
            'HeroItems' => HeroItem::class,
            'HomeContentBlocks' => HomeContentBlock::class,
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->removeByName(['Sidebar']);

            $fields->addFieldsToTab('Root.HeroBanner', [
                LiteralField::create('GlobalBannerHelp' , '<h2>Global Hero Content</h2>'),
                TextField::create('HeroTitle', _t(__CLASS__ . '.HEROTITLE', 'Title')),
                TextField::create('HeroButtonText', _t(__CLASS__ . '.HEROBUTTONTEXT', 'Button Text')),
                TreeDropdownField::create('HeroLinkedPageID', _t(__CLASS__ . '.HEROLINKEDPAGEID', 'Linked Page'), SiteTree::class),
                LiteralField::create('MobileBannerHelp' , '<h2>Mobile Hero Content</h2>'),
                TextField::create('MobileHeroTitle', _t(__CLASS__ . '.MOBILEHEROTITLE', 'Mobile Hero Title')),
                $mobile_hero_image = UploadField::create('MobileHeroImage', _t(__CLASS__ . '.MOBILEHEROIMAGE', 'Mobile Hero Image'))
                  ->setDescription('Image should be at least 315px wide and 70px tall. Larger images will be resized to fit. Allowed file types: JPG, JPEG, PNG'),
                LiteralField::create('DesktopBannerHelp' , '<h2>Desktop Hero Content</h2>'),
                GridField::create(
                    'HeroItems',
                    _t(__CLASS__ . '.HEROITEMS', 'Items'),
                    $this->HeroItems(),
                    $config = GridFieldConfig_RecordEditor::create()
                )
            ]);

            $mobile_hero_image->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $mobile_hero_image->setFolderName('homepage');

            if (!$this->canCreateHeroItem()) {
                $config->removeComponentsByType(GridFieldAddNewButton::class);
            }

            $fields->addFieldsToTab('Root.ContentBlocks', [
                GridField::create(
                    'HomeContentBlocks',
                    _t(__CLASS__ . '.HOMECONTENTBLOCKS', 'Content Blocks'),
                    $this->HomeContentBlocks(),
                    $config = GridFieldConfig_RecordEditor::create()
                )
            ]);

            $fields->addFieldsToTab('Root.Snapshot', [
                TextField::create('SnapshotLabel', _t(__CLASS__ .'.SNAPSHOTLABEL', 'Snapshot Label')),
                UploadField::create('SnapshotImage',_t(__CLASS__ .'.SNAPSHOTIMAGE', 'Snapshot Image'))
                  ->setDescription('Image should be at least 215px wide and 70px tall. Larger images will be resized to fit. Allowed file types: JPG, JPEG, PNG'),
                NumericField::create('SnapshotNumberOne', _t(__CLASS__ .'.SNAPSHOTNUMBERONE','Projects Completed')),
                NumericField::create('SnapshotNumberTwo', _t(__CLASS__ .'.SNAPSHOTNUMBERTWO','Projects in progress')),
                NumericField::create('SnapshotNumberThree',_t(__CLASS__ .'.SNAPSHOTNUMBERTHREE','Customers signed up')),
                TextField::create('SnapshotButtonText', _t(__CLASS__ .'.SNAPSHOTBUTTONTEXT', 'Snapshot Button Text')),
                TreeDropdownField::create('SnapshotLinkedPageID', _t(__CLASS__ . '.SNAPSHOTLINKEDPAGEID', 'View All Projects'), SiteTree::class),
            ]);

            return $fields;
        }

        public function getDisplayBlogPosts()
        {
            $posts = $this->FeaturedBlogPosts();

            if (!$posts->count()) {
                $posts = BlogPost::get()->sort([
                    'PublishDate' => 'desc'
                ])->limit(
                    $this->config()->get('max_featured_blog_posts')
                );
            }

            return $posts;
        }

        public function getMaxFeaturedTopics()
        {
            return $this->config()->get('max_featured_topics');
        }

        public function getMaxFeaturedBlogPosts()
        {
            return $this->config()->get('max_featured_blog_posts');
        }

        public function getMaxFeaturedEvents()
        {
            return $this->config()->get('max_featured_events');
        }

    }
}
