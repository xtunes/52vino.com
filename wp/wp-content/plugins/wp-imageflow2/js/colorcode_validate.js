/*
**	Validate Hex color code of given colorcode, background turns red if invalid
**
*/

function colorcode_validate(element, colorcode)
{
var regColorcode = /^(#)?([0-9a-fA-F]{3})([0-9a-fA-F]{3})?$/;

var style2 = element.style;

if((regColorcode.test(colorcode) == false) && (colorcode != 'transparent'))
{
style2.backgroundColor = "#CD0000";
}
else
{
style2.backgroundColor = "#FFFFFF";
}
}

