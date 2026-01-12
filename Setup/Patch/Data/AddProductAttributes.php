<?php
declare(strict_types=1);

namespace Elielweb\ProductAttribute\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * Add Product Attributes for Google Shopping and SEO
 */
class AddProductAttributes implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Original attributes
        $this->addProductSelectAttribute($eavSetup);
        $this->addProductCustomAttribute($eavSetup);

        // New Google Shopping / SEO attributes
        $this->addGenderAttribute($eavSetup);
        $this->addBrandAttribute($eavSetup);
        $this->addAgeGroupAttribute($eavSetup);
        $this->addGtinAttribute($eavSetup);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Add Product Select Attribute (Enable/Disable status)
     */
    private function addProductSelectAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->removeAttribute(Product::ENTITY, 'product_select_attribute');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_select_attribute',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'Product Elie Status',
                'input' => 'select',
                'source' => \Elielweb\ProductAttribute\Model\Config\Source\StatusOptions::class,
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
    }

    /**
     * Add Product Custom Attribute (Text value)
     */
    private function addProductCustomAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->removeAttribute(Product::ENTITY, 'product_custom_attribute');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_custom_attribute',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'varchar',
                'label' => 'Product Elie Attribute Value',
                'input' => 'text',
                'frontend_class' => 'required-entry',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'unique' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true
            ]
        );
    }

    /**
     * Add Gender Attribute for Google Shopping
     */
    private function addGenderAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->removeAttribute(Product::ENTITY, 'gender');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'gender',
            [
                'group' => 'Brand-Gtin-Gender',
                'type' => 'varchar',
                'label' => 'Gender',
                'input' => 'multiselect',
                'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
                'source' => \Elielweb\ProductAttribute\Model\Config\Source\GenderOptions::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'note' => 'Gender for Google Shopping feed (male, female, unisex)'
            ]
        );
    }

    /**
     * Add Brand Attribute
     */
    private function addBrandAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->removeAttribute(Product::ENTITY, 'brand');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'brand',
            [
                'group' => 'Brand-Gtin-Gender',
                'type' => 'varchar',
                'label' => 'Brand',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'note' => 'Brand name for Google Shopping and SEO'
            ]
        );
    }

    /**
     * Add Age Group Attribute for Google Shopping
     */
    private function addAgeGroupAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->removeAttribute(Product::ENTITY, 'age_group');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'age_group',
            [
                'group' => 'Brand-Gtin-Gender',
                'type' => 'varchar',
                'label' => 'Age Group',
                'input' => 'multiselect',
                'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
                'source' => \Elielweb\ProductAttribute\Model\Config\Source\AgeGroupOptions::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'note' => 'Age group for Google Shopping feed'
            ]
        );
    }

    /**
     * Add GTIN Attribute for Google Shopping
     */
    private function addGtinAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->removeAttribute(Product::ENTITY, 'gtin');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'gtin',
            [
                'group' => 'Brand-Gtin-Gender',
                'type' => 'varchar',
                'label' => 'Gtin',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'note' => 'GTIN/EAN/UPC code for Google Shopping'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->removeAttribute(Product::ENTITY, 'product_select_attribute');
        $eavSetup->removeAttribute(Product::ENTITY, 'product_custom_attribute');
        $eavSetup->removeAttribute(Product::ENTITY, 'gender');
        $eavSetup->removeAttribute(Product::ENTITY, 'brand');
        $eavSetup->removeAttribute(Product::ENTITY, 'age_group');
        $eavSetup->removeAttribute(Product::ENTITY, 'gtin');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
