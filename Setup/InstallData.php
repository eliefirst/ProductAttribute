<?php
namespace Elielweb\ProductAttribute\Setup;
 
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
 
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
 
        /**
         * Add attributes to the eav_attribute
         */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_select_attribute');
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'product_custom_attribute');
 
        $statusOptions = 'Elielweb\ProductAttribute\Model\Config\Source\StatusOptions';
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'product_select_attribute',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Product Elie Status',
                'input' => 'select',
                'class' => '',
                'source' => $statusOptions,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'is_used_in_grid' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false
            ]
        );
 
        /**
         * Add attributes to the eav_attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'product_custom_attribute',
            [
                'group'        => 'Custom Product Attribute',
                'type'         => 'varchar',
                'backend'      => '',
                'frontend'     => '',
                'label'        => 'Product Elie Attribute Value',
                'input'        => 'text',
                'frontend_class' => 'required-entry',
                'global'       => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible'      => true,
                'required'     => true,
                'user_defined' => false,
                'default'      => '',
                'searchable'   => true,
                'filterable'   => true,
                'comparable'   => true,
                'unique'       => false,
                'visible_on_front'        => false,
                'used_in_product_listing' => true
            ]
        );
    }
}
