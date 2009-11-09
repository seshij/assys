
// directory of where all the images are
var cmThemeCSAbBase = '/JSCookMenu/ThemeCSAb/';

// the follow block allows user to re-define theme base directory
// before it is loaded.
try
{
	if (myThemeCSAbBase)
	{
		cmThemeCSAbBase = myThemeCSAbBase;
	}
}
catch (e)
{
}

var cmThemeCSAb =
{
	prefix:	'ThemeCSAb',
  	// main menu display attributes
  	//
  	// Note.  When the menu bar is horizontal,
  	// mainFolderLeft and mainFolderRight are
  	// put in <span></span>.  When the menu
  	// bar is vertical, they would be put in
  	// a separate TD cell.

  	// HTML code to the left of the folder item
  	mainFolderLeft: '<img alt="" src="' + cmThemeCSAbBase + 'blank.gif">',
  	// HTML code to the right of the folder item
  	mainFolderRight: '<img alt="" src="' + cmThemeCSAbBase + 'arrow.gif">',
	// HTML code to the left of the regular item
	mainItemLeft: '<img alt="" src="' + cmThemeCSAbBase + 'blank.gif">',
	// HTML code to the right of the regular item
	mainItemRight: '<img alt="" src="' + cmThemeCSAbBase + 'blank.gif">',

	// sub menu display attributes

	// HTML code to the left of the folder item
	folderLeft: '<img alt="" src="' + cmThemeCSAbBase + 'blank.gif">',
	// HTML code to the right of the folder item
	folderRight: '<span style="border: 0; width: 24px;"><img alt="" src="' + cmThemeCSAbBase + 'arrow.gif"></span>',
	// HTML code to the left of the regular item
	itemLeft: '<img alt="" src="' + cmThemeCSAbBase + 'blank.gif">',
	// HTML code to the right of the regular item
	itemRight: '<img alt="" src="' + cmThemeCSAbBase + 'blank.gif">',
	// cell spacing for main menu
	mainSpacing: 0,
	// cell spacing for sub menus
	subSpacing: 0,

	subMenuHeader: '<div class="ThemeCSAbSubMenuShadow"></div><div class="ThemeCSAbSubMenuBorder">',
	subMenuFooter: '</div>',

	// move the first lvl of vbr submenu up a bit
	offsetVMainAdjust:	[0, -2],
	// also for the other lvls
	offsetSubAdjust:	[0, -2]

	// rest use default settings
};

// for sub menu horizontal split
var cmThemeCSAbHSplit = [_cmNoClick, '<td colspan="3" class="ThemeCSAbMenuSplit"><div class="ThemeCSAbMenuSplit"></div></td>'];
// for vertical main menu horizontal split
var cmThemeCSAbMainHSplit = [_cmNoClick, '<td colspan="3" class="ThemeCSAbMenuSplit"><div class="ThemeCSAbMenuSplit"></div></td>'];
// for horizontal main menu vertical split
var cmThemeCSAbMainVSplit = [_cmNoClick, '|'];

/* IE can't do negative margin on tables */
/*@cc_on
	cmThemeCSAb.subMenuHeader = '<div class="ThemeCSAbSubMenuShadow" style="background-color: #aaaaaa;filter: alpha(opacity=50);"></div><div class="ThemeCSAbSubMenuBorder">';
@*/
