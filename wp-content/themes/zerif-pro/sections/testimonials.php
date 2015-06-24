<?php		global $wp_customize;					$zerif_testimonials_show = get_theme_mod('zerif_testimonials_show');						if( isset($zerif_testimonials_show) && $zerif_testimonials_show != 1 ):					echo '<section class="testimonial" id="testimonials">';				elseif ( isset( $wp_customize ) ):					echo '<section class="testimonial zerif_hidden_if_not_customizer" id="testimonials">';				endif;
		if( (isset($zerif_testimonials_show) && $zerif_testimonials_show != 1) || isset( $wp_customize ) ):	
				echo '<div class="container">';
					echo '<div class="section-header">';						/* title */											$zerif_testimonials_title = get_theme_mod('zerif_testimonials_title','Testimonials');												if( !empty($zerif_testimonials_title) ):					
							echo '<h2 class="white-text">'.$zerif_testimonials_title.'</h2>';													elseif ( isset( $wp_customize ) ):													echo '<h2 class="white-text zerif_hidden_if_not_customizer"></h2>';													endif;
						$zerif_testimonials_subtitle = get_theme_mod('zerif_testimonials_subtitle');
						if( !empty($zerif_testimonials_subtitle) ):
							echo '<h6 class="white-text">'.$zerif_testimonials_subtitle.'</h6>';						elseif ( isset( $wp_customize ) ):													echo '<h6 class="white-text zerif_hidden_if_not_customizer"></h6>';							
						endif;
					echo '</div>';
					echo '<div class="row" data-scrollreveal="enter right after 0s over 1s">';
						echo '<div class="col-md-12">';
							echo '<div id="client-feedbacks" class="owl-carousel owl-theme">';									if(is_active_sidebar( 'sidebar-testimonials' )):										dynamic_sidebar( 'sidebar-testimonials' );									else:																			the_widget( 'zerif_testimonial_widget','title=John Dow&text=Add a testimonial widget in the "Widgets: Testimonials section" in Customizer' );										the_widget( 'zerif_testimonial_widget','title=John Dow&text=Add a testimonial widget in the "Widgets: Testimonials section" in Customizer' );										the_widget( 'zerif_testimonial_widget','title=John Dow&text=Add a testimonial widget in the "Widgets: Testimonials section" in Customizer' );																		endif;								
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</section>';		endif;
?>