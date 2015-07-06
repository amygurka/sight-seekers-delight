<?php

	global $wp_customize;
	
	$zerif_aboutus_show = get_theme_mod('zerif_aboutus_show');
				
	if( isset($zerif_aboutus_show) && $zerif_aboutus_show != 1 ):
	
		echo '<section class="about-us" id="aboutus">';
	
	elseif( isset( $wp_customize ) ):
	
		echo '<section class="about-us zerif_hidden_if_not_customizer" id="aboutus">';
	
	endif;
	
	if(( isset($zerif_aboutus_show) && $zerif_aboutus_show != 1 ) || isset( $wp_customize ) ):

			echo '<div class="container">';
		
				/* SECTION HEADER */

				echo '<div class="section-header">';

					/* title */
					
					$zerif_aboutus_title = get_theme_mod('zerif_aboutus_title','About US');
					
					if( !empty($zerif_aboutus_title) ):
					
						echo '<h2 class="white-text">'.$zerif_aboutus_title.'</h2>';
						
					elseif ( isset( $wp_customize ) ):	
						
						echo '<h2 class="white-text zerif_hidden_if_not_customizer"></h2>';
						
					endif;
					
					/* subtitle */

					$zerif_aboutus_subtitle = get_theme_mod('zerif_aboutus_subtitle','Add a subtitle in Customizer, "About us section"');


					if( !empty($zerif_aboutus_subtitle) ):

						echo '<h6 class="white-text">'.$zerif_aboutus_subtitle.'</h6>';

					elseif ( isset( $wp_customize ) ):
					
						echo '<h6 class="white-text zerif_hidden_if_not_customizer">'.$zerif_aboutus_subtitle.'</h6>';

					endif;

				echo '</div>';

				/* 3 COLUMNS OF ABOUT US */

				echo '<div class="row">';
				
					/* COLUMN 1 - BIG MESSAGE ABOUT THE COMPANY */
					$zerif_aboutus_biglefttitle = get_theme_mod('zerif_aboutus_biglefttitle','Title');
					
					$zerif_aboutus_text = get_theme_mod('zerif_aboutus_text','You can add here a large piece of text. For that, please go in the Admin Area, Customizer, "About us section"');
					
					$zerif_aboutus_feature1_title = get_theme_mod('zerif_aboutus_feature1_title','Feature');
					$zerif_aboutus_feature1_text = get_theme_mod('zerif_aboutus_feature1_text');

					switch (
						(empty($zerif_aboutus_biglefttitle) ? 0 : 1)
						+ (empty($zerif_aboutus_text) ? 0 : 1)
						+ (empty($zerif_aboutus_feature1_title) && empty($zerif_aboutus_feature1_text) ? 0 : 1)
					) {
						case 3:
							$colCount = 4;
							break;
						case 2:
							$colCount = 6;
							break;
						default:
							$colCount = 12;
					}


					if( !empty($zerif_aboutus_biglefttitle) ):


						echo '<div class="col-lg-' . $colCount . ' col-md-' . $colCount . ' column">';


							echo '<div class="big-intro" data-scrollreveal="enter left after 0s over 1s">'.__($zerif_aboutus_biglefttitle,'zerif').'</div>';


						echo '</div>';


					endif;


					if( !empty($zerif_aboutus_text) ):

						echo '<div class="col-lg-' . $colCount . ' col-md-' . $colCount . ' column zerif_about_us_center" data-scrollreveal="enter bottom after 0s over 1s">';


								echo '<p>';


									echo __($zerif_aboutus_text,'zerif');


								echo '</p>';


						echo '</div>';


					endif;

					/* COLUMN 1 - SKILSS */

					echo '<div class="col-lg-'.$colCount.' col-md-'.$colCount.' column">';

						echo '<ul class="skills" data-scrollreveal="enter right after 0s over 1s">';

						/* SKILL ONE */

						echo '<li class="skill skill_1">';

							$zerif_aboutus_feature1_nr = get_theme_mod('zerif_aboutus_feature1_nr','50');


							if( !empty($zerif_aboutus_feature1_nr) ):


								echo '<div class="skill-count">';


									echo '<input type="text" value="'.$zerif_aboutus_feature1_nr.'" data-thickness=".2" class="skill1">';


								echo '</div>';


							endif;
							
							
							
							if( !empty($zerif_aboutus_feature1_title) ):
							
								echo '<h6>'.$zerif_aboutus_feature1_title.'</h6>';
								
							elseif ( isset( $wp_customize ) ):

								echo '<h6 class="zerif_hidden_if_not_customizer"></h6>';
								
							endif;
							
							if( !empty($zerif_aboutus_feature1_text) ):
							
								echo '<p>'.$zerif_aboutus_feature1_text.'</p>';
								
							elseif ( isset( $wp_customize ) ):

								echo '<p class="zerif_hidden_if_not_customizer"></p>';	
								
							endif;

						echo '</li>';

						/* SKILL TWO */

						echo '<li class="skill skill_2">';
						
							$zerif_aboutus_feature2_nr = get_theme_mod('zerif_aboutus_feature2_nr','70');


							if( !empty($zerif_aboutus_feature2_nr) ):


								echo '<div class="skill-count">';


									echo '<input type="text" value="'.$zerif_aboutus_feature2_nr.'" data-thickness=".2" class="skill2">';


								echo '</div>';


							endif;
							
							$zerif_aboutus_feature2_title = get_theme_mod('zerif_aboutus_feature2_title','Feature');
							$zerif_aboutus_feature2_text = get_theme_mod('zerif_aboutus_feature2_text');
							
							if( !empty($zerif_aboutus_feature2_title) ):
							
								echo '<h6>'.$zerif_aboutus_feature2_title.'</h6>';
								
							elseif ( isset( $wp_customize ) ):
							
								echo '<h6 class="zerif_hidden_if_not_customizer"></h6>';
								
							endif;
							
							if( !empty($zerif_aboutus_feature2_text) ):
							
								echo '<p>'.$zerif_aboutus_feature2_text.'</p>';
								
							elseif ( isset( $wp_customize ) ):

								echo '<p class="zerif_hidden_if_not_customizer"></p>';	
								
							endif;

						echo '</li>';

						/* SKILL THREE */


						echo '<li class="skill skill_3">';

							$zerif_aboutus_feature3_nr = get_theme_mod('zerif_aboutus_feature3_nr','100');


							if( !empty($zerif_aboutus_feature3_nr) ):


								echo '<div class="skill-count">';


									echo '<input type="text" value="'.$zerif_aboutus_feature3_nr.'" data-thickness=".2" class="skill3">';


								echo '</div>';


							endif;
							
							$zerif_aboutus_feature3_title = get_theme_mod('zerif_aboutus_feature3_title','Feature');
							$zerif_aboutus_feature3_text = get_theme_mod('zerif_aboutus_feature3_text');
							
							if( !empty($zerif_aboutus_feature3_title) ):
							
								echo '<h6>'.$zerif_aboutus_feature3_title.'</h6>';
								
							elseif ( isset( $wp_customize ) ):
							
								echo '<h6 class="zerif_hidden_if_not_customizer"></h6>';
								
							endif;
							
							if( !empty($zerif_aboutus_feature3_text) ):
							
								echo '<p>'.$zerif_aboutus_feature3_text.'</p>';
								
							elseif ( isset( $wp_customize ) ):

								echo '<p class="zerif_hidden_if_not_customizer"></p>';	
								
							endif;

						echo '</li>';


						/* SKILL FOUR */


						echo '<li class="skill skill_4">';

							$zerif_aboutus_feature4_nr = get_theme_mod('zerif_aboutus_feature4_nr','10');


							if( !empty($zerif_aboutus_feature4_nr) ):


								echo '<div class="skill-count">';


									echo '<input type="text" value="'.$zerif_aboutus_feature4_nr.'" data-thickness=".2" class="skill4">';


								echo '</div>';


							endif;
							
							$zerif_aboutus_feature4_title = get_theme_mod('zerif_aboutus_feature4_title','Feature');
							$zerif_aboutus_feature4_text = get_theme_mod('zerif_aboutus_feature4_text');
							
							if( !empty($zerif_aboutus_feature4_title) ):
							
								echo '<h6>'.$zerif_aboutus_feature4_title.'</h6>';
								
							elseif ( isset( $wp_customize ) ):
							
								echo '<h6 class="zerif_hidden_if_not_customizer"></h6>';
								
							endif;
							
							if( !empty($zerif_aboutus_feature4_text) ):
							
								echo '<p>'.$zerif_aboutus_feature4_text.'</p>';
								
							elseif ( isset( $wp_customize ) ):

								echo '<p class="zerif_hidden_if_not_customizer"></p>';	
								
							endif;

						echo '</li>';

					echo '</ul>';


				echo '</div> <!-- / END SKILLS COLUMN-->';


			echo '</div> <!-- / END 3 COLUMNS OF ABOUT US-->';

			/* CLIENTS */
		
			if(is_active_sidebar( 'sidebar-aboutus' )):
			
				$zerif_aboutus_clients_title_text = get_theme_mod('zerif_aboutus_clients_title_text','OUR HAPPY CLIENTS');
			
				echo '<div class="our-clients">';
				
					if( !empty($zerif_aboutus_clients_title_text) ):
				
						echo '<h5><span class="section-footer-title">'.$zerif_aboutus_clients_title_text.'</span></h5>';
						
					else:
					
						echo '<h5><span class="section-footer-title">'.__('OUR HAPPY CLIENTS','zerif').'</span></h5>';

					endif;
					
				echo '</div>';
				
				echo '<div class="client-list">';
					echo '<div data-scrollreveal="enter right move 60px after 0.00s over 2.5s">';
					dynamic_sidebar( 'sidebar-aboutus' );
					echo '</div>';
				echo '</div> ';
			endif;

		echo '</div> <!-- / END CONTAINER -->';

	echo '</section> <!-- END ABOUT US SECTION -->';
	
	endif;