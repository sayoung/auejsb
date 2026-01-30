<?php

namespace Drupal\md_new_prestation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_product\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends ControllerBase {

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
        
        
            $nature_projet = $product->get('field_nature_du_projet_2')->value ?? 'N/A';
            $consistance_du_projet = $product->get('field_consistance_du_projet')->value ?? 'N/A';
            $type = $product->get('field_type_2')->value ?? 'N/A'; 
            $type_projet = $product->get('field_type_du_projet')->value ?? 'N/A';
            $Superficie_totale = $product->get('field_superficie_totale')->value ?? 'N/A';
        
        	$nombre_de_niveaux = $product->get('field_nombre_de_niveaux')->value ?? 'N/A';
        	$surface_terrain = $product->get('field_surface_de_terrain')->value ?? 'N/A';
        	$metrage_du_projet = $product->get('field_metrage_du_projet')->value ?? 'N/A';
        	$surface_planchers_couverts_supplementaires_apres_modification = $product->get('field_supp_apres_modi')->value ?? 'N/A';
            $surfaces_cessibles_apres_modification = $product->get('field_surf_cessible_modi')->value ?? 'N/A';
			$montant_investissement = $product->get('field_mnt_investissement_mdhs')->value ?? 'N/A';
        
        
        
        
        
        $date_declaration = date("d/m/Y"); // Current date
        
$checkboxHtml = '<fieldset>';
if ($type === "Nouveau projet") {
    $checkboxHtml .= '
        <div>
            <span style="padding: 2px;">✔</span>
            <label for="scales">NOUVEAU PROJET</label>
        </div>
        <div>
            <span style="padding: 2px;">☐</span>
            <label for="horns">MODIFICATION</label>
        </div>';
} elseif ($type === "Modification") {
    $checkboxHtml .= '
        <div>
            <span style="padding: 2px;">☐</span>
            <label for="scales">NOUVEAU PROJET</label>
        </div>
        <div>
            <span style="padding: 2px;">✔</span>
            <label for="horns">MODIFICATION</label>
        </div>';
}
$checkboxHtml .= '</fieldset>';
$const_ame_nouv_modif = '<tr>';
if ($type === "Nouveau projet") {
    $const_ame_nouv_modif .= '
                <td colspan="6"><strong>Surface planchers couverts</strong></td>
                <td colspan="7">' . $metrage_du_projet . ' m²</td> ';
            
} elseif ($type === "Modification") {
    $const_ame_nouv_modif .= '
                <td colspan="6"><strong>Surface planchers couverts supplémentaires après modification</strong></td>
                <td colspan="7">' . $surface_planchers_couverts_supplementaires_apres_modification . ' m²</td> ';
               
}


$const_ame_nouv_modif .= '</tr>';
        // Define the project type blocks.
        $construction_block = '
        <table style="width:100%;">
            <tr>
                
               <th colspan="6"><strong>CONSTRUCTION : </strong></th>
                <th colspan="7">' . $checkboxHtml . '</th>
            </tr>
            <tr>
                <td colspan="6"><strong>Surface de terrain</strong></td>
                <td colspan="7">' . $surface_terrain . ' m²</td>
            </tr>
            
            ' . $const_ame_nouv_modif . '
            
            <tr>
                <td colspan="6"><strong>Montant d’investissement (Mdhs)</strong></td>
                <td colspan="7">' . $montant_investissement . '</td>
            </tr>
        </table>';
 $amenagement_block = '
        <table style="width:100%;">
            
            <tr>
                
               <th colspan="6"><strong>AMENAGEMENT : </strong></th>
                <th colspan="7">' . $checkboxHtml . '</th>
            </tr>
            <tr>
                <td colspan="6"><strong>Surface de terrain</strong></td>
                <td colspan="7">' . $surface_terrain . ' m²</td>
            </tr>
            ' . $const_ame_nouv_modif . '
            <tr>
                <td colspan="6"><strong>Montant d’investissement (Mdhs)</strong></td>
                <td colspan="7">' . $montant_investissement . '</td>
            </tr>
        </table>';
 $group_nouv_modif = '<tr>';
if ($type === "Nouveau projet") {
    $group_nouv_modif .= '
                <td colspan="6"><strong>Surface planchers couverts</strong></td>
                <td colspan="7">' . $metrage_du_projet . ' m²</td> ';
            
} elseif ($type === "Modification") {
    $group_nouv_modif .= '
                <td colspan="6"><strong>planchers couverts supplémentaires après modification</strong></td>
                <td colspan="7">' . $surface_planchers_couverts_supplementaires_apres_modification . '</td>';
               
}   

 $lot_nouv_modif = '<tr>';
if ($type === "Nouveau projet") {
    $lot_nouv_modif .= '
                <td colspan="6"><strong>Surface totale des parcelles cessibles</strong></td>
                <td colspan="7">' . $metrage_du_projet . ' m²</td> ';
            
} elseif ($type === "Modification") {
    $lot_nouv_modif .= '
                <td colspan="6"><strong>surfaces cessibles après modification</strong></td>
                <td colspan="7">' . $surface_planchers_couverts_supplementaires_apres_modification . '</td>';
               
}

        $lotissement_block = '<table style="width:100%;">
            <tr>
                <th colspan="6"><strong>LOTISSEMENT : </strong></th>
                <th colspan="7">' . $checkboxHtml . '</th>
            </tr>
            <tr>
                <td colspan="6"><strong>Surface terrain</strong></td>
                <td colspan="7">' . $surface_terrain . ' m²</td>
            </tr>
                    ' . $lot_nouv_modif . '
            <tr>
                <td colspan="6"><strong>Montant d\'investissement (Mdhs)</strong></td>
                <td colspan="7">' . $montant_investissement . '</td>
            </tr>
        </table>';
		 $Groupement_habitation = '<table style="width:100%;">
            <tr>
                <th colspan="6"><strong>GROUPEMENT D\'HABITATION : </strong></th>
                <th colspan="7">' . $checkboxHtml . '</th>
            </tr>
            <tr>
                <td colspan="6"><strong>Surface terrain</strong></td>
                <td colspan="7">' . $surface_terrain . ' m²</td>
            </tr>
  ' . $group_nouv_modif . '
            </tr>

            <tr>
                <td colspan="6"><strong>Montant d\'investissement (Mdhs)</strong></td>
                <td colspan="7">' . $montant_investissement . '</td>
            </tr>
        </table>';

        $distraction_fusion_block = '
        <table style="width:100%;">
            <tr>
                <th style="font-size:12;width:60%;"><strong>DISTRACTION -- FUSION (à l\'intérieur du périmètre urbain)</strong></th>
                
            </tr>
            <tr>
                <td style="font-size:12;width:60%;"><strong>Superficie totale :</strong></td>
                <td colspan="8"> '. $Superficie_totale .'  m²</td>
            </tr>
            <tr>
                <td style="font-size:12;width:60%;"><strong>Superficie à Distraire en M² :</strong></td>
                <td colspan="8">' . $metrage_du_projet . ' m²</td>
            </tr>
        </table>';

        $morcellement_block = '
        <table style="width:100%;">
            <tr>
                <th colspan="6"><strong>MORCELLEMENT (hors périmètre urbain)</strong></th>
            </tr>
            <tr>
                <td colspan="6"><strong>Superficie totale :</strong></td>
                <td colspan="7"> ' . $Superficie_totale . ' m²</td>
            </tr>
            <tr>
                <td colspan="6"><strong>Superficie à Morceler en H :</strong></td>
                <td colspan="7"> ' . $metrage_du_projet . ' </td>
            </tr>
        </table>';

        // Determine which block to include based on $nature_projet.
        $project_block = '';
        switch ($nature_projet) {
            case 'Construction':
                $project_block = $construction_block;
                break;
            case 'Groupement d’habitation':
                $project_block = $Groupement_habitation;
                break;
            case 'Aménagement':
                $project_block = $amenagement_block; // Assuming similar to Construction
                break;
            case 'Lotissement':
                $project_block = $lotissement_block;
                break;
            case 'Distraction-fusion (à l’intérieur du périmètre urbain)':
                $project_block = $distraction_fusion_block;
                break;
            case 'Morcellement (hors périmètre urbain)':
                $project_block = $morcellement_block;
                break;
            default:
                $project_block = '<p>No specific project block for this nature.</p>';
                break;
        }
        
        if ($type === "Nouveau projet") {
                $footer_block = '•	la surface planchers et/ou cessible est de : <strong>' . $metrage_du_projet . ' m² </strong>. ';
        } elseif ($type === "Modification") {
               $footer_block = '•	la surface planchers et/ou cessible est de : <strong>' . $surface_planchers_couverts_supplementaires_apres_modification . ' m² </strong>. ';        
        }

        
       
        $morcellement_footer_block = '•	la surface Morcelée est de : <strong> ' . $metrage_du_projet . ' m² </strong>. ';
        $distraction_fusion_footer_block = '•	la surface Distraite est de : <strong> ' . $metrage_du_projet . ' m² </strong>. ';
        
        $project_footer_block = '';
        switch ($nature_projet) {
            case 'Construction':
                $project_footer_block = $footer_block;
                break;
            case 'Groupement d’habitation':
                $project_footer_block = $footer_block;
                break;
            case 'Aménagement':
                $project_footer_block = $footer_block; // Assuming similar to Construction
                break;
            case 'Lotissement':
                $project_footer_block = $footer_block;
                break;
            case 'Distraction-fusion (à l’intérieur du périmètre urbain)':
                $project_footer_block = $distraction_fusion_footer_block;
                break;
            case 'Morcellement (hors périmètre urbain)':
                $project_footer_block = $morcellement_footer_block;
                break;
            default:
                $project_footer_block = '<p>No specific project block for this nature.</p>';
                break;
        }
        $project_demandeur = '';
        switch ($nature_projet) {
            case 'Distraction-fusion (à l’intérieur du périmètre urbain)':
                $project_demandeur = 'Topographe';
                break;
            case 'Morcellement (hors périmètre urbain)':
                $project_demandeur = 'Topographe';
                break;
            default:
                $project_demandeur = 'Architecte';
                break;
        }

        // Define HTML template.
        $html = '
        <style>
            body { font-family: Arial, sans-serif; }
            h2, h3 { text-align: center; margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 10px; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            td {font-size:11px;}
            .signature { margin-top: 15px; text-align: center; }
            .footer { margin-top: 15px; font-size: 12px; text-align: center;bottom:10px;position: absolute; }
            .tit-back {background-color: #D0E7F5; font-weight: bold;align-items: center;text-align: center; }
            .tit-back-1 {background-color: #4a52a1; font-weight: bold;align-items: center;text-align: center; }
			.clr-white {color:#fff;}
			.lotissement-container {
            width: 500px;
            border: 1px solid black;
            background-color: #D0E7F5; /* Light blue background */
            padding: 10px;
            font-family: Arial, sans-serif;
        }

        .lotissement-bold {
            font-weight: bold;
        }


        .checkbox-circle {
            width: 18px;
            height: 18px;
            border: 2px solid black;
            border-radius: 50%;
            margin-left: 10px;
        }

        /* Optional: Style for selected checkbox */
        .checked , #scales {
            background-color: black;
            color:red;
        }
        </style>
            <div style="width:50%;text-align: center;margin-left:25%;">
            <h5>Royaume du Maroc</h5>
            <img style="width:80px;" src="sites/default/logo_maroc.jpg"   />  
			
			<h5>Ministère de l’Aménagement du Territoire National, de l’Urbanisme, de l’Habitat et de la Politique de la Ville <br/> Agence Urbaine d’El Jadida-Sidi Bennour</h5>
            <h5>FICHE TECHNIQUE <br/> Relative au Calcul des Surfaces</h5>
            </div>  
			
			

			<div class="tit-back">
			<span class="lotissement-bold">DONNEES GENERALES </span>
			</div>
			<span>N° de dossier - ROKHAS : <span class="lotissement-bold">' . $ndeg_dossier . '</span><span> <br/>
			<span>Nature de projet : <span class="lotissement-bold">' . $nature_projet . '</span> &nbsp;&nbsp; </span><br/>
			<span>Consistance du projet : <span  class="lotissement-bold"> '. $consistance_du_projet .' </span><br/>
			<span>Type du projet : <span class="lotissement-bold">' . $type_projet . '</span><span> <br/>
			<span>Situation du projet : <span class="lotissement-bold">' . $situation_projet . '</span><span> <br/>
			<span>Province : <span class="lotissement-bold">' . $province . '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Commune : <span class="lotissement-bold">' . $commune . '</span><span> <br/>
			<span>Références foncières : <span class="lotissement-bold">' . $references_foncieres . '</span><span> <br/>
			<span>Maître d’ouvrage : <span class="lotissement-bold">' . $nom_maitre_ouvrage . '</span><span> <br/>
			<span>Adresse : <span class="lotissement-bold">' . $adresse_maitre_ouvrage . '</span><span> <br/>
			<span>N° CIN/ RC   : <span class="lotissement-bold">' . $cin_maitre_ouvrage . '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Maitre d’oeuvre : <span class="lotissement-bold">' . $nom_architecte . '</span><span> <br/>
			

			
			' . $project_block . '

			<div class="tit-back-1">
			<span class="lotissement-bold clr-white">Conformément à la réglementation en vigueur notamment l’article 33 du RGC : </span>
			</div>
			
			<p style="font-size: 10px;">Je soussigné(e) <strong>' . $nom_architecte . '</strong>, '. $project_demandeur .' auteur du projet cité ci-dessus, déclare sur l’honneur l’exactitude des renseignements contenus dans la présente déclaration, et confirme que : <br/>
		    ' .	$project_footer_block  . ' </p>

			<div class="signature">
				<p>Fait de bonne foi à <strong>El Jadida</strong>, le ' . $date_declaration . '</p>
			
			</div>

			<div class="footer">
				<hr style="border: 1px solid black; margin-top: 20px;">

				

				<p style="font-size: 08px; margin-top: 0px;">
					<strong>NB :</strong> Lors de la constitution du dossier complémentaire, prière bien vouloir adjoindre à ce document, 
					une copie de l’autorisation de versement et du justificatif de paiement avec en dessus 
					le <strong>N° du dossier Rokhas</strong> et le <strong>nom du maître d’ouvrage</strong> manuscrits.
				</p>
			</div>

        </div>';

        // Generate PDF.
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => 'sites/default/files/generated_pdfs',
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
        $mpdf->Output($ndeg_dossier . '_fiche_technique.pdf', 'D');
    }
}