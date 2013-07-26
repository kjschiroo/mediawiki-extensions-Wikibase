<?php

namespace Wikibase;

use Diff\Diff;

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.3
 *
 * @file
 * @ingroup WikibaseLib
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 * @author Daniel Kinzler
 */
class ItemChange extends EntityChange {

	/**
	 * @since 0.3
	 *
	 * @return Diff
	 */
	public function getSiteLinkDiff() {
		$diff = $this->getDiff();

		if ( !$diff instanceof ItemDiff ) {
			// This shouldn't happen, but we should be robust against corrupt, incomplete
			// or obsolete instances in the database, etc.

			$cls = $diff === null ? 'null' : get_class( $diff );

			wfLogWarning(
				'Cannot get sitelink diff from ' . $cls . '. Change #' . $this->getId()
				. ", type " . $this->getType() );

			return new Diff();
		} else {
			return $diff->getSiteLinkDiff();
		}
	}
}
