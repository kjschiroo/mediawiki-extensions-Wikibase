<?php

namespace Wikibase;
use Content, Html;

/**
 * Difference view for Wikibase entities.
 *
 * @since 0.1
 *
 * @file
 * @ingroup Wikibase
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityContentDiffView extends \DifferenceEngine {

	/**
	 * @see DifferenceEngine::__construct
	 *
	 * @param null $context
	 * @param int $old
	 * @param int $new
	 * @param int $rcid
	 * @param bool $refreshCache
	 * @param bool $unhide
	 */
	public function __construct( $context = null, $old = 0, $new = 0, $rcid = 0, $refreshCache = false, $unhide = false ) {
		parent::__construct( $context, $old, $new, $rcid, $refreshCache, $unhide );

		$this->mRefreshCache = true; #FIXME: debug only!
	}

	/**
	 * @see DifferenceEngine::addHeader
	 *
	 * @param $diff
	 * @param $otitle
	 * @param $ntitle
	 * @param string $multi
	 * @param string $notice
	 *
	 * @return string
	 */
	public function addHeader( $diff, $otitle, $ntitle, $multi = '', $notice = '' ) {
		// if we don't want a two column table layout, we have to change this
		return parent::addHeader( $diff, $otitle, $ntitle, $multi, $notice );
	}

	/**
	 * Get a header for a specified revision.
	 *
	 * @param $rev \Revision
	 * @param $complete String: 'complete' to get the header wrapped depending
	 *        the visibility of the revision and a link to edit the page.
	 * @return String HTML fragment
	 */
	protected function getRevisionHeader( \Revision $rev, $complete = '' ) {
		//NOTE: This must be kept in sync with the parent implementation.
		//      Perhaps some parts could be factored out to reduce code duplication.

		$lang = $this->getLanguage();
		$user = $this->getUser();
		$revtimestamp = $rev->getTimestamp();
		$timestamp = $lang->userTimeAndDate( $revtimestamp, $user );
		$dateofrev = $lang->userDate( $revtimestamp, $user );
		$timeofrev = $lang->userTime( $revtimestamp, $user );

		$header = $this->msg(
			$rev->isCurrent() ? 'currentrev-asof' : 'revisionasof',
			$timestamp,
			$dateofrev,
			$timeofrev
		)->escaped();

		if ( $complete !== 'complete' ) {
			return $header;
		}

		$title = $rev->getTitle();

		$header = \Linker::linkKnown( $title, $header, array(),
			array( 'oldid' => $rev->getID() ) );

		if ( $rev->userCan( \Revision::DELETED_TEXT, $user ) ) {
			if ( $title->quickUserCan( 'edit', $user ) ) {
				if ( $rev->isCurrent() ) {
					$editQuery = array( 'action' => 'edit' ); //XXX: does nothing, just like view
					$msg = $this->msg( 'editold' )->escaped();
				} else {
					$editQuery = array( 'action' => 'reset' ); //XXX: not yet implemented
					$editQuery['oldid'] = $rev->getID();
					$msg = $this->msg( 'wikibase-resetold' )->escaped();
				}

				$header .= ' (' . \Linker::linkKnown( $title, $msg, array(), $editQuery ) . ')';
			}

			if ( $rev->isDeleted( \Revision::DELETED_TEXT ) ) {
				$header = Html::rawElement( 'span', array( 'class' => 'history-deleted' ), $header );
			}
		} else {
			$header = Html::rawElement( 'span', array( 'class' => 'history-deleted' ), $header );
		}

		return $header;
	}

	function generateContentDiffBody( Content $old, Content $new ) {
		/**
		 * @var EntityContent $old
		 * @var EntityContent $new
		 */
		$diffView = $old->getEntity()->getDiff( $new->getEntity() )->getView();
		$diffView->setContext( $this->getContext() );
		return $diffView->getHtml();
	}

	protected function getParserOutput( \WikiPage $page, \Revision $rev ) { //NOTE: needs a core change to work
		$parserOptions = \ParserOptions::newFromContext( $this->getContext() );
		$parserOptions->enableLimitReport();
		$parserOptions->setTidy( true );

		$parserOptions->setEditSection( false );
		$parserOptions->addExtraKey("diff=1"); // don't poison parser cache with diff-specific stuff

		$parserOutput = $page->getParserOutput( $parserOptions, $rev->getId() );
		return $parserOutput;
	}

}
