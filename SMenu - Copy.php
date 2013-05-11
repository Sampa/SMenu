<?php

class SMenu extends CWidget
{
	public $theme= "sphere-orange";
	public $items = array();
	public $htmlOptions = array(	);
	
	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		$this->registerClientScript();
	
	}

	public static function build($url,$menu=false,$icon=false,$htmlOptions=array()){
				return array( 
				'url'=>$url,
				'menu'=>$menu,
				'icon'=>$icon,
				'htmlOptions'=>$htmlOptions,
				
			);
	}
	/**
	 * Run this widget.
	 */
	public function run()
	{	
		$items = $this->items;
		?>
		<ul class="topmenu" id="<?=$this->theme;?>" >
		<li class="topfirst">
			<a title="Home" href="<?=Yii::app()->homeUrl;?>">Sampa</a></li>
		<?php foreach($items as $item=>$values):?>
		<li class="topmenu">	
			<?php
				if(!isset($values['icon']) || !$values['icon']){
					$icon = null;
				}else{
					$icon = '<i class="icon-'.$values['icon'].'"></i>';
				} 
				$htmlOptions = isset($values['htmlOptions'])?$values['htmlOptions']:array(); 
				//link
				echo CHtml::link( $icon. $item,$values['url'],$htmlOptions);
				//if it has a submenu
				if(isset($values['menu']) && is_array($values['menu'])):
					$columnStyle = in_array("column",$values['menu']) ? 'width:50%;' :null;
					$submenuStyle = in_array("column",$values['menu']) ? 'min-width:300%;' : null;
					
			?>
			<div class="submenu" style="<?=$submenuStyle;?>">
				<div class="column" style="<?=$columnStyle;?>">
					<ul>
					<?php
					 foreach($values['menu'] as $submenu=>$values)://each submenu
						if($submenu=="column"){
							echo '</ul></div><div class="column" style="width:50%"><ul>';
							continue;
						}
					?>
						<li>			
							<?php
								$icon = isset($values['icon'])? '<i class="icon-'.$values['icon'].'"></i>':null;
								$htmlOptions = isset($values['htmlOptions'])?$values['htmlOptions']:array(); 
								echo CHtml::link( $icon. $submenu,$values['url'],$htmlOptions);
								if(isset($values['menu']) && is_array($values['menu'])):
							?>
							<div class="submenu">
									<ul>
									<?php 
										foreach($values['menu'] as $submenu=>$values){
											$icon = isset($values['icon'])? '<i class="icon-'.$values['icon'].'"></i>':null;
											$htmlOptions = isset($values['htmlOptions'])?$values['htmlOptions']:array(); 
											echo  CHtml::tag('li',array(),CHtml::link( $icon. $submenu,$values['url'],$htmlOptions));
										}
									?>
									</ul>
							</div>
							<?php endif;?>
						</li>
			<?php
					endforeach; //each submenu
				endif; //if isset menu
			?>

					</ul>
				</div>
			</div>

		</li>
	<?php endforeach;?>
	</ul>
	 <?php
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
