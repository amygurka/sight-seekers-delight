<?php 	global $wp_customize;
	$zerif_ribbonright_text = get_theme_mod('zerif_ribbonright_text');
	if( !empty($zerif_ribbonright_text) ):
		echo '<section class="purchase-now" id="ribbon_right">';
			echo '<div class="container">';
				echo '<div class="row">';
					echo '<div class="col-md-9" data-scrollreveal="enter left after 0s over 1s">';
						echo '<h3 class="white-text">'.$zerif_ribbonright_text.'</h3>';	
					echo '</div>';
					$zerif_ribbonright_buttonlabel = get_theme_mod('zerif_ribbonright_buttonlabel');
					$zerif_ribbonright_buttonlink = get_theme_mod('zerif_ribbonright_buttonlink');
					if( !empty($zerif_ribbonright_buttonlabel) && !empty($zerif_ribbonright_buttonlink) ):
						echo '<div class="col-md-3" data-scrollreveal="enter right after 0s over 1s">';
							echo '<a href="'.$zerif_ribbonright_buttonlink.'" class="btn btn-primary custom-button red-btn">'.$zerif_ribbonright_buttonlabel.'</a>';
						echo '</div>';					elseif ( isset( $wp_customize ) ):												echo '<div class="col-md-3" data-scrollreveal="enter right after 0s over 1s">';							echo '<a href="" class="btn btn-primary custom-button red-btn zerif_hidden_if_not_customizer"></a>';						echo '</div>';						
					endif;
				echo '</div>';
			echo '</div>';
		echo '</section>';		elseif ( isset( $wp_customize ) ):				echo '<section class="purchase-now zerif_hidden_if_not_customizer" id="ribbon_right">';			echo '<div class="container">';				echo '<div class="row">';					echo '<div class="col-md-9" data-scrollreveal="enter left after 0s over 1s">';						echo '<h3 class="white-text"></h3>';						echo '</div>';					$zerif_ribbonright_buttonlabel = get_theme_mod('zerif_ribbonright_buttonlabel');					$zerif_ribbonright_buttonlink = get_theme_mod('zerif_ribbonright_buttonlink');					if( !empty($zerif_ribbonright_buttonlabel) && !empty($zerif_ribbonright_buttonlink) ):						echo '<div class="col-md-3" data-scrollreveal="enter right after 0s over 1s">';							echo '<a href="'.$zerif_ribbonright_buttonlink.'" class="btn btn-primary custom-button red-btn">'.$zerif_ribbonright_buttonlabel.'</a>';						echo '</div>';					elseif ( isset( $wp_customize ) ):												echo '<div class="col-md-3" data-scrollreveal="enter right after 0s over 1s">';							echo '<a href="" class="btn btn-primary custom-button red-btn zerif_hidden_if_not_customizer"></a>';						echo '</div>';											endif;				echo '</div>';			echo '</div>';		echo '</section>';		
	endif;
?>