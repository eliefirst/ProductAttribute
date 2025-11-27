<?php
declare(strict_types=1);

namespace ElielWeb\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Age Group options for Google Shopping
 */
class AgeGroupOptions extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('Newborn'), 'value' => 'newborn'],
                ['label' => __('Infant'), 'value' => 'infant'],
                ['label' => __('Toddler'), 'value' => 'toddler'],
                ['label' => __('Kids'), 'value' => 'kids'],
                ['label' => __('Adult'), 'value' => 'adult']
            ];
        }
        return $this->_options;
    }
}
