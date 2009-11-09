
// directory of where all the images are
var cmThemeCSA_bottomBase = '/JSCookMenu/ThemeCSA_bottom/';

// the follow block allows user to re-define theme base directory
// before it is loaded.
try
{
	if (myThemeCSA_bottomBase)
	{
		cmThemeCSA_bottomBase = myThemeCSA_bottomBase;
	}
}
catch (e)
{
}

var cmThemeCSA_bottom =
{
	prefix:	'ThemeCSA_bottom',
  	// main menu display attributes
  	//
  	// Note.  When the menu bar is horizontal,
  	// mainFolderLeft and mainFolderRight are
  	// put in <span></span>.  When the menu
  	// bar is vertical, they would be put in
  	// a separate TD cell.

  	// HTML code to the left of the folder item
  	mainFolderLeft: '<img alt="" src="' + cmThemeCSA_bottomBase + 'blank.gif">',
  	// HTML code to the right of the folder item
  	mainFolderRight: '<img alt="" src="' + cmThemeCSA_bottomBase + 'arrow.gif">',
	// HTML code to the left of the regular item
	mainItemLeft: '<img alt="" src="' + cmThemeCSA_bottomBase + 'blank.gif">',
	// HTML code to the right of the regular item
	mainItemRight: '<img alt="" src="' + cmThemeCSA_bottomBase + 'blank.gif">',

	// sub menu display attributes

	// HTML code to the left of the folder item
	folderLeft: '<img alt="" src="' + cmThemeCSA_bottomBase + 'blank.gif">',
	// HTML code to the right of the folder item
	folderRight: '<span style="border: 0; width: 24px;"><img alt="" src="' + cmThemeCSA_bottomBase + 'arrow.gif"></span>',
	// HTML code to the left of the regular item
	itemLeft: '<img alt="" src="' + cmThemeCSA_bottomBase + 'blank.gif">',
	// HTML code to the right of the regular item
	itemRight: '<img alt="" src="' + cmThemeCSA_bottomBase + 'blank.gif">',
	// cell spacing for main menu
	mainSpacing: 0,
	// cell spacing for sub menus
	subSpacing: 0,

	subMenuHeader: '<div class="ThemeCSA_bottomSubMenuShadow"></div><div class="ThemeCSA_bottomSubMenuBorder">',
	subMenuFooter: '</div>',

	// move the first lvl of vbr submenu up a bit
	offsetVMainAdjust:	[0, -2],
	// also for the other lvls
	offsetSubAdjust:	[0, -2]

	// rest use default settings
};

// for sub menu horizontal split
var cmThemeCSA_bottomHSplit = [_cmNoClick, '<td colspan="3" class="ThemeCSA_bottomMenuSplit"><div class="ThemeCSA_bottomMenuSplit"></div></td>'];
// for vertical main menu horizontal split
var cmThemeCSA_bottomMainHSplit = [_cmNoClick, '<td colspan="3" class="ThemeCSA_bottomMenuSplit"><div class="ThemeCSA_bottomMenuSplit"></div></td>'];
// for horizontal main menu vertical split
var cmThemeCSA_bottomMainVSplit = [_cmNoClick, '|'];

/* IE can't do negative margin on tables */
/*@cc_on
	cmThemeCSA_bottom.subMenuHeader = '<div class="ThemeCSA_bottomSubMenuShadow" style="background-color: #aaaaaa;filter: alpha(opacity=50);"></div><div class="ThemeCSA_bottomSubMenuBorder">';
@*/
