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
 * @Table(name="ToessLabNewsLetterMailTemplateFoot")
 */

class ToessLabNewsLetterMailTemplateFoot
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
     * @Column(type="string", length=4)
     */
    protected $foot_height = '200';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_padding_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_margin_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $foot_margin_bottom = '0';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_height_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_margin_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=18)
     */
    protected $foot_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_border_top_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $foot_border_top_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $foot_border_top_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_border_right_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $foot_border_right_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $foot_border_right_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_border_bottom_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $foot_border_bottom_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $foot_border_bottom_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $foot_border_left_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $foot_border_left_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $foot_border_left_color = 'rgb(247, 247, 247)';

    public function setTplId($tpl_id)
    {
        $this->tplID = $tpl_id;
    }

    public function getTplId()
    {
        return $this->tplID;
    }

    public function getFootHeight()
    {
        return $this->foot_height;
    }

    public function setFootHeight($foot_height)
    {
        $this->foot_height = $foot_height;
    }

    public function getFootPaddingTop()
    {
        return $this->foot_padding_top;
    }

    public function setFootPaddingTop($foot_padding_top)
    {
        $this->foot_padding_top = $foot_padding_top;
    }

    public function getFootPaddingLeft()
    {
        return $this->foot_padding_left;
    }

    public function setFootPaddingLeft($foot_padding_left)
    {
        $this->foot_padding_left = $foot_padding_left;
    }

    public function getFootPaddingRight()
    {
        return $this->foot_padding_right;
    }

    public function setFootPaddingRight($foot_padding_right)
    {
        $this->foot_padding_right = $foot_padding_right;
    }

    public function getFootPaddingBottom()
    {
        return $this->foot_padding_bottom;
    }

    public function setFootPaddingBottom($foot_padding_bottom)
    {
        $this->foot_padding_bottom = $foot_padding_bottom;
    }

    public function getFootMarginTop()
    {
        return $this->foot_margin_top;
    }

    public function setFootMarginTop($foot_margin_top)
    {
        $this->foot_margin_top = $foot_margin_top;
    }

    public function getFootMarginLeft()
    {
        return $this->foot_margin_left;
    }

    public function setFootMarginLeft($foot_margin_left)
    {
        $this->foot_margin_left = $foot_margin_left;
    }

    public function getFootMarginRight()
    {
        return $this->foot_margin_right;
    }

    public function setFootMarginRight($foot_margin_right)
    {
        $this->foot_margin_right = $foot_margin_right;
    }

    public function getFootMarginBottom()
    {
        return $this->foot_margin_bottom;
    }

    public function setFootMarginBottom($foot_margin_bottom)
    {
        $this->foot_margin_bottom = $foot_margin_bottom;
    }
    public function getFootHeightUnit()
    {
        return $this->foot_height_unit;
    }

    public function setFootHeightUnit($foot_height_unit)
    {
        $this->foot_height_unit = $foot_height_unit;
    }

    public function getFootPaddingTopUnit()
    {
        return $this->foot_padding_top_unit;
    }

    public function setFootPaddingTopUnit($foot_padding_top_unit)
    {
        $this->foot_padding_top_unit = $foot_padding_top_unit;
    }

    public function getFootPaddingLeftUnit()
    {
        return $this->foot_padding_left_unit;
    }

    public function setFootPaddingLeftUnit($foot_padding_left_unit)
    {
        $this->foot_padding_left_unit = $foot_padding_left_unit;
    }

    public function getFootPaddingRightUnit()
    {
        return $this->foot_padding_right_unit;
    }

    public function setFootPaddingRightUnit($foot_padding_right_unit)
    {
        $this->foot_padding_right_unit = $foot_padding_right_unit;
    }

    public function getFootPaddingBottomUnit()
    {
        return $this->foot_padding_bottom_unit;
    }

    public function setFootPaddingBottomUnit($foot_padding_bottom_unit)
    {
        $this->foot_padding_bottom_unit = $foot_padding_bottom_unit;
    }

    public function getFootMarginTopUnit()
    {
        return $this->foot_margin_top_unit;
    }

    public function setFootMarginTopUnit($foot_margin_top_unit)
    {
        $this->foot_margin_top_unit = $foot_margin_top_unit;
    }

    public function getFootMarginLeftUnit()
    {
        return $this->foot_margin_left_unit;
    }

    public function setFootMarginLeftUnit($foot_margin_left_unit)
    {
        $this->foot_margin_left_unit = $foot_margin_left_unit;
    }

    public function getFootMarginRightUnit()
    {
        return $this->foot_margin_right_unit;
    }

    public function setFootMarginRightUnit($foot_margin_right_unit)
    {
        $this->foot_margin_right_unit = $foot_margin_right_unit;
    }

    public function getFootMarginBottomUnit()
    {
        return $this->foot_margin_bottom_unit;
    }

    public function setFootMarginBottomUnit($foot_margin_bottom_unit)
    {
        $this->foot_margin_bottom_unit = $foot_margin_bottom_unit;
    }

    public function getFootColor()
    {
        return $this->foot_color;
    }

    public function setFootColor($foot_color)
    {
        $this->foot_color = $foot_color;
    }
    public function getFootBorderTopWidth()
    {
        return $this->foot_border_top_width;
    }

    public function setFootBorderTopWidth($foot_border_top_width)
    {
        $this->foot_border_top_width = $foot_border_top_width;
    }

    public function getFootBorderTopStyle()
    {
        return $this->foot_border_top_style;
    }

    public function setFootBorderTopStyle($foot_border_top_style)
    {
        $this->foot_border_top_style = $foot_border_top_style;
    }

    public function getFootBorderTopColor()
    {
        return $this->foot_border_top_color;
    }

    public function setFootBorderTopColor($foot_border_top_color)
    {
        $this->foot_border_top_color = $foot_border_top_color;
    }

    public function getFootBorderRightWidth()
    {
        return $this->foot_border_right_width;
    }

    public function setFootBorderRightWidth($foot_border_right_width)
    {
        $this->foot_border_right_width = $foot_border_right_width;
    }

    public function getFootBorderRightStyle()
    {
        return $this->foot_border_right_style;
    }

    public function setFootBorderRightStyle($foot_border_right_style)
    {
        $this->foot_border_right_style = $foot_border_right_style;
    }

    public function getFootBorderRightColor()
    {
        return $this->foot_border_right_color;
    }

    public function setFootBorderRightColor($foot_border_right_color)
    {
        $this->foot_border_right_color = $foot_border_right_color;
    }

    public function getFootBorderBottomWidth()
    {
        return $this->foot_border_bottom_width;
    }

    public function setFootBorderBottomWidth($foot_border_bottom_width)
    {
        $this->foot_border_bottom_width = $foot_border_bottom_width;
    }

    public function getFootBorderBottomStyle()
    {
        return $this->foot_border_bottom_style;
    }

    public function setFootBorderBottomStyle($foot_border_bottom_style)
    {
        $this->foot_border_bottom_style = $foot_border_bottom_style;
    }

    public function getFootBorderBottomColor()
    {
        return $this->foot_border_bottom_color;
    }

    public function setFootBorderBottomColor($foot_border_bottom_color)
    {
        $this->foot_border_bottom_color = $foot_border_bottom_color;
    }

    public function getFootBorderLeftWidth()
    {
        return $this->foot_border_left_width;
    }

    public function setFootBorderLeftWidth($foot_border_left_width)
    {
        $this->foot_border_left_width = $foot_border_left_width;
    }

    public function getFootBorderLeftStyle()
    {
        return $this->foot_border_left_style;
    }

    public function setFootBorderLeftStyle($foot_border_left_style)
    {
        $this->foot_border_left_style = $foot_border_left_style;
    }

    public function getFootBorderLeftColor()
    {
        return $this->foot_border_left_color;
    }

    public function setFootBorderLeftColor($foot_border_left_color)
    {
        $this->foot_border_left_color = $foot_border_left_color;
    }

}
