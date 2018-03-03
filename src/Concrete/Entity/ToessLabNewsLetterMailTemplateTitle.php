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
 * @Table(name="ToessLabNewsLetterMailTemplateTitle")
 */

class ToessLabNewsLetterMailTemplateTitle
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
    protected $h1_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_weight = '200';

    /**
     * @Column(type="string")
     */
    protected $h1_font = 'Arial';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_size = '16';

    /**
     * @Column(type="string", length=7)
     */
    protected $h1_style = 'normal';

    /**
     * @Column(type="string", length=7)
     */
    protected $h2_style = 'normal';

    /**
     * @Column(type="string", length=7)
     */
    protected $h3_style = 'normal';

    /**
     * @Column(type="string", length=7)
     */
    protected $h4_style = 'normal';

    /**
     * @Column(type="string", length=7)
     */
    protected $h5_style = 'normal';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_size_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_padding_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h1_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_margin_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h1_margin_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h1_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h1_margin_left_unit = 'px';
    /**
     * @Column(type="string", length=18)
     */
    protected $h2_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=7)
     */
    protected $h2_weight = '200';

    /**
     * @Column(type="string")
     */
    protected $h2_font = 'Arial';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_size = '16';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_size_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_padding_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h2_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_margin_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h2_margin_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h2_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h2_margin_left_unit = 'px';
    /**
     * @Column(type="string", length=18)
     */
    protected $h3_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=7)
     */
    protected $h3_weight = '200';

    /**
     * @Column(type="string")
     */
    protected $h3_font = 'Arial';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_size = '16';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_size_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_padding_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h3_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_margin_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h3_margin_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h3_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h3_margin_left_unit = 'px';

    /**
     * @Column(type="string", length=18)
     */
    protected $h4_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=7)
     */
    protected $h4_weight = '200';

    /**
     * @Column(type="string")
     */
    protected $h4_font = 'Arial';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_size = '16';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_size_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_padding_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h4_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_margin_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h4_margin_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h4_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h4_margin_left_unit = 'px';
    /**
     * @Column(type="string", length=18)
     */
    protected $h5_color = 'rgb(247,247,247)';

    /**
     * @Column(type="string", length=7)
     */
    protected $h5_weight = '200';

    /**
     * @Column(type="string")
     */
    protected $h5_font = 'Arial';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_size = '16';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_size_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_padding_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_padding_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_padding_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_padding_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h5_padding_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_padding_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_padding_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_padding_left_unit = 'px';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_margin_top = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_margin_right = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_margin_bottom = '0';

    /**
     * @Column(type="string", length=4)
     */
    protected $h5_margin_left = '0';
    /**
     * @Column(type="string", length=2)
     */
    protected $h5_margin_top_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_margin_right_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_margin_bottom_unit = 'px';

    /**
     * @Column(type="string", length=2)
     */
    protected $h5_margin_left_unit = 'px';

    public function setTplId($tpl_id)
    {
        $this->tplID = $tpl_id;
    }

    public function getTplId()
    {
        return $this->tplID;
    }


    public function getH1Color()
    {
        return $this->h1_color;
    }

    public function setH1Color($h1_color)
    {
        $this->h1_color = $h1_color;
    }

    public function getH1Weight()
    {
        return $this->h1_weight;
    }

    public function setH1Weight($h1_weight)
    {
        $this->h1_weight = $h1_weight;
    }

    public function getH1Font()
    {
        return $this->h1_font;
    }

    public function setH1Font($h1_font)
    {
        $this->h1_font = $h1_font;
    }

    public function getH1Size()
    {
        return $this->h1_size;
    }

    public function setH1Size($h1_size)
    {
        $this->h1_size = $h1_size;
    }

    public function getH1Style()
    {
        return $this->h1_style;
    }

    public function setH1Style($h1_style)
    {
        $this->h1_style = $h1_style;
    }

    public function getH2Style()
    {
        return $this->h2_style;
    }

    public function setH2Style($h2_style)
    {
        $this->h2_style = $h2_style;
    }

    public function getH3Style()
    {
        return $this->h3_style;
    }

    public function setH3Style($h3_style)
    {
        $this->h3_style = $h3_style;
    }

    public function getH4Style()
    {
        return $this->h4_style;
    }

    public function setH4Style($h4_style)
    {
        $this->h4_style = $h4_style;
    }

    public function getH5Style()
    {
        return $this->h5_style;
    }

    public function setH5Style($h5_style)
    {
        $this->h5_style = $h5_style;
    }

    public function getH1SizeUnit()
    {
        return $this->h1_size_unit;
    }

    public function setH1SizeUnit($h1_size_unit)
    {
        $this->h1_size_unit = $h1_size_unit;
    }

    public function getH1PaddingTop()
    {
        return $this->h1_padding_top;
    }

    public function setH1PaddingTop($h1_padding_top)
    {
        $this->h1_padding_top = $h1_padding_top;
    }

    public function getH1PaddingRight()
    {
        return $this->h1_padding_right;
    }

    public function setH1PaddingRight($h1_padding_right)
    {
        $this->h1_padding_right = $h1_padding_right;
    }

    public function getH1PaddingLeft()
    {
        return $this->h1_padding_left;
    }

    public function setH1PaddingLeft($h1_padding_left)
    {
        $this->h1_padding_left = $h1_padding_left;
    }

    public function getH1PaddingBottom()
    {
        return $this->h1_padding_bottom;
    }

    public function setH1PaddingBottom($h1_padding_bottom)
    {
        $this->h1_padding_bottom = $h1_padding_bottom;
    }
    public function getH1PaddingTopUnit()
    {
        return $this->h1_padding_top_unit;
    }

    public function setH1PaddingTopUnit($h1_padding_top_unit)
    {
        $this->h1_padding_top_unit = $h1_padding_top_unit;
    }

    public function getH1PaddingRightUnit()
    {
        return $this->h1_padding_right_unit;
    }

    public function setH1PaddingRightUnit($h1_padding_right_unit)
    {
        $this->h1_padding_right_unit = $h1_padding_right_unit;
    }

    public function getH1PaddingLeftUnit()
    {
        return $this->h1_padding_left_unit;
    }

    public function setH1PaddingLeftUnit($h1_padding_left_unit)
    {
        $this->h1_padding_left_unit = $h1_padding_left_unit;
    }

    public function getH1PaddingBottomUnit()
    {
        return $this->h1_padding_bottom_unit;
    }

    public function setH1PaddingBottomUnit($h1_padding_bottom_unit)
    {
        $this->h1_padding_bottom_unit = $h1_padding_bottom_unit;
    }
    public function getH1MarginTop()
    {
        return $this->h1_margin_top;
    }

    public function setH1MarginTop($h1_margin_top)
    {
        $this->h1_margin_top = $h1_margin_top;
    }

    public function getH1MarginRight()
    {
        return $this->h1_margin_right;
    }

    public function setH1MarginRight($h1_margin_right)
    {
        $this->h1_margin_right = $h1_margin_right;
    }

    public function getH1MarginLeft()
    {
        return $this->h1_margin_left;
    }

    public function setH1MarginLeft($h1_margin_left)
    {
        $this->h1_margin_left = $h1_margin_left;
    }

    public function getH1MarginBottom()
    {
        return $this->h1_margin_bottom;
    }

    public function setH1MarginBottom($h1_margin_bottom)
    {
        $this->h1_margin_bottom = $h1_margin_bottom;
    }
    public function getH1MarginTopUnit()
    {
        return $this->h1_margin_top_unit;
    }

    public function setH1MarginTopUnit($h1_margin_top_unit)
    {
        $this->h1_margin_top_unit = $h1_margin_top_unit;
    }

    public function getH1MarginRightUnit()
    {
        return $this->h1_margin_right_unit;
    }

    public function setH1MarginRightUnit($h1_margin_right_unit)
    {
        $this->h1_margin_right_unit = $h1_margin_right_unit;
    }

    public function getH1MarginLeftUnit()
    {
        return $this->h1_margin_left_unit;
    }

    public function setH1MarginLeftUnit($h1_margin_left_unit)
    {
        $this->h1_margin_left_unit = $h1_margin_left_unit;
    }

    public function getH1MarginBottomUnit()
    {
        return $this->h1_margin_bottom_unit;
    }

    public function setH1MarginBottomUnit($h1_margin_bottom_unit)
    {
        $this->h1_margin_bottom_unit = $h1_margin_bottom_unit;
    }
    public function getH2Color()
    {
        return $this->h2_color;
    }

    public function setH2Color($h2_color)
    {
        $this->h2_color = $h2_color;
    }

    public function getH2Weight()
    {
        return $this->h2_weight;
    }

    public function setH2Weight($h2_weight)
    {
        $this->h2_weight = $h2_weight;
    }

    public function getH2Font()
    {
        return $this->h2_font;
    }

    public function setH2Font($h2_font)
    {
        $this->h2_font = $h2_font;
    }

    public function getH2Size()
    {
        return $this->h2_size;
    }

    public function setH2Size($h2_size)
    {
        $this->h2_size = $h2_size;
    }

    public function getH2SizeUnit()
    {
        return $this->h2_size_unit;
    }

    public function setH2SizeUnit($h2_size_unit)
    {
        $this->h2_size_unit = $h2_size_unit;
    }

    public function getH2PaddingTop()
    {
        return $this->h2_padding_top;
    }

    public function setH2PaddingTop($h2_padding_top)
    {
        $this->h2_padding_top = $h2_padding_top;
    }

    public function getH2PaddingRight()
    {
        return $this->h2_padding_right;
    }

    public function setH2PaddingRight($h2_padding_right)
    {
        $this->h2_padding_right = $h2_padding_right;
    }

    public function getH2PaddingLeft()
    {
        return $this->h2_padding_left;
    }

    public function setH2PaddingLeft($h2_padding_left)
    {
        $this->h2_padding_left = $h2_padding_left;
    }

    public function getH2PaddingBottom()
    {
        return $this->h2_padding_bottom;
    }

    public function setH2PaddingBottom($h2_padding_bottom)
    {
        $this->h2_padding_bottom = $h2_padding_bottom;
    }
    public function getH2PaddingTopUnit()
    {
        return $this->h2_padding_top_unit;
    }

    public function setH2PaddingTopUnit($h2_padding_top_unit)
    {
        $this->h2_padding_top_unit = $h2_padding_top_unit;
    }

    public function getH2PaddingRightUnit()
    {
        return $this->h2_padding_right_unit;
    }

    public function setH2PaddingRightUnit($h2_padding_right_unit)
    {
        $this->h2_padding_right_unit = $h2_padding_right_unit;
    }

    public function getH2PaddingLeftUnit()
    {
        return $this->h2_padding_left_unit;
    }

    public function setH2PaddingLeftUnit($h2_padding_left_unit)
    {
        $this->h2_padding_left_unit = $h2_padding_left_unit;
    }

    public function getH2PaddingBottomUnit()
    {
        return $this->h2_padding_bottom_unit;
    }

    public function setH2PaddingBottomUnit($h2_padding_bottom_unit)
    {
        $this->h2_padding_bottom_unit = $h2_padding_bottom_unit;
    }
    public function getH2MarginTop()
    {
        return $this->h2_margin_top;
    }

    public function setH2MarginTop($h2_margin_top)
    {
        $this->h2_margin_top = $h2_margin_top;
    }

    public function getH2MarginRight()
    {
        return $this->h2_margin_right;
    }

    public function setH2MarginRight($h2_margin_right)
    {
        $this->h2_margin_right = $h2_margin_right;
    }

    public function getH2MarginLeft()
    {
        return $this->h2_margin_left;
    }

    public function setH2MarginLeft($h2_margin_left)
    {
        $this->h2_margin_left = $h2_margin_left;
    }

    public function getH2MarginBottom()
    {
        return $this->h2_margin_bottom;
    }

    public function setH2MarginBottom($h2_margin_bottom)
    {
        $this->h2_margin_bottom = $h2_margin_bottom;
    }
    public function getH2MarginTopUnit()
    {
        return $this->h2_margin_top_unit;
    }

    public function setH2MarginTopUnit($h2_margin_top_unit)
    {
        $this->h2_margin_top_unit = $h2_margin_top_unit;
    }

    public function getH2MarginRightUnit()
    {
        return $this->h2_margin_right_unit;
    }

    public function setH2MarginRightUnit($h2_margin_right_unit)
    {
        $this->h2_margin_right_unit = $h2_margin_right_unit;
    }

    public function getH2MarginLeftUnit()
    {
        return $this->h2_margin_left_unit;
    }

    public function setH2MarginLeftUnit($h2_margin_left_unit)
    {
        $this->h2_margin_left_unit = $h2_margin_left_unit;
    }

    public function getH2MarginBottomUnit()
    {
        return $this->h2_margin_bottom_unit;
    }

    public function setH2MarginBottomUnit($h2_margin_bottom_unit)
    {
        $this->h2_margin_bottom_unit = $h2_margin_bottom_unit;
    }
    public function getH3Color()
    {
        return $this->h3_color;
    }

    public function setH3Color($h3_color)
    {
        $this->h3_color = $h3_color;
    }

    public function getH3Weight()
    {
        return $this->h3_weight;
    }

    public function setH3Weight($h3_weight)
    {
        $this->h3_weight = $h3_weight;
    }

    public function getH3Font()
    {
        return $this->h3_font;
    }

    public function setH3Font($h3_font)
    {
        $this->h3_font = $h3_font;
    }

    public function getH3Size()
    {
        return $this->h3_size;
    }

    public function setH3Size($h3_size)
    {
        $this->h3_size = $h3_size;
    }

    public function getH3SizeUnit()
    {
        return $this->h3_size_unit;
    }

    public function setH3SizeUnit($h3_size_unit)
    {
        $this->h3_size_unit = $h3_size_unit;
    }

    public function getH3PaddingTop()
    {
        return $this->h3_padding_top;
    }

    public function setH3PaddingTop($h3_padding_top)
    {
        $this->h3_padding_top = $h3_padding_top;
    }

    public function getH3PaddingRight()
    {
        return $this->h3_padding_right;
    }

    public function setH3PaddingRight($h3_padding_right)
    {
        $this->h3_padding_right = $h3_padding_right;
    }

    public function getH3PaddingLeft()
    {
        return $this->h3_padding_left;
    }

    public function setH3PaddingLeft($h3_padding_left)
    {
        $this->h3_padding_left = $h3_padding_left;
    }

    public function getH3PaddingBottom()
    {
        return $this->h3_padding_bottom;
    }

    public function setH3PaddingBottom($h3_padding_bottom)
    {
        $this->h3_padding_bottom = $h3_padding_bottom;
    }
    public function getH3PaddingTopUnit()
    {
        return $this->h3_padding_top_unit;
    }

    public function setH3PaddingTopUnit($h3_padding_top_unit)
    {
        $this->h3_padding_top_unit = $h3_padding_top_unit;
    }

    public function getH3PaddingRightUnit()
    {
        return $this->h3_padding_right_unit;
    }

    public function setH3PaddingRightUnit($h3_padding_right_unit)
    {
        $this->h3_padding_right_unit = $h3_padding_right_unit;
    }

    public function getH3PaddingLeftUnit()
    {
        return $this->h3_padding_left_unit;
    }

    public function setH3PaddingLeftUnit($h3_padding_left_unit)
    {
        $this->h3_padding_left_unit = $h3_padding_left_unit;
    }

    public function getH3PaddingBottomUnit()
    {
        return $this->h3_padding_bottom_unit;
    }

    public function setH3PaddingBottomUnit($h3_padding_bottom_unit)
    {
        $this->h3_padding_bottom_unit = $h3_padding_bottom_unit;
    }
    public function getH3MarginTop()
    {
        return $this->h3_margin_top;
    }

    public function setH3MarginTop($h3_margin_top)
    {
        $this->h3_margin_top = $h3_margin_top;
    }

    public function getH3MarginRight()
    {
        return $this->h3_margin_right;
    }

    public function setH3MarginRight($h3_margin_right)
    {
        $this->h3_margin_right = $h3_margin_right;
    }

    public function getH3MarginLeft()
    {
        return $this->h3_margin_left;
    }

    public function setH3MarginLeft($h3_margin_left)
    {
        $this->h3_margin_left = $h3_margin_left;
    }

    public function getH3MarginBottom()
    {
        return $this->h3_margin_bottom;
    }

    public function setH3MarginBottom($h3_margin_bottom)
    {
        $this->h3_margin_bottom = $h3_margin_bottom;
    }
    public function getH3MarginTopUnit()
    {
        return $this->h3_margin_top_unit;
    }

    public function setH3MarginTopUnit($h3_margin_top_unit)
    {
        $this->h3_margin_top_unit = $h3_margin_top_unit;
    }

    public function getH3MarginRightUnit()
    {
        return $this->h3_margin_right_unit;
    }

    public function setH3MarginRightUnit($h3_margin_right_unit)
    {
        $this->h3_margin_right_unit = $h3_margin_right_unit;
    }

    public function getH3MarginLeftUnit()
    {
        return $this->h3_margin_left_unit;
    }

    public function setH3MarginLeftUnit($h3_margin_left_unit)
    {
        $this->h3_margin_left_unit = $h3_margin_left_unit;
    }

    public function getH3MarginBottomUnit()
    {
        return $this->h3_margin_bottom_unit;
    }

    public function setH3MarginBottomUnit($h3_margin_bottom_unit)
    {
        $this->h3_margin_bottom_unit = $h3_margin_bottom_unit;
    }

    public function getH4Color()
    {
        return $this->h4_color;
    }

    public function setH4Color($h4_color)
    {
        $this->h4_color = $h4_color;
    }

    public function getH4Weight()
    {
        return $this->h4_weight;
    }

    public function setH4Weight($h4_weight)
    {
        $this->h4_weight = $h4_weight;
    }

    public function getH4Font()
    {
        return $this->h4_font;
    }

    public function setH4Font($h4_font)
    {
        $this->h4_font = $h4_font;
    }

    public function getH4Size()
    {
        return $this->h4_size;
    }

    public function setH4Size($h4_size)
    {
        $this->h4_size = $h4_size;
    }

    public function getH4SizeUnit()
    {
        return $this->h4_size_unit;
    }

    public function setH4SizeUnit($h4_size_unit)
    {
        $this->h4_size_unit = $h4_size_unit;
    }

    public function getH4PaddingTop()
    {
        return $this->h4_padding_top;
    }

    public function setH4PaddingTop($h4_padding_top)
    {
        $this->h4_padding_top = $h4_padding_top;
    }

    public function getH4PaddingRight()
    {
        return $this->h4_padding_right;
    }

    public function setH4PaddingRight($h4_padding_right)
    {
        $this->h4_padding_right = $h4_padding_right;
    }

    public function getH4PaddingLeft()
    {
        return $this->h4_padding_left;
    }

    public function setH4PaddingLeft($h4_padding_left)
    {
        $this->h4_padding_left = $h4_padding_left;
    }

    public function getH4PaddingBottom()
    {
        return $this->h4_padding_bottom;
    }

    public function setH4PaddingBottom($h4_padding_bottom)
    {
        $this->h4_padding_bottom = $h4_padding_bottom;
    }
    public function getH4PaddingTopUnit()
    {
        return $this->h4_padding_top_unit;
    }

    public function setH4PaddingTopUnit($h4_padding_top_unit)
    {
        $this->h4_padding_top_unit = $h4_padding_top_unit;
    }

    public function getH4PaddingRightUnit()
    {
        return $this->h4_padding_right_unit;
    }

    public function setH4PaddingRightUnit($h4_padding_right_unit)
    {
        $this->h4_padding_right_unit = $h4_padding_right_unit;
    }

    public function getH4PaddingLeftUnit()
    {
        return $this->h4_padding_left_unit;
    }

    public function setH4PaddingLeftUnit($h4_padding_left_unit)
    {
        $this->h4_padding_left_unit = $h4_padding_left_unit;
    }

    public function getH4PaddingBottomUnit()
    {
        return $this->h4_padding_bottom_unit;
    }

    public function setH4PaddingBottomUnit($h4_padding_bottom_unit)
    {
        $this->h4_padding_bottom_unit = $h4_padding_bottom_unit;
    }
    public function getH4MarginTop()
    {
        return $this->h4_margin_top;
    }

    public function setH4MarginTop($h4_margin_top)
    {
        $this->h4_margin_top = $h4_margin_top;
    }

    public function getH4MarginRight()
    {
        return $this->h4_margin_right;
    }

    public function setH4MarginRight($h4_margin_right)
    {
        $this->h4_margin_right = $h4_margin_right;
    }

    public function getH4MarginLeft()
    {
        return $this->h4_margin_left;
    }

    public function setH4MarginLeft($h4_margin_left)
    {
        $this->h4_margin_left = $h4_margin_left;
    }

    public function getH4MarginBottom()
    {
        return $this->h4_margin_bottom;
    }

    public function setH4MarginBottom($h4_margin_bottom)
    {
        $this->h4_margin_bottom = $h4_margin_bottom;
    }
    public function getH4MarginTopUnit()
    {
        return $this->h4_margin_top_unit;
    }

    public function setH4MarginTopUnit($h4_margin_top_unit)
    {
        $this->h4_margin_top_unit = $h4_margin_top_unit;
    }

    public function getH4MarginRightUnit()
    {
        return $this->h4_margin_right_unit;
    }

    public function setH4MarginRightUnit($h4_margin_right_unit)
    {
        $this->h4_margin_right_unit = $h4_margin_right_unit;
    }

    public function getH4MarginLeftUnit()
    {
        return $this->h4_margin_left_unit;
    }

    public function setH4MarginLeftUnit($h4_margin_left_unit)
    {
        $this->h4_margin_left_unit = $h4_margin_left_unit;
    }

    public function getH4MarginBottomUnit()
    {
        return $this->h4_margin_bottom_unit;
    }

    public function setH4MarginBottomUnit($h4_margin_bottom_unit)
    {
        $this->h4_margin_bottom_unit = $h4_margin_bottom_unit;
    }

    public function getH5Color()
    {
        return $this->h5_color;
    }

    public function setH5Color($h5_color)
    {
        $this->h5_color = $h5_color;
    }

    public function getH5Weight()
    {
        return $this->h5_weight;
    }

    public function setH5Weight($h5_weight)
    {
        $this->h5_weight = $h5_weight;
    }

    public function getH5Font()
    {
        return $this->h5_font;
    }

    public function setH5Font($h5_font)
    {
        $this->h5_font = $h5_font;
    }

    public function getH5Size()
    {
        return $this->h5_size;
    }

    public function setH5Size($h5_size)
    {
        $this->h5_size = $h5_size;
    }

    public function getH5SizeUnit()
    {
        return $this->h5_size_unit;
    }

    public function setH5SizeUnit($h5_size_unit)
    {
        $this->h5_size_unit = $h5_size_unit;
    }

    public function getH5PaddingTop()
    {
        return $this->h5_padding_top;
    }

    public function setH5PaddingTop($h5_padding_top)
    {
        $this->h5_padding_top = $h5_padding_top;
    }

    public function getH5PaddingRight()
    {
        return $this->h5_padding_right;
    }

    public function setH5PaddingRight($h5_padding_right)
    {
        $this->h5_padding_right = $h5_padding_right;
    }

    public function getH5PaddingLeft()
    {
        return $this->h5_padding_left;
    }

    public function setH5PaddingLeft($h5_padding_left)
    {
        $this->h5_padding_left = $h5_padding_left;
    }

    public function getH5PaddingBottom()
    {
        return $this->h5_padding_bottom;
    }

    public function setH5PaddingBottom($h5_padding_bottom)
    {
        $this->h5_padding_bottom = $h5_padding_bottom;
    }
    public function getH5PaddingTopUnit()
    {
        return $this->h5_padding_top_unit;
    }

    public function setH5PaddingTopUnit($h5_padding_top_unit)
    {
        $this->h5_padding_top_unit = $h5_padding_top_unit;
    }

    public function getH5PaddingRightUnit()
    {
        return $this->h5_padding_right_unit;
    }

    public function setH5PaddingRightUnit($h5_padding_right_unit)
    {
        $this->h5_padding_right_unit = $h5_padding_right_unit;
    }

    public function getH5PaddingLeftUnit()
    {
        return $this->h5_padding_left_unit;
    }

    public function setH5PaddingLeftUnit($h5_padding_left_unit)
    {
        $this->h5_padding_left_unit = $h5_padding_left_unit;
    }

    public function getH5PaddingBottomUnit()
    {
        return $this->h5_padding_bottom_unit;
    }

    public function setH5PaddingBottomUnit($h5_padding_bottom_unit)
    {
        $this->h5_padding_bottom_unit = $h5_padding_bottom_unit;
    }
    public function getH5MarginTop()
    {
        return $this->h5_margin_top;
    }

    public function setH5MarginTop($h5_margin_top)
    {
        $this->h5_margin_top = $h5_margin_top;
    }

    public function getH5MarginRight()
    {
        return $this->h5_margin_right;
    }

    public function setH5MarginRight($h5_margin_right)
    {
        $this->h5_margin_right = $h5_margin_right;
    }

    public function getH5MarginLeft()
    {
        return $this->h5_margin_left;
    }

    public function setH5MarginLeft($h5_margin_left)
    {
        $this->h5_margin_left = $h5_margin_left;
    }

    public function getH5MarginBottom()
    {
        return $this->h5_margin_bottom;
    }

    public function setH5MarginBottom($h5_margin_bottom)
    {
        $this->h5_margin_bottom = $h5_margin_bottom;
    }
    public function getH5MarginTopUnit()
    {
        return $this->h5_margin_top_unit;
    }

    public function setH5MarginTopUnit($h5_margin_top_unit)
    {
        $this->h5_margin_top_unit = $h5_margin_top_unit;
    }

    public function getH5MarginRightUnit()
    {
        return $this->h5_margin_right_unit;
    }

    public function setH5MarginRightUnit($h5_margin_right_unit)
    {
        $this->h5_margin_right_unit = $h5_margin_right_unit;
    }

    public function getH5MarginLeftUnit()
    {
        return $this->h5_margin_left_unit;
    }

    public function setH5MarginLeftUnit($h5_margin_left_unit)
    {
        $this->h5_margin_left_unit = $h5_margin_left_unit;
    }

    public function getH5MarginBottomUnit()
    {
        return $this->h5_margin_bottom_unit;
    }

    public function setH5MarginBottomUnit($h5_margin_bottom_unit)
    {
        $this->h5_margin_bottom_unit = $h5_margin_bottom_unit;
    }

}
