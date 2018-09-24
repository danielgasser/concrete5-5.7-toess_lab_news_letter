To start it is advisable to duplicate one of the 2 existent templates and play around with it. A template is divided into four parts. All parts have a background, padding, margin and border section. These can be styled using [CSS (Cascading Style Sheets)](https://en.wikipedia.org/wiki/Cascading_Style_Sheets). See explanations on CSS below.

**<span style="color: #676665; font-family: Ubuntu, sans-serif; font-size: 15px;">Please be aware that for designing a descent template having some HTML & CSS knowledge is highly recommended.</span>**

**Head Section**

In the Head section, you may choose to insert an Image, like your logo or a portrait. This image will be linked to your concrete5 website. The following settings can be made concerning the header image:

*   The size of the image:
    *   Changing the width value will automatically calculate the height value and vice versa.
*   The position of the image in the head container:  

    *   Left
    *   Center
    *   Right
*   The position of the whole header relative to the mail container
    *   Top
    *   Right
    *   Bottom
    *   Left

**Body and Footer Section**

The following settings can be made here:

*   Background color and minimum height
*   Padding
*   Margin
*   Borders

**Headers**

There are 5 different headers which can be used in the rich text editor inside the Newsletters. Here you can style them as follows:

*   Font family
*   Font weight
*   Font size
*   Font color
*   Padding
*   Margin

**CSS Explanations**

CSS is used to style the parts of the template. Templates can be styled using the CSS units pixel or percent. The CSS-commands being used in are explained below.

Text styling is made inside the rich text editor of concrete5\. Please refer to the [Editors Guide of concrete5](http://documentation.concrete5.org/editors).

<div class="ccm-custom-style-container ccm-custom-style-main-259 doc_style" style="box-sizing: border-box; color: #676665; font-family: Ubuntu, sans-serif; font-size: 15px; line-height: 20.25px;">

<div id="HTMLBlock259" class="HTMLBlock" style="box-sizing: border-box;">

<div id="style_dialog" style="box-sizing: border-box;">

<div style="box-sizing: border-box;">**Padding**

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; width: 712.5px; margin-left: 20px; margin-right: 10px; background-color: #428bca;"><span style="box-sizing: border-box;">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span></div>

</div>

<div style="box-sizing: border-box;">

**Explanation**

<span style="box-sizing: border-box; color: white; padding: 1px; background-color: #428bca;">Padding</span> is the distance between the container and the inner content.   
In this example the padding is as follows:

*   Padding Top: 5 Pixels
*   Padding Right: 10 Pixels
*   Padding Bottom: 15 Pixels
*   Padding Left: 20 Pixels

</div>

</div>

* * *

<div style="box-sizing: border-box;">

<div style="box-sizing: border-box;">**Margin**

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; background-color: #428bca;"><span style="box-sizing: border-box;">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span> </div>

</div>

</div>

<div style="box-sizing: border-box;">

**Explanation**

<span style="box-sizing: border-box; color: white; padding: 1px; background-color: #000000;">Margin</span> is the distance between the container and its parent container, in our case this would be the email body.  
In this example the margin is as follows:

*   Margin Top: 5 Pixels
*   Margin Right: 10 Pixels
*   Margin Bottom: 15 Pixels
*   Margin Left: 20 Pixels

</div>

</div>

* * *

<div style="box-sizing: border-box;">

<div style="box-sizing: border-box;">**Minimum height**

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; min-height: 75px; background-color: #428bca;"><span style="box-sizing: border-box;">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span> </div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; min-height: 75px; background-color: #428bca;"><span style="box-sizing: border-box;">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</span> </div>

</div>

</div>

<div style="box-sizing: border-box;">

**Explanation**

The minimum height defines the height a container _must_ have. If the content is bigger than this height, the height will be adapted automatically.  
In this example the minimum height is as follows:

*   Minimum height: 75 Pixels

</div>

</div>

* * *

<div style="box-sizing: border-box;">

<div style="box-sizing: border-box;">**Borders**

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 197px; background-color: #242729;">

<div class="newsletter-padding newsletter-border top" style="box-sizing: border-box; padding: 5px 10px 15px 20px; border-top-width: 3px; border-style: solid none none; border-color: white; float: left; width: 158.391px; margin-right: 7.1875px; min-height: 36px; background-color: #428bca;"><span style="box-sizing: border-box;">Border top 3 pixels wide, solid style, color white.</span> </div>

<div class="newsletter-padding newsletter-border right" style="box-sizing: border-box; padding: 5px 10px 15px 20px; border-right-width: 3px; border-style: none solid none none; border-color: white; float: left; width: 158.391px; margin-right: 7.1875px; min-height: 36px; background-color: #428bca;"><span style="box-sizing: border-box;">Border right 3 pixels wide, solid style, color white.</span> </div>

<div class="newsletter-padding newsletter-border bottom" style="box-sizing: border-box; padding: 5px 10px 15px 20px; border-bottom-width: 3px; border-style: none none solid; border-color: white; float: left; width: 158.391px; margin-right: 7.1875px; min-height: 36px; background-color: #428bca;"><span style="box-sizing: border-box;">Border bottom 3 pixels wide, solid style, color white.</span> </div>

<div class="newsletter-padding newsletter-border left" style="box-sizing: border-box; padding: 5px 10px 15px 20px; border-left-width: 3px; border-style: none none none solid; border-color: white; float: left; width: 158.391px; margin-right: 7.1875px; min-height: 36px; background-color: #428bca;"><span style="box-sizing: border-box;">Border left 3 pixels wide, solid style, color white.</span> </div>

</div>

</div>

<div style="box-sizing: border-box;">

Explanation

Borders may have width defined in pixels. They can be on top, right, bottom or left side of the container. The following styles are allowed:

*   none
*   dotted
*   dashed
*   solid
*   double
*   groove
*   ridge
*   inset
*   outset
*   initial

*   1px width
*   2px width
*   3px width
*   4px width
*   ...

</div>

</div>

* * *

<div style="box-sizing: border-box;">

<div style="box-sizing: border-box;">**Positioning**

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; margin: 10px auto; position: relative; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; position: absolute; top: 15px; right: 10px; background-color: #428bca;"><span style="box-sizing: border-box;">Element is positioned as follows: 15 pixels from the top, 10 pixels from right.</span> </div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; margin: 10px auto; position: relative; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; position: absolute; bottom: 15px; left: 10px; background-color: #428bca;"><span style="box-sizing: border-box;">Element is positioned as follows: 15 pixels from the bottom, 10 pixels from left.</span> </div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; margin: 10px auto; position: relative; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; position: absolute; top: 15px; left: 10px; background-color: #428bca;"><span style="box-sizing: border-box;">Element is positioned as follows: 15 pixels from the top, 10 pixels from left.</span> </div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; margin: 10px auto; position: relative; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; position: absolute; bottom: 15px; right: 10px; background-color: #428bca;"><span style="box-sizing: border-box;">Element is positioned as follows: 15 pixels from the bottom, 10 pixels from right.</span> </div>

</div>

</div>

<div style="box-sizing: border-box;">

**Explanation**

The position of an element is in relation to it's parent container in that case the mail container.</div>

</div>

* * *

<div style="box-sizing: border-box;">

<div style="box-sizing: border-box;">**Headers**

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; background-color: #428bca;">Header 1: Font family Arial, font size 22 pixels, font weight bold, font style normal, color red, padding top, left, bottom, right 10 pixels, margin same</div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; background-color: #428bca;">

## Header 2: Font family Times New Roman, font size 20 pixels, font weight 300, font style italic, color white, padding top, left, bottom, right 15 pixels, margin same

</div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; background-color: #428bca;">

### Header 3: Font family Ubuntu, font size 18 pixels, font weight 700, font style oblique, color white, padding top, left, bottom, right 30 pixels, margin same

</div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; background-color: #428bca;">Header 4: Font family Helvetica, font size 16 pixels, font weight 400, font style normal, color yellow, padding top, left, bottom, right 4 pixels, margin same</div>

</div>

<div class="newsletter-margin" style="box-sizing: border-box; padding: 5px 10px 15px 20px; color: white; min-height: 97px; background-color: #242729;">

<div class="newsletter-padding" style="box-sizing: border-box; padding: 5px 10px 15px 20px; background-color: #428bca;">Header 5: Same as header 4, except the font size is 14 pixels</div>

</div>

</div>

<div style="box-sizing: border-box;">

**Explanation**

There are 5 headers defined in the rich text editor of concrete5 (in HTML generally there are 6 of them). You may define font family, style, color, weight, padding and margin of them.</div>

</div>

* * *

<div style="box-sizing: border-box; float: none; clear: both;">

<div style="box-sizing: border-box;">Units Following CSS units are allowed:  
**pixels or percents.**

*   **Pixels**: _In digital imaging, a pixel, pel, or picture element is a physical point in a raster image, or the smallest addressable element in an all points addressable display device ... [Read more. ](https://en.wikipedia.org/wiki/Pixel.)_<span style="box-sizing: border-box; font-size: 11.25px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em;">[1]</span>
*   **Percent**: _The percentage CSS data types represent a percentage value. Many CSS properties can take percentage values, often to define sizes in terms of parent objects ... [Read more. ](https://developer.mozilla.org/en/docs/Web/CSS/percentage.)_<span style="box-sizing: border-box; font-size: 11.25px; line-height: 0; position: relative; vertical-align: baseline; top: -0.5em;">[2]</span>

<span style="box-sizing: border-box;">_[1] Reference: [Wikipedia](https://en.wikipedia.org/wiki/Pixel)_</span>  
<span style="box-sizing: border-box;">_[2] Reference: [Mozilla Developer Network](https://developer.mozilla.org/en/docs/Web/CSS/percentage)_</span></div>

</div>

</div>

</div>

As for the templates, there are three section of the newsletter: Head, Body Foot. These are then inserted in the template you choose to be attached with the newsletter. The content is made by using the integrated rich text editor of concrete5\. Please refer to the [Editors Guide of concrete5](http://documentation.concrete5.org/editors).

**User attributes**

A part of that you may insert user attributes defined in the member section of your dashboard. These attributes will then be filled with the corresponding value of each user the newsletter will be sent to. These values will only be visible, when sending the mailing or sending a test message. The following user attribute can be inserted:

*   Text
*   Number
*   Date/Time
*   Email
*   Address
*   Text Area
*   <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - First Name
*   <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - Last Name
*   <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - Address

Simply click at the desired position in the editor, then click on the desired attribute and it will be inserted at the cursors position.

There are four user attributes coming with this add-on:

*   <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - First Name
*   <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - Last Name
*   <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - Address
    *   Dear
    *   Dear Madam
    *   Dear Sir
    *   Madam
    *   Misses
    *   Mister
    *   Sir
*   Receive <span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span> - Newsletter
    *   Checked/Unchecked

<span style="color: #676665; font-family: Ubuntu, sans-serif; font-size: 15px; line-height: 20.25px;">Receive </span><span class="toess-lab" style="box-sizing: border-box; font-family: Ubuntu, Helvetica, Arial, sans-serif; color: #676665; font-size: 15px; line-height: 20.25px;"><span class="toess" style="box-sizing: border-box; color: #000000;">toess</span><span class="lab" style="box-sizing: border-box; color: #a61c29;">lab</span></span><span style="color: #676665; font-family: Ubuntu, sans-serif; font-size: 15px; line-height: 20.25px;"> - Newsletter is the most important one and has been added because of the spam rules being in place today. You are strongly advised to add an information in the newsletter to tell your members that there is the possibility to unsubscribe from the newsletter by adapting this user attribute.</span>  
**IMPORTANT: Any member having this attribute set to "No" is not receiving the mailing!**

**Please do not delete this attribute!**

**Social Links**<span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"> </span>

The same way Social links can be inserted such as Facebook, Google+ etc. Those social links are defined under Dashboard -> System & Settings -> Basics -> Social Links. Please be aware that there is no warranty that the icons will be shown in all email clients.

**Unsubscribe-Link**

The same way an Unsubscribe Link can be inserted.

1.  Choose the Page where the 'Un/Subscribe from toesslab - Newsletter' Block is placed.
2.  Place the cursor at the desired position in the Text Section below.
3.  Then click 'Insert' to place the link at the cursors position.
4.  Important! Do NOT change the URL of the link. Otherwise the Un/subscribe-Link won't work.


<div style="color: #676665; font-family: Ubuntu, sans-serif; font-size: 15px;">

**Dashboard:**

To subscribe or unsubscribe members from receiving your nesletters, you may add severla email addresses separated by ne line into the text area. Then hit "Subscribe" "Unsubscribe".

In the lower section of the page there are 2 lists:

The one on the left side contains all subscripted email addresses, the one on the right side all not subscripted. You may select addresses by dragging a rectangle over them with the mouse. (The selected emails become red/green). You may Also select single addresses by clicking on them and several addresses by pushing down the CTRL key and drag or click.

Then hit "Move selected to 'unsubscripted" or "Move selected to 'subscripted'".

**Notice: all subscribed email addresses must belong to a user account!**

**Block:**

The "Un/Subscribe from toesslab - Newsletter" Block can be placed everywhere and handled as any other block. You may create Templates for it, style it, etc as usual. You can then insert a link to that page in your Newsletters as you insert Social Links or User Attributes. See "[Create Newsletter](/marketplace/addons/toesslab-newsletter/create-newsletter/ "Create Newsletter")" for further information.

</div>
