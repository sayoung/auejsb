<?php

namespace Drupal\md_new_prestation\Controller;
//require_once  'public_html/modules/md_new_prestation/includes/helpers.inc';

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_product\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class PdfFactureSigne extends ControllerBase {





    /**
     * Generate a PDF for a Commerce Product.
     */
    public function generateFacture($product_id) {
        // Load the product entity.
        $product = Product::load($product_id);

        if (!$product) {
            return new Response('Product not found', 404);
        }

        // Retrieve field values safely.
            $ndeg_dossier = $product->get('field_ndeg_de_dossier')->value ?? 'N/A';
            $situation_projet = $product->get('field_situation_du_projet')->value ?? 'N/A';
            $province = $product->get('field_collectivite_territoriale_')->value ?? 'N/A';
            $commune = $product->get('field_commune')->value ?? 'N/A';
            $references_foncieres = $product->get('field_references_foncieres')->value ?? 'N/A';
            $nom_maitre_ouvrage = $product->get('field_nom_du_petitionnaire')->value ?? 'N/A';
            $adresse_maitre_ouvrage = $product->get('field_adresse_maitres')->value ?? 'N/A';
            $cin_maitre_ouvrage = $product->get('field_cin')->value ?? 'N/A';
            $nom_architecte = $product->get('field_architecte_ou_igt')->value ?? 'N/A'; // Default value as per the PDF
            $date_validation = $product->get('field_date_validation')->value ?? 'N/A'; 
            $facture_n = $product->get('field_ndeg_facture')->value ?? 'N/A'; 
            $facture_date = $product->get('field_date_de_facture')->value ?? 'N/A'; 
            $nature_projet = $product->get('field_nature_du_projet_2')->value ?? 'N/A';
            $type = $product->get('field_type_2')->value ?? 'N/A';
            $consistance = $product->get('field_consistance_du_projet')->value ?? 'N/A';
        
        
        	$nombre_de_niveaux = $product->get('field_nombre_de_niveaux')->value ?? 'N/A';
        	$surface_terrain = $product->get('field_surface_de_terrain')->value ?? 'N/A';
        	$metrage_du_projet = $product->get('field_metrage_du_projet')->value ?? 'N/A';
        	$surface_voirie_espace_vert_parking_equipements_a_ceder = $product->get('field_surface_voirie')->value ?? 'N/A';
        	$surface_totale_des_parcelles_cessibles = $product->get('field_surface_totale')->value ?? 'N/A';
        	$surface_planchers_couverts_supplementaires_apres_modification = $product->get('field_supp_apres_modi')->value ?? 'N/A';
            $surfaces_cessibles_apres_modification = $product->get('field_surf_cessible_modi')->value ?? 'N/A';
            $surface_planchers_surfaces_cessibles_gph_ = $product->get('field_surf_planchers_gph')->value ?? 'N/A';
			$montant_investissement = $product->get('field_mnt_investissement_mdhs')->value ?? 'N/A';
			$mode_reglement = $product->get('field_reglement')->value ?? 'N/A';
			
			// Date de coupure : 7 janvier 2026
            $cutoff_date = strtotime('2026-01-07');
            $created_date = $product->getCreatedTime();
            
            if ($created_date >= $cutoff_date) {
                // Produits créés APRÈS le 07/01/2026 : utiliser uniquement field_autorisation_nbr
                $sid = $product->get('field_autorisation_nbr')->value ?? 'N/A';
            } else {
                // Produits créés AVANT le 07/01/2026 : ancienne logique avec fallback
                if ($product->get('field_autorisation_nbr')->isEmpty() || empty($product->get('field_autorisation_nbr')->value)) {
                    $sid = $product->get('field_sid')->value ?? 'N/A';
                } else {
                    $sid = $product->get('field_autorisation_nbr')->value ?? 'N/A';
                }
            }
			
			
			$prix_en_lettre = $product->get('field_prix_en_lettre')->value ?? 'N/A';
            
             
                if ($nature_projet === "Morcellement (hors périmètre urbain)") { 
                    $price_in_table = ""; // Si c'est un Morcellement, rien n'est affiché
                } else { 
                    $price_in_table = "3.6"; // Sinon, afficher 3.6
                } 
                if ($nature_projet === "Morcellement (hors périmètre urbain)") { 
                    $lib_in_table = "prix de tranche"; // Si c'est un Morcellement, rien n'est affiché
                } else { 
                    $lib_in_table = "Prix unitaire en DH TTC"; // Sinon, afficher 3.6
                }
            
            
            if ($type === "Modification") {
            $metrage_du_projet = $surface_planchers_couverts_supplementaires_apres_modification;
            }else {
                $metrage_du_projet = $metrage_du_projet ;
            }
            
            function calculateTotal($metrage_du_projet, $nature_projet) {
                    $total = 0;
                
                    if ($nature_projet === "Morcellement (hors périmètre urbain)") {
                        if ($metrage_du_projet < 10000) {
                            return [
                                'total' => null,
                                'message' => "Veuillez vérifier SVP les superficies déclarées"
                            ];
                        } elseif ($metrage_du_projet == 10000) {
                            $total = 2500 * 1.2;
                        } elseif ($metrage_du_projet > 10000 && $metrage_du_projet < 20000) {
                            $total = 4500 * 1.2;
                        } elseif ($metrage_du_projet == 20000) {
                            $total = 5000 * 1.2;
                        } elseif ($metrage_du_projet > 20000 && $metrage_du_projet < 30000) {
                            $total = 7000 * 1.2;
                        } elseif ($metrage_du_projet == 30000) {
                            $total = 7000 * 1.2;
                        } elseif ($metrage_du_projet > 30000 && $metrage_du_projet < 40000) {
                            $total = 8500 * 1.2;
                        } elseif ($metrage_du_projet == 40000) {
                            $total = 9000 * 1.2;
                        } elseif ($metrage_du_projet == 50000) {
                            $total = 10500 * 1.2;
                        } elseif ($metrage_du_projet > 50000) {
                            // Calcul du nombre d'hectares supplémentaires au-dessus de 50 000
                            $additional_hectares = floor(($metrage_du_projet - 50000) / 10000);
                            $additional_cost = 10500 + ($additional_hectares * 1500);
                
                            // Vérification du reste pour ajouter 1200
                            if (($metrage_du_projet - 50000) % 10000 > 0) {
                                $additional_cost += 1200;
                            }
                
                            $total = $additional_cost * 1.2;
                        }
                    } else {
                        $total = $metrage_du_projet * 3.6;
                    }
                
                    return [
                        'total' => $total,
                        'message' => null
                    ];
                }
                  // Exemple de type de projet
                
                $price = calculateTotal($metrage_du_projet, $nature_projet);
            
                if ($nature_projet === "Morcellement (hors périmètre urbain)") { 
                    $price_in_table = $price['total']; // Si c'est un Morcellement, rien n'est affiché

                } else { 
                    $price_in_table = "3"; // Sinon, afficher 3.6
                } 
                if ($nature_projet === "Morcellement (hors périmètre urbain)") { 
                    $lib_in_table = "Montant Total"; // Si c'est un Morcellement, rien n'est affiché
                } else { 
                    $lib_in_table = "Prix unitaire en DH TTC"; // Sinon, afficher 3.6
                }
			
        
        
        
        
        
        $date_declaration = $product->get('created')->value 
    ? date('Y', $product->get('created')->value)
    : 'N/A';
 // Current date

$date_validation_display = '';

if ($product->hasField('field_date_validation')) {
  $value = $product->get('field_date_validation')->value;
  if (!empty($value)) {
    // Formatage optionnel de la date (ex. : "10/04/2025")
    $date_formatted = (new \DateTime($value))->format('d/m/Y');
    $date_validation_display = 'Date : ' . $date_formatted;
  }
}

if ($product->hasField('field_date_de_facture')) {
  $value = $product->get('field_date_de_facture')->value;
  if (!empty($value)) {
    // Formatage optionnel de la date (ex. : "10/04/2025")
    $date_formatted = (new \DateTime($value))->format('d/m/Y');
    $date_facture_display = 'Date : ' . $date_formatted;
  }
}


if ($product->hasField('field_date_validation')) {
  $value = $product->get('field_date_validation')->value;
  if (!empty($value)) {
    // Formatage optionnel de la date (ex. : "10/04/2025")
    $date_formatted = (new \DateTime($value))->format('d/m/Y');
    $date_validation_display = 'Date : ' . $date_formatted;
  }
}



                if ($nature_projet === "Morcellement (hors périmètre urbain)") { 
                   
                    $ttc_1 = $price_in_table;
                    $taux_tva = 0.20;
                    $ht = $ttc_1 / (1 + $taux_tva);
                    $tva = $ht * 0.20;
                    $ttc = $ht * 1.2 ;
                    $prix_u = $ht / $metrage_du_projet;
                } else { 
                    $ht = $metrage_du_projet * $price_in_table;
                    $tva = $ht * 0.20;
                    $ttc = $ht * 1.2 ;
                } 


//$en_lettres = number_to_words_money($ttc);
$en_lettres = $this->numberToWordsMoney($ttc);
    $all_block ='
	                <tr style="width:100%; border-collapse: collapse;">
						<th colspan="6" style="border: 1px solid black; text-align: left; font-size:10px;">Désignation</th>
						<th colspan="3" style="border: 1px solid black; text-align: left; font-size:10px;">Superficie Totale (en m²)</th>
						<th colspan="3" style="border: 1px solid black; text-align: left; font-size:10px;">P. U (en Dh)</th>
						<th colspan="3" style="border: 1px solid black; text-align: left; font-size:10px;">Prix H.T</th>
					</tr>
					<tr>
						<td colspan="6" style="border: 1px solid black; font-size:11px;">
							<span class="lotissement-bold">Frais de services rendus au profit de :</span><br>
							'. htmlspecialchars($nom_maitre_ouvrage) .'<br>
							'. htmlspecialchars($situation_projet) .'<br>
							'. htmlspecialchars($province) .' 
							'. htmlspecialchars(' - ' . $commune) .'<br>
							'. htmlspecialchars($nature_projet) .'<br>
							'. htmlspecialchars($consistance) .'<br>
							Dossier N° : '. htmlspecialchars($ndeg_dossier) .'<br>
							'. htmlspecialchars('MO : ' .$nom_architecte) .'<br>
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
							'. number_format($metrage_du_projet, 2, ',', ' ') .' 
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
							'. $price_in_table .' 
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
							'. number_format($metrage_du_projet * $price_in_table, 2, ',', ' ') .' 
						</td>
					</tr>
					<tr>
						<td colspan="6" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
											  Total H.T                                                                     
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
								'. number_format($metrage_du_projet * $price_in_table, 2, ',', ' ') .' 
						</td>
					</tr>
					<tr>
						<td colspan="6" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
											  TVA 20%                                                                     
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
								 ' . number_format($tva, 2, ',', ' ') . ' MAD' .' 
						</td>
					</tr>
					<tr>
						<td colspan="6" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
											  Total T.T.C                                                                     
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
								'. number_format($ttc, 2, ',', ' ') .' 
						</td>
					</tr>		
';
$morcellement_block = '
                    <tr style="width:100%; border-collapse: collapse;">
						<th colspan="6" style="border: 1px solid black; text-align: left; font-size:10px;">Désignation</th>
						<th colspan="6" style="border: 1px solid black; text-align: left; font-size:10px;">Superficie Totale (en m²)</th>
						<th colspan="3" style="border: 1px solid black; text-align: left; font-size:10px;">Prix H.T</th>
					</tr>
					<tr>
						<td colspan="6" style="border: 1px solid black; font-size:10px;">
							<span class="lotissement-bold">Frais de services rendus au profit de :</span><br>
							'. htmlspecialchars($nom_maitre_ouvrage) .'<br>
							'. htmlspecialchars($nature_projet) .'<br>
							'. htmlspecialchars($situation_projet) .'<br>
								'. htmlspecialchars($nom_architecte) .'<br> 
							Dossier N° : '. htmlspecialchars($ndeg_dossier) .' 
						</td>
						<td colspan="6" style="border: 1px solid black; font-size:10px;">
							'. number_format($metrage_du_projet, 2, ',', ' ') .' 
						</td>

						<td colspan="3" style="border: 1px solid black; font-size:10px;">
							'. number_format($ht, 2, ',', ' ') .'  
						</td>
					</tr>
					<tr>
						<td colspan="6" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
											  Total H.T                                                                     
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
								'. number_format($ht, 2, ',', ' ') .'
						</td>
					</tr>
					<tr>
						<td colspan="6" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
											  TVA 20%                                                                     
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
								 ' . number_format($tva, 2, ',', ' ') . ' MAD' .' 
						</td>
					</tr>
					<tr>
						<td colspan="6" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 0px solid transparent; font-size:10px;">
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
											  Total T.T.C                                                                     
						</td>
						<td colspan="3" style="border: 1px solid black; font-size:10px;">
								'. number_format($ttc, 2, ',', ' ') .' 
						</td>
					</tr>		
';

$project_block = '';
        switch ($nature_projet) {
            case 'Construction':
                $project_block = $all_block;
                break;
            case 'Groupement d’habitation':
                $project_block = $all_block;
                break;
            case 'Aménagement':
                $project_block = $all_block; // Assuming similar to Construction
                break;
            case 'Lotissement':
                $project_block = $all_block;
                break;
            case 'Distraction-fusion (à l’intérieur du périmètre urbain)':
                $project_block = $all_block;
                break;
            case 'Morcellement (hors périmètre urbain)':
                $project_block = $morcellement_block;
                break;
            default:
               $project_block = $all_block;
                break;
        }
$year = (new DateTime($facture_date))->format('Y');

$html = '
<style>        
		@page { margin: 0px;}
        body { margin: 0px;}
        * {font-family: Verdana, Arial, sans-serif;}
        a {color: #fff;decoration: none;}
        table { font-size: x-small;}
		.border {border:1px solid black;}
        tfoot tr td {font-weight: bold;font-size: x-small;}
        .invoice table {margin: 5px;}
        .invoice h3 {margin-left: 15px;}
		.invoice h4 { margin-left: 15px;}
		.footer {background-color: #fff;color: #F00;}
		.footer table {padding: 10px;}
        .information { background-color: #60A7A6;color: #FFF;}
        .information .logo {margin: 5px;}
		.w-100 {width:150px;}
        .pad-50 {padding-left:50;}
		.pad-top-10 {padding-top:10px;}
		.pad-10 {padding:10px;}
		.pad-0 {padding:0;}
		.bg {background-color: #60A7A6;}
		.height-150 {height:150px;}
		.height-130 {height:130px;}
		.text-footer {font-size:10px;}
		.tit-back  {background-color: #60A7A6;}
		table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 10px; margin-left: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        td {font-size:11px;}
        .black {color:000;font-weight: bold;}
		
    </style>
	<div style="width: 100%; overflow: hidden; ">
		
			<table>
				<tbody>
					<tr>
						<td style="border: 0px solid transparent; font-size:10px;with:100%;text-align:center;" >
						<div class="black">Royaume du Maroc</div>
						<div><img alt="" src="/sites/default/files/logo_013.jpg" text-align="center" height:150px;/></div><br/>
						<div class="black">Ministère  de l’Aménagement du Territoire  National de l’Urbanisme</div>
						<div class="black">de l\'Habitat et de la Politique de la Ville</div>
						<div class="black">Agence Urbaine D\'El Jadida-Sidi Bennour</div>
						</td>
						<td align="right" text-align="right" style="border: 0px solid transparent; font-size:10px;with" > &nbsp; </td>
					</tr>
				</tbody>
			</table>	
	</div>
	<br/>
	<div style="width: 80%; overflow: hidden; margin-left:10%;">
    <div class="black" style="width:100%;text-align:right;" >El Jadida le :  ' . htmlspecialchars($date_facture_display) . ' 	</div>
			<div class="">
					<div style="margin-left:15px;" class="tit-back" style="width:100%;text-align:center;">
					<span class="lotissement-bold" >Facture N° : ' . $facture_n . '-RSR-'. $year .'  </span>
				</div>
				<table width="100%">
						<tr>
						<th align="left" style="width: 40%;"> CLIENT </th>
						<th align="left" style="width: 40%;"> autorisation  N° </th>
						<th align="left" style="width: 20%;"> DATE </th>
						</tr>
						<tr>
							<td align="left" style="width: 40%;"> ' . $nom_maitre_ouvrage . '</td> 
							<td align="center"  style="width: 40%;" >
								<h3>' . $sid . '   <h3><br/> </td>
							<td align="right" style="width: 20%;"> ' . htmlspecialchars($date_validation_display) . ' </td>
						</tr>
				</table>
			</div>


			<br/>
		
		<div style="width: 100%; overflow: hidden;">
			<!-- Left Section (65%) -->
			<div style="width: 100%; float: left;">
				<table style="width:100%; border-collapse: collapse;">
                          '.  $project_block .'
						
				</table>
                <div style="margin-left:15px;" style="width:100%;text-align:left;">
					<span class="lotissement-bold">La présente facture est arrêtée à la somme de :  '. $en_lettres .' </span>
				</div>
				<br/><br/>
				<div style="margin-left:15px;" style="width:100%;text-align:left;">
				<table width="100%">
				    <tr>
						<th style="border: 0px solid transparent; font-size:10px;background-color:transparent;width: 40%;" align="left" > <span class="lotissement-bold">Mode de Règlement : '. $mode_reglement .' </span> </th>
						<th style="border: 0px solid transparent; font-size:10px;background-color:transparent;width: 60%;" align="left"> <img alt="" src="/sites/default/files/photos/auejsb_signe.jpg" text-align="center" height:100px;/> </th>
					</tr>
				
				</table>
				</div>
				<br/><br/>
				
				 <div style="margin-left:15px;" style="width:100%;text-align:center;">
					<span class="lotissement-bold">Nous sommes à votre disposition pour tout complément d’informations. </span><br/>
					 <span class="lotissement-bold">Nous vous prions d’agréer, cher client, nos sincères  salutations</span>
				</div>
				<br/><br/><br/><br/>
				
			</div>
		</div>
	</div>

</body>
</html>';
$footer = '
<table width="100%">
	<tr>
		<td align="center" style="width: 80%;border: 0px solid transparent; font-size:10px;"> 
		    <div style="margin-left:15px;" style="width:100%;text-align:center;">
				<span class="text-footer">Avenue Bir Anzarane Résidence  Al manar Imm Agence Urbaine El Jadida -Sidi Bennour B.P 3238-El Jadida-24000,<br/>               
				ICE:001811377000027; I.F : 40177482, RIB: 310 170 100 412 470 088 950 118, trésorerie Provincial d\'El Jadida <br/>              
				Tél: 05 23 37 00 61/Fax: 05 23 37 00 63 E-mail: contact@auejsb.ma  </span>
			</div>
		</td>
		<td align="right" style="width: 20%;border: 0px solid transparent; font-size:10px;"> <img alt="" src="/sites/default/files/logo_012.jpg" text-align="center" height:150px;/> </td>
	</tr>
	
</table>

';
// Generate PDF.
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => 'sites/default/files/tmp',
            'format' => 'A4',
            'default_font' => 'Arial'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooter($footer);
        $mpdf->showImageErrors = true;

        // Ensure no extra output before PDF generation.
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Force PDF download.
        $mpdf->Output($facture_n . '-RSR-'. $year . '-facture.pdf', 'D');
    }
private function numberToWordsMoney($number) {
      $f = new \NumberFormatter("fr", \NumberFormatter::SPELLOUT);
    
      $parts = explode('.', number_format($number, 2, '.', ''));
    
      $dirhams = (int)$parts[0];
      $centimes = (int)$parts[1];
    
      $words = ucfirst($f->format($dirhams)) . ' dirham' . ($dirhams > 1 ? 's' : '');
    
      if ($centimes > 0) {
        $words .= ' et ' . $f->format($centimes) . ' centime' . ($centimes > 1 ? 's' : '');
      }
    
      return $words;
}

}