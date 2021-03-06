<?php
/**
 * @author Hemant Mann <hemant.mann121@gmail.com>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH.
 * @license GPL-2.0
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace OCA\Files_external_neim\Backend;

use \OCA\Files_External\Lib\DefinitionParameter;
use OCA\Files_External\Lib\Auth\AuthMechanism;
use OCA\Files_External\Lib\Backend\Backend;
use OCP\IL10N;

class Neim extends Backend {

	/**
	 * Dropbox constructor.
	 *
	 * @param IL10N $l
	 */
	public function __construct(IL10N $l) {
		$this
			->setIdentifier('files_external_neim')
			->addIdentifierAlias('\OC\Files\External_Storage\Neim')// legacy compat
			->setStorageClass('\OCA\Files_external_neim\Storage\Neim')
			->setText($l->t('Neim V2'))
			->addParameters([
				// new DefinitionParameter('host', $l->t('Host')),
				// (new DefinitionParameter('root', $l->t('Root')))
                // ->setFlag(DefinitionParameter::FLAG_OPTIONAL),
			])
			->addAuthScheme(AuthMechanism::SCHEME_PASSWORD);
			// ->addCustomJs('../../files_external_neim/js/neim');
	}

}
