<?php
/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/src/Concrete/Entity/ToessLabNewsLetterMailTemplate.php
 */
namespace Concrete\Package\ToessLabNewsLetter\Entity;

/**
 * @Entity
 * @Table(name="ToessLabNewsLetterMailTemplateBody")
 */

class ToessLabNewsLetterMailTemplateBody
{

    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $mailtemplateID;

    /**
     * @Column(type="integer")
     */
    protected $tplID;

    /**
     * @Column(type="string", length=18)
     */
    protected $body_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=4)
     */
    protected $body_min_height = '200';

    /**
     * @Column(type="string", length=4)
     */
    protected $padding_top = '0';


    /**
     * @Column(type="string", length=4)
     */
    protected $padding_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $padding_bottom = '0';
    /**
     * @Column(type="string", length=4)
     */
    protected $margin_top = '0';


    /**
     * @Column(type="string", length=4)
     */
    protected $margin_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $margin_bottom = '0';

    /**
     * @Column(type="string", length=2)
     */
    protected $body_min_height_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $padding_top_unit = 'px';


    /**
     * @Column(type="string", length=2)
     */
    protected $padding_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $padding_bottom_unit = 'px';
    /**
     * @Column(type="string", length=2)
     */
    protected $margin_top_unit = 'px';


    /**
     * @Column(type="string", length=2)
     */
    protected $margin_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $body_border_top_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $body_border_top_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $body_border_top_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $body_border_right_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $body_border_right_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $body_border_right_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $body_border_bottom_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $body_border_bottom_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $body_border_bottom_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $body_border_left_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $body_border_left_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $body_border_left_color = 'rgb(247, 247, 247)';

    public function setTplId($tpl_id)
    {
        $this->tplID = $tpl_id;
    }

    public function getTplId()
    {
        return $this->tplID;
    }


    public function getBodyColor()
    {
        return $this->body_color;
    }

    public function setBodyColor($body_color)
    {
        $this->body_color = $body_color;
    }

    public function getBodyMinSize()
    {
        return $this->body_min_height;
    }

    public function setBodyMinSize($body_min_height)
    {
        $this->body_min_height = $body_min_height;
    }

    public function getPaddingTop()
    {
        return $this->padding_top;
    }

    public function setPaddingTop($padding_top)
    {
        $this->padding_top = $padding_top;
    }

    public function getPaddingLeft()
    {
        return $this->padding_left;
    }

    public function setPaddingLeft($padding_left)
    {
        $this->padding_left = $padding_left;
    }

    public function getPaddingRight()
    {
        return $this->padding_right;
    }

    public function setPaddingRight($padding_right)
    {
        $this->padding_right = $padding_right;
    }

    public function getPaddingBottom()
    {
        return $this->padding_bottom;
    }

    public function setPaddingBottom($padding_bottom)
    {
        $this->padding_bottom = $padding_bottom;
    }

    public function getMarginTop()
    {
        return $this->margin_top;
    }

    public function setMarginTop($margin_top)
    {
        $this->margin_top = $margin_top;
    }

    public function getMarginLeft()
    {
        return $this->margin_left;
    }

    public function setMarginLeft($margin_left)
    {
        $this->margin_left = $margin_left;
    }

    public function getMarginRight()
    {
        return $this->margin_right;
    }

    public function setMarginRight($margin_right)
    {
        $this->margin_right = $margin_right;
    }

    public function getMarginBottom()
    {
        return $this->margin_bottom;
    }

    public function setMarginBottom($margin_bottom)
    {
        $this->margin_bottom = $margin_bottom;
    }

    public function getBodyMinSizeUnit()
    {
        return $this->body_min_height_unit;
    }

    public function setBodyMinSizeUnit($body_min_height_unit)
    {
        $this->body_min_height_unit = $body_min_height_unit;
    }

    public function getPaddingTopUnit()
    {
        return $this->padding_top_unit;
    }

    public function setPaddingTopUnit($padding_top_unit)
    {
        $this->padding_top_unit = $padding_top_unit;
    }

    public function getPaddingLeftUnit()
    {
        return $this->padding_left_unit;
    }

    public function setPaddingLeftUnit($padding_left_unit)
    {
        $this->padding_left_unit = $padding_left_unit;
    }

    public function getPaddingRightUnit()
    {
        return $this->padding_right_unit;
    }

    public function setPaddingRightUnit($padding_right_unit)
    {
        $this->padding_right_unit = $padding_right_unit;
    }

    public function getPaddingBottomUnit()
    {
        return $this->padding_bottom_unit;
    }

    public function setPaddingBottomUnit($padding_bottom_unit)
    {
        $this->padding_bottom_unit = $padding_bottom_unit;
    }

    public function getMarginTopUnit()
    {
        return $this->margin_top_unit;
    }

    public function setMarginTopUnit($margin_top_unit)
    {
        $this->margin_top_unit = $margin_top_unit;
    }

    public function getMarginLeftUnit()
    {
        return $this->margin_left_unit;
    }

    public function setMarginLeftUnit($margin_left_unit)
    {
        $this->margin_left_unit = $margin_left_unit;
    }

    public function getMarginRightUnit()
    {
        return $this->margin_right_unit;
    }

    public function setMarginRightUnit($margin_right_unit)
    {
        $this->margin_right_unit = $margin_right_unit;
    }

    public function getMarginBottomUnit()
    {
        return $this->margin_bottom_unit;
    }

    public function setMarginBottomUnit($margin_bottom_unit)
    {
        $this->margin_bottom_unit = $margin_bottom_unit;
    }

    public function getBodyBorderTopWidth()
    {
        return $this->body_border_top_width;
    }

    public function setBodyBorderTopWidth($body_border_top_width)
    {
        $this->body_border_top_width = $body_border_top_width;
    }

    public function getBodyBorderTopStyle()
    {
        return $this->body_border_top_style;
    }

    public function setBodyBorderTopStyle($body_border_top_style)
    {
        $this->body_border_top_style = $body_border_top_style;
    }

    public function getBodyBorderTopColor()
    {
        return $this->body_border_top_color;
    }

    public function setBodyBorderTopColor($body_border_top_color)
    {
        $this->body_border_top_color = $body_border_top_color;
    }

    public function getBodyBorderRightWidth()
    {
        return $this->body_border_right_width;
    }

    public function setBodyBorderRightWidth($body_border_right_width)
    {
        $this->body_border_right_width = $body_border_right_width;
    }

    public function getBodyBorderRightStyle()
    {
        return $this->body_border_right_style;
    }

    public function setBodyBorderRightStyle($body_border_right_style)
    {
        $this->body_border_right_style = $body_border_right_style;
    }

    public function getBodyBorderRightColor()
    {
        return $this->body_border_right_color;
    }

    public function setBodyBorderRightColor($body_border_right_color)
    {
        $this->body_border_right_color = $body_border_right_color;
    }

    public function getBodyBorderBottomWidth()
    {
        return $this->body_border_bottom_width;
    }

    public function setBodyBorderBottomWidth($body_border_bottom_width)
    {
        $this->body_border_bottom_width = $body_border_bottom_width;
    }

    public function getBodyBorderBottomStyle()
    {
        return $this->body_border_bottom_style;
    }

    public function setBodyBorderBottomStyle($body_border_bottom_style)
    {
        $this->body_border_bottom_style = $body_border_bottom_style;
    }

    public function getBodyBorderBottomColor()
    {
        return $this->body_border_bottom_color;
    }

    public function setBodyBorderBottomColor($body_border_bottom_color)
    {
        $this->body_border_bottom_color = $body_border_bottom_color;
    }

    public function getBodyBorderLeftWidth()
    {
        return $this->body_border_left_width;
    }

    public function setBodyBorderLeftWidth($body_border_left_width)
    {
        $this->body_border_left_width = $body_border_left_width;
    }

    public function getBodyBorderLeftStyle()
    {
        return $this->body_border_left_style;
    }

    public function setBodyBorderLeftStyle($body_border_left_style)
    {
        $this->body_border_left_style = $body_border_left_style;
    }

    public function getBodyBorderLeftColor()
    {
        return $this->body_border_left_color;
    }

    public function setBodyBorderLeftColor($body_border_left_color)
    {
        $this->body_border_left_color = $body_border_left_color;
    }
}
