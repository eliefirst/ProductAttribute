<?php
declare(strict_types=1);

namespace ElielWeb\ProductAttribute\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * ViewModel for Product Attributes
 */
class ProductAttributes implements ArgumentInterface
{
    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Registry $registry,
        ProductRepositoryInterface $productRepository
    ) {
        $this->registry = $registry;
        $this->productRepository = $productRepository;
    }

    /**
     * Get current product
     *
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Get Product Elie Status (Enable/Disable)
     *
     * @return string
     */
    public function getProductElieStatus(): string
    {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        $value = $product->getData('product_select_attribute');
        if ($value === null || $value === '') {
            return '';
        }

        return (int)$value === 1 ? __('Enable')->render() : __('Disable')->render();
    }

    /**
     * Get Product Elie Attribute Value
     *
     * @return string
     */
    public function getProductElieAttributeValue(): string
    {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        return (string)$product->getData('product_custom_attribute') ?: '';
    }

    /**
     * Check if custom attribute section should be displayed
     *
     * @return bool
     */
    public function hasCustomAttributeData(): bool
    {
        return !empty($this->getProductElieStatus()) || !empty($this->getProductElieAttributeValue());
    }

    /**
     * Get Gender (can be multiple values)
     *
     * @return string
     */
    public function getGender(): string
    {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        $genderValue = $product->getData('gender');
        if (!$genderValue) {
            return '';
        }

        // Handle multiselect values
        if (is_string($genderValue)) {
            $genderArray = explode(',', $genderValue);
        } else {
            $genderArray = (array)$genderValue;
        }

        $genderLabels = [];
        $genderMap = [
            'male' => __('Male'),
            'female' => __('Female'),
            'unisex' => __('Unisex')
        ];

        foreach ($genderArray as $value) {
            $value = trim($value);
            if (isset($genderMap[$value])) {
                $genderLabels[] = $genderMap[$value]->render();
            }
        }

        return implode(', ', $genderLabels);
    }

    /**
     * Get Brand
     *
     * @return string
     */
    public function getBrand(): string
    {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        return (string)$product->getData('brand') ?: '';
    }

    /**
     * Get Age Group (can be multiple values)
     *
     * @return string
     */
    public function getAgeGroup(): string
    {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        $ageGroupValue = $product->getData('age_group');
        if (!$ageGroupValue) {
            return '';
        }

        // Handle multiselect values
        if (is_string($ageGroupValue)) {
            $ageGroupArray = explode(',', $ageGroupValue);
        } else {
            $ageGroupArray = (array)$ageGroupValue;
        }

        $ageGroupLabels = [];
        $ageGroupMap = [
            'newborn' => __('Newborn'),
            'infant' => __('Infant'),
            'toddler' => __('Toddler'),
            'kids' => __('Kids'),
            'adult' => __('Adult')
        ];

        foreach ($ageGroupArray as $value) {
            $value = trim($value);
            if (isset($ageGroupMap[$value])) {
                $ageGroupLabels[] = $ageGroupMap[$value]->render();
            }
        }

        return implode(', ', $ageGroupLabels);
    }

    /**
     * Get GTIN
     *
     * @return string
     */
    public function getGtin(): string
    {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        return (string)$product->getData('gtin') ?: '';
    }

    /**
     * Check if Google Shopping section should be displayed
     *
     * @return bool
     */
    public function hasGoogleShoppingData(): bool
    {
        return !empty($this->getGender())
            || !empty($this->getBrand())
            || !empty($this->getAgeGroup())
            || !empty($this->getGtin());
    }

    /**
     * Get all Google Shopping data as JSON-LD for structured data
     *
     * @return string
     */
    public function getStructuredData(): string
    {
        $product = $this->getProduct();
        if (!$product || !$this->hasGoogleShoppingData()) {
            return '';
        }

        $data = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->getName(),
            'sku' => $product->getSku()
        ];

        if ($brand = $this->getBrand()) {
            $data['brand'] = [
                '@type' => 'Brand',
                'name' => $brand
            ];
        }

        if ($gtin = $this->getGtin()) {
            $data['gtin'] = $gtin;
        }

        if ($gender = $this->getGender()) {
            $data['audience'] = [
                '@type' => 'PeopleAudience',
                'suggestedGender' => $gender
            ];
        }

        if ($ageGroup = $this->getAgeGroup()) {
            if (!isset($data['audience'])) {
                $data['audience'] = ['@type' => 'PeopleAudience'];
            }
            $data['audience']['suggestedMinAge'] = $ageGroup;
        }

        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
