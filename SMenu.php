<?php
Yii::import('zii.widgets.CMenu');
class SMenu extends CMenu
{
	public $theme= "sphere-orange";
	public $submenuClass;	
	public $columnWidth ="50%";	
	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		$this->registerClientScript();
	
	}

	/**
	 * Run this widget.
	 */
	public function run()
	{	
	
		parent::run();
	}
	protected function renderMenuRecursive($items,$submenu=false)
	{
		$count=0;
		$n=count($items);
		foreach($items as $item)
		{
			if(isset($item[0]) && $item[0]=='column'){
				$width = isset($item['width']) ? $item['width'] : "50%";

				echo '</ul></div><div class="column" style="width:'.$width.'"><ul>';
			}else{
			$count++;
			$options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
			$class=array();
			if($item['active'] && $this->activeCssClass!='')
				$class[]=$this->activeCssClass;
			if($count===1 && $this->firstItemCssClass!==null)
				$class[]=$this->firstItemCssClass;
			if($count===$n && $this->lastItemCssClass!==null)
				$class[]=$this->lastItemCssClass;
			if(!$submenu){
				if($this->itemCssClass!==null)
					$class[]=$this->itemCssClass;
			}else{
				if($this->submenuClass !==null)
					$class[]=$this->submenuClass;
			}
			if($class!==array())
			{
				if(empty($options['class']))
					$options['class']=implode(' ',$class);
				else
					$options['class'].=' '.implode(' ',$class);
			}

			echo CHtml::openTag('li', $options);

			$menu=$this->renderMenuItem($item);
			if(isset($this->itemTemplate) || isset($item['template']))
			{
				$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
				echo strtr($template,array('{menu}'=>$menu));
			}
			else
				echo $menu;

			if(isset($item['items']) && count($item['items']))
			{
				$subWidth= isset($item['subWidth'])? 'style="width:'.$item['subWidth'].';"': null;
				$divOpen = false;
				echo '<div class="submenu" '.$subWidth.' >';

				foreach($item['items'] as $subitem){
					if(isset($subitem[0]) && $subitem[0]=='column'){
						$width = isset($subitem['width']) ? $subitem['width'] : "50%";
						echo '<div class="column" style="width:'.$width.'">';
						$divOpen = true;
					}
				}
				
				
				echo "\n".CHtml::openTag('ul',isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions)."\n";
				$this->renderMenuRecursive($item['items'],true);
				echo CHtml::closeTag('ul')."\n";

				if($divOpen)
					echo '</div>';
				echo "</div>";
			}

			echo CHtml::closeTag('li')."\n";
			}
		}
	}
	protected function renderMenuItem($item)
	{
		if(isset($item['url']))
		{
			$label=$this->linkLabelWrapper===null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
			$icon = isset($item['icon'])? '<i class="icon-'.$item['icon'].'"></i>': null;
			return CHtml::link($icon.$label,$item['url'],isset($item['linkOptions']) ? $item['linkOptions'] : array());
		}
		else
			return CHtml::tag('span',isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
	}
	public function registerClientScript()
	{			
		try{
			$assets = dirname(__FILE__).'/assets';
			$baseUrl = Yii::app()->assetManager->publish($assets);
			$cs=Yii::app()->getClientScript();		
			$cs->registerCssFile($baseUrl.'/'.$this->theme.'.css');							
		}catch(CException $e){
			throw new CException('failed to publish/register assets : '.$e->getMessage());
		}
	}	
	
}
