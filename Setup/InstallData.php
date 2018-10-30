<?php

namespace MagentoEse\ProductBadgeSampleData\Setup;

use Magento\Framework\Setup;

    /**
 * @codeCoverageIgnore
 */
class InstallData implements Setup\InstallDataInterface
{

    protected $executor;

    /**
     * @var Installer
     */
    protected $installer;

    public function __construct(Setup\SampleData\Executor $executor, Installer $installer)
    {
        $this->executor = $executor;
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function install(Setup\ModuleDataSetupInterface $setup, Setup\ModuleContextInterface $moduleContext)
    {
        $this->executor->exec($this->installer);
    }
}
