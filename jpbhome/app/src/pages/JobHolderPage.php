<?php

namespace {

    class JobHolderPage extends Page
    {
        private static $extensions = [
            BluehouseGroup\UniquePage\Extensions\UniquePage::class,
            SilverStripe\Lumberjack\Model\Lumberjack::class,
        ];

        private static $allowed_children = [
            JobPage::class,
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            return $fields;
        }
    }
}

