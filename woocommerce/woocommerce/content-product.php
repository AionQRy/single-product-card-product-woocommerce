<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$excerpt_product_s = get_field('excerpt_product_s');
$flash_sale = get_field('flash_sale');
$product = wc_get_product( get_the_ID() );
$show_stock = false;
if ( ! empty( $woocommerce_loop['show_stock'] ) )
	$show_stock = true;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
$flash_sale = get_field('flash_sale');
$product = wc_get_product( get_the_ID() );

$category_detail = get_the_terms( get_the_ID() , 'product_cat' );

$promotion_p = get_field('promotion_p');
$new_product = get_field('new_product');


if ( $product->is_type( 'simple' ) ) {

  if ($product->get_sale_price() == '') {
      $max_percentage = 0;
  }
  else {
    $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
  }
}
else   {
   $max_percentage = 0;
   foreach ( $product->get_children() as $child_id ) {
      $variation = wc_get_product( $child_id );
      $price = $variation->get_regular_price();
      $sale = $variation->get_sale_price();
      if ( $price != 0 && ! empty( $sale ) ) $percentage = ( $price - $sale ) / $price * 100;
      if ( $percentage > $max_percentage ) {
         $max_percentage = $percentage;
      }
   }
}


// Display labels.
 ?>
<div class="main-article post-pp">
    <div class="box-thumbnail">
      <!-- sad -->
      <div class="bar-icon">
                <?php if( $flash_sale == 'sale' ) : ?>
                <div class="ic-timing flash_sale-icon">
                 <!-- <img src="/wp-content/uploads/2019/05/Asset-3icon.png" alt="flash_sale"> -->
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 18l1.291-4h-4.291l6.584-7-1.375 4h3.791l-6 7zm1.5-16c-5.288 0-9.649 3.914-10.377 9h-3.123l4 5.917 4-5.917h-2.847c.711-3.972 4.174-7 8.347-7 4.687 0 8.5 3.813 8.5 8.5s-3.813 8.5-8.5 8.5c-3.015 0-5.662-1.583-7.171-3.957l-1.2 1.775c1.916 2.536 4.948 4.182 8.371 4.182 5.797 0 10.5-4.702 10.5-10.5s-4.703-10.5-10.5-10.5z"/></svg>
                </div>
                <?php endif; ?>

                <?php if( $max_percentage > 0 ) : ?>
                <div class="ic-timing time_sale-icon">
                 <!-- <img src="/wp-content/uploads/2019/05/Asset-3icon.png" alt="flash_sale"> -->
                 <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M12.628 21.412l5.969-5.97 1.458 3.71-12.34 4.848-4.808-12.238 9.721 9.65zm-1.276-21.412h-9.352v9.453l10.625 10.547 9.375-9.375-10.648-10.625zm4.025 9.476c-.415-.415-.865-.617-1.378-.617-.578 0-1.227.241-2.171.804-.682.41-1.118.584-1.456.584-.361 0-1.083-.408-.961-1.218.052-.345.25-.697.572-1.02.652-.651 1.544-.848 2.276-.106l.744-.744c-.476-.476-1.096-.792-1.761-.792-.566 0-1.125.227-1.663.677l-.626-.627-.698.699.653.652c-.569.826-.842 2.021.076 2.938 1.011 1.011 2.188.541 3.413-.232.6-.379 1.083-.563 1.475-.563.589 0 1.18.498 1.078 1.258-.052.386-.26.763-.621 1.122-.451.451-.904.679-1.347.679-.418 0-.747-.192-1.049-.462l-.739.739c.463.458 1.082.753 1.735.753.544 0 1.087-.201 1.612-.597l.54.538.697-.697-.52-.521c.743-.896 1.157-2.209.119-3.247zm-9.678-7.476c.938 0 1.699.761 1.699 1.699 0 .938-.761 1.699-1.699 1.699-.938 0-1.699-.761-1.699-1.699 0-.938.761-1.699 1.699-1.699z"/></svg>
                </div>
                <?php endif; ?>
      </div>

                
            <div class="box-img_p">
                <?php $chk_postimg = woocommerce_get_product_thumbnail(get_the_ID()); ?>
                <?php if ( $chk_postimg ) : ?>
                <?php echo woocommerce_get_product_thumbnail() ?>
                <?php else: ?>
                <div class="contact-img">
                </div>
                <?php endif; ?>
                <div class="add-box">
                <?php
                if ($product->is_type( 'variable' )){
                  $available_variations = $product->get_available_variations();
                    $stock_count = 0;
                   foreach( $available_variations as $key => $variation ) {
                     $variation_id = $variation['variation_id'];
                     $variation_obj = new WC_Product_variation($variation_id);
                     $stock_qty = $variation_obj->get_stock_quantity(); // Stock qty

                       $stock_count += $stock_qty;
                   }
                   if ($stock_count > 0) {
                     ?>
                     <div class="detail_a">                    
                    <a href="<?php echo get_the_permalink(); ?>" class="btn-detail"><?php esc_html_e( 'รายละเอียดสินค้า', 'asfurniturehome' ); ?></a>
                    </div>
                     <?php
                   }
                   else {
                     ?>
                      <div class="detail_w">
                        <span class="out-stock_p"><?php esc_html_e( 'สินค้าหมด', 'asfurniturehome' ); ?></span>
                      </div> 
                     <?php
                   }
                }
                else {
                  if($product->get_stock_quantity()>0) {
                     ?>
                     <div class="detail_w">
                        <a href="<?php echo $product->add_to_cart_url() ?>" value="<?php echo esc_attr( $product->get_id() ); ?>" class="ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku="<?php echo esc_attr($sku) ?>" aria-label="Add “<?php the_title_attribute() ?>” to your cart">
                        <?php esc_html_e( 'หยิบใส่รถเข็น', 'asfurniturehome' ); ?></a>
                    </div>
                     
                     <?php
                  } else {
                      ?>
                      <div class="detail_w">
                        <span class="out-stock_p"><?php esc_html_e( 'สินค้าหมด', 'asfurniturehome' ); ?></span>
                      </div>                  
                      <?php
                  }
                  ?>
                   <div class="detail_a">                    
                    <a href="<?php echo get_the_permalink(); ?>" class="btn-detail"><?php esc_html_e( 'รายละเอียดสินค้า', 'asfurniturehome' ); ?></a>
                    </div>
                  <?php
                }
				            ?>
                </div>
            </div>
    </div>
    <div class="box-detail">
        <div class="box-title">         
                <h3 class="post-title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>         

            <div class="promotion-box">


            <ul>
            <?php  if( $new_product == 'on' ): ?>
                    <li id="new_product" class="list-new_product"><?php esc_html_e( 'สินค้าใหม่', 'asfurniturehome' ); ?></li>
              <?php endif; ?>
              <?php  if( $promotion_p == 'on' ): ?>
                    <li id="promotion_p" class="list-promotion"><?php esc_html_e( 'โปรโมชั่น', 'asfurniturehome' ); ?></li>

              <?php endif; ?>
            <?php
            // echo $max_percentage;new_product          
                 if ( $max_percentage > 0 ) echo "<li id='sale-promotion' class='list-sale'>ลด -" . round($max_percentage) . "%</li>";
                 ?>


            </ul>
            </div>
            <div class="excerpt_cc">
                <div class="text-excerpt">
                    <p><?php esc_html_e( 'กxยxส: 120x60x123 ซม.', 'asfurniturehome' ); ?></p>
                </div>
                <?php if($excerpt_product_s): ?>
                <div class="text-excerpt">
                    <p><?php echo $excerpt_product_s; ?></p>
                </div>
                <?php else: ?>
                <div class="text-excerpt">
                    <p><?php esc_html_e( 'สินค้าคุณภาพจาก DeskSpace', 'asfurniturehome' ); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div class="main-box_bar">
                <div class="price-box">
                <?php
                    global $product;
                                    if ($product->is_type( 'simple' )) {
                                        echo $product->get_price_html();
                        }
                    if($product->product_type=='variable') {
                                            echo $product->get_price_html();
                    }
                    ?>


                </div>
                <div class="btn-add_bar">
                <?php
                if ($product->is_type( 'variable' )){
                  $available_variations = $product->get_available_variations();
                    $stock_count = 0;
                   foreach( $available_variations as $key => $variation ) {
                     $variation_id = $variation['variation_id'];
                     $variation_obj = new WC_Product_variation($variation_id);
                     $stock_qty = $variation_obj->get_stock_quantity(); // Stock qty

                       $stock_count += $stock_qty;
                   }
                   if ($stock_count > 0) {
                     ?>
                     <div class="detail_a">                    
                    <a href="<?php echo get_the_permalink(); ?>" class="btn-detail"><?php esc_html_e( 'รายละเอียดสินค้า', 'asfurniturehome' ); ?></a>
                    </div>
                     <?php
                   }
                   else {
                     ?>
                      <div class="detail_w">
                        <span class="out-stock_p"><?php esc_html_e( 'สินค้าหมด', 'asfurniturehome' ); ?></span>
                      </div> 
                     <?php
                   }
                }
                else {
                  if($product->get_stock_quantity()>0) {
                     ?>
                     <div class="detail_w">
                        <a href="<?php echo $product->add_to_cart_url() ?>" value="<?php echo esc_attr( $product->get_id() ); ?>" class="ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku="<?php echo esc_attr($sku) ?>" aria-label="Add “<?php the_title_attribute() ?>” to your cart">
                        <?php esc_html_e( 'หยิบใส่รถเข็น', 'asfurniturehome' ); ?></a>
                    </div>
                     
                     <?php
                  } else {
                      ?>
                      <div class="detail_w">
                        <span class="out-stock_p"><?php esc_html_e( 'สินค้าหมด', 'asfurniturehome' ); ?></span>
                      </div>                  
                      <?php
                  }
                  ?>
                  <?php
                }
				            ?>
                </div>
            </div>
        </div>
    </div>
    </div>

</article>