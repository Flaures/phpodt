<?php

/**
 * A Class containing constants used in the library.
 *
 * @author Issam RACHDI
 */

class StyleConstants {

	const NONE = 1;
	const NORMAL = 7;
	const AUTO = 22;

	/** Lower case text transform */const LOWER_CASE = 2;
	/** Upper case text transform */const UPPER_CASE = 3;
	/** Capitalize text transform */const CAPITALIZE = 4;

	/** Single line */const SINGLE = 5;
	/** Double line */const DOUBLE = 6;

	/** Below the baseline */const SUB = 9;
	/** Above the baseline */const SUPER = 10;

	/** Italic */const ITALIC = 12;
	/** Oblique */const OBLIQUE = 13;

	/** Italic */const EMBOSSED = 14;
	/** Oblique */const ENGRAVED = 15;

	/** Solid Underline */const SOLID = 16;
	/** Dotted Underline */const DOTTED = 17;
	/** Dash Underline */const DASH = 18;
	/** Long dash Underline */const LONG_DASH = 19;
	/** Dot dot dash Underline */const DOT_DOT_DASH = 20;
	/** Wave Underline */const WAVE = 21;

	/** Bold line */const BOLD = 24;
	/** Thin Underline */const THIN = 25;
	/** Medium Underline */const MEDIUM = 27;
	/** Thick Underline */const THICK = 28;

	/** Continuous underline */const CONTINUOUS = 29;
	/** Skip white spaces when underlining */const SKIP_WHITE_SPACE = 30;

	/** Letters */const LETTERS = 31;
	/** Letters */const LINES = 32;

	/** Specifies that the content is to be aligned on the start-edge in the inline-progression-direction. */const START = 33;
	/** Specifies that the content is to be aligned on the end-edge in the inline-progression-direction. */const END = 34;
	/** (Interpreted as START if for progression-direction.), (Align table left) */const LEFT = 35;
	/** (Interpreted as END if for progression-direction.), (Align table right) */const RIGHT = 36;
	/** (Specifies that the content is to be centered in the inline-progression-direction.), (Center the table) */const CENTER = 37;
	/** Specifies that the contents is to be expanded to fill the available width in the inline-progression-direction.*/const JUSTIFY = 38;

	/** The lines of a paragraph should be kept together on the same page or column */const ALWAYS = 39;

	/** Page break */const PAGE = 40;
	/** Column break */const COLUMN = 41;

	/** Transparent */const TRANSPARENT = 42;

	/** No repetition */const NO_REPEAT = 43;
	/** Repeat background image */const REPEAT = 44;
	/** Stretch image */const STRETCH = 45;

	/** Top */const TOP = 46;
	/** Bottom */const BOTTOM = 47;
	/** Middle */const MIDDLE = 48;
	/** Baseline */const BASELINE = 49;

	/** Inline components and text within a line are written left-to-right. Lines and blocks are placed top-to-bottom. */const LR_TB = 50;
	/** Inline components and text within a line are written right-to-left. Lines and blocks are placed top-to-bottom. */const RL_TB = 51;
	/** Inline components and text within a line are written top-to-bottom. Lines and blocks are placed right-to-left. */const TB_RL = 52;
	/** Inline components and text within a line are stacked top-to-bottom. Lines and blocks are stacked left-to-right. */const TB_LR = 53;
	/** Shorthand for lr-tb. */const LR = 54;
	/** Shorthand for rl-tb. */const RL = 55;
	/** Shorthand for tb-rl. */const TB = 56;

	/** Portrait */const PORTRAIT = 57;
	/** Landscape */const LANDSCAPE = 58;

	/** Current page number */const PAGE_NUMBER = 59;
	/** Current date */const CURRENT_DATE = 60;

	/** In the case of table: The table fills all the space between the left and right margins,  */const MARGINS = 61;
	
	const COLLAPSING = 62;
	const SEPARATING = 63;
	
	const FIX = 64;
	const VALUE_TYPE = 65;
	
	const LTR = 66;
	const TTB = 67;
	
	const WRAP = 68;
	const NO_WRAP = 69;
	
	const BULLET = '&#x2022;';
	const BLACK_CIRCLE = '&#x25CF;';
	const CHECK_MARK = '&#x2714;';
	const BALLOT_X = '&#x2717;';
	const RIGHT_ARROW = '&#x2794;';
	const RIGHT_ARROWHEAD = '&#x27A2;';
	
	const RUBY_ABOVE = 70;
	const RUBY_BELOW = 71;
	
	const DISTRIBUTE_LETTER = 72;
	const DISTRIBUTE_SPACE = 73;
	
	const FOOTNOTE = 74;
	const ENDNOTE = 75;
}

?>
	
