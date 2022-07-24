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
 * Copyright (C) 2013-2019 Kern Sibbald
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

use Bacularis\Common\Modules\Errors\JobError;

/**
 * Job totals endpoint.
 *
 * @author Marcin Haba <marcin.haba@bacula.pl>
 * @category API
 */
class JobTotals extends BaculumAPIServer
{
	public function get()
	{
		$error = false;
		$allowed = [];
		$result = $this->getModule('bconsole')->bconsoleCommand(
			$this->director,
			['.jobs'],
			null,
			true
		);
		if ($result->exitcode === 0) {
			$allowed = $result->output;
			if (count($allowed) == 0) {
				// no $allowed means that user has no job resource assigned.
				$error = true;
				$this->output = [];
				$this->error = JobError::ERROR_NO_ERRORS;
			}
		} else {
			$error = true;
			$this->output = $result->output;
			$this->error = $result->error;
		}

		if ($error === false) {
			$jobtotals = $this->getModule('job')->getJobTotals($allowed);
			$this->output = $jobtotals;
			$this->error = JobError::ERROR_NO_ERRORS;
		}
	}
}
