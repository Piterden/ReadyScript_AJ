<?php /* Smarty version Smarty-3.1.18, created on 2016-01-12 01:28:57
         compiled from "/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/list_products.tpl" */ ?>
<?php /*%%SmartyHeaderCode:198646245156942ca926ab53-16463236%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '321933da4cf2081d53db5d7568abd69cb497920d' => 
    array (
      0 => '/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/list_products.tpl',
      1 => 1452548190,
      2 => 'rs',
    ),
  ),
  'nocache_hash' => '198646245156942ca926ab53-16463236',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'shop_config' => 0,
    'no_query_error' => 0,
    'category' => 0,
    'sub_dirs' => 0,
    'query' => 0,
    'dir_id' => 0,
    'one_dir' => 0,
    'item' => 0,
    'list' => 0,
    'view_as' => 0,
    'total' => 0,
    'cur_sort' => 0,
    'cur_n' => 0,
    'sort' => 0,
    'can_rank_sort' => 0,
    'page_size' => 0,
    'items_on_page' => 0,
    'current_user' => 0,
    'product' => 0,
    'specCategory' => 0,
    'router' => 0,
    'THEME_IMG' => 0,
    'main_image' => 0,
    'check_quantity' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56942ca95139a3_28097630',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56942ca95139a3_28097630')) {function content_56942ca95139a3_28097630($_smarty_tpl) {?><?php if (!is_callable('smarty_function_addjs')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.addjs.php';
if (!is_callable('smarty_function_urlmake')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.urlmake.php';
if (!is_callable('smarty_function_moduleinsert')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.moduleinsert.php';
if (!is_callable('smarty_function_verb')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.verb.php';
if (!is_callable('smarty_function_static_call')) include '/home/groupvm/www/site22/public_html/core/smarty/rsplugins/function.static_call.php';
?><?php echo smarty_function_addjs(array('file'=>"jquery.changeoffer.js"),$_smarty_tpl);?>

<?php $_smarty_tpl->tpl_vars['shop_config'] = new Smarty_variable(\RS\Config\Loader::byModule('shop'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['check_quantity'] = new Smarty_variable($_smarty_tpl->tpl_vars['shop_config']->value['check_quantity'], null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['no_query_error']->value) {?>
<div class="noQuery">
	Не задан поисковый запрос
</div> 
<?php } else { ?>
<div id="products" class="productList<?php if ($_smarty_tpl->tpl_vars['shop_config']->value) {?> shopVersion<?php }?><?php if ($_smarty_tpl->tpl_vars['category']->value['level']==0&&$_smarty_tpl->tpl_vars['category']->value['is_spec_dir']=='N') {?> container<?php }?>">
	<!-- <?php if ($_smarty_tpl->tpl_vars['category']->value['description']) {?><article class="categoryDescription"><?php echo $_smarty_tpl->tpl_vars['category']->value['description'];?>
</article><?php }?> -->
	<?php if (count($_smarty_tpl->tpl_vars['sub_dirs']->value)) {?><?php $_smarty_tpl->tpl_vars['one_dir'] = new Smarty_variable(reset($_smarty_tpl->tpl_vars['sub_dirs']->value), null, 0);?><?php }?>
	<?php if ((empty($_smarty_tpl->tpl_vars['query']->value)||(count($_smarty_tpl->tpl_vars['sub_dirs']->value)&&$_smarty_tpl->tpl_vars['dir_id']->value!=$_smarty_tpl->tpl_vars['one_dir']->value['id']))&&$_smarty_tpl->tpl_vars['category']->value['level']==0&&$_smarty_tpl->tpl_vars['category']->value['is_spec_dir']=='N') {?>
		<div class="row productListTitle">
			<div class="col-md-24 text-center mainTitle">
				<?php if (!empty($_smarty_tpl->tpl_vars['query']->value)) {?>
					<h1>Результаты поиска</h1>
				<?php } else { ?>
					<h1><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</h1>
				<?php }?>
			</div>
		</div>
		<div class="row subCategories topCategory">
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sub_dirs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['sub_dir']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['sub_dir']['index']++;
?>
			<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['sub_dir']['index']==3) {?>
			    <?php break 1?>
			<?php }?>
			<div class="col-md-8 subCategory <?php echo $_smarty_tpl->tpl_vars['item']->value['alias'];?>
">
				<div class="wrapper">
					<a href="<?php echo smarty_function_urlmake(array('category'=>$_smarty_tpl->tpl_vars['item']->value['_alias'],'p'=>null,'f'=>null,'bfilter'=>null),$_smarty_tpl);?>
">
						<img src="<?php echo $_smarty_tpl->tpl_vars['item']->value->__image->getUrl(358,528);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
">
						<span class="textBlock">
							<span class="wrapper">
								<span class="icon"></span>
								<span class="categoryTitle h3"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</span>
								<span class="lookAtAll h5 cornered-border">Смотреть все</span>
							</span>
						</span>
					</a>
				</div>	
			</div>
			<?php } ?>
		</div>
		<div class="row top">
			<?php echo smarty_function_moduleinsert(array('name'=>"\Catalog\Controller\Block\TopProducts",'indexTemplate'=>'blocks/topproducts/top_products.tpl','pageSize'=>'4','dirs'=>'10','order'=>'dateof'),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/list_products.tpl');?>

		</div>
	
	<?php } else { ?>
		<?php if (count($_smarty_tpl->tpl_vars['list']->value)) {?>
			<?php if ($_smarty_tpl->tpl_vars['view_as']->value=='blocks') {?>
				<div class="row productsBlock">
					<!-- Sortline -->
					<div class="sortLine col-md-24">
						<span class="total h4"><?php echo smarty_function_verb(array('item'=>$_smarty_tpl->tpl_vars['total']->value,'values'=>"модель,модели,моделей"),$_smarty_tpl);?>
</span>
				        <span class="sort">
				            Сортировать по
				            <span class="ddList">
				                <span class="value h4"><?php if ($_smarty_tpl->tpl_vars['cur_sort']->value=='dateof') {?>дате<?php } elseif ($_smarty_tpl->tpl_vars['cur_sort']->value=='rating') {?>популярности<?php } else { ?>цене<?php }?> <i class="fa fa-sort-<?php echo $_smarty_tpl->tpl_vars['cur_n']->value;?>
"></i></span>
				                <ul>
				                    <li><a href="<?php echo smarty_function_urlmake(array('sort'=>"cost",'nsort'=>$_smarty_tpl->tpl_vars['sort']->value['cost']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['cur_sort']->value=='cost') {?> <?php echo $_smarty_tpl->tpl_vars['cur_n']->value;?>
<?php }?>" rel="nofollow">цене</a></li>                
				                    <li><a href="<?php echo smarty_function_urlmake(array('sort'=>"rating",'nsort'=>$_smarty_tpl->tpl_vars['sort']->value['rating']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['cur_sort']->value=='rating') {?> <?php echo $_smarty_tpl->tpl_vars['cur_n']->value;?>
<?php }?>" rel="nofollow">популярности</a></li>                    
				                    <li><a href="<?php echo smarty_function_urlmake(array('sort'=>"dateof",'nsort'=>$_smarty_tpl->tpl_vars['sort']->value['dateof']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['cur_sort']->value=='dateof') {?> <?php echo $_smarty_tpl->tpl_vars['cur_n']->value;?>
<?php }?>" rel="nofollow">дате</a></li>
				                    <li><a href="<?php echo smarty_function_urlmake(array('sort'=>"num",'nsort'=>$_smarty_tpl->tpl_vars['sort']->value['num']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['cur_sort']->value=='num') {?> <?php echo $_smarty_tpl->tpl_vars['cur_n']->value;?>
<?php }?>" rel="nofollow">наличию</a></li>
				                    <?php if ($_smarty_tpl->tpl_vars['can_rank_sort']->value) {?>
				                    <li><a href="<?php echo smarty_function_urlmake(array('sort'=>"rank",'nsort'=>$_smarty_tpl->tpl_vars['sort']->value['rank']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['cur_sort']->value=='rank') {?> <?php echo $_smarty_tpl->tpl_vars['cur_n']->value;?>
<?php }?>" rel="nofollow"><i>релевантности</i></a></li>
				                    <?php }?>                    
				                </ul>
				            </span>
				        </span>
				        
				        <span class="pageSize">
				            Показывать по 
				            <span class="ddList">
				                <span class="value h4"><?php echo $_smarty_tpl->tpl_vars['page_size']->value;?>
</span>
				                <ul>
				                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items_on_page']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
				                    <li class="<?php if ($_smarty_tpl->tpl_vars['page_size']->value==$_smarty_tpl->tpl_vars['item']->value) {?> act<?php }?>"><a href="<?php echo smarty_function_urlmake(array('pageSize'=>$_smarty_tpl->tpl_vars['item']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</a></li>
				                    <?php } ?>
				                </ul>
				            </span>
				        </span>                    
				    </div>
					<?php echo smarty_function_static_call(array('var'=>'added_ids','callback'=>array('\Wishlist\Model\WishApi','getWishedProductIds'),'params'=>array($_smarty_tpl->tpl_vars['current_user']->value['id'])),$_smarty_tpl);?>

				    <!-- /Sortline -->
					<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
						<?php $_smarty_tpl->tpl_vars['main_image'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getMainImage(), null, 0);?>
			            <div class="col-sm-8 productItem" <?php echo $_smarty_tpl->tpl_vars['product']->value->getDebugAttributes();?>
 data-id="<?php echo $_smarty_tpl->tpl_vars['product']->value['id'];?>
">
			                <div class="productItemWrap">
				                <div class="specCategoriesBlockWrap">
									<?php  $_smarty_tpl->tpl_vars['specCategory'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['specCategory']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['product']->value->getSpecDirs(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['specCategory']->key => $_smarty_tpl->tpl_vars['specCategory']->value) {
$_smarty_tpl->tpl_vars['specCategory']->_loop = true;
?>
										<?php if ($_smarty_tpl->tpl_vars['specCategory']->value['image']!=''&&$_smarty_tpl->tpl_vars['product']->value->inDir($_smarty_tpl->tpl_vars['specCategory']->value['alias'])) {?>
										<div class="specCategory">
											<img src="<?php echo $_smarty_tpl->tpl_vars['specCategory']->value->__image->getLink();?>
" alt="<?php echo $_smarty_tpl->tpl_vars['specCategory']->value['name'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['specCategory']->value['name'];?>
">
										</div>
										<?php }?>
									<?php } ?>
								</div>
			                    <div class="wishWrap">
			                        <?php echo smarty_function_moduleinsert(array('name'=>"\Wishlist\Controller\Block\WishActions",'product_id'=>$_smarty_tpl->tpl_vars['product']->value['id']),$_smarty_tpl,'/home/groupvm/www/site22/public_html/templates/autofashion/moduleview/catalog/list_products.tpl');?>

			                    </div>
			                    <div class="toCartWrap">
			                        <?php if ($_smarty_tpl->tpl_vars['product']->value->isOffersUse()||$_smarty_tpl->tpl_vars['product']->value->isMultiOffersUse()) {?>
			                            <a data-href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-multioffers',array("product_id"=>$_smarty_tpl->tpl_vars['product']->value['id']));?>
" class="button showMultiOffers inDialog noShowCart">
			                                <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/cart.png" alt="Add to Cart" width="20px" height="20px">
			                                <div class="toCartText">
			                                    В корзину
			                                </div>
			                            </a>
			                        <?php } else { ?>
			                            <a data-href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-cartpage',array("add"=>$_smarty_tpl->tpl_vars['product']->value['id']));?>
" class="button addToCart noShowCart" data-add-text="Добавлено">
			                                <img src="<?php echo $_smarty_tpl->tpl_vars['THEME_IMG']->value;?>
/cart.png" alt="Add to Cart" width="20px" height="20px">
			                                <div class="toCartText">
			                                    В корзину
			                                </div>
			                            </a>
			                        <?php }?>
			                    </div>
			                    <a href="<?php echo $_smarty_tpl->tpl_vars['product']->value->getUrl();?>
" class="mainLink">
			                        <div class="pic text-center">
			                            <img src="<?php echo $_smarty_tpl->tpl_vars['main_image']->value->getUrl(185,300,'axy');?>
" alt="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['main_image']->value['title'])===null||$tmp==='' ? ((string)$_smarty_tpl->tpl_vars['product']->value['title']) : $tmp);?>
"/>
			                        </div>
			                        <div class="infoWrap">
			                            <div class="info">
			                                <div class="price"><?php echo $_smarty_tpl->tpl_vars['product']->value->getCost();?>
 <?php echo $_smarty_tpl->tpl_vars['product']->value->getCurrency();?>
</div>
			                                <div class="old-price">
			                                    <?php if ($_smarty_tpl->tpl_vars['product']->value->getCost(2)>0) {?>
			                                        <?php echo $_smarty_tpl->tpl_vars['product']->value->getCost(2);?>
 <?php echo $_smarty_tpl->tpl_vars['product']->value->getCurrency();?>

			                                    <?php }?>
			                                </div>
			                                <h3 class="title"><?php echo $_smarty_tpl->tpl_vars['product']->value['title'];?>
</h3>
			                                <div class="desc"><?php echo $_smarty_tpl->tpl_vars['product']->value['short_description'];?>
</div>
			                            </div>
			                        </div>
			                    </a>
			                </div>
						</div>
					<?php } ?>
				</div>
			<?php } else { ?>
				<table class="productTable">
					<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
					<tr <?php echo $_smarty_tpl->tpl_vars['product']->value->getDebugAttributes();?>
 data-id="<?php echo $_smarty_tpl->tpl_vars['product']->value['id'];?>
">
						<?php $_smarty_tpl->tpl_vars['main_image'] = new Smarty_variable($_smarty_tpl->tpl_vars['product']->value->getMainImage(), null, 0);?>
						<td class="image"><a href="<?php echo $_smarty_tpl->tpl_vars['product']->value->getUrl();?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['main_image']->value->getUrl(100,100);?>
" alt="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['main_image']->value['title'])===null||$tmp==='' ? ((string)$_smarty_tpl->tpl_vars['product']->value['title']) : $tmp);?>
"/></a></td>
						<td class="info">
							<a href="<?php echo $_smarty_tpl->tpl_vars['product']->value->getUrl();?>
" class="title"><?php echo $_smarty_tpl->tpl_vars['product']->value['title'];?>
</a>
							<?php if ($_smarty_tpl->tpl_vars['product']->value['barcode']) {?><p class="barcode">Артикул: <?php echo $_smarty_tpl->tpl_vars['product']->value['barcode'];?>
</p><?php }?>
							<p class="descr"><?php echo $_smarty_tpl->tpl_vars['product']->value['short_description'];?>
</p>
						</td>
						<td class="price"><?php echo $_smarty_tpl->tpl_vars['product']->value->getCost();?>
 <?php echo $_smarty_tpl->tpl_vars['product']->value->getCurrency();?>
</td>
						<td class="actions">
							<?php if ($_smarty_tpl->tpl_vars['shop_config']->value) {?>
								<?php if ($_smarty_tpl->tpl_vars['product']->value->shouldReserve()) {?>
									<a href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-reservation',array("product_id"=>$_smarty_tpl->tpl_vars['product']->value['id']));?>
" class="button reserve inDialog">Заказать</a>
								<?php } else { ?>        
									<?php if ($_smarty_tpl->tpl_vars['check_quantity']->value&&$_smarty_tpl->tpl_vars['product']->value['num']<1) {?>
										<div class="noAvaible">Нет в наличии</div>
									<?php } else { ?>
										<?php if ($_smarty_tpl->tpl_vars['product']->value->isOffersUse()||$_smarty_tpl->tpl_vars['product']->value->isMultiOffersUse()) {?>
											<span data-href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-multioffers',array("product_id"=>$_smarty_tpl->tpl_vars['product']->value['id']));?>
" class="button showMultiOffers inDialog noShowCart">В корзину</span>
										<?php } else { ?>
											<a data-href="<?php echo $_smarty_tpl->tpl_vars['router']->value->getUrl('shop-front-cartpage',array("add"=>$_smarty_tpl->tpl_vars['product']->value['id']));?>
" class="button addToCart noShowCart" data-add-text="Добавлено">В корзину</a>
										<?php }?>                                                                                        
									<?php }?>
								<?php }?>
							<?php }?>
							<a class="compare<?php if ($_smarty_tpl->tpl_vars['product']->value->inCompareList()) {?> inCompare<?php }?>"><span>Сравнить</span><span class="already">Добавлено</span></a>
						</td>
					</tr>                       
					<?php } ?>
				</table>
			<?php }?>
			<?php echo $_smarty_tpl->getSubTemplate ("%THEME%/paginator.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php } else { ?>    
			<div class="container noProducts">
				<div class="row">
					<div class="col-md-24">
						<?php if (!empty($_smarty_tpl->tpl_vars['query']->value)) {?>
						Извините, ничего не найдено
						<?php } else { ?>
						В данной категории нет ни одного товара
						<?php }?>
					</div>
				</div>
			</div>
		<?php }?>
	<?php }?>

</div>
<?php }?>

<!--  -->          <?php }} ?>
