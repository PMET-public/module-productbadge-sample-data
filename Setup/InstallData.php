<?php

namespace MagentoEse\ProductBadgeSampleData\Setup;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\State;
use Magento\Catalog\Model\Product\Attribute\Repository;

    /**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * ispuData
     *
     * @var array
     */
    private $ispuData;
    private $productFactory;
    private $state;
    private $productAttributeRepository;


    public function __construct(ProductFactory $productFactory, State $state, Repository $productAttributeRepository)
    {
        $this->productFactory = $productFactory;
        //Area code is required, but cannot be set if already set by another module
        //Try to set it. If it fails, ignore it.
        try{
            $state->setAreaCode('adminhtml');
        }
        catch(\Magento\Framework\Exception\LocalizedException $e){
            // left empty
        }
        $this->ispuData = require 'BadgesData.php';
        $this->productAttributeRepository = $productAttributeRepository;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $_attribcode='badge';

         $_product = $this->productFactory->create();
        foreach ($this->ispuData as $_pickupData) {

            //get product
            $_product->load($_product->getIdBySku($_pickupData[0]));

            //get attribute based on text value
            $_attr = $_product->getResource()->getAttribute( $_attribcode);
            $_optionId = $_attr->getSource()->getOptionId($_pickupData[1]);

            //Set data and save product
            $_product->setData($_attribcode,$_optionId );
            $_product->getResource()->saveAttribute($_product,$_attribcode);
        }
    }
}
