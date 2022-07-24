<?php
/*
 * Bacularis - Bacula web interface
 *
 * Copyright (C) 2021-2022 Marcin Haba
 *
 * The main author of Bacularis is Marcin Haba, with contributors, whose
 * full list can be found in the AUTHORS file.
 *
 * Bacula(R) - The Network Backup Solution
 * Baculum   - Bacula web interface
 *
 * Copyright (C) 2013-2021 Kern Sibbald
 *
 * The main author of Baculum is Marcin Haba.
 * The original author of Bacula is Kern Sibbald, with contributions
 * from many others, a complete list can be found in the file AUTHORS.
 *
 * You may use this file and others of this release according to the
 * license defined in the LICENSE file, which includes the Affero General
 * Public License, v3.0 ("AGPLv3") and some additional permissions and
 * terms pursuant to its AGPLv3 Section 7.
 *
 * This notice must be preserved when any source code is
 * conveyed and/or propagated.
 *
 * Bacula(R) is a registered trademark of Kern Sibbald.
 */

namespace Bacularis\API\Modules;

use Bacularis\Common\Modules\AuthBasic;
use Bacularis\Common\Modules\BaculumPage;
use Bacularis\API\Pages\Requirements;
use Bacularis\API\Modules\APIConfig;

session_start();

/**
 * Main API pages class.
 *
 * @author Marcin Haba <marcin.haba@bacula.pl>
 * @category Page
 */
class BaculumAPIPage extends BaculumPage
{
	/**
	 * It is first application user pre-defined for first login.
	 * It is removed just after setup application.
	 */
	public const DEFAULT_AUTH_USER = 'admin';

	public function onPreInit($param)
	{
		parent::onPreInit($param);
		$auth_mod = $this->getModule('basic_apiuser');
		if ($this->getModule('auth_basic')->authenticate($auth_mod, AuthBasic::REALM_PANEL, false) === false) {
			// authentication failed
			exit();
		}

		$config = $this->getModule('api_config')->getConfig('api');
		if (count($config) === 0) {
			if ($this->Service->getRequestedPagePath() != 'APIInstallWizard') {
				$this->goToPage('APIInstallWizard');
			}
			// without config there is no way to use API panel
			return;
		}
		$lang = key_exists('lang', $config) ? $config['lang'] : APIConfig::DEF_LANG;
		$this->Application->getGlobalization()->Culture = $lang;
	}
}
