<?php

class VS7_SalesRuleListInCart_Model_Observer
{
    public function addList($observer)
    {
        $block = $observer->getBlock();
        if ($block->getType() != 'adminhtml/customer_edit_tab_cart') {
            return;
        }

        $transport = $observer->getTransport();
        $customer = Mage::registry('current_customer');
        $storeIds = Mage::app()->getWebsite($block->getWebsiteId())->getStoreIds();

        $quote = Mage::getModel('sales/quote')
            ->setSharedStoreIds($storeIds)
            ->loadByCustomer($customer);

        $html = $transport->getHtml();
        $appliedRuleIds = $quote->getAppliedRuleIds();
        if (empty($appliedRuleIds)) {
            return;
        }

        $appliedRuleIds = explode(',', $quote->getAppliedRuleIds());
        $html .= '
<div class="content-header skip-header">
    <table cellspacing="0">
        <tbody><tr>
            <td style="width:50%;"><h4 style="text-align: right;">Applied Rule IDs (Discounts): ' . implode(', ', $appliedRuleIds) . '</h4></td>
        </tr>
    </tbody></table>
</div>';

        $transport->setHtml($html);
    }
}