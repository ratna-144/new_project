<?php
namespace Selecto\Demo\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class EstimateProfit extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $priceCurrencyFormat,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->localeCurrency = $localeCurrency;
        $this->storeManager = $storeManager;
        $this->priceCurrencyFormat = $priceCurrencyFormat;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $store = $this->storeManager->getStore(
                $this->context->getFilterParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            );
            $currency = $this->localeCurrency->getCurrency($store->getBaseCurrencyCode());
            foreach ($dataSource['data']['items'] as & $item) {        
                if(isset($item['cost'])){
                    $qty = $item['qty'];
                    $price = $this->priceCurrencyFormat->format($item['price'], ['precision'=>0, 'display'=>\Zend_Currency::NO_SYMBOL], 0);
                    $cost = $this->priceCurrencyFormat->format($item['cost'], ['precision'=>0, 'display'=>\Zend_Currency::NO_SYMBOL], 0);
                    $estimatedPrice = ($price - $cost)*(int)$qty;
                    $item['estimated_profit'] = $currency->toCurrency(sprintf("%f", $estimatedPrice));    
                }
            }
        }
        return $dataSource;
    }
}
