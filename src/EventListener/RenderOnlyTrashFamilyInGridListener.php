<?php
declare(strict_types=1);

namespace KTPL\AkeneoTrashBundle\EventListener;

use KTPL\AkeneoTrashBundle\Manager\AkeneoTrashManager;
use Oro\Bundle\PimDataGridBundle\Datasource\FamilyDatasource;
use Oro\Bundle\DataGridBundle\Event\BuildAfter;

/**
 * Render only trashed families in the grid
 *
 * @author    Krishan Kant <krishan.kant@krishtechnolabs.com>
 * @copyright 2021 Krishtechnolabs (https://www.krishtechnolabs.com/)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class RenderOnlyTrashFamilyInGridListener
{
    const FAMILY_RESOURCE_NAME = 'family';

    /** @var AkeneoTrashManager */
    protected $akeneoTrashManager;

    public function __construct(AkeneoTrashManager $akeneoTrashManager)
    {
        $this->akeneoTrashManager = $akeneoTrashManager;
    }

    public function onBuildAfter(BuildAfter $event): void
    {
        $dataSource = $event->getDatagrid()->getDatasource();

        if ($dataSource instanceof FamilyDatasource) {
            $resourcesCodeTobeRenderInGridGrid = $this->akeneoTrashManager->getTrashResourcesCode(
                [
                    self::FAMILY_RESOURCE_NAME
                ]
            );

            $qb = $dataSource->getQueryBuilder();
            $rootAlias = $qb->getRootAlias();

            $qb->andWhere($rootAlias.'.code in (:familiesCode)')
                ->setParameter('familiesCode', $resourcesCodeTobeRenderInGridGrid);
        }
    }
}
