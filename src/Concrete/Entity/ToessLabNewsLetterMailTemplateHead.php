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
 * @Table(name="ToessLabNewsLetterMailTemplateHead")
 */

class ToessLabNewsLetterMailTemplateHead
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
     * @Column(type="string", nullable=true)
     */
    protected $mail_logo = null;

    /**
     * @Column(type="string", length=4)
     */
    protected $logo_width = '200';

    /**
     * @Column(type="string", length=4)
     */
    protected $logo_height = 'auto';

    /**
     * @Column(type="string", length=2)
     */
    protected $logo_width_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $logo_height_unit = 'px';

    /**
     * @Column(type="string", length=18)
     */
    protected $head_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_height = '200';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_height_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_padding_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_img_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_img_margin_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_img_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_img_margin_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_margin_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $head_margin_bottom = '0';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_img_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_img_margin_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_img_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_img_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_margin_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $logo_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $logo_left = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $logo_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $logo_bottom = '0';

    /**
     * @Column(type="string", length=2)
     */
    protected $logo_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $logo_left_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $logo_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $logo_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_border_top_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $head_border_top_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $head_border_top_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_border_right_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $head_border_right_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $head_border_right_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_border_bottom_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $head_border_bottom_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $head_border_bottom_color = 'rgb(247, 247, 247)';

    /**
     * @Column(type="string", length=2)
     */
    protected $head_border_left_width = '0';

    /**
     * @Column(type="string", length=7)
     */
    protected $head_border_left_style = 'none';

    /**
     * @Column(type="string", length=18)
     */
    protected $head_border_left_color = 'rgb(247, 247, 247)';

    public function setTplId($tpl_id)
    {
        $this->tplID = $tpl_id;
    }

    public function getTplId()
    {
        return $this->tplID;
    }

    public function getMailLogo()
    {
        return $this->mail_logo;
    }

    public function setMailLogo($mail_logo)
    {
        $this->mail_logo = $mail_logo;
    }

    public function getLogoWidth()
    {
        return $this->logo_width;
    }

    public function setLogoWidth($logo_width)
    {
        $this->logo_width = $logo_width;
    }

    public function getLogoHeight()
    {
        return $this->logo_height;
    }

    public function setLogoHeight($logo_height)
    {
        $this->logo_height = $logo_height;
    }

    public function getLogoWidthUnit()
    {
        return $this->logo_width_unit;
    }

    public function setLogoWidthUnit($logo_width_unit)
    {
        $this->logo_width_unit = $logo_width_unit;
    }

    public function getLogoHeightUnit()
    {
        return $this->logo_height_unit;
    }

    public function setLogoHeightUnit($logo_height_unit)
    {
        $this->logo_height_unit = $logo_height_unit;
    }

    public function getHeadColor()
    {
        return $this->head_color;
    }

    public function setHeadColor($head_color)
    {
        $this->head_color = $head_color;
    }

    public function getHeadHeight()
    {
        return $this->head_height;
    }

    public function setHeadHeight($head_height)
    {
        $this->head_height = $head_height;
    }

    public function getHeadPaddingTop()
    {
        return $this->head_padding_top;
    }

    public function setHeadPaddingTop($head_padding_top)
    {
        $this->head_padding_top = $head_padding_top;
    }

    public function getHeadPaddingLeft()
    {
        return $this->head_padding_left;
    }

    public function setHeadPaddingLeft($head_padding_left)
    {
        $this->head_padding_left = $head_padding_left;
    }

    public function getHeadPaddingRight()
    {
        return $this->head_padding_right;
    }

    public function setHeadPaddingRight($head_padding_right)
    {
        $this->head_padding_right = $head_padding_right;
    }

    public function getHeadPaddingBottom()
    {
        return $this->head_padding_bottom;
    }

    public function setHeadPaddingBottom($head_padding_bottom)
    {
        $this->head_padding_bottom = $head_padding_bottom;
    }

    public function getHeadImgMarginTop()
    {
        return $this->head_img_margin_top;
    }

    public function setHeadImgMarginTop($head_img_margin_top)
    {
        $this->head_img_margin_top = $head_img_margin_top;
    }

    public function getHeadImgMarginLeft()
    {
        return $this->head_img_margin_left;
    }

    public function setHeadImgMarginLeft($head_img_margin_left)
    {
        $this->head_img_margin_left = $head_img_margin_left;
    }

    public function getHeadImgMarginRight()
    {
        return $this->head_img_margin_right;
    }

    public function setHeadImgMarginRight($head_img_margin_right)
    {
        $this->head_img_margin_right = $head_img_margin_right;
    }

    public function getHeadImgMarginBottom()
    {
        return $this->head_img_margin_bottom;
    }

    public function setHeadImgMarginBottom($head_img_margin_bottom)
    {
        $this->head_img_margin_bottom = $head_img_margin_bottom;
    }

    public function getHeadMarginTop()
    {
        return $this->head_margin_top;
    }

    public function setHeadMarginTop($head_margin_top)
    {
        $this->head_margin_top = $head_margin_top;
    }

    public function getHeadMarginLeft()
    {
        return $this->head_margin_left;
    }

    public function setHeadMarginLeft($head_margin_left)
    {
        $this->head_margin_left = $head_margin_left;
    }

    public function getHeadMarginRight()
    {
        return $this->head_margin_right;
    }

    public function setHeadMarginRight($head_margin_right)
    {
        $this->head_margin_right = $head_margin_right;
    }

    public function getHeadMarginBottom()
    {
        return $this->head_margin_bottom;
    }

    public function setHeadMarginBottom($head_margin_bottom)
    {
        $this->head_margin_bottom = $head_margin_bottom;
    }
    public function getHeadHeightUnit()
    {
        return $this->head_height_unit;
    }

    public function setHeadHeightUnit($head_height_unit)
    {
        $this->head_height_unit = $head_height_unit;
    }

    public function getHeadPaddingTopUnit()
    {
        return $this->head_padding_top_unit;
    }

    public function setHeadPaddingTopUnit($head_padding_top_unit)
    {
        $this->head_padding_top_unit = $head_padding_top_unit;
    }

    public function getHeadPaddingLeftUnit()
    {
        return $this->head_padding_left_unit;
    }

    public function setHeadPaddingLeftUnit($head_padding_left_unit)
    {
        $this->head_padding_left_unit = $head_padding_left_unit;
    }

    public function getHeadPaddingRightUnit()
    {
        return $this->head_padding_right_unit;
    }

    public function setHeadPaddingRightUnit($head_padding_right_unit)
    {
        $this->head_padding_right_unit = $head_padding_right_unit;
    }

    public function getHeadPaddingBottomUnit()
    {
        return $this->head_padding_bottom_unit;
    }

    public function setHeadPaddingBottomUnit($head_padding_bottom_unit)
    {
        $this->head_padding_bottom_unit = $head_padding_bottom_unit;
    }

    public function getHeadImgMarginTopUnit()
    {
        return $this->head_img_margin_top_unit;
    }

    public function setHeadImgMarginTopUnit($head_img_margin_top_unit)
    {
        $this->head_img_margin_top_unit = $head_img_margin_top_unit;
    }

    public function getHeadImgMarginLeftUnit()
    {
        return $this->head_img_margin_left_unit;
    }

    public function setHeadImgMarginLeftUnit($head_img_margin_left_unit)
    {
        $this->head_img_margin_left_unit = $head_img_margin_left_unit;
    }

    public function getHeadImgMarginRightUnit()
    {
        return $this->head_img_margin_right_unit;
    }

    public function setHeadImgMarginRightUnit($head_img_margin_right_unit)
    {
        $this->head_img_margin_right_unit = $head_img_margin_right_unit;
    }

    public function getHeadImgMarginBottomUnit()
    {
        return $this->head_img_margin_bottom_unit;
    }

    public function setHeadImgMarginBottomUnit($head_img_margin_bottom_unit)
    {
        $this->head_img_margin_bottom_unit = $head_img_margin_bottom_unit;
    }


    public function getHeadMarginTopUnit()
    {
        return $this->head_margin_top_unit;
    }

    public function setHeadMarginTopUnit($head_margin_top_unit)
    {
        $this->head_margin_top_unit = $head_margin_top_unit;
    }

    public function getHeadMarginLeftUnit()
    {
        return $this->head_margin_left_unit;
    }

    public function setHeadMarginLeftUnit($head_margin_left_unit)
    {
        $this->head_margin_left_unit = $head_margin_left_unit;
    }

    public function getHeadMarginRightUnit()
    {
        return $this->head_margin_right_unit;
    }

    public function setHeadMarginRightUnit($head_margin_right_unit)
    {
        $this->head_margin_right_unit = $head_margin_right_unit;
    }

    public function getHeadMarginBottomUnit()
    {
        return $this->head_margin_bottom_unit;
    }

    public function setHeadMarginBottomUnit($head_margin_bottom_unit)
    {
        $this->head_margin_bottom_unit = $head_margin_bottom_unit;
    }

    public function getLogoTop()
    {
        return $this->logo_top;
    }

    public function setLogoTop($logo_top)
    {
        $this->logo_top = $logo_top;
    }

    public function getLogoLeft()
    {
        return $this->logo_left;
    }

    public function setLogoLeft($logo_left)
    {
        $this->logo_left = $logo_left;
    }

    public function getLogoRight()
    {
        return $this->logo_right;
    }

    public function setLogoRight($logo_right)
    {
        $this->logo_right = $logo_right;
    }

    public function getLogoBottom()
    {
        return $this->logo_bottom;
    }

    public function setLogoBottom($logo_bottom)
    {
        $this->logo_bottom = $logo_bottom;
    }

    public function getLogoTopUnit()
    {
        return $this->logo_top_unit;
    }

    public function setLogoTopUnit($logo_top_unit)
    {
        $this->logo_top_unit = $logo_top_unit;
    }

    public function getLogoLeftUnit()
    {
        return $this->logo_left_unit;
    }

    public function setLogoLeftUnit($logo_left_unit)
    {
        $this->logo_left_unit = $logo_left_unit;
    }

    public function getLogoRightUnit()
    {
        return $this->logo_right_unit;
    }

    public function setLogoRightUnit($logo_right_unit)
    {
        $this->logo_right_unit = $logo_right_unit;
    }

    public function getLogoBottomUnit()
    {
        return $this->logo_bottom_unit;
    }

    public function setLogoBottomUnit($logo_bottom_unit)
    {
        $this->logo_bottom_unit = $logo_bottom_unit;
    }

    public function getHeadBorderTopWidth()
    {
        return $this->head_border_top_width;
    }

    public function setHeadBorderTopWidth($head_border_top_width)
    {
        $this->head_border_top_width = $head_border_top_width;
    }

    public function getHeadBorderTopStyle()
    {
        return $this->head_border_top_style;
    }

    public function setHeadBorderTopStyle($head_border_top_style)
    {
        $this->head_border_top_style = $head_border_top_style;
    }

    public function getHeadBorderTopColor()
    {
        return $this->head_border_top_color;
    }

    public function setHeadBorderTopColor($head_border_top_color)
    {
        $this->head_border_top_color = $head_border_top_color;
    }

    public function getHeadBorderRightWidth()
    {
        return $this->head_border_right_width;
    }

    public function setHeadBorderRightWidth($head_border_right_width)
    {
        $this->head_border_right_width = $head_border_right_width;
    }

    public function getHeadBorderRightStyle()
    {
        return $this->head_border_right_style;
    }

    public function setHeadBorderRightStyle($head_border_right_style)
    {
        $this->head_border_right_style = $head_border_right_style;
    }

    public function getHeadBorderRightColor()
    {
        return $this->head_border_right_color;
    }

    public function setHeadBorderRightColor($head_border_right_color)
    {
        $this->head_border_right_color = $head_border_right_color;
    }

    public function getHeadBorderBottomWidth()
    {
        return $this->head_border_bottom_width;
    }

    public function setHeadBorderBottomWidth($head_border_bottom_width)
    {
        $this->head_border_bottom_width = $head_border_bottom_width;
    }

    public function getHeadBorderBottomStyle()
    {
        return $this->head_border_bottom_style;
    }

    public function setHeadBorderBottomStyle($head_border_bottom_style)
    {
        $this->head_border_bottom_style = $head_border_bottom_style;
    }

    public function getHeadBorderBottomColor()
    {
        return $this->head_border_bottom_color;
    }

    public function setHeadBorderBottomColor($head_border_bottom_color)
    {
        $this->head_border_bottom_color = $head_border_bottom_color;
    }

    public function getHeadBorderLeftWidth()
    {
        return $this->head_border_left_width;
    }

    public function setHeadBorderLeftWidth($head_border_left_width)
    {
        $this->head_border_left_width = $head_border_left_width;
    }

    public function getHeadBorderLeftStyle()
    {
        return $this->head_border_left_style;
    }

    public function setHeadBorderLeftStyle($head_border_left_style)
    {
        $this->head_border_left_style = $head_border_left_style;
    }

    public function getHeadBorderLeftColor()
    {
        return $this->head_border_left_color;
    }

    public function setHeadBorderLeftColor($head_border_left_color)
    {
        $this->head_border_left_color = $head_border_left_color;
    }
}
