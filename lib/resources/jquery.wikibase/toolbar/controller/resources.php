<?php
/**
 * @licence GNU GPL v2+
 * @author H. Snater < mediawiki@snater.com >
 *
 * @codeCoverageIgnoreStart
 */
return call_user_func( function() {
	preg_match( '+' . preg_quote( DIRECTORY_SEPARATOR ) . '(?:vendor|extensions)'
		. preg_quote( DIRECTORY_SEPARATOR ) . '.*+', __DIR__, $remoteExtPath );

	$moduleTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => '..' . $remoteExtPath[0],
	);

	$modules = array(

		'jquery.wikibase.toolbarcontroller.definitions.addtoolbar.claimgrouplistview-claimlistview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/addtoolbar/claimgrouplistview-claimlistview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.addtoolbar',
				'jquery.wikibase.claimgrouplistview',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.addtoolbar.claimlistview-statementview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/addtoolbar/claimlistview-statementview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.addtoolbar',
				'jquery.wikibase.claimlistview',
				'jquery.wikibase.toolbarcontroller',
				'wikibase.templates',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.addtoolbar.referenceview-snakview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/addtoolbar/referenceview-snakview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.addtoolbar',
				'jquery.wikibase.referenceview',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.addtoolbar.statementview-referenceview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/addtoolbar/statementview-referenceview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.addtoolbar',
				'jquery.wikibase.statementview',
				'jquery.wikibase.toolbarcontroller',
			),
			'messages' => array(
				'wikibase-addreference',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.addtoolbar.statementview-snakview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/addtoolbar/statementview-snakview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.addtoolbar',
				'jquery.wikibase.statementview',
				'jquery.wikibase.toolbarcontroller',
			),
			'messages' => array(
				'wikibase-addqualifier',
			),
		),


		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.aliasesview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/aliasesview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.aliasesview',
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.descriptionview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/descriptionview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.descriptionview',
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.entitytermsview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/entitytermsview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.entitytermsview',
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.labelview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/labelview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.labelview',
				'jquery.wikibase.toolbarcontroller',
				'wikibase.templates',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.referenceview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/referenceview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.referenceview',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.sitelinkgroupview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/sitelinkgroupview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.sitelinkgroupview',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.edittoolbar.statementview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/edittoolbar/statementview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.statementview',
				'jquery.wikibase.toolbarcontroller',
			),
		),


		'jquery.wikibase.toolbarcontroller.definitions.removetoolbar.referenceview-snakview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/removetoolbar/referenceview-snakview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.referenceview',
				'jquery.wikibase.removetoolbar',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.removetoolbar.sitelinkgroupview-sitelinkview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/removetoolbar/sitelinkgroupview-sitelinkview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.removetoolbar',
				'jquery.wikibase.sitelinkgroupview',
				'jquery.wikibase.toolbarcontroller',
			),
		),

		'jquery.wikibase.toolbarcontroller.definitions.removetoolbar.statementview-snakview' => $moduleTemplate + array(
			'scripts' => array(
				'definitions/removetoolbar/statementview-snakview.js',
			),
			'dependencies' => array(
				'jquery.wikibase.removetoolbar',
				'jquery.wikibase.statementview',
				'jquery.wikibase.toolbarcontroller',
			),
		),


		'jquery.wikibase.toolbarcontroller' => $moduleTemplate + array(
			'scripts' => array(
				'jquery.wikibase.toolbarcontroller.js',
				'jquery.wikibase.toolbarcontroller.definitions.js',
			),
			'dependencies' => array(
				'jquery.wikibase.addtoolbar',
				'jquery.wikibase.edittoolbar',
				'jquery.wikibase.movetoolbar',
				'jquery.wikibase.removetoolbar',
			),
		),

	);

	return $modules;
} );