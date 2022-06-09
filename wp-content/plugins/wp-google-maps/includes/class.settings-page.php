<?php

namespace WPGMZA;

class SettingsPage extends Page
{
	public function __construct()
	{
		global $wpgmza;
		
		Page::__construct();
		
		$this->document->loadPHPFile(plugin_dir_path(__DIR__) . 'html/settings-page.html.php');
		
		$this->disableProFeatures();
		$this->hideSelectedProFeatures();
		
		$this->form = $this->document->querySelector('form');

		$addOnTabs = apply_filters("wpgmza_global_settings_tabs", "");
		if(!empty($addOnTabs)){
			$this->form->querySelector('.settings-tabs-nav')->import($addOnTabs);
		}

		$addOnContent = apply_filters("wpgmza_global_settings_tab_content", "");
		if(!empty($addOnContent)){
			$this->form->querySelector('.addition-tabs')->import($addOnContent);
		}
		
		if(empty($_POST))
		{
			$this->document->populate($wpgmza->settings);
			$this->addFormNonces();
		}
		else
		{
			if(!$this->isNonceValid($this->form, $_POST['nonce']))
				throw new \Exception("Invalid nonce");
			
			$oldPullMethod	= $wpgmza->settings->wpgmza_settings_marker_pull;
			
			// NB: Prevent slashes accumulating in paths on Windows machines
			$data			= array_map('stripslashes', $_POST);
			
			$this->document->populate($data);
			
			$data			= $this->form->serializeFormData();

			$data = apply_filters("wpgmza_global_settings_save_redux", $data);
			
			foreach($data as $key => $value)
				$wpgmza->settings->{$key} = $value;
			
			// Update XML caches if we've just switched to XML mode
			if($wpgmza->settings->wpgmza_settings_marker_pull == Plugin::MARKER_PULL_XML && $oldPullMethod != Plugin::MARKER_PULL_XML)
				$wpgmza->updateAllMarkerXMLFiles();
			
			wp_redirect($_SERVER['HTTP_REFERER']);
			return;
		}
	}
	
	
}

add_action('admin_post_wpgmza_save_settings', function() {
	
	$settingsPage = SettingsPage::createInstance();
	
});
