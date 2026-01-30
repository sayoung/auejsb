<?php

namespace Drupal\md_new_prestation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_product\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class PdfAutoController extends ControllerBase {

    /**
     * Generate a PDF for a Commerce Product.
     */
    public function generateProductPdf($product_id) {
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
        
        
            $nature_projet = $product->get('field_nature_du_projet_2')->value ?? 'N/A';
            $type = $product->get('field_type_2')->value ?? 'N/A';
        
        
        	$nombre_de_niveaux = $product->get('field_nombre_de_niveaux')->value ?? 'N/A';
        	$surface_terrain = $product->get('field_surface_de_terrain')->value ?? 'N/A';
        	$metrage_du_projet = $product->get('field_metrage_du_projet')->value ?? 'N/A';
        	$surface_voirie_espace_vert_parking_equipements_a_ceder = $product->get('field_surface_voirie')->value ?? 'N/A';
        	$surface_totale_des_parcelles_cessibles = $product->get('field_surface_totale')->value ?? 'N/A';
        	$surface_planchers_couverts_supplementaires_apres_modification = $product->get('field_supp_apres_modi')->value ?? 'N/A';
            $surfaces_cessibles_apres_modification = $product->get('field_surf_cessible_modi')->value ?? 'N/A';
            $surface_planchers_surfaces_cessibles_gph_ = $product->get('field_surf_planchers_gph')->value ?? 'N/A';
			$montant_investissement = $product->get('field_mnt_investissement_mdhs')->value ?? 'N/A';
			
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
                        } else if ($metrage_du_projet > 40000 && $metrage_du_projet < 50000) {
                            $total = 10500 * 1.2;
                        } else if ($metrage_du_projet == 50000) {
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
                        'total' => round($total, 2),
                        'message' => null
                    ];
                }
                  // Exemple de type de projet
                
                $price = calculateTotal($metrage_du_projet, $nature_projet);
            
                if ($nature_projet === "Morcellement (hors périmètre urbain)") { 
                    $price_in_table = $price[total]; // Si c'est un Morcellement, rien n'est affiché
                } else { 
                    $price_in_table = "3.6"; // Sinon, afficher 3.6
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
		.tit-back  {background-color: #60A7A6;}
		            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 10px; margin-left: 15px; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            td {font-size:11px;}
		
    </style>
	
<body width="100%" >

    <div class="">
    
        <table>
        	<tbody>
        		<tr>
        			<td><img alt="" src="/sites/default/files/logo-auto.png" text-align="left" /></td>
        			<td width="20%">&nbsp;</td>
        			<td align="right" text-align="right"> ' . htmlspecialchars($date_validation_display) . '</td>
        		</tr>
        	</tbody>
        </table>
        <table width="100%">
            <tr>
                <td align="left" style="width: 20%;"> </td>
                <td align="center"  style="width: 60%;" >
                    <h3>AUTORISATION DE VERSEMENT N° : ' . $sid . '   <h3><br/>
                    </pre> <h3>à remettre aux point de paiements<h3>
                </td>
                <td align="right" style="width: 20%;"></td>
            </tr>
        </table>
    </div>


    <br/>

    <div style="margin-left:15px;">
        <span ><strong>Réf:</strong> Décision n°48/2023 du 26/06/2023 </span>
	</div>
    <div style="margin-left:15px;" class="tit-back" style="width:54%;text-align:center;">
        <span class="lotissement-bold">CLIENT </span>
    </div>
    <br/>
    
<div style="width: 100%; overflow: hidden;">
    <!-- Left Section (65%) -->
    <div style="width: 55%; float: left;">
        <table style="width:100%; border-collapse: collapse;">
            <tr style="width:100%; border-collapse: collapse;">
                <th colspan="3" style="border: 1px solid black; text-align: left;font-size:10px;">CIN</th>
                <th colspan="6" style="border: 1px solid black; text-align: left;font-size:10px;">Nom et Prénom ou Raison Sociale</th>
                <th colspan="4" style="border: 1px solid black; text-align: left;font-size:10px;">Situation du projet</th>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid black;">'. $cin_maitre_ouvrage .' </td>
                <td colspan="6" style="border: 1px solid black;"> '. $nom_maitre_ouvrage .'</td>
                <td colspan="4" style="border: 1px solid black;"> '. $situation_projet .'  </td>
            </tr>
        </table>
        
        <div style="margin-left:15px;" class="tit-back" style="width:100%;text-align:center;">
            <span class="lotissement-bold">PRODUIT </span>
        </div>
        
                <table style="width:100%; border-collapse: collapse;">
            <tr style="width:100%; border-collapse: collapse;">
                <th colspan="3" style="border: 1px solid black; text-align: left;font-size:10px;">N° de dossier sur rokhas</th>
                <th colspan="4" style="border: 1px solid black; text-align: left;font-size:10px;">Nature du projet</th>
                <th colspan="2" style="border: 1px solid black; text-align: left;font-size:10px;">Situation du projet</th>
                <th colspan="2" style="border: 1px solid black; text-align: left;font-size:10px;">Total en m²</th>
                <th colspan="2" style="border: 1px solid black; text-align: left;font-size:10px;"> '. $lib_in_table .' </th>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid black;">'. $ndeg_dossier .' </td>
                <td colspan="4" style="border: 1px solid black;"> '. $nature_projet .'</td>
                <td colspan="2" style="border: 1px solid black;"> '. $situation_projet .'  </td>
                <td colspan="2" style="border: 1px solid black;"> '. $metrage_du_projet .'  </td>
                <td colspan="2" style="border: 1px solid black;"> '. $price_in_table .' </td>
            </tr>
        </table>
        
    </div>

    <!-- Right Section (35%) -->
    <div style="width: 44%; float: right;">
	<div class="border pad-10" >
	<div align="left"  font-size="22px" >
    <strong>RIB N°: </strong> 310 170 100 412 470 088 950 118
    </div> 
	<div  align="left" font-size="22px" >
          <strong>Cadre réservé à la trésorerie</strong>
    </div> 
	<div align="left" font-size="22px" >
          Montant :<strong> ' . $price['total'] . '</strong>
    </div>
<div align="left" font-size="22px" >
          Réglé le : 
    </div>	
	<div width="100%" align="left" font-size="22px" >
          <strong  class="w-100 pad-0" >En espèce :  </strong><input class="pad-50" type="checkbox" align="left" >
    </div>
	<div width="100%" align="left"  font-size="22px" >
        <strong  class="w-100 pad-0" > Par virement : </strong> <input class="pad-50" type="checkbox" align="left" >
    </div>
	<div width="100%" align="left"  font-size="22px" >
          <strong>Numéro Banque</strong>: .......................................
    </div>
	<div width="100%" align="left"  font-size="22px" >
          .......................................................................
    </div>
	<div width="100%" align="left"  font-size="22px" >
          <strong>Reçu N° </strong>: .............................................................
    </div>
	</div>
    </div>
</div>

<div style="width: 100%; overflow: hidden;">
    <!-- Left Section (65%) -->
    <div style="width: 55%; float: left;">
        <div class="border pad-10 height-150" >
        	<div align="left"  font-size="22px" >
                  <strong>VERSEMENT</strong>
            </div> 
        	<div align="left"  font-size="22px" >
                  <strong>Montant à verser en Chiffres(Dh):  ' . $price['total'] . ' Dhs</strong>
            </div> 
        	<div align="left"  font-size="22px" >
                   <strong>Montant à verser en lettres (Dh) :  ' . $prix_en_lettre . ' Dhs</strong>
            </div> 
    	</div>
    </div>

    <!-- Right Section (35%) -->
    <div style="width: 44%; float: right;">
		<div class="border pad-10 height-150" >
    		<div align="left"  font-size="22px" >
    			  <strong>Cadre réservé à l\'Agence Urbaine </strong>
    		</div> 
    		<img style="width:120px;" src="themes/auer/images/code.jpg">
	</div>
       
    </div>
</div>
</body>
</html>';
// Generate PDF.
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => 'sites/default/files/tmp',
            'format' => 'A4',
            'default_font' => 'Arial'
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->showImageErrors = true;

        // Ensure no extra output before PDF generation.
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Force PDF download.
        $mpdf->Output($ndeg_dossier . '_autorisation.pdf', 'D');
    }
}