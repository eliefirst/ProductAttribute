<?php
declare(strict_types=1);

namespace Elielweb\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Gender options for Google Shopping
 */
class GenderOptions extends AbstractSource
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
                ['label' => __('Male'), 'value' => 'male'],
                ['label' => __('Female'), 'value' => 'female'],
                ['label' => __('Unisex'), 'value' => 'unisex']
            ];
        }
        return $this->_options;
    }
}
