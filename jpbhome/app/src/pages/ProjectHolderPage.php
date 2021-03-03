<?php

namespace {
    use SilverStripe\Core\Config\Config;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldDataColumns;
    use SilverStripe\Forms\GridField\GridFieldAddNewButton;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

    class ProjectHolderPage extends Page
    {
        private static $extensions = [
            BluehouseGroup\UniquePage\Extensions\UniquePage::class,
            SilverStripe\Lumberjack\Model\Lumberjack::class,
        ];

        private static $allowed_children = [
            ProjectPage::class,
        ];

        private static $db = [
            'FirstLinkedFAQLabel'  => 'Varchar(255)',
            'SecondLinkedFAQLabel' => 'Varchar(255)',
            'EmptyTableContent' => 'Text',
            'FilterTitle' => 'Varchar(255)',
            'FilterText' => 'Text'
        ];

        private static $has_one = [
            'FirstLinkedFaqGroup'  => 'FaqGroup',
            'SecondLinkedFaqGroup' => 'FaqGroup',
        ];

        private static $many_many = [
            'FeaturedFaqs' => Faq::class,
        ];

        private static $many_many_extraFields = [
            'FeaturedFaqs' => [
                'FeaturedSortOrder' => 'Int',
            ],
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->addFieldToTab(
                'Root.Main',
                TextareaField::create(
                    'EmptyTableContent',
                    _t(__CLASS__ . '.EmptyTableContent', 'Empty Table Content')
                )->setDescription('This text will display when no projects match the selected filters.'),
                'Metadata'
            );

            $fields->addFieldToTab('Root.Types', GridField::create(
                'ProjectTypes',
                _t(__CLASS__ . '.ProjectTypes', 'Project Types'),
                $this->Types(),
                $type_config = GridFieldConfig_RecordEditor::create()
            ));

            $type_config->addComponent(new GridFieldSortableRows('SortOrder'));

            $fields->addFieldToTab('Root.Statuses', GridField::create(
                'ProjectStatuses',
                _t(__CLASS__ . '.ProjectStatuses', 'Project Statuses'),
                $this->Statuses(),
                $status_config = GridFieldConfig_RecordEditor::create()
            ));

            $status_config->addComponent(new GridFieldSortableRows('SortOrder'));

            $fields->addFieldToTab('Root.Municipalities', GridField::create(
                'Municipalities',
                _t(__CLASS__ . '.Municipalities', 'Municipalities'),
                $this->Municipalities(),
                GridFieldConfig_RecordEditor::create()
            ));

            $fields->addFieldsToTab('Root.FAQ', [
                $first_faq_group = DropdownField::create(
                    'FirstLinkedFaqGroupID',
                    _t(__CLASS__ . '.FIRSTLINKEDFAQGROUP', 'First Linked FAQ Group'),
                    FaqGroup::get()->map()->toArray()
                ),
                TextField::create('FirstLinkedFAQLabel', _t(__CLASS__ . '.FIRSTLINKEDFAQLABEL', 'First Linked FAQ Label'))
                    ->setDescription('If left blank, will default to title of FAQ Group selected above.'),
                $second_faq_group = DropdownField::create(
                    'SecondLinkedFaqGroupID',
                    _t(__CLASS__ . '.SECONDLINKEDFAQGROUP', 'Second Linked FAQ Group'),
                    FaqGroup::get()->map()->toArray()
                ),
                TextField::create('SecondLinkedFAQLabel', _t(__CLASS__ . '.SECONDLINKEDFAQLABEL', 'Second Linked FAQ Label'))
                    ->setDescription('If left blank, will default to title of FAQ Group selected above.'),
                TextField::create('FilterTitle', _t(__CLASS__ . 'FILTERTITLE', 'Table Filter Title')),
                TextField::create('FilterText', _t(__CLASS__ . 'FILTERTEXT', 'Table Filter Text'))
            ]);

            $first_faq_group->setEmptyString('-- Select FAQ Group --');
            $first_faq_group->setDescription('Select none to hide this button.');
            $second_faq_group->setEmptyString('-- Select FAQ Group --');
            $second_faq_group->setDescription('Select none to hide this button.');

            $fields->addFieldToTab('Root.FAQ', GridField::create(
                'FeaturedFaqs',
                _t(__CLASS__ . '.FeaturedFaqs', 'Featured FAQs'),
                $this->FeaturedFaqs(),
                $faq_config = GridFieldConfig_RelationEditor::create()
            ));

            $faq_config->removeComponentsByType(GridFieldAddNewButton::class);
            $faq_config->addComponent(new GridFieldSortableRows('FeaturedSortOrder'));

            // Lumberjack won't respect `summary_fields` by default
            if ($projects = $fields->fieldByName("Root.ChildPages.ChildPages")) {
                if ($columns = $projects->getConfig()->getComponentByType(GridFieldDataColumns::class)) {
                    $columns->setDisplayFields(Config::inst()->get(ProjectPage::class, 'summary_fields'));
                }
            }

            return $fields;
        }

        public function Statuses()
        {
            return ProjectStatus::get();
        }

        public function Types()
        {
            return ProjectType::get();
        }

        public function Municipalities()
        {
            return Municipality::get();
        }

        /**
         * Lumberjack doesn't automatically enforce sort order, so we apply it here.
         */
        public function getLumberjackPagesForGridfield($excluded = [])
        {
            return ProjectPage::get()->filter([
                'ParentID' => $this->owner->ID,
                'ClassName' => $excluded,
            ])->sort(Config::inst()->get(ProjectPage::class, 'default_sort'));
        }

        /**
         * SiteTree subclasses don't seems to respect custom sort order either, so
         * we apply it here.
         */
        public function Children()
        {
            return parent::Children()->sort(Config::inst()->get(ProjectPage::class, 'default_sort'));
        }
    }
}
