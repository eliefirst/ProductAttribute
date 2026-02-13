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
 * Add Module Attributes (carat, climp_type, flower, material, odeis_sku,
 * seo_family, seo_main_product, store_name, visibility_redline, weight_silver)
 */
class AddModuleAttributes implements DataPatchInterface, PatchRevertableInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private EavSetupFactory $eavSetupFactory;

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

        $this->addCaratAttribute($eavSetup);
        $this->addClimpTypeAttribute($eavSetup);
        $this->addFlowerAttribute($eavSetup);
        $this->addMaterialAttribute($eavSetup);
        $this->addOdeisskuAttribute($eavSetup);
        $this->addSeoFamilyAttribute($eavSetup);
        $this->addSeoMainProductAttribute($eavSetup);
        $this->addStoreNameAttribute($eavSetup);
        $this->addVisibilityRedlineAttribute($eavSetup);
        $this->addWeightSilverAttribute($eavSetup);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Add Carat attribute (text / varchar)
     */
    private function addCaratAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'carat',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'varchar',
                'label' => 'Carat',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add Serti attribute (select / int)
     */
    private function addClimpTypeAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'climp_type',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'Serti',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add Flower attribute (select / int)
     */
    private function addFlowerAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'flower',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'Flower',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add Material attribute (select / int)
     */
    private function addMaterialAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'material',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'Material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add ODEIS Sku attribute (text / varchar)
     */
    private function addOdeisskuAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'odeis_sku',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'varchar',
                'label' => 'ODEIS Sku',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add SEO Famille produit attribute (text / varchar)
     */
    private function addSeoFamilyAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'seo_family',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'varchar',
                'label' => 'SEO Famille produit',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add SEO Produit Principal attribute (select / int)
     */
    private function addSeoMainProductAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'seo_main_product',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'SEO Produit Principal',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add Store_Name attribute (select / int)
     */
    private function addStoreNameAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'store_name',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'Store_Name',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add Visibilite REDLINE attribute (boolean / int)
     */
    private function addVisibilityRedlineAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'visibility_redline',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'int',
                'label' => 'Visibilité (REDLINE)',
                'input' => 'boolean',
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '0',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
            ]
        );
    }

    /**
     * Add Weight Silver attribute (text / varchar)
     */
    private function addWeightSilverAttribute(EavSetup $eavSetup): void
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'weight_silver',
            [
                'group' => 'Custom Product Attribute',
                'type' => 'varchar',
                'label' => 'Weight Silver',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
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

        $eavSetup->removeAttribute(Product::ENTITY, 'carat');
        $eavSetup->removeAttribute(Product::ENTITY, 'climp_type');
        $eavSetup->removeAttribute(Product::ENTITY, 'flower');
        $eavSetup->removeAttribute(Product::ENTITY, 'material');
        $eavSetup->removeAttribute(Product::ENTITY, 'odeis_sku');
        $eavSetup->removeAttribute(Product::ENTITY, 'seo_family');
        $eavSetup->removeAttribute(Product::ENTITY, 'seo_main_product');
        $eavSetup->removeAttribute(Product::ENTITY, 'store_name');
        $eavSetup->removeAttribute(Product::ENTITY, 'visibility_redline');
        $eavSetup->removeAttribute(Product::ENTITY, 'weight_silver');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [
            AddProductAttributes::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
