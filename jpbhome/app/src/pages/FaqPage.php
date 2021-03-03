<?php

namespace {

    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

    class FaqPage extends Page
    {
        private static $extensions = [
            BluehouseGroup\UniquePage\Extensions\UniquePage::class
        ];

        private static $has_many = [
            'FaqGroups' => 'FaqGroup'
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->addFieldToTab('Root.QuestionGroups', GridField::create(
                'FaqGroups',
                _t(__CLASS__ . '.FAQGROUPS', 'FAQ Groups'),
                $this->FaqGroups(),
                $config = GridFieldConfig_RecordEditor::create()
            ));

            $config->addComponent(new GridFieldSortableRows('SortOrder'));

            return $fields;
        }

    }

}
