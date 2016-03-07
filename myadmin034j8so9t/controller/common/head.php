<?php 
class ControllerCommonHead extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle(); 

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');

		$this->language->load('common/header');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_attribute'] = $this->language->get('text_attribute');
		$this->data['text_attribute_group'] = $this->language->get('text_attribute_group');
		$this->data['text_backup'] = $this->language->get('text_backup');
		$this->data['text_banner'] = $this->language->get('text_banner');
		$this->data['text_catalog'] = $this->language->get('text_catalog');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_coupon'] = $this->language->get('text_coupon');
		$this->data['text_currency'] = $this->language->get('text_currency');			
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_customer_field'] = $this->language->get('text_customer_field');
		$this->data['text_customer_ban_ip'] = $this->language->get('text_customer_ban_ip');
		$this->data['text_custom_field'] = $this->language->get('text_custom_field');
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_design'] = $this->language->get('text_design');
		$this->data['text_documentation'] = $this->language->get('text_documentation');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_error_log'] = $this->language->get('text_error_log');
		$this->data['text_extension'] = $this->language->get('text_extension');
		$this->data['text_feed'] = $this->language->get('text_feed');
		$this->data['text_filter'] = $this->language->get('text_filter');
		$this->data['text_front'] = $this->language->get('text_front');
		$this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$this->data['text_dashboard'] = $this->language->get('text_dashboard');
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_language'] = $this->language->get('text_language');
		$this->data['text_layout'] = $this->language->get('text_layout');
		$this->data['text_localisation'] = $this->language->get('text_localisation');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_manager'] = $this->language->get('text_manager');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_opencart'] = $this->language->get('text_opencart');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_product'] = $this->language->get('text_product'); 
		$this->data['text_profile'] = $this->language->get('text_profile');
		$this->data['text_reports'] = $this->language->get('text_reports');
		$this->data['text_report_sale_order'] = $this->language->get('text_report_sale_order');
		$this->data['text_report_sale_tax'] = $this->language->get('text_report_sale_tax');
		$this->data['text_report_sale_shipping'] = $this->language->get('text_report_sale_shipping');
		$this->data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$this->data['text_report_sale_coupon'] = $this->language->get('text_report_sale_coupon');
		$this->data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
		$this->data['text_report_product_purchased'] = $this->language->get('text_report_product_purchased');
		$this->data['text_report_customer_online'] = $this->language->get('text_report_customer_online');
		$this->data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$this->data['text_report_customer_reward'] = $this->language->get('text_report_customer_reward');
		$this->data['text_report_customer_credit'] = $this->language->get('text_report_customer_credit');
		$this->data['text_report_affiliate_commission'] = $this->language->get('text_report_affiliate_commission');
		$this->data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$this->data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
		$this->data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_return_action'] = $this->language->get('text_return_action');
		$this->data['text_return_reason'] = $this->language->get('text_return_reason');
		$this->data['text_return_status'] = $this->language->get('text_return_status');
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['text_shipping'] = $this->language->get('text_shipping');
		$this->data['text_setting'] = $this->language->get('text_setting');
		$this->data['text_stock_status'] = $this->language->get('text_stock_status');
		$this->data['text_system'] = $this->language->get('text_system');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_tax_class'] = $this->language->get('text_tax_class');
		$this->data['text_tax_rate'] = $this->language->get('text_tax_rate');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_user_group'] = $this->language->get('text_user_group');
		$this->data['text_users'] = $this->language->get('text_users');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_voucher_theme'] = $this->language->get('text_voucher_theme');
		$this->data['text_weight_class'] = $this->language->get('text_weight_class');
		$this->data['text_length_class'] = $this->language->get('text_length_class');
		$this->data['text_zone'] = $this->language->get('text_zone');
		$this->data['text_openbay_extension'] = $this->language->get('text_openbay_extension');
		$this->data['text_openbay_dashboard'] = $this->language->get('text_openbay_dashboard');
		$this->data['text_openbay_orders'] = $this->language->get('text_openbay_orders');
		$this->data['text_openbay_items'] = $this->language->get('text_openbay_items');
		$this->data['text_openbay_ebay'] = $this->language->get('text_openbay_ebay');
		$this->data['text_openbay_amazon'] = $this->language->get('text_openbay_amazon');
		$this->data['text_openbay_amazonus'] = $this->language->get('text_openbay_amazonus');
		$this->data['text_openbay_settings'] = $this->language->get('text_openbay_settings');
		$this->data['text_openbay_links'] = $this->language->get('text_openbay_links');
		$this->data['text_openbay_report_price'] = $this->language->get('text_openbay_report_price');
		$this->data['text_openbay_order_import'] = $this->language->get('text_openbay_order_import');

		$this->data['text_paypal_express'] = $this->language->get('text_paypal_manage');
		$this->data['text_paypal_express_search'] = $this->language->get('text_paypal_search');
		$this->data['text_recurring_profile'] = $this->language->get('text_recurring_profile');

		$this->template = 'common/head.tpl';

		$this->render();
	}
}
?>