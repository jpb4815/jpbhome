<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\View\Requirements;
    use SilverStripe\View\SSViewer;


    class FaqPageController extends PageController
    {
        protected function init()
        {
            parent::init();

            Requirements::javascript('javascript/faq.js');
            SSViewer::setRewriteHashLinksDefault(false);
        }
    }
}
