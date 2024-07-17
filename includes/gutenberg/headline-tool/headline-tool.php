<?php

namespace MonsterInsightsHeadlineToolPlugin;

// setup defines
define( 'MONSTERINSIGHTS_HEADLINE_TOOL_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Headline Tool
 *
 * @since      0.1
 * @author     Debjit Saha
 */
class MonsterInsightsHeadlineToolPlugin {

	/**
	 * Class Variables.
	 */
	private $emotion_power_words = array();
	private $power_words = array();
	private $common_words = array();
	private $uncommon_words = array();

	/**
	 * Constructor
	 *
	 * @return   none
	 */
	function __construct() {
		$this->init();
	}

	/**
	 * Add the necessary hooks and filters
	 */
	function init() {
		add_action( 'wp_ajax_monsterinsights_gutenberg_headline_analyzer_get_results', array( $this, 'get_result' ) );
	}

	/**
	 * Ajax request endpoint for the uptime check
	 */
	function get_result() {

		// csrf check
		if ( check_ajax_referer( 'monsterinsights_gutenberg_headline_nonce', false, false ) === false ) {
			$content = self::output_template( 'results-error.php' );
			wp_send_json_error(
				array(
					'html' => $content
				)
			);
		}

		// get whether or not the website is up
		$result = $this->get_headline_scores();

		if ( ! empty( $result->err ) ) {
			$content = self::output_template( 'results-error.php', $result );
			wp_send_json_error(
				array( 'html' => $content, 'analysed' => false )
			);
		} else {
			if(!isset($_REQUEST['q'])){
				wp_send_json_error(
					array( 'html' => '', 'analysed' => false )
				);
			}
			$q = (isset($_REQUEST['q'])) ? sanitize_text_field($_REQUEST['q']) : '';
			// send the response
			wp_send_json_success(
				array(
					'result'   => $result,
					'analysed' => ! $result->err,
					'sentence' => ucwords( wp_unslash( $q ) ),
					'score'    => ( isset( $result->score ) && ! empty( $result->score ) ) ? $result->score : 0
				)
			);

		}
	}

	/**
	 * function to match words from sentence
	 * @return Object
	 */
	function match_words( $sentence, $sentence_split, $words ) {
		$ret = array();
		foreach ( $words as $wrd ) {
			// check if $wrd is a phrase
			if ( strpos( $wrd, ' ' ) !== false ) {
				$word_position = strpos( $sentence, $wrd );

				// Word not found in the sentence.
				if ( $word_position === false ) {
					continue;
				}

				// Check this is the end of the sentence.
				$is_end = strlen( $sentence ) === $word_position + 1;

				// Check the next character is a space.
				$is_space = " " === substr( $sentence, $word_position + strlen( $wrd ), 1 );

				// If it is a phrase then the next character must end of sentence or a space.
				if ( $is_end || $is_space ) {
					$ret[] = $wrd;
				}
			} // if $wrd is a single word
			else {
				if ( in_array( $wrd, $sentence_split ) ) {
					$ret[] = $wrd;
				}
			}
		}

		return $ret;
	}

	/**
	 * main function to calculate headline scores
	 * @return Object
	 */
	function get_headline_scores() {
		$input = (isset($_REQUEST['q'])) ? sanitize_text_field($_REQUEST['q']) : '';

		// init the result array
		$result                   = new \stdClass();
		$result->input_array_orig = explode( ' ', wp_unslash( $input ) );

		// strip useless characters
		$input = preg_replace( '/[^A-Za-z0-9 ]/', '', $input );

		// strip whitespace
		$input = preg_replace( '!\s+!', ' ', $input );

		// lower case
		$input = strtolower( $input );

		$result->input = $input;

		// bad input
		if ( ! $input || $input == ' ' || trim( $input ) == '' ) {
			$result->err = true;
			$result->msg = __( 'Bad Input', 'google-analytics-for-wordpress' );

			return $result;
		}

		// overall score;
		$scoret = 0;

		// headline array
		$input_array = explode( ' ', $input );

		$result->input_array = $input_array;

		// all okay, start analysis
		$result->err = false;

		// Length - 55 chars. optimal
		$result->length = strlen( str_replace( ' ', '', $input ) );
		$scoret         = $scoret + 3;

		if ( $result->length <= 19 ) {
			$scoret += 5;
		} elseif ( $result->length >= 20 && $result->length <= 34 ) {
			$scoret += 8;
		} elseif ( $result->length >= 35 && $result->length <= 66 ) {
			$scoret += 11;
		} elseif ( $result->length >= 67 && $result->length <= 79 ) {
			$scoret += 8;
		} elseif ( $result->length >= 80 ) {
			$scoret += 5;
		}

		// Count - typically 6-7 words
		$result->word_count = count( $input_array );
		$scoret             = $scoret + 3;

		if ( $result->word_count == 0 ) {
			$scoret = 0;
		} else if ( $result->word_count >= 2 && $result->word_count <= 4 ) {
			$scoret += 5;
		} elseif ( $result->word_count >= 5 && $result->word_count <= 9 ) {
			$scoret += 11;
		} elseif ( $result->word_count >= 10 && $result->word_count <= 11 ) {
			$scoret += 8;
		} elseif ( $result->word_count >= 12 ) {
			$scoret += 5;
		}

		// Calculate word match counts
		$result->power_words        = $this->match_words( $result->input, $result->input_array, $this->power_words() );
		$result->power_words_per    = count( $result->power_words ) / $result->word_count;
		$result->emotion_words      = $this->match_words( $result->input, $result->input_array, $this->emotion_power_words() );
		$result->emotion_words_per  = count( $result->emotion_words ) / $result->word_count;
		$result->common_words       = $this->match_words( $result->input, $result->input_array, $this->common_words() );
		$result->common_words_per   = count( $result->common_words ) / $result->word_count;
		$result->uncommon_words     = $this->match_words( $result->input, $result->input_array, $this->uncommon_words() );
		$result->uncommon_words_per = count( $result->uncommon_words ) / $result->word_count;
		$result->word_balance       = __( 'Can Be Improved', 'google-analytics-for-wordpress' );
		$result->word_balance_use   = array();

		if ( $result->emotion_words_per < 0.1 ) {
			$result->word_balance_use[] = __( 'emotion', 'google-analytics-for-wordpress' );
		} else {
			$scoret = $scoret + 15;
		}

		if ( $result->common_words_per < 0.2 ) {
			$result->word_balance_use[] = __( 'common', 'google-analytics-for-wordpress' );
		} else {
			$scoret = $scoret + 11;
		}

		if ( $result->uncommon_words_per < 0.1 ) {
			$result->word_balance_use[] = __( 'uncommon', 'google-analytics-for-wordpress' );
		} else {
			$scoret = $scoret + 15;
		}

		if ( count( $result->power_words ) < 1 ) {
			$result->word_balance_use[] = __( 'power', 'google-analytics-for-wordpress' );
		} else {
			$scoret = $scoret + 19;
		}

		if (
			$result->emotion_words_per >= 0.1 &&
			$result->common_words_per >= 0.2 &&
			$result->uncommon_words_per >= 0.1 &&
			count( $result->power_words ) >= 1 ) {
			$result->word_balance = __( 'Perfect', 'google-analytics-for-wordpress' );
			$scoret               = $scoret + 3;
		}

		// Sentiment analysis also look - https://github.com/yooper/php-text-analysis

		// Emotion of the headline - sentiment analysis
		// Credits - https://github.com/JWHennessey/phpInsight/
		require_once MONSTERINSIGHTS_HEADLINE_TOOL_DIR_PATH . '/phpinsight/autoload.php';
		$sentiment         = new \PHPInsight\Sentiment();
		$class_senti       = $sentiment->categorise( $input );
		$result->sentiment = $class_senti;

		$scoret = $scoret + ( $result->sentiment === 'pos' ? 10 : ( $result->sentiment === 'neg' ? 10 : 7 ) );

		// Headline types
		$headline_types = array();

		// HDL type: how to, how-to, howto
		if ( strpos( $input, __( 'how to', 'google-analytics-for-wordpress' ) ) !== false || strpos( $input, __( 'howto', 'google-analytics-for-wordpress' ) ) !== false ) {
			$headline_types[] = __( 'How-To', 'google-analytics-for-wordpress' );
			$scoret           = $scoret + 7;
		}

		// HDL type: numbers - numeric and alpha
		$num_quantifiers = array(
			__( 'one', 'google-analytics-for-wordpress' ),
			__( 'two', 'google-analytics-for-wordpress' ),
			__( 'three', 'google-analytics-for-wordpress' ),
			__( 'four', 'google-analytics-for-wordpress' ),
			__( 'five', 'google-analytics-for-wordpress' ),
			__( 'six', 'google-analytics-for-wordpress' ),
			__( 'seven', 'google-analytics-for-wordpress' ),
			__( 'eight', 'google-analytics-for-wordpress' ),
			__( 'nine', 'google-analytics-for-wordpress' ),
			__( 'eleven', 'google-analytics-for-wordpress' ),
			__( 'twelve', 'google-analytics-for-wordpress' ),
			__( 'thirt', 'google-analytics-for-wordpress' ),
			__( 'fift', 'google-analytics-for-wordpress' ),
			__( 'hundred', 'google-analytics-for-wordpress' ),
			__( 'thousand', 'google-analytics-for-wordpress' ),
		);

		$list_words = array_intersect( $input_array, $num_quantifiers );
		if ( preg_match( '~[0-9]+~', $input ) || ! empty ( $list_words ) ) {
			$headline_types[] = __( 'List', 'google-analytics-for-wordpress' );
			$scoret           = $scoret + 7;
		}

		// HDL type: Question
		$qn_quantifiers     = array(
			__( 'where', 'google-analytics-for-wordpress' ),
			__( 'when', 'google-analytics-for-wordpress' ),
			__( 'how', 'google-analytics-for-wordpress' ),
			__( 'what', 'google-analytics-for-wordpress' ),
			__( 'have', 'google-analytics-for-wordpress' ),
			__( 'has', 'google-analytics-for-wordpress' ),
			__( 'does', 'google-analytics-for-wordpress' ),
			__( 'do', 'google-analytics-for-wordpress' ),
			__( 'can', 'google-analytics-for-wordpress' ),
			__( 'are', 'google-analytics-for-wordpress' ),
			__( 'will', 'google-analytics-for-wordpress' ),
		);
		$qn_quantifiers_sub = array(
			__( 'you', 'google-analytics-for-wordpress' ),
			__( 'they', 'google-analytics-for-wordpress' ),
			__( 'he', 'google-analytics-for-wordpress' ),
			__( 'she', 'google-analytics-for-wordpress' ),
			__( 'your', 'google-analytics-for-wordpress' ),
			__( 'it', 'google-analytics-for-wordpress' ),
			__( 'they', 'google-analytics-for-wordpress' ),
			__( 'my', 'google-analytics-for-wordpress' ),
			__( 'have', 'google-analytics-for-wordpress' ),
			__( 'has', 'google-analytics-for-wordpress' ),
			__( 'does', 'google-analytics-for-wordpress' ),
			__( 'do', 'google-analytics-for-wordpress' ),
			__( 'can', 'google-analytics-for-wordpress' ),
			__( 'are', 'google-analytics-for-wordpress' ),
			__( 'will', 'google-analytics-for-wordpress' ),
		);
		if ( in_array( $input_array[0], $qn_quantifiers ) ) {
			if ( in_array( $input_array[1], $qn_quantifiers_sub ) ) {
				$headline_types[] = __( 'Question', 'google-analytics-for-wordpress' );
				$scoret           = $scoret + 7;
			}
		}

		// General headline type
		if ( empty( $headline_types ) ) {
			$headline_types[] = __( 'General', 'google-analytics-for-wordpress' );
			$scoret           = $scoret + 5;
		}

		// put to result
		$result->headline_types = $headline_types;

		// Resources for more reading:
		// https://kopywritingkourse.com/copywriting-headlines-that-sell/
		// How To _______ That Will Help You ______
		// https://coschedule.com/blog/how-to-write-the-best-headlines-that-will-increase-traffic/

		$result->score = $scoret >= 93 ? 93 : $scoret;

		return $result;
	}

	/**
	 * Output template contents
	 *
	 * @param $template String template file name
	 *
	 * @return String template content
	 */
	static function output_template( $template, $result = '', $theme = '' ) {
		ob_start();
		require MONSTERINSIGHTS_HEADLINE_TOOL_DIR_PATH . '' . $template;
		$tmp = ob_get_contents();
		ob_end_clean();

		return $tmp;
	}

	/**
	 * Get User IP
	 *
	 * Returns the IP address of the current visitor
	 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/blob/904db487f6c07a3a46903202d31d4e8ea2b30808/includes/misc-functions.php#L163
	 * @return string $ip User's IP address
	 */
	static function get_ip() {

		$ip = '127.0.0.1';

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
		}

		// Fix potential CSV returned from $_SERVER variables
		$ip_array = explode( ',', $ip );
		$ip_array = array_map( 'trim', $ip_array );

		return $ip_array[0];
	}

	/**
	 * Emotional power words
	 *
	 * @return array emotional power words
	 */
	function emotion_power_words() {
		if ( isset( $this->emotion_power_words ) && ! empty( $this->emotion_power_words ) ) {
			return $this->emotion_power_words;
		}

		$this->emotion_power_words = array(
			__( "destroy", "google-analytics-for-wordpress" ),
			__( "extra", "google-analytics-for-wordpress" ),
			__( "in a", "google-analytics-for-wordpress" ),
			__( "devastating", "google-analytics-for-wordpress" ),
			__( "eye-opening", "google-analytics-for-wordpress" ),
			__( "gift", "google-analytics-for-wordpress" ),
			__( "in the world", "google-analytics-for-wordpress" ),
			__( "devoted", "google-analytics-for-wordpress" ),
			__( "fail", "google-analytics-for-wordpress" ),
			__( "in the", "google-analytics-for-wordpress" ),
			__( "faith", "google-analytics-for-wordpress" ),
			__( "grateful", "google-analytics-for-wordpress" ),
			__( "inexpensive", "google-analytics-for-wordpress" ),
			__( "dirty", "google-analytics-for-wordpress" ),
			__( "famous", "google-analytics-for-wordpress" ),
			__( "disastrous", "google-analytics-for-wordpress" ),
			__( "fantastic", "google-analytics-for-wordpress" ),
			__( "greed", "google-analytics-for-wordpress" ),
			__( "grit", "google-analytics-for-wordpress" ),
			__( "insanely", "google-analytics-for-wordpress" ),
			__( "disgusting", "google-analytics-for-wordpress" ),
			__( "fearless", "google-analytics-for-wordpress" ),
			__( "disinformation", "google-analytics-for-wordpress" ),
			__( "feast", "google-analytics-for-wordpress" ),
			__( "insidious", "google-analytics-for-wordpress" ),
			__( "dollar", "google-analytics-for-wordpress" ),
			__( "feeble", "google-analytics-for-wordpress" ),
			__( "gullible", "google-analytics-for-wordpress" ),
			__( "double", "google-analytics-for-wordpress" ),
			__( "fire", "google-analytics-for-wordpress" ),
			__( "hack", "google-analytics-for-wordpress" ),
			__( "fleece", "google-analytics-for-wordpress" ),
			__( "had enough", "google-analytics-for-wordpress" ),
			__( "invasion", "google-analytics-for-wordpress" ),
			__( "drowning", "google-analytics-for-wordpress" ),
			__( "floundering", "google-analytics-for-wordpress" ),
			__( "happy", "google-analytics-for-wordpress" ),
			__( "ironclad", "google-analytics-for-wordpress" ),
			__( "dumb", "google-analytics-for-wordpress" ),
			__( "flush", "google-analytics-for-wordpress" ),
			__( "hate", "google-analytics-for-wordpress" ),
			__( "irresistibly", "google-analytics-for-wordpress" ),
			__( "hazardous", "google-analytics-for-wordpress" ),
			__( "is the", "google-analytics-for-wordpress" ),
			__( "fool", "google-analytics-for-wordpress" ),
			__( "is what happens when", "google-analytics-for-wordpress" ),
			__( "fooled", "google-analytics-for-wordpress" ),
			__( "helpless", "google-analytics-for-wordpress" ),
			__( "it looks like a", "google-analytics-for-wordpress" ),
			__( "embarrass", "google-analytics-for-wordpress" ),
			__( "for the first time", "google-analytics-for-wordpress" ),
			__( "help are the", "google-analytics-for-wordpress" ),
			__( "jackpot", "google-analytics-for-wordpress" ),
			__( "forbidden", "google-analytics-for-wordpress" ),
			__( "hidden", "google-analytics-for-wordpress" ),
			__( "jail", "google-analytics-for-wordpress" ),
			__( "empower", "google-analytics-for-wordpress" ),
			__( "force-fed", "google-analytics-for-wordpress" ),
			__( "high", "google-analytics-for-wordpress" ),
			__( "jaw-dropping", "google-analytics-for-wordpress" ),
			__( "forgotten", "google-analytics-for-wordpress" ),
			__( "jeopardy", "google-analytics-for-wordpress" ),
			__( "energize", "google-analytics-for-wordpress" ),
			__( "hoax", "google-analytics-for-wordpress" ),
			__( "jubilant", "google-analytics-for-wordpress" ),
			__( "foul", "google-analytics-for-wordpress" ),
			__( "hope", "google-analytics-for-wordpress" ),
			__( "killer", "google-analytics-for-wordpress" ),
			__( "frantic", "google-analytics-for-wordpress" ),
			__( "horrific", "google-analytics-for-wordpress" ),
			__( "know it all", "google-analytics-for-wordpress" ),
			__( "epic", "google-analytics-for-wordpress" ),
			__( "how to make", "google-analytics-for-wordpress" ),
			__( "evil", "google-analytics-for-wordpress" ),
			__( "freebie", "google-analytics-for-wordpress" ),
			__( "frenzy", "google-analytics-for-wordpress" ),
			__( "hurricane", "google-analytics-for-wordpress" ),
			__( "excited", "google-analytics-for-wordpress" ),
			__( "fresh on the mind", "google-analytics-for-wordpress" ),
			__( "frightening", "google-analytics-for-wordpress" ),
			__( "hypnotic", "google-analytics-for-wordpress" ),
			__( "lawsuit", "google-analytics-for-wordpress" ),
			__( "frugal", "google-analytics-for-wordpress" ),
			__( "illegal", "google-analytics-for-wordpress" ),
			__( "fulfill", "google-analytics-for-wordpress" ),
			__( "lick", "google-analytics-for-wordpress" ),
			__( "explode", "google-analytics-for-wordpress" ),
			__( "lies", "google-analytics-for-wordpress" ),
			__( "exposed", "google-analytics-for-wordpress" ),
			__( "gambling", "google-analytics-for-wordpress" ),
			__( "like a normal", "google-analytics-for-wordpress" ),
			__( "nightmare", "google-analytics-for-wordpress" ),
			__( "results", "google-analytics-for-wordpress" ),
			__( "line", "google-analytics-for-wordpress" ),
			__( "no good", "google-analytics-for-wordpress" ),
			__( "pound", "google-analytics-for-wordpress" ),
			__( "loathsome", "google-analytics-for-wordpress" ),
			__( "no questions asked", "google-analytics-for-wordpress" ),
			__( "revenge", "google-analytics-for-wordpress" ),
			__( "lonely", "google-analytics-for-wordpress" ),
			__( "looks like a", "google-analytics-for-wordpress" ),
			__( "obnoxious", "google-analytics-for-wordpress" ),
			__( "preposterous", "google-analytics-for-wordpress" ),
			__( "revolting", "google-analytics-for-wordpress" ),
			__( "looming", "google-analytics-for-wordpress" ),
			__( "priced", "google-analytics-for-wordpress" ),
			__( "lost", "google-analytics-for-wordpress" ),
			__( "prison", "google-analytics-for-wordpress" ),
			__( "lowest", "google-analytics-for-wordpress" ),
			__( "of the", "google-analytics-for-wordpress" ),
			__( "privacy", "google-analytics-for-wordpress" ),
			__( "rich", "google-analytics-for-wordpress" ),
			__( "lunatic", "google-analytics-for-wordpress" ),
			__( "off-limits", "google-analytics-for-wordpress" ),
			__( "private", "google-analytics-for-wordpress" ),
			__( "risky", "google-analytics-for-wordpress" ),
			__( "lurking", "google-analytics-for-wordpress" ),
			__( "offer", "google-analytics-for-wordpress" ),
			__( "prize", "google-analytics-for-wordpress" ),
			__( "ruthless", "google-analytics-for-wordpress" ),
			__( "lust", "google-analytics-for-wordpress" ),
			__( "official", "google-analytics-for-wordpress" ),
			__( "luxurious", "google-analytics-for-wordpress" ),
			__( "on the", "google-analytics-for-wordpress" ),
			__( "profit", "google-analytics-for-wordpress" ),
			__( "scary", "google-analytics-for-wordpress" ),
			__( "lying", "google-analytics-for-wordpress" ),
			__( "outlawed", "google-analytics-for-wordpress" ),
			__( "protected", "google-analytics-for-wordpress" ),
			__( "scream", "google-analytics-for-wordpress" ),
			__( "searing", "google-analytics-for-wordpress" ),
			__( "overcome", "google-analytics-for-wordpress" ),
			__( "provocative", "google-analytics-for-wordpress" ),
			__( "make you", "google-analytics-for-wordpress" ),
			__( "painful", "google-analytics-for-wordpress" ),
			__( "pummel", "google-analytics-for-wordpress" ),
			__( "secure", "google-analytics-for-wordpress" ),
			__( "pale", "google-analytics-for-wordpress" ),
			__( "punish", "google-analytics-for-wordpress" ),
			__( "marked down", "google-analytics-for-wordpress" ),
			__( "panic", "google-analytics-for-wordpress" ),
			__( "quadruple", "google-analytics-for-wordpress" ),
			__( "secutively", "google-analytics-for-wordpress" ),
			__( "massive", "google-analytics-for-wordpress" ),
			__( "pay zero", "google-analytics-for-wordpress" ),
			__( "seize", "google-analytics-for-wordpress" ),
			__( "meltdown", "google-analytics-for-wordpress" ),
			__( "payback", "google-analytics-for-wordpress" ),
			__( "might look like a", "google-analytics-for-wordpress" ),
			__( "peril", "google-analytics-for-wordpress" ),
			__( "mind-blowing", "google-analytics-for-wordpress" ),
			__( "shameless", "google-analytics-for-wordpress" ),
			__( "minute", "google-analytics-for-wordpress" ),
			__( "rave", "google-analytics-for-wordpress" ),
			__( "shatter", "google-analytics-for-wordpress" ),
			__( "piranha", "google-analytics-for-wordpress" ),
			__( "reckoning", "google-analytics-for-wordpress" ),
			__( "shellacking", "google-analytics-for-wordpress" ),
			__( "mired", "google-analytics-for-wordpress" ),
			__( "pitfall", "google-analytics-for-wordpress" ),
			__( "reclaim", "google-analytics-for-wordpress" ),
			__( "mistakes", "google-analytics-for-wordpress" ),
			__( "plague", "google-analytics-for-wordpress" ),
			__( "sick and tired", "google-analytics-for-wordpress" ),
			__( "money", "google-analytics-for-wordpress" ),
			__( "played", "google-analytics-for-wordpress" ),
			__( "refugee", "google-analytics-for-wordpress" ),
			__( "silly", "google-analytics-for-wordpress" ),
			__( "money-grubbing", "google-analytics-for-wordpress" ),
			__( "pluck", "google-analytics-for-wordpress" ),
			__( "refund", "google-analytics-for-wordpress" ),
			__( "moneyback", "google-analytics-for-wordpress" ),
			__( "plummet", "google-analytics-for-wordpress" ),
			__( "plunge", "google-analytics-for-wordpress" ),
			__( "murder", "google-analytics-for-wordpress" ),
			__( "pointless", "google-analytics-for-wordpress" ),
			__( "sinful", "google-analytics-for-wordpress" ),
			__( "myths", "google-analytics-for-wordpress" ),
			__( "poor", "google-analytics-for-wordpress" ),
			__( "remarkably", "google-analytics-for-wordpress" ),
			__( "six-figure", "google-analytics-for-wordpress" ),
			__( "never again", "google-analytics-for-wordpress" ),
			__( "research", "google-analytics-for-wordpress" ),
			__( "surrender", "google-analytics-for-wordpress" ),
			__( "to the", "google-analytics-for-wordpress" ),
			__( "varify", "google-analytics-for-wordpress" ),
			__( "skyrocket", "google-analytics-for-wordpress" ),
			__( "toxic", "google-analytics-for-wordpress" ),
			__( "vibrant", "google-analytics-for-wordpress" ),
			__( "slaughter", "google-analytics-for-wordpress" ),
			__( "swindle", "google-analytics-for-wordpress" ),
			__( "trap", "google-analytics-for-wordpress" ),
			__( "victim", "google-analytics-for-wordpress" ),
			__( "sleazy", "google-analytics-for-wordpress" ),
			__( "taboo", "google-analytics-for-wordpress" ),
			__( "treasure", "google-analytics-for-wordpress" ),
			__( "victory", "google-analytics-for-wordpress" ),
			__( "smash", "google-analytics-for-wordpress" ),
			__( "tailspin", "google-analytics-for-wordpress" ),
			__( "vindication", "google-analytics-for-wordpress" ),
			__( "smug", "google-analytics-for-wordpress" ),
			__( "tank", "google-analytics-for-wordpress" ),
			__( "triple", "google-analytics-for-wordpress" ),
			__( "viral", "google-analytics-for-wordpress" ),
			__( "smuggled", "google-analytics-for-wordpress" ),
			__( "tantalizing", "google-analytics-for-wordpress" ),
			__( "triumph", "google-analytics-for-wordpress" ),
			__( "volatile", "google-analytics-for-wordpress" ),
			__( "sniveling", "google-analytics-for-wordpress" ),
			__( "targeted", "google-analytics-for-wordpress" ),
			__( "truth", "google-analytics-for-wordpress" ),
			__( "vulnerable", "google-analytics-for-wordpress" ),
			__( "snob", "google-analytics-for-wordpress" ),
			__( "tawdry", "google-analytics-for-wordpress" ),
			__( "try before you buy", "google-analytics-for-wordpress" ),
			__( "tech", "google-analytics-for-wordpress" ),
			__( "turn the tables", "google-analytics-for-wordpress" ),
			__( "wanton", "google-analytics-for-wordpress" ),
			__( "soaring", "google-analytics-for-wordpress" ),
			__( "warning", "google-analytics-for-wordpress" ),
			__( "teetering", "google-analytics-for-wordpress" ),
			__( "unauthorized", "google-analytics-for-wordpress" ),
			__( "spectacular", "google-analytics-for-wordpress" ),
			__( "temporary fix", "google-analytics-for-wordpress" ),
			__( "unbelievably", "google-analytics-for-wordpress" ),
			__( "spine", "google-analytics-for-wordpress" ),
			__( "tempting", "google-analytics-for-wordpress" ),
			__( "uncommonly", "google-analytics-for-wordpress" ),
			__( "what happened", "google-analytics-for-wordpress" ),
			__( "spirit", "google-analytics-for-wordpress" ),
			__( "what happens when", "google-analytics-for-wordpress" ),
			__( "terror", "google-analytics-for-wordpress" ),
			__( "under", "google-analytics-for-wordpress" ),
			__( "what happens", "google-analytics-for-wordpress" ),
			__( "staggering", "google-analytics-for-wordpress" ),
			__( "underhanded", "google-analytics-for-wordpress" ),
			__( "what this", "google-analytics-for-wordpress" ),
			__( "that will make you", "google-analytics-for-wordpress" ),
			__( "undo", "when you see", "google-analytics-for-wordpress" ),
			__( "that will make", "google-analytics-for-wordpress" ),
			__( "unexpected", "google-analytics-for-wordpress" ),
			__( "when you", "google-analytics-for-wordpress" ),
			__( "strangle", "google-analytics-for-wordpress" ),
			__( "that will", "google-analytics-for-wordpress" ),
			__( "whip", "google-analytics-for-wordpress" ),
			__( "the best", "google-analytics-for-wordpress" ),
			__( "whopping", "google-analytics-for-wordpress" ),
			__( "stuck up", "google-analytics-for-wordpress" ),
			__( "the ranking of", "google-analytics-for-wordpress" ),
			__( "wicked", "google-analytics-for-wordpress" ),
			__( "stunning", "google-analytics-for-wordpress" ),
			__( "the most", "google-analytics-for-wordpress" ),
			__( "will make you", "google-analytics-for-wordpress" ),
			__( "stupid", "google-analytics-for-wordpress" ),
			__( "the reason why is", "google-analytics-for-wordpress" ),
			__( "unscrupulous", "google-analytics-for-wordpress" ),
			__( "thing ive ever seen", "google-analytics-for-wordpress" ),
			__( "withheld", "google-analytics-for-wordpress" ),
			__( "this is the", "google-analytics-for-wordpress" ),
			__( "this is what happens", "google-analytics-for-wordpress" ),
			__( "unusually", "google-analytics-for-wordpress" ),
			__( "wondrous", "google-analytics-for-wordpress" ),
			__( "this is what", "google-analytics-for-wordpress" ),
			__( "uplifting", "google-analytics-for-wordpress" ),
			__( "worry", "google-analytics-for-wordpress" ),
			__( "sure", "google-analytics-for-wordpress" ),
			__( "this is", "google-analytics-for-wordpress" ),
			__( "wounded", "google-analytics-for-wordpress" ),
			__( "surge", "google-analytics-for-wordpress" ),
			__( "thrilled", "google-analytics-for-wordpress" ),
			__( "you need to know", "google-analytics-for-wordpress" ),
			__( "thrilling", "google-analytics-for-wordpress" ),
			__( "valor", "google-analytics-for-wordpress" ),
			__( "you need to", "google-analytics-for-wordpress" ),
			__( "you see what", "google-analytics-for-wordpress" ),
			__( "surprising", "google-analytics-for-wordpress" ),
			__( "tired", "google-analytics-for-wordpress" ),
			__( "you see", "google-analytics-for-wordpress" ),
			__( "surprisingly", "google-analytics-for-wordpress" ),
			__( "to be", "google-analytics-for-wordpress" ),
			__( "vaporize", "google-analytics-for-wordpress" ),
		);

		return $this->emotion_power_words;
	}

	/**
	 * Power words
	 *
	 * @return array power words
	 */
	function power_words() {
		if ( isset( $this->power_words ) && ! empty( $this->power_words ) ) {
			return $this->power_words;
		}

		$this->power_words = array(
			__( "great", "google-analytics-for-wordpress" ),
			__( "free", "google-analytics-for-wordpress" ),
			__( "focus", "google-analytics-for-wordpress" ),
			__( "remarkable", "google-analytics-for-wordpress" ),
			__( "confidential", "google-analytics-for-wordpress" ),
			__( "sale", "google-analytics-for-wordpress" ),
			__( "wanted", "google-analytics-for-wordpress" ),
			__( "obsession", "google-analytics-for-wordpress" ),
			__( "sizable", "google-analytics-for-wordpress" ),
			__( "new", "google-analytics-for-wordpress" ),
			__( "absolutely lowest", "google-analytics-for-wordpress" ),
			__( "surging", "google-analytics-for-wordpress" ),
			__( "wonderful", "google-analytics-for-wordpress" ),
			__( "professional", "google-analytics-for-wordpress" ),
			__( "interesting", "google-analytics-for-wordpress" ),
			__( "revisited", "google-analytics-for-wordpress" ),
			__( "delivered", "google-analytics-for-wordpress" ),
			__( "guaranteed", "google-analytics-for-wordpress" ),
			__( "challenge", "google-analytics-for-wordpress" ),
			__( "unique", "google-analytics-for-wordpress" ),
			__( "secrets", "google-analytics-for-wordpress" ),
			__( "special", "google-analytics-for-wordpress" ),
			__( "lifetime", "google-analytics-for-wordpress" ),
			__( "bargain", "google-analytics-for-wordpress" ),
			__( "scarce", "google-analytics-for-wordpress" ),
			__( "tested", "google-analytics-for-wordpress" ),
			__( "highest", "google-analytics-for-wordpress" ),
			__( "hurry", "google-analytics-for-wordpress" ),
			__( "alert famous", "google-analytics-for-wordpress" ),
			__( "improved", "google-analytics-for-wordpress" ),
			__( "expert", "google-analytics-for-wordpress" ),
			__( "daring", "google-analytics-for-wordpress" ),
			__( "strong", "google-analytics-for-wordpress" ),
			__( "immediately", "google-analytics-for-wordpress" ),
			__( "advice", "google-analytics-for-wordpress" ),
			__( "pioneering", "google-analytics-for-wordpress" ),
			__( "unusual", "google-analytics-for-wordpress" ),
			__( "limited", "google-analytics-for-wordpress" ),
			__( "the truth about", "google-analytics-for-wordpress" ),
			__( "destiny", "google-analytics-for-wordpress" ),
			__( "outstanding", "google-analytics-for-wordpress" ),
			__( "simplistic", "google-analytics-for-wordpress" ),
			__( "compare", "google-analytics-for-wordpress" ),
			__( "unsurpassed", "google-analytics-for-wordpress" ),
			__( "energy", "google-analytics-for-wordpress" ),
			__( "powerful", "google-analytics-for-wordpress" ),
			__( "colorful", "google-analytics-for-wordpress" ),
			__( "genuine", "google-analytics-for-wordpress" ),
			__( "instructive", "google-analytics-for-wordpress" ),
			__( "big", "google-analytics-for-wordpress" ),
			__( "affordable", "google-analytics-for-wordpress" ),
			__( "informative", "google-analytics-for-wordpress" ),
			__( "liberal", "google-analytics-for-wordpress" ),
			__( "popular", "google-analytics-for-wordpress" ),
			__( "ultimate", "google-analytics-for-wordpress" ),
			__( "mainstream", "google-analytics-for-wordpress" ),
			__( "rare", "google-analytics-for-wordpress" ),
			__( "exclusive", "google-analytics-for-wordpress" ),
			__( "willpower", "google-analytics-for-wordpress" ),
			__( "complete", "google-analytics-for-wordpress" ),
			__( "edge", "google-analytics-for-wordpress" ),
			__( "valuable", "google-analytics-for-wordpress" ),
			__( "attractive", "google-analytics-for-wordpress" ),
			__( "last chance", "google-analytics-for-wordpress" ),
			__( "superior", "google-analytics-for-wordpress" ),
			__( "how to", "google-analytics-for-wordpress" ),
			__( "easily", "google-analytics-for-wordpress" ),
			__( "exploit", "google-analytics-for-wordpress" ),
			__( "unparalleled", "google-analytics-for-wordpress" ),
			__( "endorsed", "google-analytics-for-wordpress" ),
			__( "approved", "google-analytics-for-wordpress" ),
			__( "quality", "google-analytics-for-wordpress" ),
			__( "fascinating", "google-analytics-for-wordpress" ),
			__( "unlimited", "google-analytics-for-wordpress" ),
			__( "competitive", "google-analytics-for-wordpress" ),
			__( "gigantic", "google-analytics-for-wordpress" ),
			__( "compromise", "google-analytics-for-wordpress" ),
			__( "discount", "google-analytics-for-wordpress" ),
			__( "full", "google-analytics-for-wordpress" ),
			__( "love", "google-analytics-for-wordpress" ),
			__( "odd", "google-analytics-for-wordpress" ),
			__( "fundamentals", "google-analytics-for-wordpress" ),
			__( "mammoth", "google-analytics-for-wordpress" ),
			__( "lavishly", "google-analytics-for-wordpress" ),
			__( "bottom line", "google-analytics-for-wordpress" ),
			__( "under priced", "google-analytics-for-wordpress" ),
			__( "innovative", "google-analytics-for-wordpress" ),
			__( "reliable", "google-analytics-for-wordpress" ),
			__( "zinger", "google-analytics-for-wordpress" ),
			__( "suddenly", "google-analytics-for-wordpress" ),
			__( "it's here", "google-analytics-for-wordpress" ),
			__( "terrific", "google-analytics-for-wordpress" ),
			__( "simplified", "google-analytics-for-wordpress" ),
			__( "perspective", "google-analytics-for-wordpress" ),
			__( "just arrived", "google-analytics-for-wordpress" ),
			__( "breakthrough", "google-analytics-for-wordpress" ),
			__( "tremendous", "google-analytics-for-wordpress" ),
			__( "launching", "google-analytics-for-wordpress" ),
			__( "sure fire", "google-analytics-for-wordpress" ),
			__( "emerging", "google-analytics-for-wordpress" ),
			__( "helpful", "google-analytics-for-wordpress" ),
			__( "skill", "google-analytics-for-wordpress" ),
			__( "soar", "google-analytics-for-wordpress" ),
			__( "profitable", "google-analytics-for-wordpress" ),
			__( "special offer", "google-analytics-for-wordpress" ),
			__( "reduced", "google-analytics-for-wordpress" ),
			__( "beautiful", "google-analytics-for-wordpress" ),
			__( "sampler", "google-analytics-for-wordpress" ),
			__( "technology", "google-analytics-for-wordpress" ),
			__( "better", "google-analytics-for-wordpress" ),
			__( "crammed", "google-analytics-for-wordpress" ),
			__( "noted", "google-analytics-for-wordpress" ),
			__( "selected", "google-analytics-for-wordpress" ),
			__( "shrewd", "google-analytics-for-wordpress" ),
			__( "growth", "google-analytics-for-wordpress" ),
			__( "luxury", "google-analytics-for-wordpress" ),
			__( "sturdy", "google-analytics-for-wordpress" ),
			__( "enormous", "google-analytics-for-wordpress" ),
			__( "promising", "google-analytics-for-wordpress" ),
			__( "unconditional", "google-analytics-for-wordpress" ),
			__( "wealth", "google-analytics-for-wordpress" ),
			__( "spotlight", "google-analytics-for-wordpress" ),
			__( "astonishing", "google-analytics-for-wordpress" ),
			__( "timely", "google-analytics-for-wordpress" ),
			__( "successful", "google-analytics-for-wordpress" ),
			__( "useful", "google-analytics-for-wordpress" ),
			__( "imagination", "google-analytics-for-wordpress" ),
			__( "bonanza", "google-analytics-for-wordpress" ),
			__( "opportunities", "google-analytics-for-wordpress" ),
			__( "survival", "google-analytics-for-wordpress" ),
			__( "greatest", "google-analytics-for-wordpress" ),
			__( "security", "google-analytics-for-wordpress" ),
			__( "last minute", "google-analytics-for-wordpress" ),
			__( "largest", "google-analytics-for-wordpress" ),
			__( "high tech", "google-analytics-for-wordpress" ),
			__( "refundable", "google-analytics-for-wordpress" ),
			__( "monumental", "google-analytics-for-wordpress" ),
			__( "colossal", "google-analytics-for-wordpress" ),
			__( "latest", "google-analytics-for-wordpress" ),
			__( "quickly", "google-analytics-for-wordpress" ),
			__( "startling", "google-analytics-for-wordpress" ),
			__( "now", "google-analytics-for-wordpress" ),
			__( "important", "google-analytics-for-wordpress" ),
			__( "revolutionary", "google-analytics-for-wordpress" ),
			__( "quick", "google-analytics-for-wordpress" ),
			__( "unlock", "google-analytics-for-wordpress" ),
			__( "urgent", "google-analytics-for-wordpress" ),
			__( "miracle", "google-analytics-for-wordpress" ),
			__( "easy", "google-analytics-for-wordpress" ),
			__( "fortune", "google-analytics-for-wordpress" ),
			__( "amazing", "google-analytics-for-wordpress" ),
			__( "magic", "google-analytics-for-wordpress" ),
			__( "direct", "google-analytics-for-wordpress" ),
			__( "authentic", "google-analytics-for-wordpress" ),
			__( "exciting", "google-analytics-for-wordpress" ),
			__( "proven", "google-analytics-for-wordpress" ),
			__( "simple", "google-analytics-for-wordpress" ),
			__( "announcing", "google-analytics-for-wordpress" ),
			__( "portfolio", "google-analytics-for-wordpress" ),
			__( "reward", "google-analytics-for-wordpress" ),
			__( "strange", "google-analytics-for-wordpress" ),
			__( "huge gift", "google-analytics-for-wordpress" ),
			__( "revealing", "google-analytics-for-wordpress" ),
			__( "weird", "google-analytics-for-wordpress" ),
			__( "value", "google-analytics-for-wordpress" ),
			__( "introducing", "google-analytics-for-wordpress" ),
			__( "sensational", "google-analytics-for-wordpress" ),
			__( "surprise", "google-analytics-for-wordpress" ),
			__( "insider", "google-analytics-for-wordpress" ),
			__( "practical", "google-analytics-for-wordpress" ),
			__( "excellent", "google-analytics-for-wordpress" ),
			__( "delighted", "google-analytics-for-wordpress" ),
			__( "download", "google-analytics-for-wordpress" ),
		);

		return $this->power_words;
	}

	/**
	 * Common words
	 *
	 * @return array common words
	 */
	function common_words() {
		if ( isset( $this->common_words ) && ! empty( $this->common_words ) ) {
			return $this->common_words;
		}

		$this->common_words = array(
			__( "a", "google-analytics-for-wordpress" ),
			__( "for", "google-analytics-for-wordpress" ),
			__( "about", "google-analytics-for-wordpress" ),
			__( "from", "google-analytics-for-wordpress" ),
			__( "after", "google-analytics-for-wordpress" ),
			__( "get", "google-analytics-for-wordpress" ),
			__( "all", "google-analytics-for-wordpress" ),
			__( "has", "google-analytics-for-wordpress" ),
			__( "an", "google-analytics-for-wordpress" ),
			__( "have", "google-analytics-for-wordpress" ),
			__( "and", "google-analytics-for-wordpress" ),
			__( "he", "google-analytics-for-wordpress" ),
			__( "are", "google-analytics-for-wordpress" ),
			__( "her", "google-analytics-for-wordpress" ),
			__( "as", "google-analytics-for-wordpress" ),
			__( "his", "google-analytics-for-wordpress" ),
			__( "at", "google-analytics-for-wordpress" ),
			__( "how", "google-analytics-for-wordpress" ),
			__( "be", "google-analytics-for-wordpress" ),
			__( "I", "google-analytics-for-wordpress" ),
			__( "but", "google-analytics-for-wordpress" ),
			__( "if", "google-analytics-for-wordpress" ),
			__( "by", "google-analytics-for-wordpress" ),
			__( "in", "google-analytics-for-wordpress" ),
			__( "can", "google-analytics-for-wordpress" ),
			__( "is", "google-analytics-for-wordpress" ),
			__( "did", "google-analytics-for-wordpress" ),
			__( "it", "google-analytics-for-wordpress" ),
			__( "do", "google-analytics-for-wordpress" ),
			__( "just", "google-analytics-for-wordpress" ),
			__( "ever", "google-analytics-for-wordpress" ),
			__( "like", "google-analytics-for-wordpress" ),
			__( "ll", "google-analytics-for-wordpress" ),
			__( "these", "google-analytics-for-wordpress" ),
			__( "me", "google-analytics-for-wordpress" ),
			__( "they", "google-analytics-for-wordpress" ),
			__( "most", "google-analytics-for-wordpress" ),
			__( "things", "google-analytics-for-wordpress" ),
			__( "my", "google-analytics-for-wordpress" ),
			__( "this", "google-analytics-for-wordpress" ),
			__( "no", "google-analytics-for-wordpress" ),
			__( "to", "google-analytics-for-wordpress" ),
			__( "not", "google-analytics-for-wordpress" ),
			__( "up", "google-analytics-for-wordpress" ),
			__( "of", "google-analytics-for-wordpress" ),
			__( "was", "google-analytics-for-wordpress" ),
			__( "on", "google-analytics-for-wordpress" ),
			__( "what", "google-analytics-for-wordpress" ),
			__( "re", "google-analytics-for-wordpress" ),
			__( "when", "google-analytics-for-wordpress" ),
			__( "she", "google-analytics-for-wordpress" ),
			__( "who", "google-analytics-for-wordpress" ),
			__( "sould", "google-analytics-for-wordpress" ),
			__( "why", "google-analytics-for-wordpress" ),
			__( "so", "google-analytics-for-wordpress" ),
			__( "will", "google-analytics-for-wordpress" ),
			__( "that", "google-analytics-for-wordpress" ),
			__( "with", "google-analytics-for-wordpress" ),
			__( "the", "google-analytics-for-wordpress" ),
			__( "you", "google-analytics-for-wordpress" ),
			__( "their", "google-analytics-for-wordpress" ),
			__( "your", "google-analytics-for-wordpress" ),
			__( "there", "google-analytics-for-wordpress" ),
		);

		return $this->common_words;
	}


	/**
	 * Uncommon words
	 *
	 * @return array uncommon words
	 */
	function uncommon_words() {
		if ( isset( $this->uncommon_words ) && ! empty( $this->uncommon_words ) ) {
			return $this->uncommon_words;
		}

		$this->uncommon_words = array(
			__( "actually", "google-analytics-for-wordpress" ),
			__( "happened", "google-analytics-for-wordpress" ),
			__( "need", "google-analytics-for-wordpress" ),
			__( "thing", "google-analytics-for-wordpress" ),
			__( "awesome", "google-analytics-for-wordpress" ),
			__( "heart", "google-analytics-for-wordpress" ),
			__( "never", "google-analytics-for-wordpress" ),
			__( "think", "google-analytics-for-wordpress" ),
			__( "baby", "google-analytics-for-wordpress" ),
			__( "here", "google-analytics-for-wordpress" ),
			__( "new", "google-analytics-for-wordpress" ),
			__( "time", "google-analytics-for-wordpress" ),
			__( "beautiful", "google-analytics-for-wordpress" ),
			__( "its", "google-analytics-for-wordpress" ),
			__( "now", "google-analytics-for-wordpress" ),
			__( "valentines", "google-analytics-for-wordpress" ),
			__( "being", "google-analytics-for-wordpress" ),
			__( "know", "google-analytics-for-wordpress" ),
			__( "old", "google-analytics-for-wordpress" ),
			__( "video", "google-analytics-for-wordpress" ),
			__( "best", "google-analytics-for-wordpress" ),
			__( "life", "google-analytics-for-wordpress" ),
			__( "one", "google-analytics-for-wordpress" ),
			__( "want", "google-analytics-for-wordpress" ),
			__( "better", "google-analytics-for-wordpress" ),
			__( "little", "google-analytics-for-wordpress" ),
			__( "out", "google-analytics-for-wordpress" ),
			__( "watch", "google-analytics-for-wordpress" ),
			__( "boy", "google-analytics-for-wordpress" ),
			__( "look", "google-analytics-for-wordpress" ),
			__( "people", "google-analytics-for-wordpress" ),
			__( "way", "google-analytics-for-wordpress" ),
			__( "dog", "google-analytics-for-wordpress" ),
			__( "love", "google-analytics-for-wordpress" ),
			__( "photos", "google-analytics-for-wordpress" ),
			__( "ways", "google-analytics-for-wordpress" ),
			__( "down", "google-analytics-for-wordpress" ),
			__( "made", "google-analytics-for-wordpress" ),
			__( "really", "google-analytics-for-wordpress" ),
			__( "world", "google-analytics-for-wordpress" ),
			__( "facebook", "google-analytics-for-wordpress" ),
			__( "make", "google-analytics-for-wordpress" ),
			__( "reasons", "google-analytics-for-wordpress" ),
			__( "year", "google-analytics-for-wordpress" ),
			__( "first", "google-analytics-for-wordpress" ),
			__( "makes", "google-analytics-for-wordpress" ),
			__( "right", "google-analytics-for-wordpress" ),
			__( "years", "google-analytics-for-wordpress" ),
			__( "found", "google-analytics-for-wordpress" ),
			__( "man", "google-analytics-for-wordpress" ),
			__( "see", "google-analytics-for-wordpress" ),
			__( "you'll", "google-analytics-for-wordpress" ),
			__( "girl", "google-analytics-for-wordpress" ),
			__( "media", "google-analytics-for-wordpress" ),
			__( "seen", "google-analytics-for-wordpress" ),
			__( "good", "google-analytics-for-wordpress" ),
			__( "mind", "google-analytics-for-wordpress" ),
			__( "social", "google-analytics-for-wordpress" ),
			__( "guy", "google-analytics-for-wordpress" ),
			__( "more", "google-analytics-for-wordpress" ),
			__( "something", "google-analytics-for-wordpress" ),
		);

		return $this->uncommon_words;
	}
}

new MonsterInsightsHeadlineToolPlugin();
