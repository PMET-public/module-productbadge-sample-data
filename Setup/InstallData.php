<?php

namespace MagentoEse\ProductBadgeSampleData\Setup;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\State;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;

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

    /** @var ProductRepositoryInterface  */
    private $productRepository;

    /** @var ProductAttributeRepositoryInterface  */
    private $productAttributeRepository;


    /**
     * InstallData constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param State $state
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, State $state, ProductAttributeRepositoryInterface $productAttributeRepository)
    {
        $this->productRepository = $productRepository;
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

        foreach ($this->ispuData as $_pickupData) {

            //get product
            $_product = $this->productRepository->get($_pickupData[0]);

            //get attribute based on text value
            $_attr = $_product->getResource()->getAttribute( $_attribcode);
            $_optionId = $_attr->getSource()->getOptionId($_pickupData[1]);

            //Set data and save product
            $_product->setCustomAttribute($_attribcode,$_optionId);
            $this->productRepository->save($_product);
        }
    }
}
