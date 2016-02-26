<?php

namespace Wikibase\Repo\Tests\Parsers;

use Language;
use Wikibase\Lib\MediaWikiNumberLocalizer;
use Wikibase\Repo\Parsers\MediaWikiNumberUnlocalizer;

/**
 * @covers Wikibase\Lib\MediaWikiNumberLocalizer
 * @covers Wikibase\Repo\Parsers\MediaWikiNumberUnlocalizer
 *
 * @group ValueParsers
 * @group WikibaseRepo
 * @group Wikibase
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 */
class MediaWikiNumberUnlocalizerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @return array[] Array of arrays of three strings: localized value, language code and expected
	 * canonical value
	 */
	public function provideUnlocalize() {
		return array(
			array( '1', 'en', '1' ),
			array( '-1.1', 'en', '-1.1' ),

			array( '-1.234,56', 'de', '-1234.56' ),

			array( "\xe2\x88\x921.234,56", 'de', '-1234.56' ),
			array( "\xe2\x93\x961.234,56", 'de', '-1234.56' ),
			array( "\xe2\x93\x951.234,56", 'de', '+1234.56' ),

			array( "1\xc2\xa0234,56", 'sv', '1234.56' ),
			array( "1 234,56", 'sv', '1234.56' ),
		);
	}

	/**
	 * @dataProvider provideUnlocalize
	 */
	public function testUnlocalize( $localized, $languageCode, $canonical ) {
		$language = Language::factory( $languageCode );
		$unlocalizer = new MediaWikiNumberUnlocalizer( $language );

		$unlocalized = $unlocalizer->unlocalizeNumber( $localized );

		$this->assertEquals( $canonical, $unlocalized );
	}

	/**
	 * @return array[] Array of arrays of two or three values: number, language code and optional
	 * expected canonical value
	 */
	public function provideLocalizationRoundTrip() {
		$numbers = array( 12, -4.111, 12345678 );
		$languages = array(
			'en', 'es', 'pt', 'fr', 'de', 'sv', 'ru',  // western arabic numerals, but different separators
			'ar', 'fa', 'my', 'pi', 'ne', 'kn', // different numerals
		);

		$cases = array();
		foreach ( $languages as $lang ) {
			foreach ( $numbers as $num ) {
				$cases[] = array( $num, $lang );
			}
		}

		return $cases;
	}

	/**
	 * @dataProvider provideLocalizationRoundTrip
	 */
	public function testLocalizationRoundTrip( $number, $languageCode, $canonical = null ) {
		if ( $canonical === null ) {
			$canonical = "$number";
		}

		$language = Language::factory( $languageCode );

		$localizer = new MediaWikiNumberLocalizer( $language );
		$unlocalizer = new MediaWikiNumberUnlocalizer( $language );

		$localized = $localizer->localizeNumber( $number );
		$unlocalized = $unlocalizer->unlocalizeNumber( $localized );

		$this->assertEquals( $canonical, $unlocalized );
	}

	/**
	 * @return array[] Array of arrays of one or two strings: value and optional language code
	 */
	public function provideGetNumberRegexMatch() {
		return array(
			array( '5' ),
			array( '+3' ),
			array( '-15' ),

			array( '5.3' ),
			array( '+3.2' ),
			array( '-15.77' ),

			array( '.3' ),
			array( '+.2' ),
			array( '-.77' ),

			array( '3e9' ),
			array( '3.1E-9' ),
			array( '-.7E+3' ),

			array( '3x10^9' ),
			array( '3.1x10^-9' ),
			array( '-.7x10^+3' ),

			array( '1,335.3' ),
			array( '+1,333.2' ),
			array( '-1,315.77' ),

			array( '12.345,77', 'de' ),
			array( "12\xc2\xa0345,77", 'sv' ), // non-breaking space, as generated by the formatter
			array( "12 345,77", 'sv' ), // regular space, as might be entered by users

			array( "1\xc2\xa0234.56", 'la' ), // incomplete separatorTransformTable
		);
	}

	/**
	 * @dataProvider provideGetNumberRegexMatch
	 */
	public function testGetNumberRegexMatch( $value, $lang = 'en' ) {
		$lang = Language::factory( $lang );
		$unlocalizer = new MediaWikiNumberUnlocalizer( $lang );
		$regex = $unlocalizer->getNumberRegex();

		$hex = utf8ToHexSequence( $regex );

		$match = (bool)preg_match( "/^(?:$regex)$/u", $value, $m );
		$this->assertTrue( $match, "Hex $value: $hex" );
		$this->assertCount( 1, $m, 'There should be no capturing groups' );
	}

	/**
	 * @return array[] Array of arrays of one or two strings: value and optional language code
	 */
	public function provideGetNumberRegexMismatch() {
		return array(
			array( '' ),
			array( ' ' ),
			array( '+' ),
			array( 'e' ),
			array( '123+456' ),

			array( '.-' ),

			array( '0x20' ),
			array( '2x2' ),
			array( 'x2' ),
			array( '2x' ),

			array( 'e.' ),
			array( '.e' ),
			array( '12e' ),
			array( '12e-' ),
			array( '12e,' ),
			array( 'E17' ),
			array( '2E+-2' ),
			array( '2e2.3' ),
			array( '2e3e4' ),

			array( 'x10^' ),
			array( '.x10^' ),
			array( '12x10^' ),
			array( '12x10^-' ),
			array( '12x10^,' ),
			array( 'x10^17' ),
			array( '2x10^+-2' ),
			array( '2x10^2.3' ),
			array( '2x10^3x10^4' ),

			array( '+-3' ),
			array( '++7' ),
			array( '--5' ),
		);
	}

	/**
	 * @dataProvider provideGetNumberRegexMismatch
	 */
	public function testGetNumberRegexMismatch( $value, $lang = 'en' ) {
		$unlocalizer = new MediaWikiNumberUnlocalizer( Language::factory( $lang ) );
		$regex = $unlocalizer->getNumberRegex();

		$this->assertFalse( (bool)preg_match( "/^($regex)$/u", $value ) );
	}

}
