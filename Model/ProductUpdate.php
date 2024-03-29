<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\ProductBadgeSampleData\Model;

use Magento\Framework\File\Csv;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

/**
 * Class Product
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductUpdate
{

    /**
     * 
     * @var FixtureManager
     */
    protected $fixtureManager;
    
    /**
     * 
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * 
     * @var Csv
     */
    protected $csvReader;

    /**
     * 
     * @var \MagentoEse\DataInstall\Model\Import\Importer\Importer
     */
    protected $importerModel;

    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->objectManager=$objectManager;
    }

    /**
     * @param array $productFixtures
     * @param array $galleryFixtures
     * @throws \Exception
     */
    public function install(array $productFixtures)
    {
        foreach ($productFixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);
            if (!file_exists($fileName)) {
                continue;
            }

            $rows = $this->csvReader->getData($fileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $_productsArray[] = array_combine($header, $row);
            }
            $this->importerModel = $this->objectManager->create('MagentoEse\DataInstall\Model\Import\Importer\Importer');
            $this->importerModel->setEntityCode('catalog_product');
            $this->importerModel->setValidationStrategy('validation-skip-errors');
            try {
                $this->importerModel->processImport($_productsArray);
            } catch (\Exception $e) {
                print_r($e->getMessage());
            }

            print_r($this->importerModel->getLogTrace());
            print_r($this->importerModel->getErrorMessages());
            unset ($_productsArray);
        }

    }

}
