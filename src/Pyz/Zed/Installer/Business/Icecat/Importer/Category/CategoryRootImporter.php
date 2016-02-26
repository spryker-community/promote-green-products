<?php

namespace Pyz\Zed\Installer\Business\Icecat\Importer\Category;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Pyz\Zed\Installer\InstallerConfig;
use Symfony\Component\Console\Output\OutputInterface;

class CategoryRootImporter extends CategoryImporter
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Root Categories';
    }

    /**
     * @return bool
     */
    public function isImported()
    {
        $query = SpyCategoryNodeQuery::create();
        $query->filterByIsRoot(true);
        $query->filterByIsMain(true);

        return $query->count() > 0;
    }

    /**
     * @param array $data
     */
    public function importOne(array $data)
    {
        $category = $this->format($data);
        $this->importRootCategory($category);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function importRootCategory(array $data)
    {
        $idCategory = $this->createCategory($data);

        $rootNodeTransfer = new NodeTransfer();
        $rootNodeTransfer->setIsRoot(true);
        $rootNodeTransfer->setIsMain(true);
        $rootNodeTransfer->setFkCategory($idCategory);

        $this->createCategoryNodeWithUrls($rootNodeTransfer);

        $this->touchRootNavigation($rootNodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $rootNodeTransfer
     *
     * @return void
     */
    protected function touchRootNavigation(NodeTransfer $rootNodeTransfer)
    {
        if (!$rootNodeTransfer->getIsRoot()) {
            return;
        }

        $this->touchFacade->touchActive(InstallerConfig::RESOURCE_NAVIGATION, $rootNodeTransfer->getIdCategoryNode());
    }

}
