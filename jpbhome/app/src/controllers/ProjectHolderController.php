<?php

namespace {
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\ORM\ArrayList;
    use SilverStripe\View\ArrayData;
    use SilverStripe\View\Requirements;
    use SilverStripe\View\SSViewer;

    class ProjectHolderController extends PageController
    {
        private const TABLE_PAGE_LENGTH = 10;

        private static $allowed_actions = [
            'handleTablePartial',
        ];

        private static $url_handlers = [
            'projects' => 'handleTablePartial',
        ];

        public function index()
        {
            Requirements::javascript('javascript/project-holder.js');
            Requirements::javascript('javascript/faq.js');
            SSViewer::setRewriteHashLinksDefault(false);

            return $this->customise([
                'TableHTML' => $this->handleTablePartial($this->getRequest()),
            ])->renderWith(['ProjectHolder', 'Page']);
        }

        public function handleTablePartial(HTTPRequest $request)
        {
            $projects = $this->Children()->limit(self::TABLE_PAGE_LENGTH);

            if ($municipality_id = $request->getVar('Municipality')) {
                $projects = $projects->filter('MunicipalityID', $municipality_id);
            }

            if ($type_id = $request->getVar('Type')) {
                $projects = $projects->filter('TypeID', $type_id);
            }

            if ($status_id = $request->getVar('Status')) {
                $projects = $projects->filter('StatusID', $status_id);
            }

            $project_count = $projects->count();

            $page_count = ceil($project_count / self::TABLE_PAGE_LENGTH);

            $page = $request->getVar('Page') ? (int) $request->getVar('Page') : 1;

            // Just ignore invalid page numbers
            if ($page <= 0 || $page > $page_count) {
                $page = 1;
            }

            $pagination = ArrayData::create([
                'Start' => ($page - 1) * self::TABLE_PAGE_LENGTH + 1,
                'End' => min($page * self::TABLE_PAGE_LENGTH, $project_count),
                'Total' => $project_count,
                'Pages' => ArrayList::create(),
            ]);

            if ($page_count > 1) {
                for ($i = 1; $i <= ceil($project_count / self::TABLE_PAGE_LENGTH); $i++) {
                    $pagination->getField('Pages')->push(ArrayData::create([
                        'Number' => $i,
                        'Current' => $i === $page,
                    ]));
                }
            }

            $projects = $projects->limit(self::TABLE_PAGE_LENGTH, $pagination->getField('Start') - 1);

            return $this->customise([
                'ProjectPages' => $projects,
                'SelectedMunicipalityID' => $municipality_id,
                'SelectedTypeID' => $type_id,
                'SelectedStatusID' => $status_id,
                'CurrentPageNumber' => $page,
                'Pagination' => $pagination,
                'EmptyContent' => $this->EmptyTableContent ?: 'There appear to be no Community Solar projects with these criteria.'
            ])->renderWith('Includes/ProjectTable');
        }
    }
}
