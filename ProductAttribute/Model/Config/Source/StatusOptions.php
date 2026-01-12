<?php
declare(strict_types=1);

namespace Elielweb\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Status options for product attribute
 */
class StatusOptions extends AbstractSource
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
                ['label' => __('Enable'), 'value' => 1],
                ['label' => __('Disable'), 'value' => 0]
            ];
        }
        return $this->_options;
    }
}