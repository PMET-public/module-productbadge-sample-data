<?php

namespace MagentoEse\ProductBadgeSampleData\Setup;

use Magento\Framework\Setup;

class Installer implements Setup\SampleData\InstallerInterface
{

    protected $productUpdate;


    public function __construct(
        \MagentoEse\InStorePickupSampleData\Model\ProductUpdate $productUpdate

    )
    {
        $this->productUpdate = $productUpdate;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->productUpdate->install(['MagentoEse_ProductBadgeSampleData::fixtures/BadgeData.csv']);
    }
}